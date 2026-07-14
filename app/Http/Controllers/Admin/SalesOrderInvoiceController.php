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

class SalesOrderInvoiceController extends Controller
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
            'has_batches'    => $ro->order && $ro->order->batches->count() > 1,
        ];
    }

    /**
     * Index untuk General Affair (read-only, semua sales)
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $isSearch = $request->filled('search');
        $perPage = (int) $request->input('perPage', 20);

        $periodeType = $request->input('periode_type', 'all');
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('n'));
        $week = $request->input('week', 1);
        $date = $request->input('date', date('Y-m-d'));
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        list($startDate, $endDate) = $this->resolveDateRange($periodeType, $year, $month, $week, $date, $startDateInput, $endDateInput);

        $salesId = $request->input('sales_id');
        $reportType = $request->input('report_type', 'all');
        $status = $request->input('status', 'all');

        $baseQuery = \App\Models\Quotation::with(['order.batches', 'items', 'customer.pics']);

        // Base filter for GA sales order invoices - only completed, partial delivery, cancel, and reject statuses
        $baseQuery->whereHas('order', function ($o) {
            $o->whereIn('status', [
                'completed',
                'not_completed',
                'canceled',
                'partial_canceled',
                'rejected_supervisor',
                'rejected_warehouse'
            ]);
        });

        // Apply search filter
        if ($isSearch) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('request_number', 'like', "%$search%")
                  ->orWhere('quotation_number', 'like', "%$search%")
                  ->orWhere('sales_order_number', 'like', "%$search%")
                  ->orWhere('customer_name', 'like', "%$search%")
                  ->orWhere('no_po', 'like', "%$search%")
                  ->orWhereHas('customer.pics', function ($picQuery) use ($search) {
                      $picQuery->where('name', 'like', "%$search%")
                          ->orWhere('position', 'like', "%$search%")
                          ->orWhere('email', 'like', "%$search%")
                          ->orWhere('phone', 'like', "%$search%");
                  });
            });
        }

        // Apply date filter
        if ($startDate && $endDate) {
            $baseQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Apply sales agent filter
        if ($salesId) {
            $baseQuery->where('sales_id', $salesId);
        }

        // Apply report type filter
        if ($reportType && $reportType !== 'all') {
            if ($reportType === 'quotation') {
                $baseQuery->whereNull('custom_quotation_id');
            } elseif ($reportType === 'custom_quotation') {
                $baseQuery->whereNotNull('custom_quotation_id');
            }
        }

        // Apply status filter
        if ($status && $status !== 'all') {
            $baseQuery->whereHas('order', function ($o) use ($status) {
                $o->where('status', $status);
            });
        }

        $requestOrders = $baseQuery->latest()
            ->paginate($perPage)
            ->appends($request->query());

        $results = $requestOrders->map(fn($ro) => $this->mapRequestOrderRow($ro));
        $salesOrders = $requestOrders;

        // Retrieve sales users for the filter dropdown
        $salesUsers = User::where('role', 'Sales')->orderBy('name')->get();

        return view('admin.sales-order-invoices.index', [
            'results'     => $results,
            'search'      => $search,
            'isSearch'    => $isSearch,
            'salesOrders' => $salesOrders,
            'salesUsers'  => $salesUsers,
        ]);
    }

    /**
     * Export General Affair sales order data to Excel.
     */
    public function exportGaSalesOrders(Request $request)
    {
        $search = $request->input('search', null);
        $periodeType = $request->input('periode_type', 'all');
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('n'));
        $week = $request->input('week', 1);
        $date = $request->input('date', date('Y-m-d'));
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        list($startDate, $endDate) = $this->resolveDateRange($periodeType, $year, $month, $week, $date, $startDateInput, $endDateInput);

        $salesId = $request->input('sales_id');
        $reportType = $request->input('report_type', 'all');
        $status = $request->input('status', 'all');

        $filename = 'ga_sales_orders_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new GaSalesOrderExport($search, $startDate, $endDate, $salesId, $status, $reportType),
            $filename
        );
    }

    /**
     * Export General Affair sales order data to PDF.
     */
    public function exportGaSalesOrdersPdf(Request $request)
    {
        $search = $request->input('search', null);
        $periodeType = $request->input('periode_type', 'all');
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('n'));
        $week = $request->input('week', 1);
        $date = $request->input('date', date('Y-m-d'));
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        list($startDate, $endDate) = $this->resolveDateRange($periodeType, $year, $month, $week, $date, $startDateInput, $endDateInput);

        $salesId = $request->input('sales_id');
        $reportType = $request->input('report_type', 'all');
        $status = $request->input('status', 'all');

        $query = Quotation::with(['items', 'customer', 'order']);

        // Base filter for GA sales order invoices - only completed, partial delivery, cancel, and reject statuses
        $query->whereHas('order', function ($o) {
            $o->whereIn('status', [
                'completed',
                'not_completed',
                'canceled',
                'partial_canceled',
                'rejected_supervisor',
                'rejected_warehouse'
            ]);
        });

        // Search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                  ->orWhere('quotation_number', 'like', "%{$search}%")
                  ->orWhere('sales_order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('no_po', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Sales agent filter
        if (!empty($salesId)) {
            $query->where('sales_id', $salesId);
        }

        // Report type filter
        if (!empty($reportType) && $reportType !== 'all') {
            if ($reportType === 'quotation') {
                $query->whereNull('custom_quotation_id');
            } elseif ($reportType === 'custom_quotation') {
                $query->whereNotNull('custom_quotation_id');
            }
        }

        // Status filter
        if (!empty($status) && $status !== 'all') {
            $query->whereHas('order', function ($o) use ($status) {
                $o->where('status', $status);
            });
        }

        $rawResults = $query->latest()->get();
        $results = $rawResults->map(function($ro) {
            $mapped = $this->mapRequestOrderRow($ro);
            $ro->status = $mapped['status']; // Use mapped user-friendly status label
            return $ro;
        });

        // Filter description
        $filterDescription = 'Semua Periode';
        if ($periodeType !== 'all') {
            if ($startDate && $endDate) {
                $filterDescription = 'Periode: ' . $startDate->format('d M Y') . ' s/d ' . $endDate->format('d M Y');
            }
        }

        $data = [
            'results' => $results,
            'filter_description' => $filterDescription,
            'company_name' => \App\Models\SystemSetting::get('company_name', 'PT. INDONUSA JAYA BERSAMA'),
            'company_address' => \App\Models\SystemSetting::get('company_address', 'Wonorejo Selatan VB No. 50 Rungkut, Surabaya - 60296'),
            'company_phone' => \App\Models\SystemSetting::get('company_phone', '08121634173'),
            'company_email' => \App\Models\SystemSetting::get('company_email', 'info@indonusa.com'),
            'leader_name' => \App\Models\SystemSetting::get('leader_name', 'Alimul Imam S.AP'),
            'leader_position' => \App\Models\SystemSetting::get('leader_position', 'Direktur'),
            'print_date' => now()->format('d M Y H:i:s'),
        ];

        $html = view('admin.pdf.ga-sales-order-pdf', $data)->render();

        $pdf = $this->getBrowsershot($html)
            ->landscape()
            ->format('A4')
            ->margins(12.7, 12.7, 12.7, 12.7)
            ->showBackground()
            ->writeOptionsToFile()
            ->pdf();

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Laporan-GA-Sales-' . now()->format('YmdHis') . '.pdf"');
    }

    /**
     * Helper to resolve start and end dates based on period selections.
     */
    private function resolveDateRange($periodeType, $year, $month, $week, $date, $startDateInput, $endDateInput)
    {
        if ($periodeType === 'all') {
            return [null, null];
        }

        $startDate = null;
        $endDate = null;

        if ($periodeType === 'daily') {
            $d = Carbon::parse($date);
            $startDate = $d->copy()->startOfDay();
            $endDate = $d->copy()->endOfDay();
        } elseif ($periodeType === 'weekly') {
            $firstDay = Carbon::create($year, $month, 1)->startOfDay();
            if ($week == 1) {
                $startDate = $firstDay->copy();
                $endDate = $firstDay->copy()->addDays(6)->endOfDay();
            } elseif ($week == 2) {
                $startDate = $firstDay->copy()->addDays(7);
                $endDate = $firstDay->copy()->addDays(13)->endOfDay();
            } elseif ($week == 3) {
                $startDate = $firstDay->copy()->addDays(14);
                $endDate = $firstDay->copy()->addDays(20)->endOfDay();
            } elseif ($week == 4) {
                $startDate = $firstDay->copy()->addDays(21);
                $endDate = $firstDay->copy()->addDays(27)->endOfDay();
            } else {
                $startDate = $firstDay->copy()->addDays(28);
                $endDate = $firstDay->copy()->endOfMonth()->endOfDay();
            }
        } elseif ($periodeType === 'monthly') {
            $startDate = Carbon::create($year, $month, 1)->startOfDay();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();
        } elseif ($periodeType === 'yearly') {
            $startDate = Carbon::create($year, 1, 1)->startOfDay();
            $endDate = Carbon::create($year, 12, 31)->endOfDay();
        } elseif ($periodeType === 'custom' && $startDateInput && $endDateInput) {
            $startDate = Carbon::parse($startDateInput)->startOfDay();
            $endDate = Carbon::parse($endDateInput)->endOfDay();
        } else {
            $startDate = Carbon::create($year, $month, 1)->startOfDay();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();
        }

        return [$startDate, $endDate];
    }

    /**
     * Get invoice items for a request order, preferring warehouse-shipped order items when available.
     */
    private function getInvoiceItems(Quotation $ro, bool $preferWarehouseItems = false): array
    {
        if ($preferWarehouseItems && $ro->order && $ro->order->items->count() > 0) {
            $warehouseItems = $ro->order->items->filter(fn ($item) => ($item->delivered_quantity ?? 0) > 0);

            if ($warehouseItems->isNotEmpty()) {
                return $warehouseItems->map(function ($item) {
                    $quantity = $item->delivered_quantity ?? $item->quantity ?? 0;
                    $harga = $item->harga ?? 0;

                    return [
                        'goods_code' => optional($item->barang)->goods_code ?? '',
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
            $barangData = \App\Models\Goods::find($item->goods_id);
            return [
                'goods_code' => optional($barangData)->goods_code ?? '',
                'nama_barang' => $item->custom_product_name
                    ?? optional($barangData)->goods_name
                    ?? '-',
                'deskripsi' => optional($barangData)->description ?? '-',
                'qty'      => $item->quantity ?? 1,
                'harga'    => $item->price ?? 0,
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
                'goods_code' => optional($barang)->goods_code ?? '',
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

    private function calculateInvoiceTotals(array $items, ?Quotation $ro = null): array
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

        $results = \App\Models\Quotation::where(function ($q) use ($search) {
                $q->where('request_number',   'like', "%$search%")
                  ->orWhere('quotation_number', 'like', "%$search%")
                  ->orWhere('sales_order_number', 'like', "%$search%")
                  ->orWhere('customer_name',   'like', "%$search%")
                  ->orWhere('no_po',           'like', "%$search%")
                  ->orWhereHas('customer.pics', function ($picQuery) use ($search) {
                      $picQuery->where('name', 'like', "%$search%")
                          ->orWhere('position', 'like', "%$search%")
                          ->orWhere('email', 'like', "%$search%")
                          ->orWhere('phone', 'like', "%$search%");
                  });
            })
            ->where(function ($q) {
                $q->whereDoesntHave('order')
                  ->orWhereHas('order', function ($o) {
                      $o->where('status', '!=', 'open')
                        ->where('status', '!=', 'sent_to_supervisor');
                  });
            })
            ->limit(10)
            ->get()
            ->map(function ($ro) {
                return [
                    'sales_order_number' => $ro->sales_order_number ?: ($ro->quotation_number ?: ($ro->request_number ?: 'Quotation')),
                    'customer_name'      => $ro->customer_name,
                    'type'               => 'quotation',
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
        $ro            = \App\Models\Quotation::with(['items', 'order.items.barang', 'order.batches'])->findOrFail($id);
        $customerName  = $ro->customer_name ?? '-';
        $noPoDisplay   = $ro->no_po ?? '-';
        $subtotal      = $ro->subtotal ?? 0;
        $tax           = $ro->tax ?? 0;
        $grandTotal    = $ro->grand_total ?? 0;

        if (!empty($ro->customer_id)) {
            $customerModel = \App\Models\Customer::find($ro->customer_id);
        }
        if (!$customerModel && !empty($customerName)) {
            $customerModel = \App\Models\Customer::where('customer_name', $customerName)->first();
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
                $customerModel->shipping_address ?? null,
                $customerModel->city             ?? null,
                $customerModel->province         ?? null,
                $customerModel->postal_code      ?? null,
            ]);
            $customerAddress = implode(', ', $parts);
        }

        // Generate and persist unique invoice number for General Affair role
        $invoiceNumber = 'IO-IJB/' . now()->format('my') . '/' . str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
        if ($isGa && $ro->order) {
            // If order already has no_invoice, use it. Otherwise generate unique and save.
            if (empty($ro->order->no_invoice)) {
                do {
                    $candidate = 'IO-IJB/' . now()->format('my') . '/' . str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
                } while (\App\Models\Order::where('no_invoice', $candidate)->exists());

                $ro->order->no_invoice = $candidate;
                try {
                    $ro->order->save();
                    $invoiceNumber = $candidate;
                } catch (\Exception $e) {
                    // If save fails, fallback to generated number (non-persisted)
                    Log::warning('Failed to save no_invoice for order id ' . $ro->order->id . ': ' . $e->getMessage());
                    $invoiceNumber = $candidate;
                }
            } else {
                $invoiceNumber = $ro->order->no_invoice;
            }
        }

        $isGa = strtolower(Auth::user()->role ?? '') === 'general affair';
        $batches = $ro->order?->batches ?? collect();

        if ($batches->count() === 1) {
            $batch = $batches->first();
            $route = $isGa
                ? route('invoice.batch.invoice', $batch->id)
                : route('delivery-orders.batch.invoice', $batch->id);
            return redirect($route);
        }

        $invoiceExcelRoute = $isGa
            ? route('invoice.excel', $id)
            : route('sales.sales-order.invoice-excel', $id);

        $order = $ro->order;

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
            'order',
        ) + ['rowId' => $id, 'rowType' => $type]);
    }

    public function getInvoiceHistory($id)
    {
        if (strtolower(Auth::user()->role ?? '') === 'sales') {
            abort(403, 'Unauthorized.');
        }

        $ro = \App\Models\Quotation::with(['order.batches.items.orderItem.barang'])->findOrFail($id);

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

        $ro              = \App\Models\Quotation::with(['items', 'order.items.barang'])->findOrFail($id);
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

        $batch = DeliveryBatch::with(['order.quotation', 'order.customer', 'items.orderItem.barang'])
            ->findOrFail($batchId);

        $order = $batch->order;
        $requestOrder = $order?->quotation;
        $customerName = $order->customer?->customer_name
            ?? $order->customer_name
            ?? $requestOrder?->customer_name
            ?? '-';
        $noPoDisplay = $requestOrder?->no_po ?? '-';

        $items = $this->getInvoiceItemsForBatch($batch);
        $totals = $this->calculateInvoiceTotals($items, $requestOrder);

        $customerModel = $order?->customer;
        if (!$customerModel && !empty($requestOrder?->customer_name)) {
            $customerModel = \App\Models\Customer::where('customer_name', $requestOrder->customer_name)->first();
        }

        $customerNpwp = $customerModel->npwp ?? '';
        $customerAddress = '';
        if ($customerModel) {
            $parts = array_filter([
                $customerModel->shipping_address ?? null,
                $customerModel->city ?? null,
                $customerModel->province ?? null,
                $customerModel->postal_code ?? null,
            ]);
            $customerAddress = implode(', ', $parts);
        }

        $isGa = strtolower(Auth::user()->role ?? '') === 'general affair';

        // Generate and persist unique invoice number for General Affair role (batch)
        $invoiceNumber = 'IO-IJB/' . now()->format('my') . '/' . str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
        if ($isGa && $order) {
            if (empty($order->no_invoice)) {
                do {
                    $candidate = 'IO-IJB/' . now()->format('my') . '/' . str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
                } while (\App\Models\Order::where('no_invoice', $candidate)->exists());

                $order->no_invoice = $candidate;
                try {
                    $order->save();
                    $invoiceNumber = $candidate;
                } catch (\Exception $e) {
                    Log::warning('Failed to save batch no_invoice for order id ' . ($order->id ?? 'unknown') . ': ' . $e->getMessage());
                    $invoiceNumber = $candidate;
                }
            } else {
                $invoiceNumber = $order->no_invoice;
            }
        }
        $invoiceExcelRoute = $isGa
            ? route('invoice.batch.excel', $batch->id)
            : route('delivery-orders.batch.invoice-excel', $batch->id);
        $batches = collect();
        $order = $batch->order;

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
            'order',
        ) + ['rowId' => $batch->id, 'rowType' => 'batch_invoice'] + $totals);
    }

    /**
     * Print Receipt (persist no_receipt on orders and render receipt)
     */
    public function printReceipt(Request $request, $id)
    {
        if (strtolower(Auth::user()->role ?? '') !== 'general affair') {
            abort(403);
        }

        $ro = \App\Models\Quotation::with(['order', 'order.items.barang'])->findOrFail($id);
        $order = $ro->order;

        if (!$order) {
            abort(404, 'Order not found for this quotation');
        }

        // Ensure invoice number exists for this order.
        if (empty($order->no_invoice)) {
            do {
                $invoiceCandidate = 'IO-IJB/' . now()->format('my') . '/' . str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
            } while (\App\Models\Order::where('no_invoice', $invoiceCandidate)->exists());

            $order->no_invoice = $invoiceCandidate;
            try {
                $order->save();
            } catch (\Exception $e) {
                Log::warning('Failed to save no_invoice for order id ' . $order->id . ': ' . $e->getMessage());
            }
        }

        // Derive receipt number from invoice number.
        $receiptCandidate = preg_replace('/^IO-IJB\//', 'KW-IJB/', $order->no_invoice);
        if ($receiptCandidate === $order->no_invoice) {
            $receiptCandidate = 'KW-IJB/' . now()->format('my') . '/' . str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
        }

        if (empty($order->no_receipt) || $order->no_receipt !== $receiptCandidate) {
            if (\App\Models\Order::where('no_receipt', $receiptCandidate)->where('id', '!=', $order->id)->exists()) {
                do {
                    $receiptCandidate = 'KW-IJB/' . now()->format('my') . '/' . str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
                } while (\App\Models\Order::where('no_receipt', $receiptCandidate)->exists());
            }

            $order->no_receipt = $receiptCandidate;
            try {
                $order->save();
            } catch (\Exception $e) {
                Log::warning('Failed to save no_receipt for order id ' . $order->id . ': ' . $e->getMessage());
            }
        }

        // Prepare totals
        $isGa = true;
        $items = $this->getInvoiceItems($ro, $isGa);
        $totals = $this->calculateInvoiceTotals($items, $ro);

        // Terbilang helper (simple)
        $terbilang = function ($number) use (&$terbilang) {
            $units = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
            $n = (int) $number;
            if ($n < 12) return $units[$n];
            if ($n < 20) return $units[$n - 10] . ' Belas';
            if ($n < 100) return $units[intval($n/10)] . ' Puluh' . ($n%10 ? ' ' . $units[$n%10] : '');
            if ($n < 200) return 'Seratus' . ($n-100 ? ' ' . $terbilang($n-100) : '');
            if ($n < 1000) return $units[intval($n/100)] . ' Ratus' . ($n%100 ? ' ' . $terbilang($n%100) : '');
            if ($n < 2000) return 'Seribu' . ($n-1000 ? ' ' . $terbilang($n-1000) : '');
            if ($n < 1000000) return $terbilang(intval($n/1000)) . ' Ribu' . ($n%1000 ? ' ' . $terbilang($n%1000) : '');
            if ($n < 1000000000) return $terbilang(intval($n/1000000)) . ' Juta' . ($n%1000000 ? ' ' . $terbilang($n%1000000) : '');
            return (string)$number;
        };

        $amountNumber = $totals['grandTotal'] ?? 0;

        return view('admin.receipts.index', [
            'order' => $order,
            'quotation' => $ro,
            'customerName' => $ro->customer_name ?? $order->customer_name ?? '-',
            'no_receipt' => $order->no_receipt,
            'amount' => $amountNumber,
            'amount_words' => trim($terbilang($amountNumber)) . ' Rupiah',
            'no_po' => $ro->no_po ?? '-',
            'date' => now()->format('d F Y'),
        ]);
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

        $batch = DeliveryBatch::with(['order.quotation', 'order.customer', 'items.orderItem.barang'])
            ->findOrFail($batchId);
        $requestOrder = $batch->order?->quotation;
        $customerName = $batch->order?->customer?->customer_name
            ?? $batch->order?->customer_name
            ?? $requestOrder?->customer_name
            ?? '-';

        $customerAddress = $batch->order?->customer?->shipping_address
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
