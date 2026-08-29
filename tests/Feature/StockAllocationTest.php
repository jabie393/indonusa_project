<?php

use App\Models\User;
use App\Models\Goods;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProcurementOfGoods;
use App\Models\ProcurementOfGoodsItem;
use App\Models\ProcurementArrivalRequest;
use App\Services\StockAllocationService;
use Illuminate\Support\Facades\DB;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('scenario 1 & 2: allocates available stock and calculates shortages', function () {
    $sales = User::factory()->create(['role' => 'Sales']);

    $goods = Goods::create([
        'goods_code' => 'HT-ALLOC-001',
        'goods_name' => 'Alloc Item 1',
        'category' => 'HANDTOOLS',
        'stock' => 5,
        'goods_status' => 'approved',
        'buy_price' => 10000,
        'selling_price' => 12000,
        'unit' => 'pcs',
        'description' => 'Test',
    ]);

    // Create a quotation needing 8 items (shortage of 3)
    $quotation = Quotation::create([
        'request_number' => 'RQ-003',
        'quotation_number' => 'PNW-003',
        'sales_id' => $sales->id,
        'customer_name' => 'Test Customer',
        'grand_total' => 96000,
        'product_category' => 'HANDTOOLS',
    ]);

    QuotationItem::create([
        'quotation_id' => $quotation->id,
        'goods_id' => $goods->id,
        'product_category' => 'HANDTOOLS',
        'quantity' => 8,
        'price' => 12000,
        'subtotal' => 96000,
    ]);

    // Mimic the order creation (draft state, offered to customer)
    $order = Order::create([
        'order_number' => 'ORD-003',
        'sales_id' => $sales->id,
        'customer_name' => 'Test Customer',
        'quotation_id' => $quotation->id,
        'status' => 'open',
        'queue_at' => null, // Offered to customer, not yet confirmed
    ]);

    $orderItem = OrderItem::create([
        'order_id' => $order->id,
        'goods_id' => $goods->id,
        'category' => 'HANDTOOLS',
        'quantity' => 8,
        'price' => 12000,
        'subtotal' => 96000,
        'allocated_quantity' => 0,
        'shortage_quantity' => 0,
    ]);

    expect($order->fresh()->queue_at)->toBeNull();
    expect($orderItem->fresh()->allocated_quantity)->toBe(0);

    // Sales clicks "Send to Warehouse" (confirming the offer)
    $response = actingAs($sales)->post(route('sales.quotation.sent-to-warehouse-from-so', $quotation->id));
    $response->assertSessionHasNoErrors();

    expect($order->fresh()->queue_at)->not->toBeNull();
    expect($orderItem->fresh()->allocated_quantity)->toBe(5);
    expect($orderItem->fresh()->shortage_quantity)->toBe(3);
    expect($order->fresh()->status)->toBe('under_procurement'); // Shortage, automatically sent to procurement
});

it('scenario 3, 4 & 5: sends shortage to procurement and satisfies order via FIFO when stock arrives', function () {
    $sales = User::factory()->create(['role' => 'Sales']);
    $ga = User::factory()->create(['role' => 'General Affair']);

    $goods = Goods::create([
        'goods_code' => 'HT-ALLOC-002',
        'goods_name' => 'Alloc Item 2',
        'category' => 'HANDTOOLS',
        'stock' => 2,
        'goods_status' => 'approved',
        'buy_price' => 10000,
        'selling_price' => 12000,
        'unit' => 'pcs',
        'description' => 'Test',
    ]);

    $quotation = Quotation::create([
        'request_number' => 'RQ-004',
        'quotation_number' => 'PNW-004',
        'sales_id' => $sales->id,
        'customer_name' => 'Test Customer',
        'grand_total' => 120000,
        'product_category' => 'HANDTOOLS',
    ]);

    QuotationItem::create([
        'quotation_id' => $quotation->id,
        'goods_id' => $goods->id,
        'product_category' => 'HANDTOOLS',
        'quantity' => 10,
        'price' => 12000,
        'subtotal' => 120000,
    ]);

    $order = Order::create([
        'order_number' => 'ORD-004',
        'sales_id' => $sales->id,
        'customer_name' => 'Test Customer',
        'quotation_id' => $quotation->id,
        'status' => 'open',
        'queue_at' => null, // Initial offered state
    ]);

    $orderItem = OrderItem::create([
        'order_id' => $order->id,
        'goods_id' => $goods->id,
        'category' => 'HANDTOOLS',
        'quantity' => 10,
        'price' => 12000,
        'subtotal' => 120000,
        'allocated_quantity' => 0,
        'shortage_quantity' => 0,
    ]);

    // Send to warehouse / procurement (first action button)
    actingAs($sales)->post(route('sales.quotation.sent-to-warehouse-from-so', $quotation->id));

    expect($orderItem->fresh()->allocated_quantity)->toBe(2);
    expect($orderItem->fresh()->shortage_quantity)->toBe(8);
    expect($order->fresh()->status)->toBe('under_procurement');

    $procurement = ProcurementOfGoods::whereNull('custom_quotation_id')->latest()->first();
    expect($procurement)->not->toBeNull();
    expect($procurement->status)->toBe('pending');
    expect($procurement->general_affair_id)->toBeNull();

    $procItem = $procurement->items()->where('goods_id', $goods->id)->first();
    expect($procItem->qty_ordered)->toBe(8);

    // GA records arrival of 8 items
    actingAs($ga)->post(route('general-affair.procurement.record-arrival', $procurement->id), [
        'type' => 'full',
        'items' => [
            [
                'goods_id' => $goods->id,
                'procurement_item_id' => $procItem->id,
                'qty_arriving' => 8,
                'buy_price' => 10000,
            ]
        ]
    ]);

    // Ensure GA takes ownership on recording arrival
    expect($procurement->fresh()->general_affair_id)->toBe($ga->id);

    $arrivalRequest = ProcurementArrivalRequest::where('procurement_of_goods_item_id', $procItem->id)->first();
    expect($arrivalRequest)->not->toBeNull();
    expect($arrivalRequest->status)->toBe('pending');

    // Warehouse approves arrival request (this updates physical stock of goods which triggers FIFO allocation via observer)
    $goods->stock += 8;
    $goods->save();

    // Trigger FIFO manually if needed, or let Goods Observer run it (observer should run automatically on save)
    expect($orderItem->fresh()->allocated_quantity)->toBe(10);
    expect($orderItem->fresh()->shortage_quantity)->toBe(0);
    expect($order->fresh()->status)->toBe('sent_to_warehouse');
});

it('scenario 6: releases allocation and reallocates to next order in FIFO queue on cancel', function () {
    $sales = User::factory()->create(['role' => 'Sales']);

    $goods = Goods::create([
        'goods_code' => 'HT-ALLOC-003',
        'goods_name' => 'Alloc Item 3',
        'category' => 'HANDTOOLS',
        'stock' => 5,
        'goods_status' => 'approved',
        'buy_price' => 10000,
        'selling_price' => 12000,
        'unit' => 'pcs',
        'description' => 'Test',
    ]);

    // Order 1 (Earlier in queue)
    $q1 = Quotation::create([
        'request_number' => 'RQ-005A',
        'quotation_number' => 'PNW-005A',
        'sales_id' => $sales->id,
        'customer_name' => 'Customer A',
        'grand_total' => 60000,
        'product_category' => 'HANDTOOLS',
    ]);

    $order1 = Order::create([
        'order_number' => 'ORD-005A',
        'sales_id' => $sales->id,
        'customer_name' => 'Customer A',
        'quotation_id' => $q1->id,
        'status' => 'open',
        'queue_at' => now()->subMinutes(10),
    ]);

    $item1 = OrderItem::create([
        'order_id' => $order1->id,
        'goods_id' => $goods->id,
        'category' => 'HANDTOOLS',
        'quantity' => 5,
        'price' => 12000,
        'subtotal' => 60000,
    ]);

    // Order 2 (Later in queue)
    $q2 = Quotation::create([
        'request_number' => 'RQ-005B',
        'quotation_number' => 'PNW-005B',
        'sales_id' => $sales->id,
        'customer_name' => 'Customer B',
        'grand_total' => 60000,
        'product_category' => 'HANDTOOLS',
    ]);

    $order2 = Order::create([
        'order_number' => 'ORD-005B',
        'sales_id' => $sales->id,
        'customer_name' => 'Customer B',
        'quotation_id' => $q2->id,
        'status' => 'open',
        'queue_at' => now(),
    ]);

    $item2 = OrderItem::create([
        'order_id' => $order2->id,
        'goods_id' => $goods->id,
        'category' => 'HANDTOOLS',
        'quantity' => 5,
        'price' => 12000,
        'subtotal' => 60000,
    ]);

    // Create procurement for Order 1 and Order 2
    $proc1 = ProcurementOfGoods::create([
        'procurement_number' => 'PRC-005A',
        'order_id' => $order1->id,
        'status' => 'pending',
    ]);
    $proc2 = ProcurementOfGoods::create([
        'procurement_number' => 'PRC-005B',
        'order_id' => $order2->id,
        'status' => 'pending',
    ]);

    // Allocate stock
    StockAllocationService::allocateAvailableStock($order1);
    StockAllocationService::allocateAvailableStock($order2);

    expect($item1->fresh()->allocated_quantity)->toBe(5);
    expect($item2->fresh()->allocated_quantity)->toBe(0);

    // Cancel Order 1 - Should release stock to Order 2
    actingAs($sales)->post(route('sales.sales-orders.cancel', $order1->id));

    expect($order1->fresh()->status)->toBe('canceled');
    expect($item1->fresh()->allocated_quantity)->toBe(0);
    expect($proc1->fresh()->status)->toBe('canceled');

    // Order 2 should now have the 5 items allocated and status changed to sent_to_warehouse
    expect($item2->fresh()->allocated_quantity)->toBe(5);
    expect($order2->fresh()->status)->toBe('sent_to_warehouse');
    expect($proc2->fresh()->status)->toBe('completed');
});

it('scenario 7: consolidates shortages of same goods across multiple SOs into 1 procurement item and fulfills via FIFO', function () {
    $sales = User::factory()->create(['role' => 'Sales']);

    $goods = Goods::create([
        'goods_code' => 'HT-CONSOLIDATE-001',
        'goods_name' => 'Consolidated Drill',
        'category' => 'HANDTOOLS',
        'stock' => 0,
        'goods_status' => 'approved',
        'buy_price' => 50000,
        'selling_price' => 60000,
        'unit' => 'pcs',
        'description' => 'Drill',
    ]);

    // SO 1: Needs 5 units
    $q1 = Quotation::create([
        'request_number' => 'REQ-CON-1',
        'quotation_number' => 'PNW-CON-1',
        'sales_id' => $sales->id,
        'customer_name' => 'Client 1',
        'grand_total' => 300000,
    ]);
    $item1 = QuotationItem::create([
        'quotation_id' => $q1->id,
        'goods_id' => $goods->id,
        'custom_product_name' => 'Consolidated Drill',
        'quantity' => 5,
        'price' => 60000,
        'subtotal' => 300000,
    ]);
    $order1 = Order::create([
        'order_number' => 'ORD-CON-1',
        'sales_id' => $sales->id,
        'customer_name' => 'Client 1',
        'quotation_id' => $q1->id,
        'status' => 'open',
        'queue_at' => null,
    ]);

    // SO 2: Needs 3 units
    $q2 = Quotation::create([
        'request_number' => 'REQ-CON-2',
        'quotation_number' => 'PNW-CON-2',
        'sales_id' => $sales->id,
        'customer_name' => 'Client 2',
        'grand_total' => 180000,
    ]);
    $item2 = QuotationItem::create([
        'quotation_id' => $q2->id,
        'goods_id' => $goods->id,
        'custom_product_name' => 'Consolidated Drill',
        'quantity' => 3,
        'price' => 60000,
        'subtotal' => 180000,
    ]);
    $order2 = Order::create([
        'order_number' => 'ORD-CON-2',
        'sales_id' => $sales->id,
        'customer_name' => 'Client 2',
        'quotation_id' => $q2->id,
        'status' => 'open',
        'queue_at' => null,
    ]);

    // Confirm SO 1
    actingAs($sales)->post(route('sales.quotation.sent-to-warehouse-from-so', $q1->id));
    // Confirm SO 2 (shortly after SO 1)
    actingAs($sales)->post(route('sales.quotation.sent-to-warehouse-from-so', $q2->id));

    // Check consolidated procurement
    $proc = ProcurementOfGoods::whereNull('custom_quotation_id')->whereNull('order_id')->first();
    expect($proc)->not->toBeNull();

    $procItem = $proc->items()->where('goods_id', $goods->id)->first();
    expect($procItem)->not->toBeNull();
    // 5 from SO1 + 3 from SO2 = 8 total ordered in 1 item!
    expect($procItem->qty_ordered)->toBe(8);

    // Both orders are under_procurement
    expect($order1->fresh()->status)->toBe('under_procurement');
    expect($order2->fresh()->status)->toBe('under_procurement');

    // Suppose 5 units arrive first
    $goods->stock += 5;
    $goods->save();

    // SO 1 (earlier queue) should be fully satisfied, SO 2 still waiting
    expect($order1->fresh()->status)->toBe('sent_to_warehouse');
    expect($order1->fresh()->items->first()->allocated_quantity)->toBe(5);
    expect($order2->fresh()->status)->toBe('under_procurement');
    expect($order2->fresh()->items->first()->allocated_quantity)->toBe(0);

    // Remaining 3 units arrive
    $goods->stock += 3;
    $goods->save();

    // Now SO 2 is also fully satisfied
    expect($order2->fresh()->status)->toBe('sent_to_warehouse');
    expect($order2->fresh()->items->first()->allocated_quantity)->toBe(3);
});
