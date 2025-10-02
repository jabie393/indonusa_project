<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;

class SupplyOrdersController extends Controller
{
    // Tampilkan daftar barang yang statusnya 'ditinjau'
    public function index()
    {
        $barangs = Barang::where('status_barang', 'ditinjau')->get();
        return view('admin.supply-orders.index', compact('barangs'));
    }

    // Approve barang
    public function approve($id)
    {
        $barang = Barang::findOrFail($id);

        if ($barang->tipe_request == 'primary') {
            $barang->status_barang = 'masuk';
            $barang->save();
        } elseif ($barang->tipe_request == 'new_stock') {
            $kodeUtama = explode('-', $barang->kode_barang)[0];
            $barangUtama = Barang::where('kode_barang', $kodeUtama)
                ->where('tipe_request', 'primary')
                ->first();

            if ($barangUtama) {
                $barangUtama->stok += $barang->stok;
                $barangUtama->save();
            }

            $barang->status_barang = 'masuk';
            $barang->save();
            $barang->delete();
        }

        return redirect()->route('supply-orders.index')->with('success', 'Barang berhasil diapprove.');
    }

    // Reject/hapus barang
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('supply-orders.index')->with('success', 'Barang berhasil dihapus.');
    }
}
