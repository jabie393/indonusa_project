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
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');

        // Daftar Procurement yang sudah dibuat
        $procurements = ProcurementOfGoods::with(['customQuotation', 'items.goods', 'generalAffair'])
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
            })
            ->latest()
            ->paginate($perPage, ['*'], 'proc_page')
            ->appends($request->except(['proc_page', 'pending_page']));

        $pendingQuotations = CustomQuotation::where('status', 'sent_to_quotation')
            ->whereHas('order', function ($q) {
                $q->where('status', 'sent_to_warehouse');
            })
            ->doesntHave('procurementOfGoods')
            ->with('items')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('quotation_number', 'like', "%{$search}%")
                        ->orWhere('to', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('date', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage, ['*'], 'pending_page')
            ->appends($request->except(['pending_page', 'proc_page']));

        return view('admin.procurement.index', compact('procurements', 'pendingQuotations'));
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

        $customQuotation->load('items.goods');

        return view('admin.procurement.create', compact('customQuotation'));
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
     * Detail procurement dan form pencatatan kedatangan.
     */
    public function show(ProcurementOfGoods $procurement)
    {
        $procurement->load(['customQuotation', 'items.goods', 'generalAffair', 'items.goodsReceipts.approver']);

        return view('admin.procurement.show', compact('procurement'));
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
                break;
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
}
