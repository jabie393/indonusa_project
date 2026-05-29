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

class SalesOrderInvoiceController extends Controller
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

        $firstPic = $ro->customer?->pics?->first();

        return [
            'id'             => $ro->id,
            'type'           => 'request_order',
            'no_request'     => $ro->request_number,
            'no_penawaran'   => $ro->nomor_penawaran,
            'no_po'          => $ro->no_po ?? '-',
            'no_sales_order' => $ro->sales_order_number,
            'tanggal'        => $ro->tanggal_kebutuhan ? $ro->tanggal_kebutuhan->format('d/m/Y') : '-',
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
        ];
    }

    /**
     * Index untuk General Affair (read-only, semua sales)
     */
    public function index(Request $request)
    {
        $search   = $request->input('search', '');
        $isSearch = $request->filled('search');
        $results  = collect();
        $perPage  = (int) $request->input('perPage', 20);

        if ($isSearch) {
            $results = \App\Models\RequestOrder::where(function ($q) use ($search) {
                    $q->where('request_number',     'like', "%$search%")
                      ->orWhere('nomor_penawaran',   'like', "%$search%")
                      ->orWhere('sales_order_number','like', "%$search%")
                      ->orWhere('customer_name',     'like', "%$search%")
                      ->orWhere('no_po',             'like', "%$search%");
                })
                ->with(['order.batches', 'items', 'customer.pics'])
                ->get()
                ->map(fn($ro) => $this->mapRequestOrderRow($ro));
        } else {
            $requestOrders = \App\Models\RequestOrder::with(['order.batches', 'items', 'customer.pics'])
                ->latest()
                ->paginate($perPage)
                ->appends($request->query());

            $results     = $requestOrders->map(fn($ro) => $this->mapRequestOrderRow($ro));
            $salesOrders = $requestOrders;
        }

        return view('admin.sales-order-invoice.index', [
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
                        'nama_barang' => optional($item->barang)->goods_name ?? '-',
                        'deskripsi' => optional($item->barang)->description ?? '-',
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
                    ?? optional($barangData)->goods_name
                    ?? '-',
                'deskripsi' => optional($barangData)->description ?? '-',
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
                    ?? optional($barang)->goods_name
                    ?? '-',
                'deskripsi' => optional($barang)->description ?? '-',
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
    public function search(Request $request)
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
        if (strtolower(Auth::user()->role ?? '') === 'sales') {
            abort(403, 'Unauthorized.');
        }

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
            ? route('invoice.excel', $id)
            : route('sales.sales-order.invoice-excel', $id);

        $batches = $ro->order?->batches ?? collect();

        return view('admin.invoice.index', compact(
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

    public function getInvoiceHistory($id)
    {
        if (strtolower(Auth::user()->role ?? '') === 'sales') {
            abort(403, 'Unauthorized.');
        }

        $ro = \App\Models\RequestOrder::with(['order.batches.items.orderItem.barang'])->findOrFail($id);

        $batches = $ro->order?->batches ?? collect();

        $isGa = strtolower(Auth::user()->role ?? '') === 'general affair';

        $history = $batches->map(function ($batchItem) use ($isGa) {
            return [
                'id' => $batchItem->id,
                'batch_number' => $batchItem->batch_number,
                'created_at' => optional($batchItem->created_at)->format('Y-m-d H:i') ?? '-',
                'items' => $batchItem->items->map(function ($item) {
                    return [
                        'goods_name' => $item->orderItem->barang->goods_name ?? ($item->orderItem->nama_barang ?? '-'),
                        'quantity_sent' => $item->quantity_sent,
                    ];
                }),
                'invoice_url' => $isGa
                    ? route('invoice.batch.invoice', $batchItem->id)
                    : route('delivery-orders.batch.invoice', $batchItem->id),
            ];
        });

        return response()->json($history);
    }

    /**
     * Download Invoice Excel
     */
    public function downloadInvoiceExcel(Request $request, $id)
    {
        if (strtolower(Auth::user()->role ?? '') === 'sales') {
            abort(403, 'Unauthorized.');
        }

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
        if (strtolower(Auth::user()->role ?? '') === 'sales') {
            abort(403, 'Unauthorized.');
        }

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
            ? route('invoice.batch.excel', $batch->id)
            : route('delivery-orders.batch.invoice-excel', $batch->id);
        $batches = collect();

        return view('admin.invoice.index', compact(
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
        if (strtolower(Auth::user()->role ?? '') === 'sales') {
            abort(403, 'Unauthorized.');
        }

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
}
