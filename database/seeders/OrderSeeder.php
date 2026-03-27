<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\RequestOrder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Simulasi login agar created_by/updated_by di model terisi
        Auth::loginUsingId(1);

        // 1. Ambil atau Buat Customer
        $customer = Customer::create([
            'nama_customer' => 'PT. Maju Bersama',
            'npwp' => '0873627166789883',
            'term_of_payments' => 30,
            'kredit_limit' => '100000000',
            'email' => 'contact@majubersama.com',
            'telepon' => '021-1234567',
            'alamat_penagihan' => 'Jl. Merdeka No. 10, Jakarta Pusat',
            'alamat_pengiriman' => 'Kawasan Industri Jababeka Blok C-12, Bekasi',
            'kota' => 'Bekasi',
            'provinsi' => 'Jawa Barat',
            'pic' => 'Budi Santoso',
            'tipe_customer' => 'Swasta',
            'created_by' => 1,
            'status' => 'active'
        ]);

        // 2. Buat PIC untuk Customer
        \Illuminate\Support\Facades\DB::table('pics')->insert([
            'customer_id' => $customer->id,
            'name' => 'Budi Santoso',
            'phone' => '08123456789',
            'email' => 'budi@majubersama.com',
            'position' => 'Procurement Manager',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Ambil User Sales
        $sales = User::where('role', 'Sales')->first() ?? User::find(4);
        $supervisor = User::where('role', 'Supervisor')->first() ?? User::find(2);
        $warehouse = User::where('role', 'Warehouse')->first() ?? User::find(6);

        // 4. Buat Request Order
        $requestOrder = RequestOrder::create([
            'request_number' => 'REQ-' . date('Ymd') . '-001',
            'sales_order_number' => 'SO-20260305-0001',
            'no_po' => 'PO/MAJU/321/2026',
            'sales_id' => $sales->id,
            'customer_id' => $customer->id,
            'customer_name' => $customer->nama_customer,
            'subject' => 'Permintaan Laptop dan Printer Office',
            'tanggal_kebutuhan' => now()->addDays(7),
            'subtotal' => 11000000,
            'tax' => 1210000,
            'grand_total' => 12210000,
            'kategori_barang' => 'MISCELLANEOUS',
        ]);

        // 5. Buat Order (Delivery Order Full)
        $order1 = Order::create([
            'order_number' => 'DO-' . date('Ymd') . '-0001',
            'sales_id' => $sales->id,
            'customer_id' => $customer->id,
            'customer_name' => $customer->nama_customer,
            'request_order_id' => $requestOrder->id,
            'supervisor_id' => $supervisor->id,
            'warehouse_id' => $warehouse->id,
            'status' => 'completed',
            'tanggal_kebutuhan' => now()->addDays(7),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'barang_id' => 1, // Laptop Asus ExpertBook (from BarangSeeder)
            'quantity' => 1,
            'delivered_quantity' => 1,
            'status_item' => 'delivered'
        ]);

        // 6. Order Kedua (Partial Delivery)
        $customer2 = Customer::create([
            'nama_customer' => 'PT. Teknologi Masa Depan',
            'npwp' => '012345678901000',
            'term_of_payments' => 30,
            'kredit_limit' => '100000000',
            'email' => 'contact@majubersama.com',
            'telepon' => '0211234567',
            'alamat_penagihan' => 'Jl. Bandung, Malang',
            'alamat_pengiriman' => 'Kawasan Blok M, Malang',
            'kota' => 'Malang',
            'provinsi' => 'Jawa Timur',
            'pic' => 'Jane Doe',
            'tipe_customer' => 'Swasta',
            'created_by' => 1,
            'status' => 'active'
        ]);

        // 2. Buat PIC untuk Customer
        \Illuminate\Support\Facades\DB::table('pics')->insert([
            'customer_id' => $customer2->id,
            'name' => 'Jane Doe',
            'phone' => '08123456789',
            'email' => 'jane@majubersama.com',
            'position' => 'Procurement Manager',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $requestOrder2 = RequestOrder::create([
            'request_number' => 'REQ-' . date('Ymd') . '-002',
            'sales_order_number' => 'SO-20260305-0002',
            'no_po' => 'PO/TECH/987/2026',
            'sales_id' => $sales->id,
            'customer_id' => $customer2->id,
            'customer_name' => $customer2->nama_customer,
            'subject' => 'Pemesanan Stock Handtools',
        ]);

        $order2 = Order::create([
            'order_number' => 'DO-' . date('Ymd') . '-0002',
            'sales_id' => $sales->id,
            'customer_id' => $customer2->id,
            'customer_name' => $customer2->nama_customer,
            'request_order_id' => $requestOrder2->id,
            'status' => 'not_completed',
        ]);

        $orderItem2 = OrderItem::create([
            'order_id' => $order2->id,
            'barang_id' => 3, // Buble Wrap (from BarangSeeder)
            'quantity' => 10,
            'delivered_quantity' => 5,
            'status_item' => 'partially_delivered'
        ]);

        // 7. Seed Delivery Batch for Partial Delivery
        $batch = \Illuminate\Support\Facades\DB::table('delivery_batches')->insertGetId([
            'order_id' => $order2->id,
            'batch_number' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \Illuminate\Support\Facades\DB::table('delivery_batch_items')->insert([
            'delivery_batch_id' => $batch,
            'order_item_id' => $orderItem2->id,
            'quantity_sent' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
