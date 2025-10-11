<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DeliveryOrdersController extends Controller
{
    // Tampilkan daftar orders yang statusnya 'sent_to_warehouse'
    public function index()
    {
        // Ambil semua orders yang dikirim ke warehouse, eager-load relasi pt dan items.barang
        $orders = Order::with(['pt', 'items.barang'])
            ->where('status', 'sent_to_warehouse')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.delivery-orders.index', compact('orders'));
    }
}
