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

        Barang::create($request->all());

        return redirect()->route('purchase-request.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    // Tampilkan detail barang
    public function show($id)
    {
        $barang = Barang::findOrFail($id);
        return view('admin.purchase-request.show', compact('barang'));
    }

    // Tampilkan form edit barang
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('admin.purchase-request.edit', compact('barang'));
    }

    // Update barang
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return redirect()->route('purchase-request.index')->with('success', 'Barang berhasil diupdate.');
    }

    // Hapus barang
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('purchase-request.index')->with('success', 'Barang berhasil dihapus.');
    }
}
