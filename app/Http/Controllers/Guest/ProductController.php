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

        $relatedGoods = collect();
        if ($barang && $barang->kategori) {
            $relatedGoods = Barang::where('kategori', $barang->kategori)
                ->where('id', '!=', $barang->id)
                ->take(6)
                ->get();
        }

        return view('guest.order.product', compact('barang', 'relatedGoods'));
    }
}
