<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomQuotation;
use App\Models\CustomQuotationItem;
use App\Models\ProcurementOfGoods;
use App\Models\ProcurementOfGoodsItem;
use App\Models\GoodsReceipt;
use App\Models\ProcurementArrivalRequest;
use App\Models\Goods;
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

        // 1. Daftar Pengadaan Listing (Shortage Stok Katalog)
        $listingProcurementQuery = ProcurementOfGoods::where('status', '!=', 'canceled')
            ->whereNull('custom_quotation_id')
            ->where(function ($q) {
                $q->whereNull('order_id')
                  ->orWhereHas('order', function ($oq) {
                      $oq->where('status', '!=', 'canceled');
                  });
            })
            ->with(['order', 'items.goods', 'generalAffair', 'items.procurementArrivalRequests'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('procurement_number', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%")
                        ->orWhereHas('order', function ($orderQuery) use ($search) {
                            $orderQuery->where('order_number', 'like', "%{$search}%");
                        })
                        ->orWhereHas('generalAffair', function ($generalAffairQuery) use ($search) {
                            $generalAffairQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc');
        $listingItems = $listingProcurementQuery->paginate($perPage, ['*'], 'listing_page')->withQueryString();

        // 2. Daftar Pengadaan Non-Listing (Custom Quotation)
        $nonListingProcurementQuery = ProcurementOfGoods::where('status', '!=', 'canceled')
            ->whereNotNull('custom_quotation_id')
            ->with(['customQuotation', 'order', 'items.goods', 'generalAffair', 'items.procurementArrivalRequests'])
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
        $nonListingProcurements = $nonListingProcurementQuery->get();

        // 2b. Custom Quotation Pending (Belum Dibuat Procurement-nya)
        $pendingQuery = CustomQuotation::where('status', 'sent_to_quotation')
            ->whereHas('order', function ($q) {
                $q->where('status', 'under_procurement');
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

        $mergedNonListing = $nonListingProcurements->concat($pendingQuotations)->sortByDesc(function ($item) {
            return $item->created_at;
        });

        $currentNonListingPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage('non_listing_page');
        $nonListingSlice = $mergedNonListing->slice(($currentNonListingPage - 1) * $perPage, $perPage)->values();
        $nonListingItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $nonListingSlice,
            $mergedNonListing->count(),
            $perPage,
            $currentNonListingPage,
            ['path' => $request->url(), 'pageName' => 'non_listing_page', 'query' => $request->query()]
        );

        // 3. Combined Requirements for Tab 3
        $combinedRequirements = ProcurementOfGoodsItem::where('status', '!=', 'completed')
            ->where('status', '!=', 'canceled')
            ->whereRaw('qty_ordered > qty_received')
            ->whereHas('procurementOfGoods', function ($q) {
                $q->whereIn('status', ['pending', 'partial_received'])
                  ->where(function ($oq) {
                      $oq->whereNull('order_id')
                         ->orWhereHas('order', function ($orderQuery) {
                             $orderQuery->where('status', '!=', 'canceled');
                         });
                  });
            })
            ->with(['goods', 'procurementOfGoods.order', 'procurementOfGoods.customQuotation'])
            ->get()
            ->groupBy('goods_id')
            ->map(function ($items, $goodsId) {
                $goods = $items->first()->goods;
                $totalOrdered = $items->sum('qty_ordered');
                $totalReceived = $items->sum('qty_received');
                
                $breakdown = $items->flatMap(function ($item) {
                    // Check if there are linked order items via procurement_order_items
                    $linkedOrders = \Illuminate\Support\Facades\DB::table('procurement_order_items')
                        ->where('procurement_of_goods_item_id', $item->id)
                        ->join('order_items', 'procurement_order_items.order_item_id', '=', 'order_items.id')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->where('orders.status', '!=', 'canceled')
                        ->select('orders.id as order_id', 'orders.order_number', 'procurement_order_items.quantity as qty')
                        ->get();

                    if ($linkedOrders->isNotEmpty()) {
                        return $linkedOrders->map(function ($lo) {
                            return [
                                'source' => 'Listing (SO: ' . $lo->order_number . ')',
                                'url' => route('sales.sales-orders.show', $lo->order_id),
                                'qty' => $lo->qty,
                            ];
                        });
                    }

                    $source = '-';
                    $url = '#';
                    if ($item->procurementOfGoods->order) {
                        $source = 'Listing (SO: ' . $item->procurementOfGoods->order->order_number . ')';
                        $url = route('sales.sales-orders.show', $item->procurementOfGoods->order->id);
                    } elseif ($item->procurementOfGoods->customQuotation) {
                        $source = 'Non-Listing (Quotation: ' . $item->procurementOfGoods->customQuotation->quotation_number . ')';
                        $url = route('sales.custom-quotation.show', $item->procurementOfGoods->customQuotation->id);
                    }
                    return [[
                        'source' => $source,
                        'url' => $url,
                        'qty' => $item->qty_ordered - $item->qty_received,
                    ]];
                });

                return [
                    'goods_name' => $goods->goods_name ?? 'Unknown',
                    'goods_code' => $goods->goods_code ?? '-',
                    'total_ordered' => $totalOrdered,
                    'total_received' => $totalReceived,
                    'total_remaining' => max(0, $totalOrdered - $totalReceived),
                    'breakdown' => $breakdown,
                ];
            });

        return view('admin.procurement.index', compact('listingItems', 'nonListingItems', 'combinedRequirements'));
    }

    /**
     * Form untuk membuat procurement baru dari Custom Quotation.
     */
    public function create(CustomQuotation $customQuotation)
    {
        if ($customQuotation->status !== 'sent_to_quotation') {
            return redirect()->route('general-affair.procurement.index')
                ->withErrors('Custom Quotation ini tidak dalam status dikirim ke Quotation.');
        }

        $order = $customQuotation->order;
        if (!$order || $order->status !== 'under_procurement') {
            return redirect()->route('general-affair.procurement.index')
                ->withErrors('Order untuk Custom Quotation ini belum diajukan untuk procurement.');
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
                return back()->withErrors('Custom Quotation ini tidak dalam status dikirim ke Quotation.')->withInput();
            }

            $order = $customQuotation->order;
            if (!$order || $order->status !== 'under_procurement') {
                return back()->withErrors('Order untuk Custom Quotation ini belum diajukan untuk procurement.')->withInput();
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
                $barang = Goods::findOrFail($itemData['goods_id']);

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

            event(new \App\Events\RealTimeNotification(
                'Warehouse',
                null,
                'procurement_arrival_submitted',
                'Barang Masuk Baru!',
                'Ada barang masuk dari procurement yang perlu ditinjau.'
            ));

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
                return response()->json(['success' => false, 'message' => 'Custom Quotation ini tidak dalam status dikirim ke Quotation.'], 400);
            }

            $order = $customQuotation->order;
            if (!$order || $order->status !== 'under_procurement') {
                return response()->json(['success' => false, 'message' => 'Order untuk Custom Quotation ini belum diajukan untuk procurement.'], 400);
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
                $barang = Goods::findOrFail($itemData['goods_id']);

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
                    // Buat ProcurementArrivalRequest penuh otomatis
                    ProcurementArrivalRequest::create([
                        'good_id' => $barang->id,
                        'procurement_of_goods_item_id' => $procItem->id,
                        'received_at' => now(),
                        'quantity' => $itemData['qty_ordered'],
                        'unit_cost' => $itemData['buy_price'],
                        'status' => 'pending',
                    ]);
                }
            }

            DB::commit();

            event(new \App\Events\RealTimeNotification(
                'Warehouse',
                null,
                'procurement_arrival_submitted',
                'Barang Masuk Baru!',
                'Ada barang masuk dari procurement yang perlu ditinjau.'
            ));

            if ($validated['type'] === 'full') {
                return response()->json([
                    'success' => true,
                    'type' => 'full',
                    'message' => "Procurement {$procurement->procurement_number} berhasil dibuat secara full. Menunggu approval Warehouse.",
                ]);
            } else {
                // Partial - load detail HTML to be rendered in modal
                $procurement->load(['customQuotation', 'items.goods', 'generalAffair', 'items.procurementArrivalRequests']);
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
        $procurement->load(['customQuotation', 'order', 'items.goods', 'generalAffair', 'items.procurementArrivalRequests']);
        return view('admin.procurement.partials.procurement-detail-modal-body', compact('procurement'));
    }

    /**
     * Ambil rincian alokasi SO untuk procurement tertentu dalam bentuk HTML partial.
     */
    public function allocationsHtml(ProcurementOfGoods $procurement)
    {
        $procurement->load(['items.goods']);

        $allLinkedSos = collect();
        foreach ($procurement->items as $pItem) {
            $sos = DB::table('procurement_order_items')
                ->where('procurement_of_goods_item_id', $pItem->id)
                ->join('order_items', 'procurement_order_items.order_item_id', '=', 'order_items.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.status', '!=', 'canceled')
                ->select(
                    'orders.id as order_id',
                    'orders.order_number',
                    'orders.customer_name',
                    'orders.status as order_status',
                    'orders.queue_at',
                    'orders.created_at',
                    'procurement_order_items.quantity as qty_needed',
                    'order_items.allocated_quantity',
                    'order_items.shortage_quantity'
                )
                ->get()
                ->map(function ($s) use ($pItem) {
                    $s->goods_name = $pItem->goods->goods_name ?? 'Barang';
                    $s->goods_code = $pItem->goods->goods_code ?? '-';
                    $s->unit = $pItem->unit ?? 'pcs';
                    return $s;
                });
            $allLinkedSos = $allLinkedSos->concat($sos);
        }

        return view('admin.procurement.partials.procurement-allocations-modal-body', compact('procurement', 'allLinkedSos'));
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
                $alreadyPending = ProcurementArrivalRequest::where('procurement_of_goods_item_id', $procItem->id)
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
            if (!$procurement->general_affair_id) {
                $procurement->general_affair_id = Auth::id();
                $procurement->save();
            }

            foreach ($validated['items'] as $itemData) {
                if ($itemData['qty_arriving'] > 0) {
                    $procItem = ProcurementOfGoodsItem::find($itemData['procurement_item_id']);
                    if ($procItem) {
                        $procItem->update(['buy_price' => $itemData['buy_price']]);
                    }

                    ProcurementArrivalRequest::create([
                        'good_id' => $itemData['goods_id'],
                        'procurement_of_goods_item_id' => $itemData['procurement_item_id'],
                        'received_at' => now(),
                        'quantity' => $itemData['qty_arriving'],
                        'unit_cost' => $itemData['buy_price'],
                        'status' => 'pending',
                    ]);
                }
            }

            DB::commit();

            event(new \App\Events\RealTimeNotification(
                'Warehouse',
                null,
                'procurement_arrival_submitted',
                'Barang Masuk Baru!',
                'Ada barang masuk dari procurement yang perlu ditinjau.'
            ));

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
            $updateData = ['status' => 'completed'];
            if (!$procurement->general_affair_id) {
                $updateData['general_affair_id'] = Auth::id();
            }
            $procurement->update($updateData);

            foreach ($procurement->items as $item) {
                $item->update(['status' => 'completed']);
            }

            // Status Custom Quotation dipertahankan pada 'sent_to_quotation' sesuai dengan alur baru.

            DB::commit();

            return redirect()->route('general-affair.procurement.show', $procurement->id)
                ->with(['title' => 'Berhasil', 'text' => 'Procurement berhasil dipaksa selesai (Force Completed).']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Force Complete Procurement Error: ' . $e->getMessage());
            return back()->withErrors('Gagal memproses Force Complete: ' . $e->getMessage());
        }
    }

    /**
     * Revisi kedatangan barang kustom yang ditolak oleh Warehouse.
     */
    public function updateReceipt(Request $request, ProcurementArrivalRequest $receipt)
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
            $otherPendingQty = ProcurementArrivalRequest::where('procurement_of_goods_item_id', $procItem->id)
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

            event(new \App\Events\RealTimeNotification(
                'Warehouse',
                null,
                'procurement_arrival_submitted',
                'Barang Masuk Baru!',
                'Ada barang masuk dari procurement yang perlu ditinjau.'
            ));

            return redirect()->route('general-affair.procurement.show', $procItem->procurement_of_goods_id)
                ->with(['title' => 'Berhasil', 'text' => 'Penerimaan barang kustom berhasil direvisi dan dikirim kembali untuk review.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Update Receipt Error: ' . $e->getMessage());
            return back()->withErrors('Gagal merevisi kedatangan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus kedatangan barang (ProcurementArrivalRequest) yang belum approved.
     */
    public function destroyReceipt(ProcurementArrivalRequest $receipt)
    {
        if ($receipt->status === 'approved') {
            return back()->withErrors('Kedatangan barang yang sudah disetujui (Approved) tidak dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            $procurementId = $receipt->procurementOfGoodsItem->procurement_of_goods_id;

            $receipt->delete();

            DB::commit();

            event(new \App\Events\RealTimeNotification('All', null, 'refresh_counts'));

            return redirect()->route('general-affair.procurement.show', $procurementId)
                ->with(['title' => 'Berhasil', 'text' => 'Catatan kedatangan barang kustom berhasil dihapus.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Destroy Receipt Error: ' . $e->getMessage());
            return back()->withErrors('Gagal menghapus kedatangan barang: ' . $e->getMessage());
        }
    }
}

