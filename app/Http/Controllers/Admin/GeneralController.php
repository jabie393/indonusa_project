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
        return view('admin.dashboard');
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
