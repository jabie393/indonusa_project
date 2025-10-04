<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class GeneralController extends Controller
{
    public function checkKodeBarang(Request $request)
    {
        $exists = Barang::where('kode_barang', $request->kode_barang)->exists();
        return response()->json(['exists' => $exists]);
    }
}
