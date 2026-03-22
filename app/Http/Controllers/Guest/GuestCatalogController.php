<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Catalog;

class GuestCatalogController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $allBrands = \App\Models\Catalog::select('brand_name')->distinct()->orderBy('brand_name')->pluck('brand_name');

        $query = \App\Models\Catalog::query();

        if ($request->filled('brand')) {
            $query->where('brand_name', $request->brand);
        }

        $catalogs = $query->get();
        return view('guest.catalog.index', compact('catalogs', 'allBrands'));
    }
}
