<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\GoodsReceipt;
use App\Models\ProcurementArrivalRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupplyOrdersController extends Controller
{
    // Tampilkan daftar barang yang statusnya 'ditinjau' dengan search dan pagination
    public function index(Request $request)
    {
        $perPage = (int) $request->input('perPage', 10); // Default to 10
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
        $goodsItems = $regulerQuery->get();

        // 2. Custom Procurement receipts (pending approval)
        $procurementQuery = ProcurementArrivalRequest::where('status', 'pending')
            ->with(['good', 'supplier', 'procurementOfGoodsItem.procurementOfGoods.customQuotation']);

        if ($query) {
            $procurementQuery->whereHas('good', function ($q) use ($query) {
                $q->where('goods_name', 'like', "%{$query}%")
                    ->orWhere('goods_code', 'like', "%{$query}%");
            });
        }
        $procurements = $procurementQuery->get();

        // Merge both collections
        $merged = $goodsItems->concat($procurements);

        // Sort by created_at desc
        $merged = $merged->sortByDesc(function ($item) {
            return $item->created_at;
        });

        // Paginate manually
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $merged->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $goods = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $merged->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.supply-orders.index', compact('goods'));
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
            GoodsReceipt::create([
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
                GoodsReceipt::create([
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
                GoodsReceipt::create([
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
    public function approveProcurement(Request $request, $requestId)
    {
        DB::beginTransaction();
        try {
            $arrivalRequest = ProcurementArrivalRequest::findOrFail($requestId);
            $procItem = $arrivalRequest->procurementOfGoodsItem;
            if (!$procItem) {
                throw new \Exception('Item pengadaan tidak ditemukan untuk kedatangan ini.');
            }

            $goods = $arrivalRequest->good;
            if (!$goods) {
                throw new \Exception('Master barang tidak ditemukan.');
            }

            // Batasi kuantitas yang diapprove agar tidak melebihi sisa kuantitas pesanan
            $maxAllowed = max(0, $procItem->qty_ordered - $procItem->qty_received);
            if ($maxAllowed <= 0) {
                // Hapus receipt pending ini karena item sudah sepenuhnya terpenuhi
                $arrivalRequest->delete();
                DB::commit();
                return redirect()->route('supply-orders.index')->with(['title' => 'Perhatian', 'text' => 'Item pengadaan ini sudah sepenuhnya terpenuhi. Kedatangan redundant dibersihkan.']);
            }

            if ($arrivalRequest->quantity > $maxAllowed) {
                $arrivalRequest->quantity = $maxAllowed;
            }

            // 1. Update master barang
            $goods->stock += $arrivalRequest->quantity;
            $goods->buy_price = $arrivalRequest->unit_cost;
            if (empty($goods->selling_price) || (float)$goods->selling_price === 0.0) {
                $goods->selling_price = round($arrivalRequest->unit_cost * 1.15, 2);
            }
            $goods->goods_status = 'approved';
            $goods->save();

            // 2. Buat record di goods_receipts
            GoodsReceipt::create([
                'good_id' => $arrivalRequest->good_id,
                'supplier_id' => $arrivalRequest->supplier_id,
                'received_at' => $arrivalRequest->received_at ?? now(),
                'approved_by' => Auth::id(),
                'quantity' => $arrivalRequest->quantity,
                'unit_cost' => $arrivalRequest->unit_cost,
            ]);

            // 3. Update status arrivalRequest
            $arrivalRequest->status = 'approved';
            $arrivalRequest->save();

            // 4. Update procurement item
            $procItem->qty_received += $arrivalRequest->quantity;
            if ($procItem->qty_received >= $procItem->qty_ordered) {
                $procItem->status = 'completed';
                
                // Hapus record kedatangan pending lainnya untuk item ini karena sudah tercapai
                ProcurementArrivalRequest::where('procurement_of_goods_item_id', $procItem->id)
                    ->where('status', 'pending')
                    ->delete();
            } else {
                $procItem->status = 'partial_received';
            }
            $procItem->save();

            // 5. Update procurement status
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
    public function rejectProcurement(Request $request, $requestId)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $arrivalRequest = ProcurementArrivalRequest::findOrFail($requestId);
            $arrivalRequest->status = 'rejected';
            $arrivalRequest->reject_reason = $request->input('reason');
            $arrivalRequest->save();

            DB::commit();
            return redirect()->route('supply-orders.index')->with(['title' => 'Berhasil', 'text' => 'Penerimaan barang kustom berhasil ditolak.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Reject Procurement Receipt Error: ' . $e->getMessage());
            return back()->withErrors('Gagal menolak penerimaan barang kustom: ' . $e->getMessage());
        }
    }
}
