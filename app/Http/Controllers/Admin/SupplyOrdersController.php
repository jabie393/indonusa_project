<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class SupplyOrdersController extends Controller
{
    // Tampilkan daftar barang yang statusnya 'ditinjau' dengan search dan pagination
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10); // Default to 10
        $query = $request->input('search');
        $barangs = Barang::where('status_barang', 'ditinjau');

        if ($query) {
            $barangs = $barangs->where(function ($q) use ($query) {
                $q->where('nama_barang', 'like', "%{$query}%")
                    ->orWhere('kode_barang', 'like', "%{$query}%")
                    ->orWhere('lokasi', 'like', "%{$query}%")
                    ->orWhere('status_barang', 'like', "%{$query}%")
                    ->orWhere('kategori', 'like', "%{$query}%");
            });
        }

        $barangs = $barangs->paginate($perPage)->appends($request->except('page'));
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

            // Hapus record new_stock tanpa memicu event model (agar tidak tercatat history delete)
            Barang::withoutEvents(function () use ($barang) {
                $barang->delete();
            });
        }

        return redirect()->route('supply-orders.index')->with('success', 'Barang berhasil diapprove.');
    }

    // Reject barang dengan alasan (catatan)
    public function reject($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->catatan = request('catatan');
        $barang->status_barang = 'ditolak';
        $barang->save();

        return response()->json(['success' => true]);
    }
}
