<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Goods;

class ProductController extends Controller
{
    public function index()
    {
        $goods = Goods::all();
        return view('guest.order.product', compact('goods'));
    }
    public function barang($id)
    {
        $barang = Goods::find($id);

        $relatedGoods = collect();
        if ($barang && $barang->category) {
            $relatedGoods = Goods::where('category', $barang->category)
                ->where('id', '!=', $barang->id)
                ->take(6)
                ->get();
        }

        return view('guest.order.product', compact('barang', 'relatedGoods'));
    }
}
