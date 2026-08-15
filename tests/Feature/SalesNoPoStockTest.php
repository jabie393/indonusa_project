<?php

use App\Models\Goods;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\User;
use function Pest\Laravel\actingAs;

it('reduces stock when sales adds no po to quotation', function () {
    $sales = User::factory()->create(['role' => 'Sales']);

    $goods = Goods::create([
        'request_type' => 'primary',
        'goods_status' => 'approved',
        'status_listing' => 'listing',
        'goods_code' => 'HT-TEST-001',
        'goods_name' => 'Test Barang',
        'category' => 'HANDTOOLS',
        'stock' => 10,
        'unit' => 'PCS',
        'buy_price' => 100000,
        'selling_price' => 115000,
        'description' => 'Barang uji coba',
        'form' => $sales->id,
    ]);

    $quotation = Quotation::create([
        'request_number' => 'RQ-001',
        'quotation_number' => 'PNW-20260815-001',
        'sales_order_number' => null,
        'no_po' => null,
        'sales_id' => $sales->id,
        'customer_name' => 'Customer Test',
        'customer_id' => null,
        'subject' => 'Test Quotation',
        'required_date' => now()->toDateString(),
        'grand_total' => 300000,
        'product_category' => 'HANDTOOLS',
    ]);

    QuotationItem::create([
        'quotation_id' => $quotation->id,
        'goods_id' => $goods->id,
        'product_category' => 'HANDTOOLS',
        'quantity' => 3,
        'price' => 100000,
        'subtotal' => 300000,
        'discount_percent' => 0,
    ]);

    actingAs($sales)
        ->post(route('request-order.update-no-po', $quotation), ['no_po' => 'PO-123']);

    expect($goods->fresh()->stock)->toBe(7);
});

it('restores stock when sales clears no po from quotation', function () {
    $sales = User::factory()->create(['role' => 'Sales']);

    $goods = Goods::create([
        'request_type' => 'primary',
        'goods_status' => 'approved',
        'status_listing' => 'listing',
        'goods_code' => 'HT-TEST-002',
        'goods_name' => 'Test Barang 2',
        'category' => 'HANDTOOLS',
        'stock' => 10,
        'unit' => 'PCS',
        'buy_price' => 100000,
        'selling_price' => 115000,
        'description' => 'Barang uji coba',
        'form' => $sales->id,
    ]);

    $quotation = Quotation::create([
        'request_number' => 'RQ-002',
        'quotation_number' => 'PNW-20260815-002',
        'sales_order_number' => 'SO-20260815-0001',
        'no_po' => 'PO-123',
        'sales_id' => $sales->id,
        'customer_name' => 'Customer Test',
        'customer_id' => null,
        'subject' => 'Test Quotation',
        'required_date' => now()->toDateString(),
        'grand_total' => 300000,
        'product_category' => 'HANDTOOLS',
    ]);

    QuotationItem::create([
        'quotation_id' => $quotation->id,
        'goods_id' => $goods->id,
        'product_category' => 'HANDTOOLS',
        'quantity' => 3,
        'price' => 100000,
        'subtotal' => 300000,
        'discount_percent' => 0,
    ]);

    actingAs($sales)
        ->post(route('request-order.update-no-po', $quotation), ['no_po' => '']);

    expect($goods->fresh()->stock)->toBe(10);
});
