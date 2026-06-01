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
        $goods = Barang::where('goods_status', 'approved');

        if ($query) {
            $goods = $goods->where(function ($q) use ($query) {
                $q->where('goods_name', 'like', "%{$query}%")
                    ->orWhere('goods_code', 'like', "%{$query}%");
            });
        }

        $goods = $goods->paginate($perPage)->appends($request->except('page')); // <-- Use paginate here

        return view('admin.add-stock.index', compact('goods'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'stock' => 'required|integer',
            'unit_cost' => 'required|numeric|min:0',
        ]);

        $barang = Barang::findOrFail($id);

        // Copy data baru dengan stok baru dan status_barang 'ditinjau'
        $copyData = $barang->replicate();
        $copyData->stock = $validated['stock'];
        $copyData->buy_price = $validated['unit_cost'];
        $copyData->goods_status = 'pending';
        $copyData->request_type = 'new_stock'; // Set tipe_request ke new_stock

        // Simpan id user yang submit ke kolom 'form'
        $copyData->form = Auth::id();

        // Generate kode_barang unik
        $originalKode = $barang->goods_code;
        $newKode = $originalKode;
        $i = 1;
        while (\App\Models\Barang::where('goods_code', $newKode)->exists()) {
            $newKode = $originalKode . '#' . $i;
            $i++;
        }
        $copyData->goods_code = $newKode;

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
