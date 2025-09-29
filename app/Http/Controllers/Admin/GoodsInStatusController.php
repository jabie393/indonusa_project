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
        $barangs = Barang::where('status_barang', 'ditinjau')->get(); // hanya yang statusnya ditinjau
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
        $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return redirect()->route('goods-in-status.index')->with('success', 'Barang berhasil diupdate.');
    }

    // Hapus barang
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('goods-in-status.index')->with('success', 'Barang berhasil dihapus.');
    }
}
