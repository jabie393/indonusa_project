<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada barang dengan id=1 di tabel barangs, dan user id 4 = admin_sales (lihat SQL dump)
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(6)),
            'sales_id' => 4,
            'status' => 'pending'
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'barang_id' => 1,
            'quantity' => 5
        ]);
    }
}
