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
        $goods = Barang::where('goods_status', 'pending');

        if ($query) {
            $goods = $goods->where(function ($q) use ($query) {
                $q->where('goods_name', 'like', "%{$query}%")
                    ->orWhere('goods_code', 'like', "%{$query}%")
                    ->orWhere('location', 'like', "%{$query}%")
                    ->orWhere('goods_status', 'like', "%{$query}%")
                    ->orWhere('category', 'like', "%{$query}%");
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

    // Store action as fallback to prevent method missing errors if form mis-submits
    public function store(Request $request)
    {
        return redirect()->back()->withErrors('Invalid action specified.');
    }

    public function reject(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->note = $request->input('reason') ?? $request->input('catatan');
        $barang->goods_status = 'rejected';
        $barang->save();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('supply-orders.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil ditolak.']);
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
            'goods_status' => 'rejected',
            'note' => $catatan
        ]);

        return response()->json(['success' => true]);
    }

    protected function processApproval($id)
    {
        $barang = Barang::findOrFail($id);

        if ($barang->request_type == 'primary') {
            $barang->goods_status = 'approved';
            $barang->save();

            // Buat record GoodsReceipt untuk barang baru
            \App\Models\GoodsReceipt::create([
                'good_id' => $barang->id,
                'supplier_id' => $barang->form, // User yang input (GA)
                'received_at' => now(),
                'approved_by' => Auth::id(), // User yang approve (Warehouse)
                'quantity' => $barang->stock,
                'unit_cost' => $barang->buy_price,
            ]);
        } elseif ($barang->request_type == 'new_stock') {
            $kodeUtama = explode('#', $barang->goods_code)[0];
            $barangUtama = Barang::where('goods_code', $kodeUtama)
                ->where('request_type', 'primary')
                ->first();

            if ($barangUtama) {
                $barangUtama->stock += $barang->stock;
                $barangUtama->buy_price = $barang->buy_price; // Update buying price
                $barangUtama->save();

                // Buat record GoodsReceipt
                \App\Models\GoodsReceipt::create([
                    'good_id' => $barangUtama->id,
                    'supplier_id' => $barang->form, // User yang input (GA)
                    'received_at' => now(),
                    'approved_by' => Auth::id(), // User yang approve (Warehouse)
                    'quantity' => $barang->stock,
                    'unit_cost' => $barang->buy_price,
                ]);

                $barang->goods_status = 'approved';
                $barang->save();

                // Hapus record new_stock tanpa memicu event model
                Barang::withoutEvents(function () use ($barang) {
                    $barang->delete();
                });
            } else {
                // Jika barang utama tidak ditemukan, jadikan barang request ini sebagai primary
                $barang->goods_code = $kodeUtama;
                $barang->request_type = 'primary';
                $barang->goods_status = 'approved';
                $barang->save();

                // Buat record GoodsReceipt
                \App\Models\GoodsReceipt::create([
                    'good_id' => $barang->id,
                    'supplier_id' => $barang->form, // User yang input (GA)
                    'received_at' => now(),
                    'approved_by' => Auth::id(), // User yang approve (Warehouse)
                    'quantity' => $barang->stock,
                    'unit_cost' => $barang->buy_price,
                ]);
            }
        }
    }
}
