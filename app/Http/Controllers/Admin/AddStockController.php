<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;

class AddStockController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $query = $request->input('search');
        $goods = Barang::where('status_barang', 'masuk');

        if ($query) {
            $goods = $goods->where(function ($q) use ($query) {
                $q->where('nama_barang', 'like', "%{$query}%")
                    ->orWhere('kode_barang', 'like', "%{$query}%");
            });
        }

        $goods = $goods->paginate($perPage)->appends($request->except('page')); // <-- Use paginate here

        return view('admin.add-stock.index', compact('goods'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'stok' => 'required|integer',
        ]);

        $barang = Barang::findOrFail($id);

        // Copy data baru dengan stok baru dan status_barang 'ditinjau'
        $copyData = $barang->replicate();
        $copyData->stok = $validated['stok'];
        $copyData->status_barang = 'ditinjau';
        $copyData->tipe_request = 'new_stock'; // Set tipe_request ke new_stock

        // Simpan id user yang submit ke kolom 'form'
        $copyData->form = Auth::id();

        // Generate kode_barang unik
        $originalKode = $barang->kode_barang;
        $newKode = $originalKode;
        $i = 1;
        while (\App\Models\Barang::where('kode_barang', $newKode)->exists()) {
            $newKode = $originalKode . '#' . $i;
            $i++;
        }
        $copyData->kode_barang = $newKode;

        $copyData->save();

        return redirect()->route('add-stock.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil diajukan peninjauan!']);
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        // Hapus folder gambar barang beserta isinya jika ada
        $folder = 'barang/' . $barang->id;
        if (\Storage::disk('public')->exists($folder)) {
            \Storage::disk('public')->deleteDirectory($folder);
        }

        $barang->delete();

        return redirect()->route('add-stock.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil dihapus!.']);
    }
}
