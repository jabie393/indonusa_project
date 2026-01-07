<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;

class SupplyOrdersController extends Controller
{
    // Tampilkan daftar barang yang statusnya 'ditinjau' dengan search dan pagination
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10); // Default to 10
        $query = $request->input('search');
        $goods = Barang::where('status_barang', 'ditinjau');

        if ($query) {
            $goods = $goods->where(function ($q) use ($query) {
                $q->where('nama_barang', 'like', "%{$query}%")
                    ->orWhere('kode_barang', 'like', "%{$query}%")
                    ->orWhere('lokasi', 'like', "%{$query}%")
                    ->orWhere('status_barang', 'like', "%{$query}%")
                    ->orWhere('kategori', 'like', "%{$query}%");
            });
        }

        $goods = $goods->paginate($perPage)->appends($request->except('page'));
        return view('admin.supply-orders.index', compact('goods'));
    }

    // Approve barang
    public function approve($id)
    {
        $this->processApproval($id);
        return redirect()->route('supply-orders.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil diapprove.']);
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

    // Bulk Approve
    public function bulkApprove(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        foreach ($ids as $id) {
            $this->processApproval($id);
        }

        return response()->json(['success' => true]);
    }

    // Bulk Reject
    public function bulkReject(Request $request)
    {
        $ids = $request->input('ids', []);
        $catatan = $request->input('catatan');

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        Barang::whereIn('id', $ids)->update([
            'status_barang' => 'ditolak',
            'catatan' => $catatan
        ]);

        return response()->json(['success' => true]);
    }

    protected function processApproval($id)
    {
        $barang = Barang::findOrFail($id);

        if ($barang->tipe_request == 'primary') {
            $barang->status_barang = 'masuk';
            $barang->save();
        } elseif ($barang->tipe_request == 'new_stock') {
            $kodeUtama = explode('#', $barang->kode_barang)[0];
            $barangUtama = Barang::where('kode_barang', $kodeUtama)
                ->where('tipe_request', 'primary')
                ->first();

            if ($barangUtama) {
                $barangUtama->stok += $barang->stok;
                $barangUtama->save();
            }

            // Buat record GoodsReceipt
            \App\Models\GoodsReceipt::create([
                'good_id' => $barangUtama ? $barangUtama->id : $barang->id,
                'supplier_id' => $barang->form, // User yang input (GA)
                'received_at' => now(),
                'approved_by' => Auth::id(), // User yang approve (Warehouse)
                'quantity' => $barang->stok,
                'unit_cost' => $barang->harga,
            ]);

            $barang->status_barang = 'masuk';
            $barang->save();

            // Hapus record new_stock tanpa memicu event model
            Barang::withoutEvents(function () use ($barang) {
                $barang->delete();
            });
        }
    }
}
