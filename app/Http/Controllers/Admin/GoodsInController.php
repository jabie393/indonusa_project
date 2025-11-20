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
        $kategoriList = Barang::KATEGORI; // Ambil daftar kategori dari model Barang

        return view('admin.goods-in.index', compact('barangs', 'kategoriList'));
    }

    // Tampilkan form tambah barang
    public function create()
    {
        // Ambil daftar kategori dari model Barang
        $kategoriList = Barang::KATEGORI;

        // Kirim daftar kategori ke view
        return view('admin.goods-in.index', compact('kategoriList'));
    }

    // Simpan barang baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'status_listing' => 'required|in:listing,non listing',
            'kode_barang' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|in:' . implode(',', Barang::KATEGORI), // Validasi kategori
            'stok' => 'required|integer',
            'satuan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        // Set default deskripsi jika tidak diisi
        if (empty($validated['deskripsi'])) {
            $validated['deskripsi'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
        }

        $validated['status_barang'] = 'ditinjau';
        $validated['tipe_request'] = 'primary'; // Set tipe_request primary

        $barang = Barang::create($validated);

        if ($request->hasFile('gambar')) {
            $folder = 'barang/' . $barang->id;
            $path = $request->file('gambar')->store($folder, 'public');
            $barang->gambar = $path;
            $barang->save();
        }

        return redirect()->route('goods-in.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil ditambahkan.']);
    }
}
