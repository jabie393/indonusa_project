<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class ImportExcelController extends Controller
{
    // Tampilkan daftar barang (purchase request)
    public function index()
    {
        $barangs = Barang::all();
        $kategoriList = Barang::KATEGORI; // Ambil daftar kategori dari model Barang

        return view('admin.import-excel.index', compact('barangs', 'kategoriList'));
    }

    // Simpan barang baru
    public function store(Request $request)
    {

        // If an Excel file is uploaded via AJAX (field name `excel`), store it and return JSON.
        if ($request->hasFile('excel')) {
            $file = $request->file('excel');
            // store in public disk under imports/ with automatic filename
            $folder = 'imports';
            $path = $file->store($folder, 'public');

            return response()->json([
                'message' => 'File uploaded',
                'path' => $path,
                'url' => asset('storage/' . $path),
            ], 200);
        }

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
