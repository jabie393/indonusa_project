<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomQuotation;
use App\Models\CustomQuotationItem;
use App\Models\ProcurementOfGoods;
use App\Models\ProcurementOfGoodsItem;
use App\Models\GoodsReceipt;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcurementController extends Controller
{
    /**
     * Tampilkan daftar procurement dan Custom Quotation pending.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('perPage', 10);
        $search = $request->input('search');

        // 1. Daftar Procurement yang sudah dibuat
        $procurementQuery = ProcurementOfGoods::with(['customQuotation', 'items.goods', 'generalAffair', 'items.goodsReceipts'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('procurement_number', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhereHas('customQuotation', function ($customQuotationQuery) use ($search) {
                            $customQuotationQuery->where('quotation_number', 'like', "%{$search}%")
                                ->orWhere('to', 'like', "%{$search}%")
                                ->orWhere('subject', 'like', "%{$search}%");
                        })
                        ->orWhereHas('generalAffair', function ($generalAffairQuery) use ($search) {
                            $generalAffairQuery->where('name', 'like', "%{$search}%");
                        });
                });
            });
        $procurements = $procurementQuery->get();

        // 2. Daftar Custom Quotation pending (belum diproses)
        $pendingQuery = CustomQuotation::where('status', 'sent_to_quotation')
            ->whereHas('order', function ($q) {
                $q->where('status', 'sent_to_warehouse');
            })
            ->doesntHave('procurementOfGoods')
            ->with(['items', 'sales'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('quotation_number', 'like', "%{$search}%")
                        ->orWhere('to', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('date', 'like', "%{$search}%")
                        ->orWhereHas('sales', function ($salesQuery) use ($search) {
                            $salesQuery->where('name', 'like', "%{$search}%");
                        });
                });
            });
        $pendingQuotations = $pendingQuery->get();

        // Merge both collections
        $merged = $procurements->concat($pendingQuotations);

        // Sort by created_at desc
        $merged = $merged->sortByDesc(function ($item) {
            return $item->created_at;
        });

        // Paginate manually
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $merged->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $items = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $merged->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.procurement.index', compact('items'));
    }

    /**
     * Form untuk membuat procurement baru dari Custom Quotation.
     */
    public function create(CustomQuotation $customQuotation)
    {
        if ($customQuotation->status !== 'sent_to_quotation') {
            return redirect()->route('general-affair.procurement.index')
                ->withErrors('Custom Quotation ini tidak dalam status menunggu pengadaan.');
        }

        $order = $customQuotation->order;
        if (!$order || $order->status !== 'sent_to_warehouse') {
            return redirect()->route('general-affair.procurement.index')
                ->withErrors('Order untuk Custom Quotation ini belum dikirim ke warehouse.');
        }

        if ($customQuotation->procurementOfGoods()->exists()) {
            return redirect()->route('general-affair.procurement.index')
                ->withErrors('Custom Quotation ini sudah diproses untuk pengadaan.');
        }

        return redirect()->route('general-affair.procurement.index', ['open_create' => $customQuotation->id]);
    }

    /**
     * Simpan procurement baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'custom_quotation_id' => 'required|exists:custom_quotations,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.goods_id' => 'required|exists:goods,id',
            'items.*.qty_requested' => 'required|integer|min:1',
            'items.*.qty_ordered' => 'required|integer|min:1',
            'items.*.buy_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $customQuotation = CustomQuotation::findOrFail($validated['custom_quotation_id']);

            if ($customQuotation->status !== 'sent_to_quotation') {
                return back()->withErrors('Custom Quotation ini tidak dalam status menunggu pengadaan.')->withInput();
            }

            $order = $customQuotation->order;
            if (!$order || $order->status !== 'sent_to_warehouse') {
                return back()->withErrors('Order untuk Custom Quotation ini belum dikirim ke warehouse.')->withInput();
            }

            if ($customQuotation->procurementOfGoods()->exists()) {
                return back()->withErrors('Custom Quotation ini sudah diproses untuk pengadaan.')->withInput();
            }

            $procurement = ProcurementOfGoods::create([
                'procurement_number' => ProcurementOfGoods::generateProcurementNumber(),
                'custom_quotation_id' => $customQuotation->id,
                'general_affair_id' => Auth::id(),
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $itemData) {
                $barang = Barang::findOrFail($itemData['goods_id']);

                ProcurementOfGoodsItem::create([
                    'procurement_of_goods_id' => $procurement->id,
                    'goods_id' => $barang->id,
                    'qty_requested' => $itemData['qty_requested'],
                    'qty_ordered' => $itemData['qty_ordered'],
                    'qty_received' => 0,
                    'unit' => $barang->unit,
                    'buy_price' => $itemData['buy_price'],
                    'selling_price' => 0.00,
                    'status' => 'pending',
                ]);
            }

            DB::commit();

            return redirect()->route('general-affair.procurement.show', $procurement->id)
                ->with(['title' => 'Berhasil', 'text' => "Procurement {$procurement->procurement_number} berhasil dibuat."]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Procurement Store Error: ' . $e->getMessage());
            return back()->withErrors('Gagal membuat procurement: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Simpan procurement baru via modal.
     */
    public function storeModal(Request $request)
    {
        $validated = $request->validate([
            'custom_quotation_id' => 'required|exists:custom_quotations,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.goods_id' => 'required|exists:goods,id',
            'items.*.qty_requested' => 'required|integer|min:1',
            'items.*.qty_ordered' => 'required|integer|min:1',
            'items.*.buy_price' => 'required|numeric|min:0',
            'type' => 'required|in:full,partial',
        ]);

        DB::beginTransaction();
        try {
            $customQuotation = CustomQuotation::findOrFail($validated['custom_quotation_id']);

            if ($customQuotation->status !== 'sent_to_quotation') {
                return response()->json(['success' => false, 'message' => 'Custom Quotation ini tidak dalam status menunggu pengadaan.'], 400);
            }

            $order = $customQuotation->order;
            if (!$order || $order->status !== 'sent_to_warehouse') {
                return response()->json(['success' => false, 'message' => 'Order untuk Custom Quotation ini belum dikirim ke warehouse.'], 400);
            }

            if ($customQuotation->procurementOfGoods()->exists()) {
                return response()->json(['success' => false, 'message' => 'Custom Quotation ini sudah diproses untuk pengadaan.'], 400);
            }

            $procurement = ProcurementOfGoods::create([
                'procurement_number' => ProcurementOfGoods::generateProcurementNumber(),
                'custom_quotation_id' => $customQuotation->id,
                'general_affair_id' => Auth::id(),
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $itemData) {
                $barang = Barang::findOrFail($itemData['goods_id']);

                $procItem = ProcurementOfGoodsItem::create([
                    'procurement_of_goods_id' => $procurement->id,
                    'goods_id' => $barang->id,
                    'qty_requested' => $itemData['qty_requested'],
                    'qty_ordered' => $itemData['qty_ordered'],
                    'qty_received' => 0,
                    'unit' => $barang->unit,
                    'buy_price' => $itemData['buy_price'],
                    'selling_price' => 0.00,
                    'status' => 'pending',
                ]);

                if ($validated['type'] === 'full') {
                    // Buat GoodsReceipt penuh otomatis
                    GoodsReceipt::create([
                        'good_id' => $barang->id,
                        'procurement_of_goods_item_id' => $procItem->id,
                        'supplier_id' => Auth::id(), // GA user who records
                        'received_at' => now(),
                        'approved_by' => null, // Waiting Warehouse approval
                        'quantity' => $itemData['qty_ordered'],
                        'unit_cost' => $itemData['buy_price'],
                    ]);
                }
            }

            DB::commit();

            if ($validated['type'] === 'full') {
                return response()->json([
                    'success' => true,
                    'type' => 'full',
                    'message' => "Procurement {$procurement->procurement_number} berhasil dibuat secara full. Menunggu approval Warehouse.",
                ]);
            } else {
                // Partial - load detail HTML to be rendered in modal
                $procurement->load(['customQuotation', 'items.goods', 'generalAffair', 'items.goodsReceipts.approver']);
                $html = view('admin.procurement.partials.procurement-detail-modal-body', compact('procurement'))->render();
                return response()->json([
                    'success' => true,
                    'type' => 'partial',
                    'procurement_id' => $procurement->id,
                    'html' => $html,
                    'message' => "Procurement {$procurement->procurement_number} berhasil dibuat secara parsial.",
                ]);
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Procurement Store Modal Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal membuat procurement: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Ambil detail procurement dalam bentuk HTML partial.
     */
    public function detailHtml(ProcurementOfGoods $procurement)
    {
        $procurement->load(['customQuotation', 'items.goods', 'generalAffair', 'items.goodsReceipts.approver']);
        return view('admin.procurement.partials.procurement-detail-modal-body', compact('procurement'));
    }

    /**
     * Detail procurement dan form pencatatan kedatangan.
     */
    public function show(ProcurementOfGoods $procurement)
    {
        return redirect()->route('general-affair.procurement.index', ['open_show' => $procurement->id]);
    }

    /**
     * Catat kedatangan barang (parsial/full) menunggu approval Warehouse.
     */
    public function recordArrival(Request $request, ProcurementOfGoods $procurement)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.goods_id' => 'required|exists:goods,id',
            'items.*.procurement_item_id' => 'required|exists:procurement_of_goods_items,id',
            'items.*.qty_arriving' => 'required|integer|min:0',
            'items.*.buy_price' => 'required|numeric|min:0',
        ]);

        $anyArriving = false;
        foreach ($validated['items'] as $itemData) {
            if ($itemData['qty_arriving'] > 0) {
                $anyArriving = true;

                $procItem = ProcurementOfGoodsItem::findOrFail($itemData['procurement_item_id']);
                
                // Hitung kuantitas pending yang sudah diajukan sebelumnya
                $alreadyPending = GoodsReceipt::where('procurement_of_goods_item_id', $procItem->id)
                    ->where('status', 'pending')
                    ->sum('quantity');
                
                $maxAllowed = max(0, $procItem->qty_ordered - $procItem->qty_received - $alreadyPending);

                if ($itemData['qty_arriving'] > $maxAllowed) {
                    return back()->withErrors("Kuantitas datang untuk '{$procItem->goods->goods_name}' ({$itemData['qty_arriving']}) melebihi sisa batas yang dapat dicatat. Maksimal yang dapat dicatat saat ini adalah {$maxAllowed} (memperhitungkan kedatangan lain yang masih pending approval).");
                }
            }
        }

        if (!$anyArriving) {
            return back()->withErrors('Kuantitas datang untuk minimal satu item harus lebih besar dari 0.');
        }

        DB::beginTransaction();
        try {
            foreach ($validated['items'] as $itemData) {
                if ($itemData['qty_arriving'] > 0) {
                    $procItem = ProcurementOfGoodsItem::find($itemData['procurement_item_id']);
                    if ($procItem) {
                        $procItem->update(['buy_price' => $itemData['buy_price']]);
                    }

                    GoodsReceipt::create([
                        'good_id' => $itemData['goods_id'],
                        'procurement_of_goods_item_id' => $itemData['procurement_item_id'],
                        'supplier_id' => Auth::id(), // User GA yang mencatat
                        'received_at' => now(),
                        'approved_by' => null, // Menunggu approval Warehouse
                        'quantity' => $itemData['qty_arriving'],
                        'unit_cost' => $itemData['buy_price'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('general-affair.procurement.show', $procurement->id)
                ->with(['title' => 'Berhasil', 'text' => 'Kedatangan barang berhasil dicatat. Menunggu approval Warehouse.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Record Arrival Error: ' . $e->getMessage());
            return back()->withErrors('Gagal mencatat kedatangan: ' . $e->getMessage());
        }
    }

    /**
     * Paksa selesai procurement (Force Complete) jika sisa barang tidak datang.
     */
    public function forceComplete(ProcurementOfGoods $procurement)
    {
        DB::beginTransaction();
        try {
            $procurement->update(['status' => 'completed']);

            foreach ($procurement->items as $item) {
                $item->update(['status' => 'completed']);
            }

            // Status Custom Quotation dipertahankan pada 'sent_to_quotation' sesuai dengan alur baru.

            DB::commit();

            return redirect()->route('general-affair.procurement.show', $procurement->id)
                ->with(['title' => 'Berhasil', 'text' => 'Procurement berhasil dipaksa selesai (Force Completed). Status Custom Quotation diubah menjadi Ready for Delivery.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Force Complete Procurement Error: ' . $e->getMessage());
            return back()->withErrors('Gagal memproses Force Complete: ' . $e->getMessage());
        }
    }

    /**
     * Revisi kedatangan barang kustom yang ditolak oleh Warehouse.
     */
    public function updateReceipt(Request $request, GoodsReceipt $receipt)
    {
        if ($receipt->status !== 'rejected') {
            return back()->withErrors('Hanya kedatangan barang yang ditolak yang dapat direvisi.');
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
        ]);

        $procItem = $receipt->procurementOfGoodsItem;
        if ($procItem) {
            $otherPendingQty = GoodsReceipt::where('procurement_of_goods_item_id', $procItem->id)
                ->where('id', '!=', $receipt->id)
                ->where('status', 'pending')
                ->sum('quantity');
            $maxAllowed = max(0, $procItem->qty_ordered - $procItem->qty_received - $otherPendingQty);

            if ($validated['quantity'] > $maxAllowed) {
                return back()->withErrors("Kuantitas revisi ({$validated['quantity']}) melebihi batas yang diperbolehkan. Maksimal kuantitas yang dapat diterima adalah {$maxAllowed}.");
            }
        }

        DB::beginTransaction();
        try {
            $receipt->update([
                'quantity' => $validated['quantity'],
                'unit_cost' => $validated['unit_cost'],
                'status' => 'pending',
                'reject_reason' => null,
            ]);

            // Perbarui buy_price pada item pengadaan jika harga beli diubah
            $procItem = $receipt->procurementOfGoodsItem;
            if ($procItem) {
                $procItem->update(['buy_price' => $validated['unit_cost']]);
            }

            DB::commit();

            return redirect()->route('general-affair.procurement.show', $procItem->procurement_of_goods_id)
                ->with(['title' => 'Berhasil', 'text' => 'Penerimaan barang kustom berhasil direvisi dan dikirim kembali untuk review.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Update Receipt Error: ' . $e->getMessage());
            return back()->withErrors('Gagal merevisi kedatangan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus kedatangan barang (GoodsReceipt) yang belum approved.
     */
    public function destroyReceipt(GoodsReceipt $receipt)
    {
        if ($receipt->status === 'approved') {
            return back()->withErrors('Kedatangan barang yang sudah disetujui (Approved) tidak dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            $procurementId = $receipt->procurementOfGoodsItem->procurement_of_goods_id;

            $receipt->delete();

            DB::commit();

            return redirect()->route('general-affair.procurement.show', $procurementId)
                ->with(['title' => 'Berhasil', 'text' => 'Catatan kedatangan barang kustom berhasil dihapus.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Destroy Receipt Error: ' . $e->getMessage());
            return back()->withErrors('Gagal menghapus kedatangan barang: ' . $e->getMessage());
        }
    }
}

