<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Barang;

class OrderController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $allCategories = \App\Models\Barang::select('category')->distinct()->whereNotNull('category')->orderBy('category')->pluck('category');

        $query = \App\Models\Barang::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $goods = $query->get();
        return view('guest.order.index', compact('goods', 'allCategories'));
    }
}
