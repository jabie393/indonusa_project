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
        $customer = Customer::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'nama_customer' => 'PT. Maju Bersama',
                'telepon' => '021-1234567',
                'alamat_penagihan' => 'Jl. Merdeka No. 10, Jakarta',
                'alamat_pengiriman' => 'Jl. Industri No. 5, Bekasi',
                'status' => 'active'
            ]
        );

        // 2. Ambil User Sales
        $sales = User::where('role', 'Sales')->first() ?? User::find(4);
        $supervisor = User::where('role', 'Supervisor')->first() ?? User::find(2);

        // 3. Buat Request Order (Sumber SO dan PO)
        $requestOrder = RequestOrder::create([
            'request_number' => 'REQ-' . date('Ymd') . '-001',
            'sales_order_number' => 'SO-20260216-0001',
            'no_po' => 'PO/CUST/123/2026',
            'sales_id' => $sales->id,
            'customer_id' => $customer->id,
            'customer_name' => $customer->nama_customer,
            'subject' => 'Permintaan Barang Alat Tulis Kantor',
            'status' => 'approved',
            'tanggal_kebutuhan' => now()->addDays(7),
        ]);

        // 4. Buat Order (Delivery Order) yang terhubung ke RequestOrder dan Customer
        $order1 = Order::create([
            'order_number' => 'DO-' . date('Ymd') . '-0001',
            'sales_id' => $sales->id,
            'customer_id' => $customer->id,
            'request_order_id' => $requestOrder->id,
            'supervisor_id' => $supervisor->id,
            'status' => 'sent_to_warehouse'
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'barang_id' => 1,
            'quantity' => 10
        ]);

        // 5. Order Kedua dengan data berbeda
        $customer2 = Customer::firstOrCreate(
            ['email' => 'budi@example.com'],
            [
                'nama_customer' => 'CV. Budi Luhur',
                'telepon' => '022-7654321',
                'alamat_penagihan' => 'Jl. Pahlawan No. 20, Bandung',
                'alamat_pengiriman' => 'Jl. Pahlawan No. 20, Bandung',
                'status' => 'active'
            ]
        );

        $requestOrder2 = RequestOrder::create([
            'request_number' => 'REQ-' . date('Ymd') . '-002',
            'sales_order_number' => 'SO-20260216-0002',
            'no_po' => 'PO/BUDI/456/2026',
            'sales_id' => 5, // Sales lainnya (Ryujin jika ada)
            'customer_id' => $customer2->id,
            'customer_name' => $customer2->nama_customer,
            'subject' => 'Pemesanan Suku Cadang Mesin',
            'status' => 'approved',
        ]);

        $order2 = Order::create([
            'order_number' => 'DO-' . date('Ymd') . '-0002',
            'sales_id' => 5,
            'customer_id' => $customer2->id,
            'request_order_id' => $requestOrder2->id,
            'status' => 'approved_warehouse'
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'barang_id' => 2,
            'quantity' => 20
        ]);
    }
}
