<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class GoodsInController extends Controller
{
    // Tampilkan daftar barang (purchase request)
    public function index()
    {
        $barangs = Barang::all();
        return view('admin.goods-in.index', compact('barangs'));
    }

    // Tampilkan form tambah barang
    public function create()
    {
        return view('admin.goods-in.create');
    }

    // Simpan barang baru
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

        $validated['status_barang'] = 'ditinjau';
        $validated['tipe_request'] = 'primary'; // Set tipe_request primary

        $barang = Barang::create($validated);

        if ($request->hasFile('gambar')) {
            $folder = 'barang/' . $barang->id;
            $path = $request->file('gambar')->store($folder, 'public');
            $barang->gambar = $path;
            $barang->save();
        }

        return redirect()->route('goods-in.index')->with('success', 'Barang berhasil ditambahkan.');
    }
}
