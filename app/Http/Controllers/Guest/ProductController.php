<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Barang;

class ProductController extends Controller
{
    public function index()
    {
        $goods = Barang::all();
        return view('guest.order.product', compact('goods'));
    }
    public function barang($id)
    {
        $barang = Barang::find($id);
        return view('guest.order.product', compact('barang'));
    }
}
