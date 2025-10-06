<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;

class DeliveryOrdersController extends Controller
{
    // Tampilkan daftar barang yang statusnya 'ditinjau'
    public function index()
    {
        $barangs = collect();
        return view('admin.delivery-orders.index', compact('barangs'));
    }
}
