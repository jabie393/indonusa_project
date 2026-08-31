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

    // Warehouse approves arrival request
    $warehouse = User::factory()->create(['role' => 'Warehouse']);
    actingAs($warehouse)->post(route('supply-orders.approve-procurement', $arrivalRequest->id));

    expect($orderItem->fresh()->allocated_quantity)->toBe(10);
    expect($orderItem->fresh()->shortage_quantity)->toBe(0);
    expect($order->fresh()->status)->toBe('sent_to_warehouse');
    expect($procItem->fresh()->qty_received)->toBe(8);
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

it('allows partial delivery for under_procurement order with allocated stock without waiting for full procurement', function () {
    $sales = User::factory()->create(['role' => 'Sales']);
    $warehouse = User::factory()->create(['role' => 'Warehouse']);

    $goods = Goods::create([
        'goods_code' => 'HT-PARTIAL-001',
        'goods_name' => 'Partial DO Item',
        'category' => 'HANDTOOLS',
        'stock' => 4,
        'goods_status' => 'approved',
        'buy_price' => 10000,
        'selling_price' => 12000,
        'unit' => 'pcs',
        'description' => 'Test Partial DO',
    ]);

    // Order 10 items (4 in stock, 6 shortage)
    $quotation = Quotation::create([
        'request_number' => 'RQ-PARTIAL-01',
        'quotation_number' => 'PNW-PARTIAL-01',
        'sales_id' => $sales->id,
        'customer_name' => 'Partial Customer',
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
        'order_number' => 'ORD-PARTIAL-01',
        'sales_id' => $sales->id,
        'customer_name' => 'Partial Customer',
        'quotation_id' => $quotation->id,
        'status' => 'open',
        'queue_at' => null,
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

    // Confirm SO: 4 allocated, 6 shortage, status under_procurement
    actingAs($sales)->post(route('sales.quotation.sent-to-warehouse-from-so', $quotation->id));

    expect($orderItem->fresh()->allocated_quantity)->toBe(4);
    expect($orderItem->fresh()->shortage_quantity)->toBe(6);
    expect($order->fresh()->status)->toBe('under_procurement');

    // 1. Check getItems API returns allocated_quantity & under_procurement
    $resItems = actingAs($warehouse)->get(route('delivery-orders.items', $order->id));
    $resItems->assertOk();
    $itemsJson = $resItems->json();
    expect($itemsJson[0]['allocated_quantity'])->toBe(4);
    expect($itemsJson[0]['shortage_quantity'])->toBe(6);
    expect($itemsJson[0]['order_status'])->toBe('under_procurement');

    // 2. Perform partial delivery for the 4 allocated units
    $resPartial = actingAs($warehouse)->post(route('delivery-orders.partial-approve', $order->id), [
        'items' => [
            $orderItem->id => 4,
        ],
    ]);
    $resPartial->assertSessionHasNoErrors();

    // Check delivery batch & stock deductions
    expect($order->fresh()->batches)->toHaveCount(1);
    expect($orderItem->fresh()->delivered_quantity)->toBe(4);
    expect($orderItem->fresh()->allocated_quantity)->toBe(0);
    expect($orderItem->fresh()->shortage_quantity)->toBe(6);
    expect($goods->fresh()->stock)->toBe(0); // 4 units shipped
    // Order status is not_completed (Partial Delivery)
    expect($order->fresh()->status)->toBe('not_completed');
    expect($order->fresh()->delivery_options)->toBe('partial');

    // 3. When the remaining 6 units arrive via procurement:
    $goods->stock += 6;
    $goods->save();

    // FIFO automatically allocates the 6 units and preserves not_completed (Partial Delivery) status
    expect($orderItem->fresh()->allocated_quantity)->toBe(6);
    expect($orderItem->fresh()->shortage_quantity)->toBe(0);
    expect($order->fresh()->status)->toBe('not_completed');

    // 4. Warehouse approves final delivery of remaining 6 units
    $resFinal = actingAs($warehouse)->post(route('delivery-orders.approve', $order->id));
    $resFinal->assertSessionHasNoErrors();

    expect($orderItem->fresh()->delivered_quantity)->toBe(10);
    expect($order->fresh()->status)->toBe('completed');
});

it('prevents SO2 from stealing stock allocated to SO1 when SO2 is partially delivered', function () {
    $sales = User::factory()->create(['role' => 'Sales']);
    $warehouse = User::factory()->create(['role' => 'Warehouse']);

    // P1 Stock = 10
    $goods = Goods::create([
        'goods_code' => 'HT-STEAL-001',
        'goods_name' => 'Steal Protection Item',
        'category' => 'HANDTOOLS',
        'stock' => 10,
        'goods_status' => 'approved',
        'buy_price' => 10000,
        'selling_price' => 12000,
        'unit' => 'pcs',
        'description' => 'Protection Test',
    ]);

    // SO 1 orders 5 units
    $q1 = Quotation::create([
        'request_number' => 'RQ-S1',
        'quotation_number' => 'PNW-S1',
        'sales_id' => $sales->id,
        'customer_name' => 'Cust 1',
        'grand_total' => 60000,
        'product_category' => 'HANDTOOLS',
    ]);
    QuotationItem::create([
        'quotation_id' => $q1->id,
        'goods_id' => $goods->id,
        'product_category' => 'HANDTOOLS',
        'quantity' => 5,
        'price' => 12000,
        'subtotal' => 60000,
    ]);
    $order1 = Order::create([
        'order_number' => 'ORD-S1',
        'sales_id' => $sales->id,
        'customer_name' => 'Cust 1',
        'quotation_id' => $q1->id,
        'status' => 'open',
    ]);
    $orderItem1 = OrderItem::create([
        'order_id' => $order1->id,
        'goods_id' => $goods->id,
        'category' => 'HANDTOOLS',
        'quantity' => 5,
        'price' => 12000,
        'subtotal' => 60000,
        'allocated_quantity' => 0,
        'shortage_quantity' => 0,
    ]);

    // SO 2 orders 10 units
    $q2 = Quotation::create([
        'request_number' => 'RQ-S2',
        'quotation_number' => 'PNW-S2',
        'sales_id' => $sales->id,
        'customer_name' => 'Cust 2',
        'grand_total' => 120000,
        'product_category' => 'HANDTOOLS',
    ]);
    QuotationItem::create([
        'quotation_id' => $q2->id,
        'goods_id' => $goods->id,
        'product_category' => 'HANDTOOLS',
        'quantity' => 10,
        'price' => 12000,
        'subtotal' => 120000,
    ]);
    $order2 = Order::create([
        'order_number' => 'ORD-S2',
        'sales_id' => $sales->id,
        'customer_name' => 'Cust 2',
        'quotation_id' => $q2->id,
        'status' => 'open',
    ]);
    $orderItem2 = OrderItem::create([
        'order_id' => $order2->id,
        'goods_id' => $goods->id,
        'category' => 'HANDTOOLS',
        'quantity' => 10,
        'price' => 12000,
        'subtotal' => 120000,
        'allocated_quantity' => 0,
        'shortage_quantity' => 0,
    ]);

    // Confirm SO 1 -> allocated 5, shortage 0, status sent_to_warehouse
    actingAs($sales)->post(route('sales.quotation.sent-to-warehouse-from-so', $q1->id));
    expect($orderItem1->fresh()->allocated_quantity)->toBe(5);
    expect($orderItem1->fresh()->shortage_quantity)->toBe(0);
    expect($order1->fresh()->status)->toBe('sent_to_warehouse');

    // Confirm SO 2 -> allocated 5, shortage 5, status under_procurement
    actingAs($sales)->post(route('sales.quotation.sent-to-warehouse-from-so', $q2->id));
    expect($orderItem2->fresh()->allocated_quantity)->toBe(5);
    expect($orderItem2->fresh()->shortage_quantity)->toBe(5);
    expect($order2->fresh()->status)->toBe('under_procurement');

    // Warehouse partially ships SO 2 (5 units)
    $resPartial1 = actingAs($warehouse)->post(route('delivery-orders.partial-approve', $order2->id), [
        'items' => [$orderItem2->id => 5],
    ]);
    $resPartial1->assertSessionHasNoErrors();

    expect($orderItem2->fresh()->delivered_quantity)->toBe(5);
    expect($orderItem2->fresh()->allocated_quantity)->toBe(0); // SO 2 now has 0 allocated!
    expect($orderItem2->fresh()->shortage_quantity)->toBe(5);
    expect($goods->fresh()->stock)->toBe(5); // Physical stock 5 left in warehouse (belongs to SO 1)
    expect($orderItem1->fresh()->allocated_quantity)->toBe(5); // SO 1 still safely holds its 5 allocated units!

    // Check SO 2 items API before procurement arrives
    $resItems = actingAs($warehouse)->get(route('delivery-orders.items', $order2->id));
    $resItems->assertOk();
    $itemsJson = $resItems->json();
    expect($itemsJson[0]['allocated_quantity'])->toBe(0);

    // If SO 2 attempts to send another 5 units before procurement arrives:
    $resPartialFail = actingAs($warehouse)->post(route('delivery-orders.partial-approve', $order2->id), [
        'items' => [$orderItem2->id => 5],
    ]);
    // It MUST be rejected with error because allocated_quantity is 0!
    $resPartialFail->assertSessionHasErrors();
    expect($orderItem2->fresh()->delivered_quantity)->toBe(5);
    expect($goods->fresh()->stock)->toBe(5); // SO 1's stock is protected!

    // SO 1 can successfully ship its 5 units!
    $resSO1 = actingAs($warehouse)->post(route('delivery-orders.approve', $order1->id));
    $resSO1->assertSessionHasNoErrors();
    expect($orderItem1->fresh()->delivered_quantity)->toBe(5);
    expect($order1->fresh()->status)->toBe('completed');
    expect($goods->fresh()->stock)->toBe(0);
});

it('allocates stock and allows partial delivery when non-listing custom quotation procurement arrives', function () {
    $sales = User::factory()->create(['role' => 'Sales', 'name' => 'Sales CQ']);
    $warehouse = User::factory()->create(['role' => 'Warehouse', 'name' => 'Warehouse CQ']);
    $ga = User::factory()->create(['role' => 'General Affair', 'name' => 'GA CQ']);

    // Create Custom Quotation
    $cq = \App\Models\CustomQuotation::create([
        'sales_id' => $sales->id,
        'quotation_number' => 'CQ-TEST-001',
        'to' => 'Customer Non-Listing',
        'up' => $sales->name,
        'subject' => 'Non Listing Test',
        'email' => 'cq@test.com',
        'our_ref' => 'REF-CQ-001',
        'date' => now(),
        'status' => 'sent_to_quotation',
        'subtotal' => 120000,
        'grand_total' => 120000,
    ]);

    $cqItem = \App\Models\CustomQuotationItem::create([
        'custom_quotation_id' => $cq->id,
        'product_name' => 'Paku Bumi Custom',
        'qty' => 12,
        'unit' => 'pcs',
        'price' => 10000,
        'subtotal' => 120000,
        'description' => 'paku bumi',
    ]);

    // Create standard Quotation linked to CQ
    $quotation = Quotation::create([
        'sales_id' => $sales->id,
        'custom_quotation_id' => $cq->id,
        'request_number' => 'RO-CQ-001',
        'quotation_number' => 'Q-CQ-001',
        'sales_order_number' => 'SO-CQ-001',
        'customer_name' => 'Customer Non-Listing',
        'date' => now(),
        'status' => 'approved',
    ]);

    $qItem = QuotationItem::create([
        'quotation_id' => $quotation->id,
        'custom_product_name' => 'Paku Bumi Custom',
        'quantity' => 12,
        'price' => 10000,
        'subtotal' => 120000,
    ]);

    // Sales sends Quotation to Warehouse / Procurement
    actingAs($sales)->post(route('sales.quotation.sent-to-warehouse-from-so', $quotation->id));

    $order = Order::where('quotation_id', $quotation->id)->first();
    expect($order)->not->toBeNull();
    $orderItem = $order->items->first();
    expect($orderItem)->not->toBeNull();
    expect($orderItem->quantity)->toBe(12);
    expect($orderItem->allocated_quantity)->toBe(0);
    expect($orderItem->shortage_quantity)->toBe(12);

    $goods = \App\Models\Goods::find($orderItem->goods_id);
    expect($goods)->not->toBeNull();
    expect($goods->stock)->toBe(0);

    // General Affair creates procurement for this custom quotation
    $procurement = \App\Models\ProcurementOfGoods::create([
        'procurement_number' => \App\Models\ProcurementOfGoods::generateProcurementNumber(),
        'order_id' => $order->id,
        'custom_quotation_id' => $cq->id,
        'general_affair_id' => $ga->id,
        'status' => 'pending',
    ]);

    $procItem = \App\Models\ProcurementOfGoodsItem::create([
        'procurement_of_goods_id' => $procurement->id,
        'goods_id' => $goods->id,
        'product_name' => 'Paku Bumi Custom',
        'qty_requested' => 12,
        'qty_ordered' => 12,
        'qty_received' => 0,
        'unit' => 'pcs',
        'buy_price' => 8000,
        'selling_price' => 10000,
        'status' => 'pending',
    ]);

    // GA records receipt of 5 pcs (partial arrival)
    $arrivalReq = \App\Models\ProcurementArrivalRequest::create([
        'procurement_of_goods_item_id' => $procItem->id,
        'good_id' => $goods->id,
        'quantity' => 5,
        'unit_cost' => 8000,
        'received_at' => now(),
        'status' => 'pending',
    ]);

    // Warehouse approves the arrival of 5 pcs
    $resApproveSupply = actingAs($warehouse)->post(route('supply-orders.approve-procurement', $arrivalReq->id));
    $resApproveSupply->assertSessionHasNoErrors();

    // Verify stock & allocation:
    expect($goods->fresh()->stock)->toBe(5);
    expect($orderItem->fresh()->allocated_quantity)->toBe(5);
    expect($orderItem->fresh()->shortage_quantity)->toBe(7);

    // Verify items API for Delivery Order modal
    $resItems = actingAs($warehouse)->get(route('delivery-orders.items', $order->id));
    $resItems->assertOk();
    $itemsJson = $resItems->json();
    expect($itemsJson[0]['allocated_quantity'])->toBe(5);
    expect($itemsJson[0]['shortage_quantity'])->toBe(7);
    expect($itemsJson[0]['stok_gudang'])->toBe(5);

    // Warehouse can now successfully process partial delivery for the 5 arrived pcs!
    $resPartial = actingAs($warehouse)->post(route('delivery-orders.partial-approve', $order->id), [
        'items' => [$orderItem->id => 5],
    ]);
    $resPartial->assertSessionHasNoErrors();

    expect($orderItem->fresh()->delivered_quantity)->toBe(5);
    expect($orderItem->fresh()->allocated_quantity)->toBe(0);
    expect($orderItem->fresh()->shortage_quantity)->toBe(7);
    expect($order->fresh()->status)->toBe('not_completed');
});




