<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\CustomQuotation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Goods;
use App\Models\DeliveryBatch;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoiceExport;
use App\Exports\GaSalesOrderExport;

class SalesOrderController extends Controller
{
    /**
     * Helper private: mapping Quotation ke array row tabel
     */
    private function mapRequestOrderRow(\App\Models\Quotation $ro): array
    {
        $diskonPersen = ($ro->items && $ro->items->count() > 0)
            ? ($ro->items->first()->discount_percent ?? 0) : 0;

        $berlakuSampai = '-';
        if ($ro->valid_date) {
            $berlakuSampai = \Carbon\Carbon::parse($ro->valid_date)->translatedFormat('d F Y');
        } elseif ($ro->expired_at) {
            $berlakuSampai = \Carbon\Carbon::parse($ro->expired_at)->translatedFormat('d F Y');
        }

        if ($ro->custom_quotation_id) {
            $picName = $ro->customQuotation?->up;
            $picPosition = 'PIC';
            if ($ro->customer && $picName) {
                $matchedPic = $ro->customer->pics->where('name', $picName)->first();
                if ($matchedPic) {
                    $picPosition = $matchedPic->position;
                }
            }
        } else {
            $picName = $ro->pic_name ?? $ro->pic?->name ?? $ro->customer?->pics?->first()?->name;
            $picPosition = $ro->pic?->position ?? $ro->customer?->pics?->first()?->position ?? 'PIC';
        }

        $warehouseStatuses = ['sent_to_warehouse', 'under_procurement', 'completed', 'not_completed'];
        if ($ro->custom_quotation_id) {
            $isSentToWarehouse = $ro->order && in_array($ro->order->status, $warehouseStatuses, true);
        } else {
            $isSentToWarehouse = $ro->order && $ro->order->queue_at !== null;
        }

        return [
            'id'             => $ro->id,
            'type'           => 'request_order',
            'custom_quotation_id' => $ro->custom_quotation_id,
            'no_request'     => $ro->request_number,
            'no_quotation'   => $ro->quotation_number,
            'no_po'          => $ro->no_po ?? '-',
            'no_sales_order' => $ro->sales_order_number,
            'tanggal'        => $ro->required_date ? $ro->required_date->format('d/m/Y') : '-',
            'customer_name'  => $ro->customer_name,
            'first_pic_name' => $picName,
            'first_pic_position' => $picPosition,
            'jumlah_item'    => $ro->items->count(),
            'total'          => $ro->grand_total ?? 0,
            'diskon'         => $diskonPersen,
            'status'         => $ro->status,
            'berlaku_sampai' => $berlakuSampai,
            'image_po'       => $ro->image_po,
            'pdf_po'         => $ro->pdf_po,
            'customer_status'=> $ro->customer->status ?? 'active',
            'aksi_url'       => '#',
            'has_batches'    => $ro->order && $ro->order->batches->count() > 1,
            'is_sent_to_warehouse' => $isSentToWarehouse,
            'can_download_pdf' => $ro->canDownloadPdf(),
        ];
    }

    public function sentRequestOrderToWarehouse(Request $request, \App\Models\Quotation $quotation)
    {
        if (is_null($quotation->custom_quotation_id)) {
            $alreadySent = Order::where('quotation_id', $quotation->id)
                ->whereNotNull('queue_at')
                ->exists();
        } else {
            $alreadySent = Order::where('quotation_id', $quotation->id)
                ->whereIn('status', ['sent_to_warehouse', 'under_procurement', 'completed', 'not_completed'])
                ->exists();
        }

        if ($alreadySent) {
            return redirect()->back()
                ->with(['title' => 'Gagal', 'text' => 'Quotation ini sudah pernah dikirim ke warehouse atau procurement.']);
        }

        DB::beginTransaction();
        try {
            $quotation->load('items', 'sales');
            $customQuotation = $quotation->customQuotation;
            $targetStatus = $quotation->custom_quotation_id ? 'under_procurement' : 'sent_to_warehouse';

            // 1. Check if there are any custom items in this quotation
            $hasCustomItems = false;
            foreach ($quotation->items as $item) {
                if (is_null($item->goods_id) && !empty($item->custom_product_name)) {
                    $hasCustomItems = true;
                    break;
                }
            }

            // 3. Process goods creation for custom items
            $goodsMap = []; // maps item ID to created goods model
            foreach ($quotation->items as $item) {
                if (is_null($item->goods_id) && !empty($item->custom_product_name)) {
                    // Generate unique goods code
                    $category = $item->product_category ?: 'OTHER CATEGORIES';
                    $generatedCode = \App\Models\Goods::generateUniqueKodeBarang($category);

                    // Find description and unit from CustomQuotationItem
                    $cqItem = null;
                    if ($customQuotation) {
                        $cqItem = $customQuotation->items()
                            ->where('product_name', $item->custom_product_name)
                            ->first();
                    }
                    $unit = $cqItem ? $cqItem->unit : 'pcs';
                    $description = $cqItem ? $cqItem->description : '-';

                    // Create the goods record
                    $goods = \App\Models\Goods::create([
                        'request_type' => 'primary',
                        'goods_status' => 'pending',
                        'status_listing' => 'non_listing',
                        'goods_code' => $generatedCode,
                        'goods_name' => $item->custom_product_name,
                        'category' => $category,
                        'stock' => 0,
                        'unit' => $unit,
                        'buy_price' => 0.00,
                        'selling_price' => 0.00,
                        'form' => $quotation->sales_id ?? Auth::id(),
                        'description' => $description,
                    ]);

                    // Update QuotationItem and CustomQuotationItem
                    $item->update(['goods_id' => $goods->id]);
                    if ($cqItem) {
                        $cqItem->update(['goods_id' => $goods->id]);
                    }

                    $goodsMap[$item->id] = $goods->id;
                }
            }

            $existingOrder = Order::where('quotation_id', $quotation->id)->first();

            if ($existingOrder) {
                if (is_null($quotation->custom_quotation_id)) {
                    $existingOrder->update([
                        'queue_at' => now(),
                        'approved_at' => $existingOrder->approved_at ?? now(),
                        'status' => 'open',
                    ]);
                    // Sync/Create OrderItems if they don't exist yet!
                    if ($existingOrder->items()->count() === 0) {
                        foreach ($quotation->items as $item) {
                            $goodsId = $item->goods_id ?? ($goodsMap[$item->id] ?? null);
                            OrderItem::create([
                                'order_id'            => $existingOrder->id,
                                'goods_id'            => $goodsId,
                                'custom_product_name' => $item->custom_product_name,
                                'category'            => $item->product_category ?? null,
                                'quantity'            => $item->quantity ?? 1,
                                'delivered_quantity'  => 0,
                                'allocated_quantity'  => 0,
                                'shortage_quantity'   => $item->quantity ?? 1,
                                'item_status'         => 'pending',
                                'price'               => $item->price ?? 0,
                                'subtotal'            => $item->subtotal ?? 0,
                            ]);
                        }
                        $existingOrder->load('items');
                    }
                    // Initialize shortage_quantity on existing items
                    foreach ($existingOrder->items as $orderItem) {
                        $orderItem->shortage_quantity = max(0, $orderItem->quantity - $orderItem->delivered_quantity - $orderItem->allocated_quantity);
                        $orderItem->save();
                    }
                    $order = $existingOrder;
                } else {
                    $existingOrder->update([
                        'status' => $targetStatus,
                        'custom_quotation_id' => $quotation->custom_quotation_id ?? $existingOrder->custom_quotation_id,
                    ]);
                    $order = $existingOrder;
                }
                $orderNumber = $existingOrder->order_number;

                // Sync goods_id to existing order items if they were null
                foreach ($existingOrder->items as $orderItem) {
                    if (is_null($orderItem->goods_id) && !empty($orderItem->custom_product_name)) {
                        // Find matching QuotationItem to get the goods_id
                        $matchedQItem = $quotation->items()
                            ->where('custom_product_name', $orderItem->custom_product_name)
                            ->first();
                        if ($matchedQItem && $matchedQItem->goods_id) {
                            $orderItem->update(['goods_id' => $matchedQItem->goods_id]);
                        }
                    }
                }
            } else {
                $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . str_pad(
                    Order::whereDate('created_at', now()->toDateString())->count() + 1,
                    4, '0', STR_PAD_LEFT
                );

                $order = Order::create([
                    'order_number'        => $orderNumber,
                    'sales_id'            => $quotation->sales_id ?? Auth::id(),
                    'customer_name'       => $quotation->customer_name,
                    'customer_id'         => $quotation->customer_id ?? null,
                    'quotation_id'        => $quotation->id,
                    'custom_quotation_id' => $quotation->custom_quotation_id ?? null,
                    'status'              => is_null($quotation->custom_quotation_id) ? 'open' : $targetStatus,
                    'required_date'       => $quotation->required_date ?? now()->toDateString(),
                    'customer_notes'      => $quotation->customer_notes ?? null,
                    'queue_at'            => is_null($quotation->custom_quotation_id) ? now() : null,
                    'approved_at'         => is_null($quotation->custom_quotation_id) ? now() : null,
                ]);

                foreach ($quotation->items as $item) {
                    $goodsId = $item->goods_id ?? ($goodsMap[$item->id] ?? null);

                    OrderItem::create([
                        'order_id'            => $order->id,
                        'goods_id'            => $goodsId,
                        'custom_product_name' => $item->custom_product_name,
                        'category'            => $item->product_category ?? null,
                        'quantity'            => $item->quantity ?? 1,
                        'delivered_quantity'  => 0,
                        'allocated_quantity'  => 0,
                        'shortage_quantity'   => $item->quantity ?? 1,
                        'item_status'         => 'pending',
                        'price'               => $item->price ?? 0,
                        'subtotal'            => $item->subtotal ?? 0,
                    ]);
                }
            }

            // Trigger stock allocation for standard Listing
            $hasShortageOnConfirmation = false;
            if (is_null($quotation->custom_quotation_id)) {
                \App\Services\StockAllocationService::allocateAvailableStock($order);

                // Check for shortage items
                $shortageItems = [];
                $order->loadMissing('items.barang');
                foreach ($order->items as $item) {
                    $shortage = $item->quantity - $item->delivered_quantity - $item->allocated_quantity;
                    if ($shortage > 0) {
                        $shortageItems[] = [
                            'item' => $item,
                            'shortage' => $shortage,
                        ];
                    }
                }

                if (!empty($shortageItems)) {
                    $hasShortageOnConfirmation = true;

                    // Automatically find or create a consolidated procurement batch for Listing shortages
                    $procurement = \App\Models\ProcurementOfGoods::whereNull('custom_quotation_id')
                        ->whereNull('order_id')
                        ->whereIn('status', ['pending', 'partial_received'])
                        ->first();

                    if (!$procurement) {
                        $procurement = \App\Models\ProcurementOfGoods::create([
                            'procurement_number' => \App\Models\ProcurementOfGoods::generateProcurementNumber(),
                            'order_id' => null, // Consolidated batch across multiple SOs
                            'custom_quotation_id' => null,
                            'general_affair_id' => null,
                            'status' => 'pending',
                            'notes' => 'Pengadaan Terpadu Shortage Stok Listing',
                        ]);
                    }

                    foreach ($shortageItems as $sItem) {
                        $item = $sItem['item'];
                        $shortage = $sItem['shortage'];

                        // Check if this goods is already in the procurement batch
                        $procItem = \App\Models\ProcurementOfGoodsItem::where('procurement_of_goods_id', $procurement->id)
                            ->where('goods_id', $item->goods_id)
                            ->whereNotIn('status', ['completed', 'canceled'])
                            ->first();

                        if ($procItem) {
                            $procItem->qty_requested += $shortage;
                            $procItem->qty_ordered += $shortage;
                            $procItem->save();
                        } else {
                            $procItem = \App\Models\ProcurementOfGoodsItem::create([
                                'procurement_of_goods_id' => $procurement->id,
                                'goods_id' => $item->goods_id,
                                'qty_requested' => $shortage,
                                'qty_ordered' => $shortage,
                                'qty_received' => 0,
                                'unit' => $item->barang->unit ?? 'pcs',
                                'buy_price' => $item->barang->buy_price ?? 0,
                                'selling_price' => $item->barang->selling_price ?? 0,
                                'status' => 'pending',
                            ]);
                        }

                        // Link the shortage requirement to this specific OrderItem via pivot
                        DB::table('procurement_order_items')->updateOrInsert(
                            [
                                'procurement_of_goods_item_id' => $procItem->id,
                                'order_item_id' => $item->id,
                            ],
                            [
                                'quantity' => $shortage,
                                'allocated_quantity' => 0,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );

                        $item->shortage_quantity = $shortage;
                        $item->save();
                    }

                    $order->status = 'under_procurement';
                    $order->save();

                    event(new \App\Events\RealTimeNotification(
                        'General Affair',
                        null,
                        'procurement_arrival_submitted',
                        'Pengadaan Baru!',
                        'Ada pengadaan baru untuk shortage Listing yang perlu diproses.'
                    ));
                }
            }

            DB::commit();
            if ($quotation->custom_quotation_id) {
                $successText = "Quotation berhasil diajukan untuk pengadaan (procurement) dengan No. {$orderNumber}.";
            } elseif ($hasShortageOnConfirmation) {
                $successText = "Sales Order berhasil diresmikan dan pengadaan otomatis untuk shortage barang telah diajukan ke GA dengan No. {$orderNumber}.";
            } else {
                $successText = "Sales Order berhasil diresmikan dan dikirim ke Warehouse dengan No. {$orderNumber}.";
            }

            return redirect()->back()
                ->with(['title' => 'Berhasil', 'text' => $successText]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors('Gagal memproses request order: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search   = $request->input('search', '');
        $results  = collect();
        $isSearch = $request->filled('search');
        $perPage  = (int) $request->input('perPage', 20);

        if ($isSearch) {
            $results = \App\Models\Quotation::where(function ($q) use ($search) {
                    $q->where('request_number',     'like', "%$search%")
                      ->orWhere('quotation_number',   'like', "%$search%")
                      ->orWhere('sales_order_number','like', "%$search%")
                      ->orWhere('customer_name',     'like', "%$search%")
                      ->orWhere('no_po',             'like', "%$search%");
                })
                ->where('sales_id', Auth::id())
                ->where(function($q) {
                    $q->whereDoesntHave('order')
                      ->orWhereHas('order', function($o) {
                          $o->where('status', '!=', 'rejected_supervisor');
                      });
                })
                ->with(['order.batches', 'items', 'customer.pics', 'customQuotation'])
                ->get()
                ->map(fn($ro) => array_merge($this->mapRequestOrderRow($ro), [
                    'catatan_customer' => $ro->customer_notes,
                    'aksi_url'         => route('sales.sales-orders.show', $ro),
                    'image_po'         => $ro->image_po,
                ]));
        } else {
            $requestOrders = \App\Models\Quotation::where('sales_id', Auth::id())
                ->where(function($q) {
                    $q->whereDoesntHave('order')
                      ->orWhereHas('order', function($o) {
                          $o->where('status', '!=', 'rejected_supervisor');
                      });
                })
                ->with(['order.batches', 'items', 'customer.pics', 'customQuotation'])
                ->latest()
                ->paginate($perPage)
                ->appends(request()->query());

            $results = $requestOrders->map(fn($ro) => array_merge($this->mapRequestOrderRow($ro), [
                'catatan_customer' => $ro->customer_notes,
                'aksi_url'         => route('sales.sales-orders.show', $ro),
                'image_po'         => $ro->image_po,
            ]));

            $salesOrders = $requestOrders;
        }

        return view('admin.sales-orders.index', [
            'results'     => $results,
            'search'      => $search,
            'isSearch'    => $isSearch,
            'salesOrders' => isset($salesOrders) ? $salesOrders : null,
        ]);
    }

    public function getQuotationDetail(Request $request)
    {
        $quotationId = $request->input('id');
        $quotation   = CustomQuotation::where('sales_id', Auth::id())->findOrFail($quotationId);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'               => $quotation->id,
                'quotation_number' => $quotation->quotation_number,
                'to'               => $quotation->to,
                'up'               => $quotation->up,
                'email'            => $quotation->email,
                'subject'          => $quotation->subject,
                'our_ref'          => $quotation->our_ref,
                'date'             => $quotation->date ? \Carbon\Carbon::parse($quotation->date)->format('d/m/Y') : '-',
                'intro_text'       => $quotation->intro_text,
                'status'           => $quotation->status,
                'subtotal'         => $quotation->subtotal,
                'tax'              => $quotation->tax,
                'grand_total'      => $quotation->grand_total,
                'items'            => $quotation->items->map(function ($item) {
                    return [
                        'nama_barang' => $item->product_name,
                        'qty'         => $item->qty,
                        'satuan'      => $item->unit,
                        'harga'       => $item->price,
                        'diskon'      => $item->discount,
                        'subtotal'    => $item->subtotal,
                        'keterangan'  => $item->description,
                        'images'      => $item->images ?? [],
                    ];
                }),
            ],
        ]);
    }

    public function create()
    {
        $customQuotations = CustomQuotation::where('sales_id', Auth::id())
            ->whereIn('status', ['open', 'approved'])
            ->with('items')
            ->latest()
            ->get();

        $salesUsers      = User::where('role', 'Sales')->pluck('name', 'name')->toArray();
        $currentUserName = Auth::user()->name;

        return view('admin.sales-orders.action.create', compact('customQuotations', 'salesUsers', 'currentUserName'));
    }

    public function store(Request $request)
    {
        Log::info('Sales Order Store Request Incoming (Pivoted to Quotation)', [
            'auth_id' => Auth::id(),
            'request' => $request->all(),
        ]);

        $salesNames = User::where('role', 'Sales')->pluck('name')->toArray();

        $validated = $request->validate([
            'to'                  => 'required|string|max:255',
            'up'                  => ['required', 'string', 'max:255', Rule::in($salesNames)],
            'subject'             => 'required|string|max:255',
            'email'               => 'required|email',
            'date'                => 'required|date',
            'items'               => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.qty'         => 'required|integer|min:1',
            'items.*.harga'       => 'required|numeric|min:0',
            'items.*.satuan'      => 'required|string|max:50',
            'items.*.diskon'      => 'nullable|integer|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            $requestOrder = Quotation::create([
                'sales_id'          => Auth::id(),
                'request_number'    => Quotation::generateQuotationNumber(),
                'customer_name'     => $validated['to'],
                'subject'           => $validated['subject'],
                'required_date'     => $validated['date'],
                'status'            => 'pending',
            ]);

            foreach ($validated['items'] as $itemData) {
                QuotationItem::create([
                    'quotation_id'        => $requestOrder->id,
                    'custom_product_name' => $itemData['nama_barang'],
                    'quantity'            => $itemData['qty'],
                    'price'               => $itemData['harga'],
                    'subtotal'            => $itemData['qty'] * $itemData['harga'],
                    'discount_percent'    => $itemData['diskon'] ?? 0,
                ]);
            }

            DB::commit();
            return redirect()->route('sales.quotation.show', $requestOrder->id)
                ->with(['title' => 'Berhasil', 'text' => "Quotation berhasil dibuat."]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Quotation Store Error (from SO Controller)', ['message' => $e->getMessage()]);
            return back()->withErrors('Gagal membuat Quotation: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $requestOrder = Quotation::with('items.barang', 'sales', 'customQuotation', 'order', 'customer')->findOrFail($id);

        $userRole = trim(strtolower(Auth::user()->role ?? ''));
        $allowed  = array_map('strtolower', ['Supervisor', 'Admin']);
        if ($requestOrder->sales_id !== Auth::id() && !in_array($userRole, $allowed)) {
            abort(403);
        }

        return view('admin.sales-order-details.index', compact('requestOrder'));
    }

    public function edit($id)
    {
        $requestOrder = Quotation::findOrFail($id);
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        $requestOrder->load('items');
        $salesUsers      = User::where('role', 'Sales')->pluck('name', 'name')->toArray();
        $currentUserName = Auth::user()->name;

        return view('admin.quotation.action.edit', compact('requestOrder', 'salesUsers', 'currentUserName'));
    }

    public function update(Request $request, $id)
    {
        $requestOrder = Quotation::findOrFail($id);
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        $salesNames = User::where('role', 'Sales')->pluck('name')->toArray();

        $validated = $request->validate([
            'to'                  => 'required|string|max:255',
            'up'                  => ['required', 'string', 'max:255', Rule::in($salesNames)],
            'subject'             => 'required|string|max:255',
            'email'               => 'required|email',
            'date'                => 'required|date',
            'items'               => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.qty'         => 'required|integer|min:1',
            'items.*.harga'       => 'required|numeric|min:0',
            'items.*.satuan'      => 'required|string|max:50',
            'items.*.diskon'      => 'nullable|integer|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            $requestOrder->update([
                'customer_name'     => $validated['to'],
                'subject'           => $validated['subject'],
                'required_date'     => $validated['date'],
            ]);

            $requestOrder->items()->delete();

            foreach ($validated['items'] as $itemData) {
                QuotationItem::create([
                    'quotation_id'        => $requestOrder->id,
                    'custom_product_name' => $itemData['nama_barang'],
                    'quantity'            => $itemData['qty'],
                    'price'               => $itemData['harga'],
                    'subtotal'            => $itemData['qty'] * $itemData['harga'],
                    'discount_percent'    => $itemData['diskon'] ?? 0,
                ]);
            }

            DB::commit();
            return redirect()->route('sales.quotation.show', $requestOrder->id)
                ->with(['title' => 'Berhasil', 'text' => 'Quotation berhasil diubah.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal mengubah Quotation: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $requestOrder = Quotation::findOrFail($id);
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($requestOrder) {
                $requestOrder->items()->delete();
                $requestOrder->delete();
            });
            return redirect()->route('sales.quotation.index')
                ->with(['title' => 'Berhasil', 'text' => 'Quotation berhasil dihapus.']);
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal menghapus Quotation: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('q', '');
        if (empty($search)) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $results = \App\Models\Quotation::where('sales_id', Auth::id())
            ->where(function($q) {
                $q->whereDoesntHave('order')
                  ->orWhereHas('order', function($o) {
                      $o->where('status', '!=', 'rejected_supervisor');
                  });
            })
            ->where(function ($q) use ($search) {
                $q->where('request_number',  'like', "%$search%")
                  ->orWhere('quotation_number','like', "%$search%")
                  ->orWhere('sales_order_number','like', "%$search%")
                  ->orWhere('customer_name',  'like', "%$search%")
                  ->orWhere('no_po',          'like', "%$search%");
            })
            ->limit(10)
            ->get()
            ->map(function ($ro) {
                return [
                    'sales_order_number' => $ro->sales_order_number ?: ($ro->quotation_number ?: ($ro->request_number ?: 'Quotation')),
                    'customer_name'      => $ro->customer_name,
                    'type'               => 'quotation',
                    'badge'              => 'Quotation',
                    'url'                => route('sales.quotation.show', $ro->id),
                    'no_po'              => $ro->no_po,
                ];
            });

        return response()->json(['success' => true, 'data' => $results]);
    }

    public function sendToProcurement(\App\Models\Order $salesOrder)
    {
        if ($salesOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        if ($salesOrder->status !== 'open') {
            return redirect()->back()->withErrors('Hanya order dengan status Open yang dapat dikirim ke Procurement.');
        }

        // Idempotency check
        $exists = \App\Models\ProcurementOfGoods::where('order_id', $salesOrder->id)->exists();
        if ($exists) {
            return redirect()->back()->with(['title' => 'Perhatian', 'text' => 'Order ini sudah diajukan untuk procurement.']);
        }

        $salesOrder->loadMissing('items.barang');

        $shortageItems = [];
        foreach ($salesOrder->items as $item) {
            $shortage = $item->quantity - $item->delivered_quantity - $item->allocated_quantity;
            if ($shortage > 0) {
                $shortageItems[] = [
                    'item' => $item,
                    'shortage' => $shortage,
                ];
            }
        }

        if (empty($shortageItems)) {
            return redirect()->back()->withErrors('Tidak ada item yang shortage pada order ini.');
        }

        DB::beginTransaction();
        try {
            // Automatically find or create a consolidated procurement batch for Listing shortages
            $procurement = \App\Models\ProcurementOfGoods::whereNull('custom_quotation_id')
                ->whereNull('order_id')
                ->whereIn('status', ['pending', 'partial_received'])
                ->first();

            if (!$procurement) {
                $procurement = \App\Models\ProcurementOfGoods::create([
                    'procurement_number' => \App\Models\ProcurementOfGoods::generateProcurementNumber(),
                    'order_id' => null,
                    'custom_quotation_id' => null,
                    'general_affair_id' => null,
                    'status' => 'pending',
                    'notes' => 'Pengadaan Terpadu Shortage Stok Listing',
                ]);
            }

            foreach ($shortageItems as $sItem) {
                $item = $sItem['item'];
                $shortage = $sItem['shortage'];

                $procItem = \App\Models\ProcurementOfGoodsItem::where('procurement_of_goods_id', $procurement->id)
                    ->where('goods_id', $item->goods_id)
                    ->whereNotIn('status', ['completed', 'canceled'])
                    ->first();

                if ($procItem) {
                    $procItem->qty_requested += $shortage;
                    $procItem->qty_ordered += $shortage;
                    $procItem->save();
                } else {
                    $procItem = \App\Models\ProcurementOfGoodsItem::create([
                        'procurement_of_goods_id' => $procurement->id,
                        'goods_id' => $item->goods_id,
                        'qty_requested' => $shortage,
                        'qty_ordered' => $shortage,
                        'qty_received' => 0,
                        'unit' => $item->barang->unit ?? 'pcs',
                        'buy_price' => $item->barang->buy_price ?? 0,
                        'selling_price' => $item->barang->selling_price ?? 0,
                        'status' => 'pending',
                    ]);
                }

                DB::table('procurement_order_items')->updateOrInsert(
                    [
                        'procurement_of_goods_item_id' => $procItem->id,
                        'order_item_id' => $item->id,
                    ],
                    [
                        'quantity' => $shortage,
                        'allocated_quantity' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // Update shortage quantity field on order item
                $item->shortage_quantity = $shortage;
                $item->save();
            }

            $salesOrder->status = 'under_procurement';
            $salesOrder->save();

            DB::commit();

            event(new \App\Events\RealTimeNotification(
                'General Affair',
                null,
                'procurement_arrival_submitted',
                'Pengadaan Baru!',
                'Ada pengadaan baru untuk shortage Listing yang perlu diproses.'
            ));

            return redirect()->back()->with(['title' => 'Berhasil', 'text' => 'Shortage Sales Order berhasil dikirim ke GA Procurement.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Gagal mengirim ke Procurement: ' . $e->getMessage());
        }
    }
}
