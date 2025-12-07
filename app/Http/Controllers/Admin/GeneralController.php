<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\User;

class GeneralController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        if (! $user) {
            return redirect()->route('login');
        }

        // jika pakai spatie/permission
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('Sales')) {
                return app(\App\Http\Controllers\Admin\SalesDashboardController::class)->dashboard();
            }
            if ($user->hasRole('Supervisor')) {
                return app(\App\Http\Controllers\Admin\SupervisorDashboardController::class)->dashboard();
            }
            if ($user->hasRole('Warehouse')) {
                return app(\App\Http\Controllers\Admin\WarehouseDashboardController::class)->dashboard(request());
            }
            if ($user->hasRole('General Affair')) {
                return app(\App\Http\Controllers\Admin\GeneralAffairDashboardController::class)->dashboard();
            }
        }

        // fallback berdasarkan kolom role di users table
        $role = strtolower($user->role ?? '');
        $r = str_replace(' ', '', $role);
        switch ($r) {
            case 'sales':
                return app(\App\Http\Controllers\Admin\SalesDashboardController::class)->dashboard();
            case 'supervisor':
                return app(\App\Http\Controllers\Admin\SupervisorDashboardController::class)->dashboard();
            case 'warehouse':
                return app(\App\Http\Controllers\Admin\WarehouseDashboardController::class)->dashboard(request());
            case 'generalaffair':
                return app(\App\Http\Controllers\Admin\GeneralAffairDashboardController::class)->dashboard();
        }

        return view('dashboard.anonymous.index');
    }

    // Cek stok barang berdasarkan kode barang
    public function getStock($kode)
    {
        $barang = Barang::where('kode_barang', $kode)->first();

        return response()->json([
            'stok' => $barang ? $barang->stok : 0
        ]);
    }
    public function checkKodeBarang(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string'
        ]);

        $kodeBarang = $request->input('kode_barang');
        $exists = Barang::where('kode_barang', $kodeBarang)->exists();

        return response()->json([
            'valid' => !$exists,
            'kode_barang' => $kodeBarang
        ]);
    }

    public function checkEmail(Request $request)
    {
        $query = User::where('email', $request->email);
        if ($request->filled('id')) {
            $query->where('id', '!=', $request->id);
        }
        $exists = $query->exists();
        return response()->json(['exists' => $exists]);
    }
}
