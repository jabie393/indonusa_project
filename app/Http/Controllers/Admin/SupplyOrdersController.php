<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupplyOrdersController extends Controller
{
    // Tampilkan daftar barang yang statusnya 'ditinjau' dengan search dan pagination
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10); // Default to 10
        $query = $request->input('search');

        // 1. Reguler Goods In (pending, not linked to procurement, excluding custom items)
        $regulerQuery = Barang::where('goods_status', 'pending')
            ->where('status_listing', '!=', 'non_listing')
            ->whereDoesntHave('procurementOfGoodsItems');

        if ($query) {
            $regulerQuery->where(function ($q) use ($query) {
                $q->where('goods_name', 'like', "%{$query}%")
                    ->orWhere('goods_code', 'like', "%{$query}%")
                    ->orWhere('location', 'like', "%{$query}%")
                    ->orWhere('category', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });
        }
        $goods = $regulerQuery->paginate($perPage, ['*'], 'reguler_page')->appends($request->except('reguler_page'));

        // 2. Custom Procurement receipts (pending approval)
        $procurementQuery = \App\Models\GoodsReceipt::whereNull('approved_by')
            ->with(['good', 'supplier', 'procurementOfGoodsItem.procurementOfGoods.customQuotation']);

        if ($query) {
            $procurementQuery->whereHas('good', function ($q) use ($query) {
                $q->where('goods_name', 'like', "%{$query}%")
                    ->orWhere('goods_code', 'like', "%{$query}%");
            });
        }
        $procurementReceipts = $procurementQuery->paginate($perPage, ['*'], 'proc_page')->appends($request->except('proc_page'));

        return view('admin.supply-orders.index', compact('goods', 'procurementReceipts'));
    }

    // Approve barang reguler
    public function approve($id)
    {
        $this->processApproval($id);
        return redirect()->route('supply-orders.index')->with(['title' => 'Berhasil', 'text' => 'Barang reguler berhasil diapprove.']);
    }

    // Store action as fallback to prevent method missing errors if form mis-submits
    public function store(Request $request)
    {
        return redirect()->back()->withErrors('Invalid action specified.');
    }

    // Reject barang reguler
    public function reject(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->note = $request->input('reason') ?? $request->input('catatan');
        $barang->goods_status = 'rejected';
        $barang->save();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('supply-orders.index')->with(['title' => 'Berhasil', 'text' => 'Barang reguler berhasil ditolak.']);
    }

    // Bulk Approve reguler
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

    // Bulk Reject reguler
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

    /**
     * Approve penerimaan barang kustom procurement
     */
    public function approveProcurement(Request $request, $receiptId)
    {
        DB::beginTransaction();
        try {
            $receipt = \App\Models\GoodsReceipt::findOrFail($receiptId);
            $procItem = $receipt->procurementOfGoodsItem;
            if (!$procItem) {
                throw new \Exception('Item pengadaan tidak ditemukan untuk kedatangan ini.');
            }

            $goods = $receipt->good;
            if (!$goods) {
                throw new \Exception('Master barang tidak ditemukan.');
            }

            // 1. Update master barang
            $goods->stock += $receipt->quantity;
            $goods->buy_price = $receipt->unit_cost;
            if (empty($goods->selling_price) || (float)$goods->selling_price === 0.0) {
                $goods->selling_price = round($receipt->unit_cost * 1.15, 2);
            }
            $goods->goods_status = 'approved';
            $goods->save();

            // 2. Approve the receipt
            $receipt->approved_by = Auth::id();
            $receipt->save();

            // 3. Update procurement item
            $procItem->qty_received += $receipt->quantity;
            if ($procItem->qty_received >= $procItem->qty_ordered) {
                $procItem->status = 'completed';
            } else {
                $procItem->status = 'partial_received';
            }
            $procItem->save();

            // 4. Update procurement status
            $procurement = $procItem->procurementOfGoods;
            $allCompleted = true;
            foreach ($procurement->items as $item) {
                if ($item->status !== 'completed') {
                    $allCompleted = false;
                }
            }
            $procurement->status = $allCompleted ? 'completed' : 'partial_received';
            $procurement->warehouse_id = Auth::id();
            $procurement->save();

            // Status Custom Quotation dipertahankan pada 'sent_to_quotation' sesuai dengan alur baru.

            DB::commit();
            return redirect()->route('supply-orders.index')->with(['title' => 'Berhasil', 'text' => 'Penerimaan barang kustom berhasil disetujui.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Approve Procurement Receipt Error: ' . $e->getMessage());
            return back()->withErrors('Gagal menyetujui penerimaan barang kustom: ' . $e->getMessage());
        }
    }

    /**
     * Tolak penerimaan barang kustom procurement
     */
    public function rejectProcurement(Request $request, $receiptId)
    {
        DB::beginTransaction();
        try {
            $receipt = \App\Models\GoodsReceipt::findOrFail($receiptId);
            $receipt->delete();

            DB::commit();
            return redirect()->route('supply-orders.index')->with(['title' => 'Berhasil', 'text' => 'Penerimaan barang kustom ditolak dan dihapus.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Reject Procurement Receipt Error: ' . $e->getMessage());
            return back()->withErrors('Gagal menolak penerimaan barang kustom: ' . $e->getMessage());
        }
    }
}
