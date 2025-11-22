<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class AddStockController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $query = $request->input('search');
        $barangs = Barang::where('status_barang', 'masuk');

        if ($query) {
            $barangs = $barangs->where(function ($q) use ($query) {
                $q->where('nama_barang', 'like', "%{$query}%")
                    ->orWhere('kode_barang', 'like', "%{$query}%");
            });
        }

        $barangs = $barangs->paginate($perPage)->appends($request->except('page')); // <-- Use paginate here

        return view('admin.add-stock.index', compact('barangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'status_listing' => 'required|in:listing,non listing',
            'kode_barang' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'stok' => 'required|integer',
            'satuan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        if (empty($validated['deskripsi'])) {
            $validated['deskripsi'] = '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."';
        }

        $barang = Barang::create($validated);

        if ($request->hasFile('gambar')) {
            $folder = 'barang/' . $barang->id;
            $path = $request->file('gambar')->store($folder, 'public');
            $barang->gambar = $path;
            $barang->save();
        }

        return redirect()->route('add-stock.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil ditambahkan!.']);
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
