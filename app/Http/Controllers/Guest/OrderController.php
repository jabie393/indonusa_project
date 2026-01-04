<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Barang;

class OrderController extends Controller
{
    public function index()
    {
        $goods = Barang::all();
        return view('guest.order.index', compact('goods'));
    }
}
