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
        $query = Barang::where('kode_barang', $request->kode_barang);
        if ($request->filled('id')) {
            $query->where('id', '!=', $request->id);
        }
        $exists = $query->exists();
        return response()->json(['exists' => $exists]);
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
