<?php
namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class SupervisorDashboardController extends Controller
{
    public function dashboard(Request $request = null)
    {
        $data = [
            'totalBarang'   => Barang::count(),
            'totalStok'     => Barang::sum('stok'),
            'lowStockItems' => Barang::where('stok', '<=', 10)->orderBy('stok')->get(),
        ];

        return view('dashboard.supervisor.index', $data);
    }
}
