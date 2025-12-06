<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;

class GeneralAffairDashboardController extends Controller
{
    public function dashboard()
    {
        $data = [
            'totalBarang'   => Barang::count(),
            'totalStok'     => Barang::sum('stok'),
            'lowStockItems' => Barang::where('stok', '<=', 10)->orderBy('stok')->get(),
        ];

        return view('dashboard.general-affair.index', $data);
    }
}
