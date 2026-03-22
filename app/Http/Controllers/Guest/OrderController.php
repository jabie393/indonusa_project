<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Barang;

class OrderController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $allCategories = \App\Models\Barang::select('kategori')->distinct()->whereNotNull('kategori')->orderBy('kategori')->pluck('kategori');

        $query = \App\Models\Barang::query();

        if ($request->filled('category')) {
            $query->where('kategori', $request->category);
        }

        $goods = $query->get();
        return view('guest.order.index', compact('goods', 'allCategories'));
    }
}
