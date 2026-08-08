<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\CustomQuotation;
use App\Models\CustomQuotationItem;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SystemSetting;
use App\Models\Catalog;
use App\Models\ProcurementOfGoods;
use App\Models\ProcurementOfGoodsItem;
use App\Models\ProcurementArrivalRequest;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key constraints to safely truncate all tables
        Schema::disableForeignKeyConstraints();
        DB::table('procurement_arrival_requests')->truncate();
        DB::table('procurement_of_goods_items')->truncate();
        DB::table('procurement_of_goods')->truncate();
        DB::table('delivery_batch_items')->truncate();
        DB::table('delivery_batches')->truncate();
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::table('quotation_items')->truncate();
        DB::table('quotations')->truncate();
        DB::table('custom_quotation_items')->truncate();
        DB::table('custom_quotations')->truncate();
        DB::table('pics')->truncate();
        DB::table('customers')->truncate();
        DB::table('goods_receipts')->truncate();
        DB::table('goods_histories')->truncate();
        DB::table('goods')->truncate();
        DB::table('catalog')->truncate();
        DB::table('system_settings')->truncate();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        // 1. Create Users
        User::create([
            'id' => 1,
            'name' => 'Operator',
            'email' => 'operator@example.com',
            'password' => Hash::make('password'),
            'role' => 'Operator',
        ]);

        // Login as Operator to satisfy Eloquent boot event listeners (created_by/updated_by columns)
        Auth::loginUsingId(1);

        User::create([
            'id' => 2,
            'name' => 'Imam',
            'email' => 'supervisor@example.com',
            'password' => Hash::make('password'),
            'role' => 'Supervisor',
        ]);

        User::create([
            'id' => 3,
            'name' => 'Fahd',
            'email' => 'generalaffair@example.com',
            'password' => Hash::make('password'),
            'role' => 'General Affair',
        ]);

        User::create([
            'id' => 4,
            'name' => 'Hilmi',
            'email' => 'sales@example.com',
            'password' => Hash::make('password'),
            'role' => 'Sales',
        ]);

        User::create([
            'id' => 5,
            'name' => 'Ryujin',
            'email' => 'salesryujin@example.com',
            'password' => Hash::make('password'),
            'role' => 'Sales',
        ]);

        User::create([
            'id' => 6,
            'name' => 'Arvan',
            'email' => 'arvanindonusa@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'Sales',
        ]);

        User::create([
            'id' => 7,
            'name' => 'Anjar',
            'email' => 'anjarsedayu.ijb@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'Sales',
        ]);

        User::create([
            'id' => 8,
            'name' => 'Rudi',
            'email' => 'hakimindonusa@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'Sales',
        ]);

        User::create([
            'id' => 9,
            'name' => 'Usman',
            'email' => 'warehouse@example.com',
            'password' => Hash::make('password'),
            'role' => 'Warehouse',
        ]);

        User::create([
            'id' => 10,
            'name' => 'Finance',
            'email' => 'finance@example.com',
            'password' => Hash::make('password'),
            'role' => 'Finance',
        ]);

        // 2. Create Customers
        $customer1 = Customer::create([
            'id' => 1,
            'customer_name' => 'PT. Maju Bersama',
            'npwp' => '0873627166789883',
            'term_of_payments' => 30,
            'credit_limit' => '100000000',
            'email' => 'contact@majubersama.com',
            'phone' => '021-1234567',
            'billing_address' => 'Jl. Merdeka No. 10, Jakarta Pusat',
            'shipping_address' => 'Kawasan Industri Jababeka Blok C-12, Bekasi',
            'city' => 'Bekasi',
            'province' => 'Jawa Barat',
            'pic' => 'Budi Santoso',
            'customer_type' => 'Swasta',
            'status' => 'active'
        ]);

        $customer2 = Customer::create([
            'id' => 2,
            'customer_name' => 'PT. Teknologi Masa Depan',
            'npwp' => '012345678901000',
            'term_of_payments' => 30,
            'credit_limit' => '100000000',
            'email' => 'contact@teknologimasadepan.com',
            'phone' => '021-9876543',
            'billing_address' => 'Jl. Dago No. 45, Bandung',
            'shipping_address' => 'Kawasan Singosari, Malang',
            'city' => 'Malang',
            'province' => 'Jawa Timur',
            'pic' => 'Jane Doe',
            'customer_type' => 'Swasta',
            'status' => 'active'
        ]);

        $customer3 = Customer::create([
            'id' => 3,
            'customer_name' => 'CV. Cahaya Abadi',
            'npwp' => '034567891234000',
            'term_of_payments' => 14,
            'credit_limit' => '50000000',
            'email' => 'purchasing@cahayaabadi.com',
            'phone' => '031-555666',
            'billing_address' => 'Jl. Darmo No. 88, Surabaya',
            'shipping_address' => 'Rungkut Industri No. 12, Surabaya',
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'pic' => 'Adi Wijaya',
            'customer_type' => 'Swasta',
            'status' => 'active'
        ]);

        $customer4 = Customer::create([
            'id' => 4,
            'customer_name' => 'PT. Global Pratama',
            'npwp' => '098765432109876',
            'term_of_payments' => 30,
            'credit_limit' => '80000000',
            'email' => 'contact@globalpratama.com',
            'phone' => '021-888999',
            'billing_address' => 'Jl. Sudirman No. 100, Jakarta Selatan',
            'shipping_address' => 'Kawasan Industri Pulogadung, Jakarta Timur',
            'city' => 'Jakarta Timur',
            'province' => 'DKI Jakarta',
            'pic' => 'Tono',
            'customer_type' => 'Swasta',
            'status' => 'inactive'
        ]);

        // 3. Create PICs
        DB::table('pics')->insert([
            ['id' => 1, 'customer_id' => 1, 'name' => 'Budi Santoso', 'phone' => '08123456789', 'email' => 'budi@majubersama.com', 'position' => 'Procurement Manager', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'customer_id' => 2, 'name' => 'Jane Doe', 'phone' => '08234567890', 'email' => 'jane@teknologimasadepan.com', 'position' => 'Procurement Specialist', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'customer_id' => 3, 'name' => 'Adi Wijaya', 'phone' => '08345678901', 'email' => 'adi@cahayaabadi.com', 'position' => 'Purchasing Lead', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'customer_id' => 4, 'name' => 'Tono', 'phone' => '08987654321', 'email' => 'tono@globalpratama.com', 'position' => 'Staff Purchasing', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. Create Approved Goods (Catalog products)
        $goods1 = Goods::create([
            'id' => 1,
            'request_type' => 'primary',
            'goods_code' => 'MSC-38274',
            'goods_status' => 'approved',
            'goods_name' => 'Laptop Asus ExpertBook',
            'category' => 'MISCELLANEOUS',
            'stock' => 10,
            'buy_price' => 8500000,
            'selling_price' => 11000000,
            'unit' => 'Unit',
            'location' => 'Rak A1',
            'status_listing' => 'listing',
            'description' => 'Laptop Asus ExpertBook for office work.',
            'form' => '3'
        ]);

        $goods2 = Goods::create([
            'id' => 2,
            'request_type' => 'primary',
            'goods_code' => 'MSC-43750',
            'goods_status' => 'approved',
            'goods_name' => 'Printer Canon Pixma',
            'category' => 'MISCELLANEOUS',
            'stock' => 5,
            'buy_price' => 2000000,
            'selling_price' => 2500000,
            'unit' => 'Unit',
            'location' => 'Rak B2',
            'status_listing' => 'listing',
            'description' => 'Printer inkjet for document printing.',
            'form' => '3'
        ]);

        $goods3 = Goods::create([
            'id' => 3,
            'request_type' => 'primary',
            'goods_code' => 'PKG-32543',
            'goods_status' => 'approved',
            'goods_name' => 'Buble Wrap 1m x 50m',
            'category' => 'PACKAGING',
            'stock' => 100,
            'buy_price' => 50000,
            'selling_price' => 65000,
            'unit' => 'Roll',
            'location' => 'Rak C3',
            'status_listing' => 'listing',
            'description' => 'High quality protection bubble wrap.',
            'form' => '3'
        ]);

        $goods4 = Goods::create([
            'id' => 4,
            'request_type' => 'primary',
            'goods_code' => 'PKG-94812',
            'goods_status' => 'approved',
            'goods_name' => 'Kardus Packing Corrugated',
            'category' => 'PACKAGING',
            'stock' => 500,
            'buy_price' => 5000,
            'selling_price' => 7500,
            'unit' => 'Pcs',
            'location' => 'Rak C4',
            'status_listing' => 'listing',
            'description' => 'Double wall corrugated carton boxes.',
            'form' => '3'
        ]);

        $goods5 = Goods::create([
            'id' => 5,
            'request_type' => 'primary',
            'goods_code' => 'MSC-37281',
            'goods_status' => 'approved',
            'goods_name' => 'Pulpen Biru 0.5',
            'category' => 'MISCELLANEOUS',
            'stock' => 200,
            'buy_price' => 2500,
            'selling_price' => 3500,
            'unit' => 'Pcs',
            'location' => 'Rak E5',
            'status_listing' => 'listing',
            'description' => 'Blue ink ballpoint pen.',
            'form' => '3'
        ]);

        // Create GoodsReceipts for initial stock
        foreach ([$goods1, $goods2, $goods3, $goods4, $goods5] as $g) {
            DB::table('goods_receipts')->insert([
                'good_id' => $g->id,
                'supplier_id' => 3, // GA
                'received_at' => now()->subDays(5),
                'approved_by' => 2, // Supervisor
                'quantity' => $g->stock,
                'unit_cost' => $g->buy_price,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ]);
        }

        // 5. Create Custom Quotations
        $cq2 = CustomQuotation::create([
            'id' => 2,
            'sales_id' => 4, // Hilmi
            'quotation_number' => 'CQ-20260805-002',
            'to' => 'PT. Teknologi Masa Depan',
            'up' => 'Jane Doe',
            'subject' => 'Pengadaan Printer Khusus',
            'email' => 'jane@teknologimasadepan.com',
            'our_ref' => 'REF/IDN/2026/08/002',
            'date' => Carbon::now()->subDays(2)->toDateString(),
            'intro_text' => 'Bersama ini kami kirimkan harga penawaran printer khusus berkecepatan tinggi.',
            'subtotal' => 7500000,
            'tax' => 825000,
            'grand_total' => 8325000,
            'status' => 'sent_to_quotation',
            'approved_by' => 2,
            'approved_at' => now(),
            'expired_at' => Carbon::now()->addDays(12),
        ]);
        CustomQuotationItem::create([
            'custom_quotation_id' => $cq2->id,
            'goods_id' => null,
            'product_name' => 'Custom Printer High-Speed',
            'category' => 'MISCELLANEOUS',
            'qty' => 3,
            'unit' => 'Unit',
            'price' => 2500000,
            'subtotal' => 7500000,
            'discount' => 0,
        ]);

        $cq3 = CustomQuotation::create([
            'id' => 3,
            'sales_id' => 5, // Ryujin
            'quotation_number' => 'CQ-20260805-003',
            'to' => 'CV. Cahaya Abadi',
            'up' => 'Adi Wijaya',
            'subject' => 'Pengadaan Laptop Kantor Cabang',
            'email' => 'adi@cahayaabadi.com',
            'our_ref' => 'REF/IDN/2026/08/003',
            'date' => Carbon::now()->subDays(1)->toDateString(),
            'intro_text' => 'Berikut rincian harga untuk kebutuhan laptop kantor cabang CV Cahaya Abadi.',
            'subtotal' => 11000000,
            'tax' => 1210000,
            'grand_total' => 12210000,
            'status' => 'approved_supervisor',
            'approved_by' => 2,
            'approved_at' => now(),
            'expired_at' => Carbon::now()->addDays(13),
        ]);
        CustomQuotationItem::create([
            'custom_quotation_id' => $cq3->id,
            'goods_id' => 1,
            'product_name' => 'Laptop Asus ExpertBook',
            'category' => 'MISCELLANEOUS',
            'qty' => 1,
            'unit' => 'Unit',
            'price' => 11000000,
            'subtotal' => 11000000,
            'discount' => 0,
        ]);

        $cq4 = CustomQuotation::create([
            'id' => 4,
            'sales_id' => 4, // Hilmi
            'quotation_number' => 'CQ-20260805-004',
            'to' => 'PT. Maju Bersama',
            'up' => 'Budi Santoso',
            'subject' => 'Peralatan Packaging',
            'email' => 'budi@majubersama.com',
            'our_ref' => 'REF/IDN/2026/08/004',
            'date' => Carbon::now()->subDays(4)->toDateString(),
            'intro_text' => 'Penawaran perlengkapan packaging bubble wrap gulung.',
            'subtotal' => 1300000,
            'tax' => 143000,
            'grand_total' => 1443000,
            'status' => 'rejected_supervisor',
            'approved_by' => 2,
            'approved_at' => now(),
            'reason' => 'Diskon 15% pada bubble wrap terlalu tinggi untuk volume pembelian rendah.',
            'expired_at' => Carbon::now()->addDays(10),
        ]);
        CustomQuotationItem::create([
            'custom_quotation_id' => $cq4->id,
            'goods_id' => 3,
            'product_name' => 'Buble Wrap 1m x 50m',
            'category' => 'PACKAGING',
            'qty' => 20,
            'unit' => 'Roll',
            'price' => 65000,
            'subtotal' => 1300000,
            'discount' => 15,
        ]);

        // 6. Seed Standard Quotations & Orders

        // --- Standard Quotation 1: Converted from Custom Quotation 3 (Status: open, waiting to send to warehouse)
        $q1 = Quotation::create([
            'id' => 1,
            'custom_quotation_id' => 3,
            'request_number' => 'REQ-20260805-001',
            'quotation_number' => 'QTN-20260805-001',
            'sales_order_number' => 'SO-20260805-001',
            'sales_id' => 5, // Ryujin
            'customer_name' => 'CV. Cahaya Abadi',
            'customer_id' => 3,
            'pic_id' => 3,
            'pic_name' => 'Adi Wijaya',
            'subject' => 'Pengadaan Laptop Kantor Cabang',
            'subtotal' => 11000000,
            'tax' => 1210000,
            'grand_total' => 12210000,
            'product_category' => 'MISCELLANEOUS',
            'no_po' => 'PO/CAHAYA/111/2026',
            'approved_by' => 2,
            'approved_at' => now(),
        ]);
        QuotationItem::create([
            'quotation_id' => $q1->id,
            'goods_id' => 1,
            'custom_product_name' => 'Laptop Asus ExpertBook',
            'quantity' => 1,
            'price' => 11000000,
            'subtotal' => 11000000,
            'discount_percent' => 0,
            'product_category' => 'MISCELLANEOUS',
        ]);
        $order1 = Order::create([
            'id' => 1,
            'order_number' => 'ORD-20260805-0001',
            'sales_id' => 5,
            'customer_name' => 'CV. Cahaya Abadi',
            'customer_id' => 3,
            'quotation_id' => $q1->id,
            'status' => 'open',
            'delivery_options' => 'full',
            'required_date' => Carbon::now()->addDays(5)->toDateString(),
        ]);
        OrderItem::create([
            'order_id' => $order1->id,
            'goods_id' => 1,
            'quantity' => 1,
            'delivered_quantity' => 0,
            'item_status' => 'pending',
            'price' => 11000000,
            'subtotal' => 11000000,
        ]);
        // --- Standard Quotation 3: Direct Standard (Status: completed, fully delivered)
        $q3 = Quotation::create([
            'id' => 3,
            'request_number' => 'REQ-20260805-003',
            'quotation_number' => 'QTN-20260805-003',
            'sales_order_number' => 'SO-20260805-003',
            'sales_id' => 4, // Hilmi
            'customer_name' => 'PT. Maju Bersama',
            'customer_id' => 1,
            'pic_id' => 1,
            'pic_name' => 'Budi Santoso',
            'subject' => 'Stok Bubble Wrap Produksi',
            'subtotal' => 6500000,
            'tax' => 715000,
            'grand_total' => 7215000,
            'product_category' => 'PACKAGING',
            'no_po' => 'PO/MAJU/999/2026',
            'approved_by' => 2,
            'approved_at' => now(),
        ]);
        QuotationItem::create([
            'quotation_id' => $q3->id,
            'goods_id' => 3,
            'custom_product_name' => 'Buble Wrap 1m x 50m',
            'quantity' => 100,
            'price' => 65000,
            'subtotal' => 6500000,
            'discount_percent' => 0,
            'product_category' => 'PACKAGING',
        ]);
        $order3 = Order::create([
            'id' => 3,
            'order_number' => 'ORD-20260805-0003',
            'sales_id' => 4,
            'customer_name' => 'PT. Maju Bersama',
            'customer_id' => 1,
            'quotation_id' => $q3->id,
            'status' => 'completed',
            'delivery_options' => 'full',
            'required_date' => Carbon::now()->subDays(2)->toDateString(),
        ]);
        $oi3 = OrderItem::create([
            'id' => 3,
            'order_id' => $order3->id,
            'goods_id' => 3,
            'quantity' => 100,
            'delivered_quantity' => 100,
            'item_status' => 'delivered',
            'price' => 65000,
            'subtotal' => 6500000,
        ]);
        // Seed Delivery Batch
        $db1 = DB::table('delivery_batches')->insertGetId([
            'order_id' => $order3->id,
            'batch_number' => 1,
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);
        DB::table('delivery_batch_items')->insert([
            'delivery_batch_id' => $db1,
            'order_item_id' => $oi3->id,
            'quantity_sent' => 100,
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        // --- Standard Quotation 4: Direct Standard (Status: not_completed, partial delivery)
        $q4 = Quotation::create([
            'id' => 4,
            'request_number' => 'REQ-20260805-004',
            'quotation_number' => 'QTN-20260805-004',
            'sales_order_number' => 'SO-20260805-004',
            'sales_id' => 4, // Hilmi
            'customer_name' => 'PT. Teknologi Masa Depan',
            'customer_id' => 2,
            'pic_id' => 2,
            'pic_name' => 'Jane Doe',
            'subject' => 'Kebutuhan Kardus Corrugated',
            'subtotal' => 1500000,
            'tax' => 165000,
            'grand_total' => 1665000,
            'product_category' => 'PACKAGING',
            'no_po' => 'PO/TECH/777/2026',
            'approved_by' => 2,
            'approved_at' => now(),
        ]);
        QuotationItem::create([
            'quotation_id' => $q4->id,
            'goods_id' => 4,
            'custom_product_name' => 'Kardus Packing Corrugated',
            'quantity' => 200,
            'price' => 7500,
            'subtotal' => 1500000,
            'discount_percent' => 0,
            'product_category' => 'PACKAGING',
        ]);
        $order4 = Order::create([
            'id' => 4,
            'order_number' => 'ORD-20260805-0004',
            'sales_id' => 4,
            'customer_name' => 'PT. Teknologi Masa Depan',
            'customer_id' => 2,
            'quotation_id' => $q4->id,
            'status' => 'not_completed',
            'delivery_options' => 'partial',
            'required_date' => Carbon::now()->addDays(3)->toDateString(),
        ]);
        $oi4 = OrderItem::create([
            'id' => 4,
            'order_id' => $order4->id,
            'goods_id' => 4,
            'quantity' => 200,
            'delivered_quantity' => 120,
            'item_status' => 'partially_delivered',
            'price' => 7500,
            'subtotal' => 1500000,
        ]);
        // Seed Delivery Batch
        $db2 = DB::table('delivery_batches')->insertGetId([
            'order_id' => $order4->id,
            'batch_number' => 1,
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);
        DB::table('delivery_batch_items')->insert([
            'delivery_batch_id' => $db2,
            'order_item_id' => $oi4->id,
            'quantity_sent' => 120,
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        // --- Standard Quotation 5: Converted from Custom Quotation 2 (Status: under_procurement)
        $q5 = Quotation::create([
            'id' => 5,
            'custom_quotation_id' => 2,
            'request_number' => 'REQ-20260805-005',
            'quotation_number' => 'QTN-20260805-005',
            'sales_order_number' => 'SO-20260805-005',
            'sales_id' => 4, // Hilmi
            'customer_name' => 'PT. Teknologi Masa Depan',
            'customer_id' => 2,
            'pic_id' => 2,
            'pic_name' => 'Jane Doe',
            'subject' => 'Pengadaan Printer Khusus',
            'subtotal' => 7500000,
            'tax' => 825000,
            'grand_total' => 8325000,
            'product_category' => 'MISCELLANEOUS',
            'no_po' => 'PO/TECH/888/2026',
            'approved_by' => 2,
            'approved_at' => now(),
        ]);
        QuotationItem::create([
            'quotation_id' => $q5->id,
            'goods_id' => null,
            'custom_product_name' => 'Custom Printer High-Speed',
            'quantity' => 3,
            'price' => 2500000,
            'subtotal' => 7500000,
            'discount_percent' => 0,
            'product_category' => 'MISCELLANEOUS',
        ]);
        $order5 = Order::create([
            'id' => 5,
            'order_number' => 'ORD-20260805-0005',
            'sales_id' => 4,
            'customer_name' => 'PT. Teknologi Masa Depan',
            'customer_id' => 2,
            'quotation_id' => $q5->id,
            'status' => 'under_procurement',
            'delivery_options' => 'full',
            'required_date' => Carbon::now()->addDays(5)->toDateString(),
        ]);
        OrderItem::create([
            'order_id' => $order5->id,
            'goods_id' => null,
            'custom_product_name' => 'Custom Printer High-Speed',
            'category' => 'MISCELLANEOUS',
            'quantity' => 3,
            'delivered_quantity' => 0,
            'item_status' => 'pending_stock',
            'price' => 2500000,
            'subtotal' => 7500000,
        ]);

        // Now seed the custom goods linked to this procurement request:
        $goodsCustom = Goods::create([
            'id' => 6,
            'request_type' => 'primary',
            'goods_code' => 'MSC-55122',
            'goods_status' => 'pending',
            'goods_name' => 'Custom Printer High-Speed',
            'category' => 'MISCELLANEOUS',
            'stock' => 0,
            'buy_price' => 2000000,
            'selling_price' => 2500000,
            'unit' => 'Unit',
            'location' => 'Rak B3',
            'status_listing' => 'non_listing',
            'description' => 'High speed customized printer for office billing.',
            'form' => '4' // Hilmi
        ]);

        // Seed ProcurementOfGoods records:
        $procurement = ProcurementOfGoods::create([
            'id' => 1,
            'procurement_number' => 'PROC-20260805-0001',
            'custom_quotation_id' => 2,
            'general_affair_id' => 3, // Fahd
            'status' => 'pending',
            'notes' => 'Pengadaan barang custom printer berkecepatan tinggi.',
        ]);

        $procItem = ProcurementOfGoodsItem::create([
            'id' => 1,
            'procurement_of_goods_id' => $procurement->id,
            'goods_id' => $goodsCustom->id,
            'qty_requested' => 3,
            'qty_ordered' => 3,
            'qty_received' => 0,
            'unit' => 'Unit',
            'buy_price' => 2000000,
            'selling_price' => 2500000,
            'status' => 'pending',
        ]);

        // Seed ProcurementArrivalRequest:
        ProcurementArrivalRequest::create([
            'procurement_of_goods_item_id' => $procItem->id,
            'good_id' => $goodsCustom->id,
            'received_at' => now(),
            'quantity' => 3,
            'unit_cost' => 2000000,
            'status' => 'pending',
        ]);

        // 7. Seed Goods histories for stock movement log
        DB::table('goods_histories')->insert([
            [
                'goods_id' => 1,
                'goods_code' => 'MSC-38274',
                'goods_name' => 'Laptop Asus ExpertBook',
                'category' => 'MISCELLANEOUS',
                'stock' => 10,
                'unit' => 'Unit',
                'location' => 'Rak A1',
                'buy_price' => 8500000,
                'selling_price' => 11000000,
                'old_status' => 'pending',
                'new_status' => 'approved',
                'changed_by' => 3,
                'form' => 3,
                'note' => 'Stok awal masuk gudang.',
                'changed_at' => now()->subDays(5),
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'goods_id' => 3,
                'goods_code' => 'PKG-32543',
                'goods_name' => 'Buble Wrap 1m x 50m',
                'category' => 'PACKAGING',
                'stock' => 0,
                'unit' => 'Roll',
                'location' => 'Rak C3',
                'buy_price' => 50000,
                'selling_price' => 65000,
                'old_status' => 'approved',
                'new_status' => 'out',
                'changed_by' => 9,
                'form' => 9,
                'note' => 'Barang keluar untuk pengiriman ORD-20260805-0003.',
                'changed_at' => now()->subDays(1),
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
        ]);

        // 8. Seed Catalog Brochures
        Catalog::create([
            'brand_name' => 'ASUS',
            'catalog_name' => 'Asus Business Laptops 2026',
            'catalog_file' => 'catalogs/asus_2026.pdf',
            'catalog_cover' => 'catalogs/covers/asus.jpg',
        ]);
        Catalog::create([
            'brand_name' => 'CANON',
            'catalog_name' => 'Canon Office Printers & Copiers',
            'catalog_file' => 'catalogs/canon_office.pdf',
            'catalog_cover' => 'catalogs/covers/canon.jpg',
        ]);

        // 9. Seed System Settings
        SystemSetting::updateOrCreate(['key' => 'company_name'], ['value' => 'PT. INDONUSA JAYA BERSAMA']);
        SystemSetting::updateOrCreate(['key' => 'company_address'], ['value' => 'Wonorejo Selatan VB No. 50 Rungkut, Surabaya - 60296']);
        SystemSetting::updateOrCreate(['key' => 'company_phone'], ['value' => '08121634173']);
        SystemSetting::updateOrCreate(['key' => 'company_email'], ['value' => 'info@indonusa.com']);
        SystemSetting::updateOrCreate(['key' => 'leader_name'], ['value' => 'Alimul Imam S.AP']);
        SystemSetting::updateOrCreate(['key' => 'leader_position'], ['value' => 'Direktur']);
    }
}
