<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class GeneralController extends Controller
{
    public function checkKodeBarang(Request $request)
    {
        $query = Barang::where('kode_barang', $request->kode_barang);
        if ($request->filled('id')) {
            $query->where('id', '!=', $request->id);
        }
        $exists = $query->exists();
        return response()->json(['exists' => $exists]);
    }
}
