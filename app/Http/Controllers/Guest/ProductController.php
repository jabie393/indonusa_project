<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Goods;

class ProductController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $allCategories = Goods::select('category')->distinct()->whereNotNull('category')->orderBy('category')->pluck('category');

        $query = Goods::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $goods = $query->get();
        return view('guest.product.index', compact('goods', 'allCategories'));
    }
    public function show($id)
    {
        $product = Goods::find($id);

        $relatedGoods = collect();
        if ($product && $product->category) {
            $relatedGoods = Goods::where('category', $product->category)
                ->where('id', '!=', $product->id)
                ->take(6)
                ->get();
        }

        return view('guest.product.detail', compact('product', 'relatedGoods'));
    }
}
