<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class PurchaseRequestController extends Controller
{
    // Tampilkan daftar barang (purchase request)
    public function index()
    {
        $barangs = Barang::all();
        return view('admin.purchase-request.index', compact('barangs'));
    }

    // Tampilkan form tambah barang
    public function create()
    {
        return view('admin.purchase-request.create');
    }

    // Simpan barang baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        $validated = $request->all();
        $validated['status_barang'] = 'ditinjau'; // Set status_barang ditinjau

        Barang::create($validated);

        return redirect()->route('purchase-request.index')->with('success', 'Barang berhasil ditambahkan.');
    }
}
