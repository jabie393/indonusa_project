<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class GoodsInStatusController extends Controller
{
    // Tampilkan daftar barang (Item Status)
    public function index()
    {
        $barangs = Barang::whereIn('status_barang', ['ditinjau', 'ditolak'])->get(); // Hanya ambil barang dengan status 'ditinjau' atau 'ditolak'
        return view('admin.goods-in-status.index', compact('barangs'));
    }

    // Tampilkan form tambah barang
    public function create()
    {
        return view('admin.goods-in-status.create');
    }

    // Tampilkan detail barang
    public function show($id)
    {
        $barang = Barang::findOrFail($id);
        return view('admin.goods-in-status.show', compact('barang'));
    }

    // Tampilkan form edit barang
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('admin.goods-in-status.edit', compact('barang'));
    }

    // Update barang
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        if ($request->has('stok') && !$request->has('nama_barang')) {
            // hanya update stok
            $request->validate([
                'stok' => 'required|integer',
            ]);
            $barang->stok = $request->stok;
            $barang->save();
        } else {
            // update full barang
            $request->validate([
                'status_listing' => 'required|string',
                'kode_barang' => 'required|string|max:255',
                'nama_barang' => 'required|string|max:255',
                'kategori' => 'required|string|max:255',
                'stok' => 'required|integer',
                'satuan' => 'required|string|max:255',
                'lokasi' => 'required|string|max:255',
                'harga' => 'required|numeric',
                'deskripsi' => 'nullable|string',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->only([
                'status_listing',
                'kode_barang',
                'nama_barang',
                'kategori',
                'stok',
                'satuan',
                'lokasi',
                'harga',
                'deskripsi'
            ]);

            $oldGambar = $barang->gambar;

            $barang->update($data);

            if ($request->hasFile('gambar')) {
                $folder = 'barang/' . $barang->id;
                $path = $request->file('gambar')->store($folder, 'public');
                $barang->gambar = $path;
                $barang->save();

                // Hapus gambar lama jika ada
                if ($oldGambar && \Storage::disk('public')->exists($oldGambar)) {
                    \Storage::disk('public')->delete($oldGambar);
                }
            }
        }

        return redirect()->route('goods-in-status.index')->with('success', 'Barang berhasil diupdate.');
    }

    // Hapus barang
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        // Hapus folder gambar barang beserta isinya jika ada
        $folder = 'barang/' . $barang->id;
        if (\Storage::disk('public')->exists($folder)) {
            \Storage::disk('public')->deleteDirectory($folder);
        }

        // Hapus data barang di database
        $barang->delete();

        return redirect()->route('goods-in-status.index')->with('success', 'Barang berhasil dihapus.');
    }
}
