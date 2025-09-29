<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class WarehouseController extends Controller
{
    public function index()
    {
        $barangs = Barang::where('status_barang', 'masuk')->get(); // hanya yang statusnya masuk
        return view('admin.warehouse.index', compact('barangs'));
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

        // Set default deskripsi jika tidak diisi
        if (empty($validated['deskripsi'])) {
            $validated['deskripsi'] = '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."';
        }

        $validated['status_barang'] = 'masuk'; // Set status_barang masuk
        $validated['tipe_request'] = 'primary'; // Set tipe_request primary

        $barang = Barang::create($validated);

        if ($request->hasFile('gambar')) {
            $folder = 'barang/' . $barang->id;
            $path = $request->file('gambar')->store($folder, 'public');
            $barang->gambar = $path;
            $barang->save();
        }

        return redirect()->route('warehouse.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil ditambahkan!']);
    }

    public function update(Request $request, $id)
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

        $validated['tipe_request'] = 'primary'; // Set tipe_request primary

        $barang = Barang::findOrFail($id);
        $oldGambar = $barang->gambar; // Simpan path gambar lama

        $barang->update($validated);

        if ($request->hasFile('gambar')) {
            // Upload gambar baru
            $folder = 'barang/' . $barang->id;
            $path = $request->file('gambar')->store($folder, 'public');
            $barang->gambar = $path;
            $barang->save();

            // Hapus gambar lama jika ada
            if ($oldGambar && \Storage::disk('public')->exists($oldGambar)) {
                \Storage::disk('public')->delete($oldGambar);
            }
        }

        return redirect()->route('warehouse.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil diupdate!']);
    }

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

        return redirect()->route('warehouse.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil dihapus!']);
    }
}
