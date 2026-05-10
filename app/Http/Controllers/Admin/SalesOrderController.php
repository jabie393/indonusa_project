<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Controllers\Controller;
use App\Models\RequestOrder;
use App\Models\RequestOrderItem;
use App\Models\CustomPenawaran;
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
     * Helper private: mapping RequestOrder ke array row tabel
     */
    private function mapRequestOrderRow(\App\Models\RequestOrder $ro): array
    {
        $diskonPersen = ($ro->items && $ro->items->count() > 0)
            ? ($ro->items->first()->diskon_percent ?? 0) : 0;

        $berlakuSampai = '-';
        if ($ro->tanggal_berlaku) {
            $berlakuSampai = \Carbon\Carbon::parse($ro->tanggal_berlaku)->translatedFormat('d F Y');
        } elseif ($ro->expired_at) {
            $berlakuSampai = \Carbon\Carbon::parse($ro->expired_at)->translatedFormat('d F Y');
        }

        return [
            'id'             => $ro->id,
            'type'           => 'request_order',
            'no_request'     => $ro->request_number,
            'no_penawaran'   => $ro->nomor_penawaran,
            'no_po'          => $ro->no_po ?? '-',
            'no_sales_order' => $ro->sales_order_number,
            'tanggal'        => $ro->tanggal_kebutuhan ? $ro->tanggal_kebutuhan->format('d/m/Y') : '-',
            'customer_name'  => $ro->customer_name,
            'jumlah_item'    => $ro->items->count(),
            'total'          => $ro->grand_total ?? 0,
            'diskon'         => $diskonPersen,
            'status'         => $ro->status,
            'berlaku_sampai' => $berlakuSampai,
            'image_po'       => $ro->image_po,
            'pdf_po'         => $ro->pdf_po,
            'customer_status'=> $ro->customer->status ?? 'active',
            'aksi_url'       => '#',
        ];
    }

    /**
     * Index untuk General Affair (read-only, semua sales)
     */
    public function gaIndex(Request $request)
    {
        $search   = $request->input('search', '');
        $isSearch = $request->filled('search');
        $results  = collect();

        if ($isSearch) {
            $results = \App\Models\RequestOrder::where(function ($q) use ($search) {
                    $q->where('request_number',     'like', "%$search%")
                      ->orWhere('nomor_penawaran',   'like', "%$search%")
                      ->orWhere('sales_order_number','like', "%$search%")
                      ->orWhere('customer_name',     'like', "%$search%")
                      ->orWhere('no_po',             'like', "%$search%");
                })
                ->with(['items', 'customer'])
                ->get()
                ->map(fn($ro) => $this->mapRequestOrderRow($ro));
        } else {
            $requestOrders = \App\Models\RequestOrder::with(['order', 'items', 'customer'])
                ->latest()
                ->paginate(20)
                ->appends($request->query());

            $results     = $requestOrders->map(fn($ro) => $this->mapRequestOrderRow($ro));
            $salesOrders = $requestOrders;
        }

        return view('admin.sales.sales-order.ga-index', [
            'results'     => $results,
            'search'      => $search,
            'isSearch'    => $isSearch,
            'salesOrders' => $salesOrders ?? null,
        ]);
    }

    /**
     * Export General Affair sales order data to Excel.
     */
    public function exportGaSalesOrders(Request $request)
    {
        $search = $request->input('search', null);
        $filename = 'ga_sales_orders_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new GaSalesOrderExport($search), $filename);
    }

    /**
     * Get invoice items for a request order, preferring warehouse-shipped order items when available.
     */
    private function getInvoiceItems(RequestOrder $ro, bool $preferWarehouseItems = false): array
    {
        if ($preferWarehouseItems && $ro->order && $ro->order->items->count() > 0) {
            $warehouseItems = $ro->order->items->filter(fn ($item) => ($item->delivered_quantity ?? 0) > 0);

            if ($warehouseItems->isNotEmpty()) {
                return $warehouseItems->map(function ($item) {
                    $quantity = $item->delivered_quantity ?? $item->quantity ?? 0;
                    $harga = $item->harga ?? 0;

                    return [
                        'nama_barang' => optional($item->barang)->nama_barang ?? '-',
                        'deskripsi' => optional($item->barang)->deskripsi ?? '-',
                        'qty' => $quantity,
                        'harga' => $harga,
                        'subtotal' => round($quantity * $harga, 2),
                    ];
                })->values()->toArray();
            }
        }

        return $ro->items->map(function ($item) {
            $barangData = \App\Models\Barang::find($item->barang_id);
            return [
                'nama_barang' => $item->nama_barang_custom
                    ?? optional($barangData)->nama_barang
                    ?? '-',
                'deskripsi' => optional($barangData)->deskripsi ?? '-',
                'qty'      => $item->quantity ?? 1,
                'harga'    => $item->harga ?? 0,
                'subtotal' => $item->subtotal ?? 0,
            ];
        })->toArray();
    }

    private function getInvoiceItemsForBatch(DeliveryBatch $batch): array
    {
        return $batch->items->map(function ($batchItem) {
            $orderItem = $batchItem->orderItem;
            $barang = $orderItem?->barang;
            $quantity = $batchItem->quantity_sent ?? 0;
            $harga = $orderItem?->harga ?? 0;

            return [
                'nama_barang' => $orderItem?->nama_barang_custom
                    ?? optional($barang)->nama_barang
                    ?? '-',
                'deskripsi' => optional($barang)->deskripsi ?? '-',
                'qty' => $quantity,
                'harga' => $harga,
                'subtotal' => round($quantity * $harga, 2),
            ];
        })->toArray();
    }

    private function calculateInvoiceTotals(array $items, ?RequestOrder $ro = null): array
    {
        $subtotal = array_reduce($items, fn ($carry, $item) => $carry + ($item['subtotal'] ?? 0), 0);
        $taxRatio = ($ro && $ro->subtotal > 0) ? ($ro->tax / $ro->subtotal) : 0;
        $tax = round($subtotal * $taxRatio, 2);
        $grandTotal = round($subtotal + $tax, 2);

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'grandTotal' => $grandTotal,
        ];
    }

    /**
     * Search untuk General Affair (semua sales, tidak filter by sales_id)
     */
    public function gaSearch(Request $request)
    {
        $search = $request->input('q', '');
        if (empty($search)) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $results = \App\Models\RequestOrder::where(function ($q) use ($search) {
                $q->where('request_number',   'like', "%$search%")
                  ->orWhere('nomor_penawaran', 'like', "%$search%")
                  ->orWhere('customer_name',   'like', "%$search%")
                  ->orWhere('no_po',           'like', "%$search%");
            })
            ->limit(10)
            ->get()
            ->map(function ($ro) {
                return [
                    'sales_order_number' => $ro->nomor_penawaran ?: ($ro->request_number ?: 'Quotation'),
                    'customer_name'      => $ro->customer_name,
                    'type'               => 'penawaran',
                    'badge'              => 'Quotation',
                    'no_po'              => $ro->no_po,
                ];
            });

        return response()->json(['success' => true, 'data' => $results]);
    }

    /**
     * Show Invoice View
     */
    public function showInvoice(Request $request, $id)
    {
        $type          = $request->query('type', 'sales_order');
        $customerModel = null;
        $ro            = \App\Models\RequestOrder::with(['items', 'order.items.barang', 'order.batches'])->findOrFail($id);
        $customerName  = $ro->customer_name ?? '-';
        $noPoDisplay   = $ro->no_po ?? '-';
        $subtotal      = $ro->subtotal ?? 0;
        $tax           = $ro->tax ?? 0;
        $grandTotal    = $ro->grand_total ?? 0;

        if (!empty($ro->customer_id)) {
            $customerModel = \App\Models\Customer::find($ro->customer_id);
        }
        if (!$customerModel && !empty($customerName)) {
            $customerModel = \App\Models\Customer::where('nama_customer', $customerName)->first();
        }

        $isGa = strtolower(Auth::user()->role ?? '') === 'general affair';
        $items = $this->getInvoiceItems($ro, $isGa);

        if ($isGa && $ro->order?->items->count() > 0) {
            $subtotal = array_reduce($items, fn ($carry, $item) => $carry + ($item['subtotal'] ?? 0), 0);
            $taxRatio = $ro->subtotal > 0 ? ($ro->tax / $ro->subtotal) : 0;
            $tax = round($subtotal * $taxRatio, 2);
            $grandTotal = round($subtotal + $tax, 2);
        }

        $customerNpwp    = $customerModel->npwp ?? '';
        $customerAddress = '';
        if ($customerModel) {
            $parts = array_filter([
                $customerModel->alamat_pengiriman ?? null,
                $customerModel->kota              ?? null,
                $customerModel->provinsi          ?? null,
                $customerModel->kode_pos          ?? null,
            ]);
            $customerAddress = implode(', ', $parts);
        }

        $invoiceNumber = 'IO-IJB/' . now()->format('my') . '/' . str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);

        $isGa = strtolower(Auth::user()->role ?? '') === 'general affair';
        $invoiceExcelRoute = $isGa
            ? route('ga.sales-order.invoice-excel', $id)
            : route('sales.sales-order.invoice-excel', $id);

        $batches = $ro->order?->batches ?? collect();

        return view('admin.sales.sales-order.invoice', compact(
            'customerName',
            'customerAddress',
            'customerNpwp',
            'noPoDisplay',
            'subtotal',
            'tax',
            'grandTotal',
            'items',
            'invoiceNumber',
            'invoiceExcelRoute',
            'batches',
        ) + ['rowId' => $id, 'rowType' => $type]);
    }

    /**
     * Download Invoice Excel
     */
    public function downloadInvoiceExcel(Request $request, $id)
    {
        $type           = $request->input('row_type', 'sales_order');
        $invNumber      = $request->input('inv_number', 'IO-IJB/' . now()->format('my') . '/' . rand(1000, 9999));
        $invDate        = $request->input('inv_date', now()->format('Y-m-d'));
        $invNpwp        = $request->input('inv_npwp', $request->input('inv_npwp_val', ''));
        $invPoNo        = $request->input('inv_po_no', '-');
        $invPaymentNote = $request->input('inv_payment_note', '');
        $invAddress     = $request->input('inv_address', '');

        $ro              = \App\Models\RequestOrder::with(['items', 'order.items.barang'])->findOrFail($id);
        $customerName    = $ro->customer_name;
        $customerAddress = $ro->customer_address ?? '';
        $isGa            = strtolower(Auth::user()->role ?? '') === 'general affair';
        $items           = collect($this->getInvoiceItems($ro, $isGa))->map(function ($item) {
            return [
                'nama_barang' => $item['nama_barang'],
                'quantity'    => $item['qty'],
                'harga'       => $item['harga'],
                'subtotal'    => $item['subtotal'],
            ];
        });
        $subtotal   = array_reduce($items->toArray(), fn ($carry, $item) => $carry + ($item['subtotal'] ?? 0), 0);
        $taxRatio   = $ro->subtotal > 0 ? ($ro->tax / $ro->subtotal) : 0;
        $tax        = round($subtotal * $taxRatio, 2);
        $grandTotal = round($subtotal + $tax, 2);
        $dpp        = $tax > 0 ? round(($subtotal * 100) / 111) : 0;

        $data = [
            'type'           => $type,
            'invNumber'      => $invNumber,
            'invDate'        => $invDate,
            'invNpwp'        => $invNpwp,
            'invPoNo'        => $invPoNo,
            'invPaymentNote' => $invPaymentNote,
            'invAddress'     => $invAddress,
            'customerName'   => $customerName,
            'customerAddress'=> $customerAddress,
            'items'          => $items,
            'subtotal'       => $subtotal,
            'tax'            => $tax,
            'grandTotal'     => $grandTotal,
            'dpp'            => $dpp,
        ];

        $filename = 'Invoice_' . str_replace(['/', ' '], ['_', '_'], $invNumber) . '.xlsx';

        return Excel::download(new InvoiceExport($data), $filename);
    }

    public function showBatchInvoice(Request $request, $batchId)
    {
        $batch = DeliveryBatch::with(['order.requestOrder', 'order.customer', 'items.orderItem.barang'])
            ->findOrFail($batchId);

        $order = $batch->order;
        $requestOrder = $order?->requestOrder;
        $customerName = $order->customer?->nama_customer
            ?? $order->customer_name
            ?? $requestOrder?->customer_name
            ?? '-';
        $noPoDisplay = $requestOrder?->no_po ?? '-';

        $items = $this->getInvoiceItemsForBatch($batch);
        $totals = $this->calculateInvoiceTotals($items, $requestOrder);

        $customerModel = $order?->customer;
        if (!$customerModel && !empty($requestOrder?->customer_name)) {
            $customerModel = \App\Models\Customer::where('nama_customer', $requestOrder->customer_name)->first();
        }

        $customerNpwp = $customerModel->npwp ?? '';
        $customerAddress = '';
        if ($customerModel) {
            $parts = array_filter([
                $customerModel->alamat_pengiriman ?? null,
                $customerModel->kota ?? null,
                $customerModel->provinsi ?? null,
                $customerModel->kode_pos ?? null,
            ]);
            $customerAddress = implode(', ', $parts);
        }

        $invoiceNumber = 'IO-IJB/' . now()->format('my') . '/' . str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
        $isGa = strtolower(Auth::user()->role ?? '') === 'general affair';
        $invoiceExcelRoute = $isGa
            ? route('ga.sales-order.batch.invoice-excel', $batch->id)
            : route('delivery-orders.batch.invoice-excel', $batch->id);
        $batches = collect();

        return view('admin.sales.sales-order.invoice', compact(
            'customerName',
            'customerAddress',
            'customerNpwp',
            'noPoDisplay',
            'items',
            'invoiceNumber',
            'invoiceExcelRoute',
            'batch',
            'batches',
        ) + ['rowId' => $batch->id, 'rowType' => 'batch_invoice'] + $totals);
    }

    public function downloadBatchInvoiceExcel(Request $request, $batchId)
    {
        $type           = $request->input('row_type', 'batch_invoice');
        $invNumber      = $request->input('inv_number', 'IO-IJB/' . now()->format('my') . '/' . rand(1000, 9999));
        $invDate        = $request->input('inv_date', now()->format('Y-m-d'));
        $invNpwp        = $request->input('inv_npwp', $request->input('inv_npwp_val', ''));
        $invPoNo        = $request->input('inv_po_no', '-');
        $invPaymentNote = $request->input('inv_payment_note', '');
        $invAddress     = $request->input('inv_address', '');

        $batch = DeliveryBatch::with(['order.requestOrder', 'order.customer', 'items.orderItem.barang'])
            ->findOrFail($batchId);
        $requestOrder = $batch->order?->requestOrder;
        $customerName = $batch->order?->customer?->nama_customer
            ?? $batch->order?->customer_name
            ?? $requestOrder?->customer_name
            ?? '-';

        $customerAddress = $batch->order?->customer?->alamat_pengiriman
            ?? $batch->order?->customer?->alamat
            ?? $requestOrder?->customer_address
            ?? '';

        $items = collect($this->getInvoiceItemsForBatch($batch))->map(function ($item) {
            return [
                'nama_barang' => $item['nama_barang'],
                'quantity' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['subtotal'],
            ];
        });

        $totals = $this->calculateInvoiceTotals($items->toArray(), $requestOrder);

        $data = [
            'type'           => $type,
            'invNumber'      => $invNumber,
            'invDate'        => $invDate,
            'invNpwp'        => $invNpwp,
            'invPoNo'        => $invPoNo,
            'invPaymentNote' => $invPaymentNote,
            'invAddress'     => $invAddress,
            'customerName'   => $customerName,
            'customerAddress'=> $customerAddress,
            'items'          => $items,
            'subtotal'       => $totals['subtotal'],
            'tax'            => $totals['tax'],
            'grandTotal'     => $totals['grandTotal'],
            'dpp'            => $totals['tax'] > 0 ? round(($totals['subtotal'] * 100) / 111) : 0,
        ];

        $filename = 'Invoice_' . str_replace(['/', ' '], ['_', '_'], $invNumber) . '.xlsx';
        return Excel::download(new InvoiceExport($data), $filename);
    }

    /**
     * Kirim RequestOrder ke Warehouse dari halaman SO
     */
    public function sentRequestOrderToWarehouse(Request $request, \App\Models\RequestOrder $requestOrder)
    {
        $alreadySent = Order::where('request_order_id', $requestOrder->id)
            ->whereIn('status', ['sent_to_warehouse', 'completed', 'not_completed'])
            ->exists();

        if ($alreadySent) {
            return redirect()->back()
                ->with(['title' => 'Gagal', 'text' => 'Request Order ini sudah pernah dikirim ke warehouse.']);
        }

        DB::beginTransaction();
        try {
            $requestOrder->load('items', 'sales');
            $existingOrder = Order::where('request_order_id', $requestOrder->id)->first();

            if ($existingOrder) {
                $doNumber = $existingOrder->do_number ?? ('DO-' . now()->format('Ymd') . '-' . str_pad(
                    Order::whereDate('created_at', now()->toDateString())->count() + 1,
                    4, '0', STR_PAD_LEFT
                ));
                $existingOrder->update(['status' => 'sent_to_warehouse', 'do_number' => $doNumber]);
                $orderNumber = $existingOrder->order_number;
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
                    'sales_id'            => $requestOrder->sales_id ?? Auth::id(),
                    'customer_name'       => $requestOrder->customer_name,
                    'customer_id'         => $requestOrder->customer_id ?? null,
                    'request_order_id'    => $requestOrder->id,
                    'custom_penawaran_id' => $requestOrder->custom_penawaran_id ?? null,
                    'status'              => 'sent_to_warehouse',
                    'tanggal_kebutuhan'   => $requestOrder->tanggal_kebutuhan ?? now()->toDateString(),
                    'catatan_customer'    => $requestOrder->catatan_customer ?? null,
                ]);

                foreach ($requestOrder->items as $item) {
                    OrderItem::create([
                        'order_id'           => $order->id,
                        'barang_id'          => $item->barang_id ?? null,
                        'quantity'           => $item->quantity ?? 1,
                        'delivered_quantity' => 0,
                        'status_item'        => 'pending',
                        'harga'              => $item->harga ?? 0,
                        'subtotal'           => $item->subtotal ?? 0,
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()
                ->with(['title' => 'Berhasil', 'text' => "Request Order berhasil dikirim ke Warehouse dengan No. {$orderNumber}."]);
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

        if ($isSearch) {
            $results = \App\Models\RequestOrder::where(function ($q) use ($search) {
                    $q->where('request_number',     'like', "%$search%")
                      ->orWhere('nomor_penawaran',   'like', "%$search%")
                      ->orWhere('sales_order_number','like', "%$search%")
                      ->orWhere('customer_name',     'like', "%$search%")
                      ->orWhere('no_po',             'like', "%$search%");
                })
                ->where('sales_id', Auth::id())
                ->with(['items', 'customer'])
                ->get()
                ->map(fn($ro) => array_merge($this->mapRequestOrderRow($ro), [
                    'catatan_customer' => $ro->catatan_customer,
                    'aksi_url'         => route('sales.request-order.show', $ro),
                    'image_po'         => $ro->image_po,
                ]));
        } else {
            $requestOrders = \App\Models\RequestOrder::where('sales_id', Auth::id())
                ->with(['order', 'items', 'customer'])
                ->latest()
                ->paginate(20)
                ->appends(request()->query());

            $results = $requestOrders->map(fn($ro) => array_merge($this->mapRequestOrderRow($ro), [
                'catatan_customer' => $ro->catatan_customer,
                'aksi_url'         => route('sales.request-order.show', $ro),
                'image_po'         => $ro->image_po,
            ]));

            $salesOrders = $requestOrders;
        }

        return view('admin.sales.sales-order.index', [
            'results'     => $results,
            'search'      => $search,
            'isSearch'    => $isSearch,
            'salesOrders' => isset($salesOrders) ? $salesOrders : null,
        ]);
    }

    public function getPenawaranDetail(Request $request)
    {
        $penawaranId = $request->input('id');
        $penawaran   = CustomPenawaran::where('sales_id', Auth::id())->findOrFail($penawaranId);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'               => $penawaran->id,
                'penawaran_number' => $penawaran->penawaran_number,
                'to'               => $penawaran->to,
                'up'               => $penawaran->up,
                'email'            => $penawaran->email,
                'subject'          => $penawaran->subject,
                'our_ref'          => $penawaran->our_ref,
                'date'             => $penawaran->date ? \Carbon\Carbon::parse($penawaran->date)->format('d/m/Y') : '-',
                'intro_text'       => $penawaran->intro_text,
                'status'           => $penawaran->status,
                'subtotal'         => $penawaran->subtotal,
                'tax'              => $penawaran->tax,
                'grand_total'      => $penawaran->grand_total,
                'items'            => $penawaran->items->map(function ($item) {
                    return [
                        'nama_barang' => $item->nama_barang,
                        'qty'         => $item->qty,
                        'satuan'      => $item->satuan,
                        'harga'       => $item->harga,
                        'diskon'      => $item->diskon,
                        'subtotal'    => $item->subtotal,
                        'keterangan'  => $item->keterangan,
                        'images'      => $item->images ?? [],
                    ];
                }),
            ],
        ]);
    }

    public function create()
    {
        $customPenawarans = CustomPenawaran::where('sales_id', Auth::id())
            ->whereIn('status', ['open', 'approved'])
            ->with('items')
            ->latest()
            ->get();

        $salesUsers      = User::where('role', 'Sales')->pluck('name', 'name')->toArray();
        $currentUserName = Auth::user()->name;

        return view('admin.sales.sales-order.create', compact('customPenawarans', 'salesUsers', 'currentUserName'));
    }

    public function store(Request $request)
    {
        Log::info('Sales Order Store Request Incoming (Pivoted to RequestOrder)', [
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
            $requestOrder = RequestOrder::create([
                'sales_id'          => Auth::id(),
                'request_number'    => RequestOrder::generateNomorPenawaran(),
                'customer_name'     => $validated['to'],
                'subject'           => $validated['subject'],
                'tanggal_kebutuhan' => $validated['date'],
                'status'            => 'pending',
            ]);

            foreach ($validated['items'] as $itemData) {
                RequestOrderItem::create([
                    'request_order_id'  => $requestOrder->id,
                    'nama_barang_custom'=> $itemData['nama_barang'],
                    'quantity'          => $itemData['qty'],
                    'harga'             => $itemData['harga'],
                    'subtotal'          => $itemData['qty'] * $itemData['harga'],
                    'diskon_percent'    => $itemData['diskon'] ?? 0,
                ]);
            }

            DB::commit();
            return redirect()->route('sales.request-order.show', $requestOrder->id)
                ->with(['title' => 'Berhasil', 'text' => "Request Order berhasil dibuat."]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Request Order Store Error (from SO Controller)', ['message' => $e->getMessage()]);
            return back()->withErrors('Gagal membuat Request Order: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $requestOrder = RequestOrder::with('items.barang', 'sales', 'customPenawaran', 'order', 'customer')->findOrFail($id);

        $userRole = trim(strtolower(Auth::user()->role ?? ''));
        $allowed  = array_map('strtolower', ['Supervisor', 'Admin']);
        if ($requestOrder->sales_id !== Auth::id() && !in_array($userRole, $allowed)) {
            abort(403);
        }

        return view('admin.sales.request-order.show', compact('requestOrder'));
    }

    public function edit($id)
    {
        $requestOrder = RequestOrder::findOrFail($id);
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        $requestOrder->load('items');
        $salesUsers      = User::where('role', 'Sales')->pluck('name', 'name')->toArray();
        $currentUserName = Auth::user()->name;

        return view('admin.sales.request-order.edit', compact('requestOrder', 'salesUsers', 'currentUserName'));
    }

    public function update(Request $request, $id)
    {
        $requestOrder = RequestOrder::findOrFail($id);
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
                'tanggal_kebutuhan' => $validated['date'],
            ]);

            $requestOrder->items()->delete();

            foreach ($validated['items'] as $itemData) {
                RequestOrderItem::create([
                    'request_order_id'   => $requestOrder->id,
                    'nama_barang_custom' => $itemData['nama_barang'],
                    'quantity'           => $itemData['qty'],
                    'harga'              => $itemData['harga'],
                    'subtotal'           => $itemData['qty'] * $itemData['harga'],
                    'diskon_percent'     => $itemData['diskon'] ?? 0,
                ]);
            }

            DB::commit();
            return redirect()->route('sales.request-order.show', $requestOrder->id)
                ->with(['title' => 'Berhasil', 'text' => 'Request Order berhasil diubah.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal mengubah Request Order: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $requestOrder = RequestOrder::findOrFail($id);
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($requestOrder) {
                $requestOrder->items()->delete();
                $requestOrder->delete();
            });
            return redirect()->route('sales.request-order.index')
                ->with(['title' => 'Berhasil', 'text' => 'Request Order berhasil dihapus.']);
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal menghapus Request Order: ' . $e->getMessage());
        }
    }

    public function uploadImage(Request $request, $id)
    {
        $requestOrder = RequestOrder::findOrFail($id);
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
        $requestOrder = RequestOrder::findOrFail($id);
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

        $results = \App\Models\RequestOrder::where('sales_id', Auth::id())
            ->where(function ($q) use ($search) {
                $q->where('request_number',  'like', "%$search%")
                  ->orWhere('nomor_penawaran','like', "%$search%")
                  ->orWhere('customer_name',  'like', "%$search%")
                  ->orWhere('no_po',          'like', "%$search%");
            })
            ->limit(10)
            ->get()
            ->map(function ($ro) {
                return [
                    'sales_order_number' => $ro->nomor_penawaran ?: ($ro->request_number ?: 'Quotation'),
                    'customer_name'      => $ro->customer_name,
                    'type'               => 'penawaran',
                    'badge'              => 'Quotation',
                    'url'                => route('sales.request-order.show', $ro->id),
                    'no_po'              => $ro->no_po,
                ];
            });

        return response()->json(['success' => true, 'data' => $results]);
    }
} // ← penutup class SalesOrderController