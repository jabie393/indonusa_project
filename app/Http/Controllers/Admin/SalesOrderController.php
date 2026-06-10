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
use App\Models\Barang;
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

        $firstPic = $ro->customer?->pics?->first();
        $warehouseStatuses = ['sent_to_warehouse', 'completed', 'not_completed'];
        $isSentToWarehouse = $ro->order && in_array($ro->order->status, $warehouseStatuses, true);

        return [
            'id'             => $ro->id,
            'type'           => 'request_order',
            'no_request'     => $ro->request_number,
            'no_quotation'   => $ro->quotation_number,
            'no_po'          => $ro->no_po ?? '-',
            'no_sales_order' => $ro->sales_order_number,
            'tanggal'        => $ro->required_date ? $ro->required_date->format('d/m/Y') : '-',
            'customer_name'  => $ro->customer_name,
            'first_pic_name' => $firstPic?->name,
            'first_pic_position' => $firstPic?->position,
            'jumlah_item'    => $ro->items->count(),
            'total'          => $ro->grand_total ?? 0,
            'diskon'         => $diskonPersen,
            'status'         => $ro->status,
            'berlaku_sampai' => $berlakuSampai,
            'image_po'       => $ro->image_po,
            'pdf_po'         => $ro->pdf_po,
            'customer_status'=> $ro->customer->status ?? 'active',
            'aksi_url'       => '#',
            'has_batches'    => $ro->order && $ro->order->batches->isNotEmpty(),
            'is_sent_to_warehouse' => $isSentToWarehouse,
        ];
    }

    /**
     * Kirim Quotation ke Warehouse dari halaman SO
     */
    public function sentRequestOrderToWarehouse(Request $request, \App\Models\Quotation $quotation)
    {
        $alreadySent = Order::where('quotation_id', $quotation->id)
            ->whereIn('status', ['sent_to_warehouse', 'completed', 'not_completed'])
            ->exists();

        if ($alreadySent) {
            return redirect()->back()
                ->with(['title' => 'Gagal', 'text' => 'Quotation ini sudah pernah dikirim ke warehouse.']);
        }

        DB::beginTransaction();
        try {
            $quotation->load('items', 'sales');
            $customQuotation = $quotation->customQuotation;

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
                    $generatedCode = \App\Models\Barang::generateUniqueKodeBarang($category);

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
                    $goods = \App\Models\Barang::create([
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
                $doNumber = $existingOrder->do_number ?? ('DO-' . now()->format('Ymd') . '-' . str_pad(
                    Order::whereDate('created_at', now()->toDateString())->count() + 1,
                    4, '0', STR_PAD_LEFT
                ));
                $existingOrder->update([
                    'status' => 'sent_to_warehouse',
                    'do_number' => $doNumber,
                    'custom_quotation_id' => $quotation->custom_quotation_id ?? $existingOrder->custom_quotation_id,
                ]);
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
                $doNumber = 'DO-' . now()->format('Ymd') . '-' . str_pad(
                    Order::whereDate('created_at', now()->toDateString())->count() + 1,
                    4, '0', STR_PAD_LEFT
                );

                $order = Order::create([
                    'order_number'        => $orderNumber,
                    'do_number'           => $doNumber,
                    'sales_id'            => $quotation->sales_id ?? Auth::id(),
                    'customer_name'       => $quotation->customer_name,
                    'customer_id'         => $quotation->customer_id ?? null,
                    'quotation_id'        => $quotation->id,
                    'custom_quotation_id' => $quotation->custom_quotation_id ?? null,
                    'status'              => 'sent_to_warehouse',
                    'required_date'       => $quotation->required_date ?? now()->toDateString(),
                    'customer_notes'      => $quotation->customer_notes ?? null,
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
                        'item_status'         => 'pending',
                        'price'               => $item->price ?? 0,
                        'subtotal'            => $item->subtotal ?? 0,
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()
                ->with(['title' => 'Berhasil', 'text' => "Quotation berhasil dikirim ke Warehouse dengan No. {$orderNumber}."]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors('Gagal mengirim ke warehouse: ' . $e->getMessage());
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
                ->with(['order.batches', 'items', 'customer.pics'])
                ->get()
                ->map(fn($ro) => array_merge($this->mapRequestOrderRow($ro), [
                    'catatan_customer' => $ro->customer_notes,
                    'aksi_url'         => route('sales.sales-order.show', $ro),
                    'image_po'         => $ro->image_po,
                ]));
        } else {
            $requestOrders = \App\Models\Quotation::where('sales_id', Auth::id())
                ->with(['order.batches', 'items', 'customer.pics'])
                ->latest()
                ->paginate($perPage)
                ->appends(request()->query());

            $results = $requestOrders->map(fn($ro) => array_merge($this->mapRequestOrderRow($ro), [
                'catatan_customer' => $ro->customer_notes,
                'aksi_url'         => route('sales.sales-order.show', $ro),
                'image_po'         => $ro->image_po,
            ]));

            $salesOrders = $requestOrders;
        }

        return view('admin.sales-order.index', [
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

        return view('admin.sales-order.action.create', compact('customQuotations', 'salesUsers', 'currentUserName'));
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

        return view('admin.sales-order-detail.index', compact('requestOrder'));
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

    public function uploadImage(Request $request, $id)
    {
        $requestOrder = Quotation::findOrFail($id);
        if ($requestOrder->sales_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('request-order-so-images', 'public');
            $requestOrder->image_so = $path;
            $requestOrder->save();
            return response()->json(['status' => 'success', 'image_url' => \Illuminate\Support\Facades\Storage::url($path)]);
        }
        return response()->json(['status' => 'error', 'message' => 'No file uploaded']);
    }

    public function deleteImage($id)
    {
        $requestOrder = Quotation::findOrFail($id);
        if ($requestOrder->sales_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        if ($requestOrder->image_so) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($requestOrder->image_so);
            $requestOrder->image_so = null;
            $requestOrder->save();
        }
        return response()->json(['status' => 'success']);
    }

    public function search(Request $request)
    {
        $search = $request->input('q', '');
        if (empty($search)) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $results = \App\Models\Quotation::where('sales_id', Auth::id())
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
}
