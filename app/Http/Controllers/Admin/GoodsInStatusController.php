<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class GoodsInStatusController extends Controller
{
    // Tampilkan daftar barang (Item Status)
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $query = $request->input('search');
        $barangs = Barang::whereIn('status_barang', ['ditinjau', 'ditolak']);

        if ($query) {
            $barangs = $barangs->where(function ($q) use ($query) {
                $q->where('nama_barang', 'like', "%{$query}%")
                    ->orWhere('kode_barang', 'like', "%{$query}%")
                    ->orWhere('kategori', 'like', "%{$query}%");
            });
        }
        $barangs = $barangs->paginate($perPage)->appends($request->except('page'));
        return view('admin.goods-in-status.index', compact('barangs'));
    }

    // Tampilkan detail barang
    public function show($id)
    {
        $barang = Barang::findOrFail($id);
        return view('admin.goods-in-status.show', compact('barang'));
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
            $barang->status_barang = 'ditinjau'; // ubah status_barang
            $barang->catatan = null; // kosongkan kolom catatan
            $barang->save();
        } else {
            // update full barang
            $request->validate([
                'status_listing' => 'required|string',
                'kode_barang' => 'required|string|max:255',
                'nama_barang' => 'required|string|max:255',
                'kategori' => 'required|in:' . implode(',', Barang::KATEGORI),
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

            $data['status_barang'] = 'ditinjau'; // ubah status_barang
            $data['catatan'] = null; // kosongkan kolom catatan

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

        return redirect()->route('goods-in-status.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil diupdate.']);
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

        return redirect()->route('goods-in-status.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil dihapus.']);
    }
}
