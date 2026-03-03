<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\CustomPenawaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use App\Models\Barang;

class SalesOrderController extends Controller
{
    /**
     * Kirim SalesOrder ke Warehouse (buat Order & OrderItem)
    public function sentToWarehouse(Request $request, SalesOrder $salesOrder)
    {
        if ($salesOrder->sales_id !== Auth::id()) {
            abort(403);
        }
        /**
         * Show Invoice View
         */
        public function showInvoice(Request $request, $id)
        {
            $type = $request->query('type', 'sales_order');
            $customerModel = null;
            if ($type === 'request_order') {
                $ro = \App\Models\RequestOrder::with('items')->findOrFail($id);
                $customerName = $ro->customer_name ?? '-';
                $noPoDisplay  = $ro->no_po ?? '-';
                $subtotal     = $ro->subtotal ?? 0;
                $tax          = $ro->tax ?? 0;
                $grandTotal   = $ro->grand_total ?? 0;

                // Cari data customer dari tabel customers
                if (!empty($ro->customer_id)) {
                    $customerModel = \App\Models\Customer::find($ro->customer_id);
                }
                if (!$customerModel && !empty($customerName)) {
                    $customerModel = \App\Models\Customer::where('nama_customer', $customerName)->first();
                }

                $items = $ro->items->map(function ($item) {
                    return [
                        'nama_barang' => $item->nama_barang_custom
                            ?? optional(\App\Models\Barang::find($item->barang_id))->nama_barang
                            ?? '-',
                        'qty'      => $item->quantity ?? 1,
                        'harga'    => $item->harga ?? 0,
                        'subtotal' => $item->subtotal ?? 0,
                    ];
                })->toArray();
            } else {
                $so = SalesOrder::with(['items', 'requestOrder', 'customer'])->findOrFail($id);
                if ($so->sales_id !== Auth::id()) {
                    abort(403);
                }
                $customerName = $so->to ?? $so->customer_name ?? '-';
                $noPoDisplay  = optional($so->requestOrder)->no_po ?? '-';
                $subtotal     = $so->subtotal ?? 0;
                $tax          = $so->tax ?? 0;
                $grandTotal   = $so->grand_total ?? 0;

                // Cari data customer dari tabel customers
                $customerModel = $so->customer ?? null;
                if (!$customerModel && !empty($customerName)) {
                    $customerModel = \App\Models\Customer::where('nama_customer', $customerName)->first();
                }

                $items = $so->items->map(function ($item) {
                    return [
                        'nama_barang' => $item->nama_barang ?? '-',
                        'qty'         => $item->qty ?? $item->quantity ?? 1,
                        'harga'       => $item->harga ?? 0,
                        'subtotal'    => $item->subtotal ?? 0,
                    ];
                })->toArray();
            }

            // Ambil data dari customer model jika ada
            $customerNpwp    = $customerModel->npwp ?? '';
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
            ) + ['rowId' => $id, 'rowType' => $type]);
        }

        /**
         * Download Invoice Excel (SpreadsheetML)
         */
        public function downloadInvoiceExcel(Request $request, $id)
        {
            $type = $request->input('row_type', 'sales_order');
            $invNumber      = $request->input('inv_number', 'IO-IJB/' . now()->format('my') . '/' . rand(1000,9999));
            $invDate        = $request->input('inv_date', now()->format('Y-m-d'));
            $invNpwp        = $request->input('inv_npwp', $request->input('inv_npwp_val', ''));
            $invPoNo        = $request->input('inv_po_no', '-');
            $invPaymentNote = $request->input('inv_payment_note', '');
            $invAddress     = $request->input('inv_address', '');

            $customerName = '';
            $customerAddress = '';
            $items = collect();
            $subtotal = 0;
            $tax = 0;
            $grandTotal = 0;

            if ($type === 'request_order') {
                $ro = \App\Models\RequestOrder::with('items')->findOrFail($id);
                $customerName = $ro->customer_name;
                $customerAddress = $ro->customer_address ?? '';
                $items = $ro->items->map(function($item) {
                    $desc = $item->nama_barang_custom ?? (Barang::find($item->barang_id)?->nama_barang ?? '-');
                    return [
                        'nama_barang' => $desc,
                        'quantity' => $item->quantity,
                        'harga' => $item->harga,
                        'subtotal' => $item->subtotal,
                    ];
                });
                $subtotal = $ro->subtotal ?? 0;
                $tax = $ro->tax ?? 0;
                $grandTotal = $ro->grand_total ?? ($subtotal + $tax);
            } else {
                $so = SalesOrder::with(['items', 'requestOrder'])->findOrFail($id);
                $customerName = $so->to ?? $so->customer_name;
                $customerAddress = $so->customer_address ?? '';
                $items = $so->items->map(function($item) {
                    return [
                        'nama_barang' => $item->nama_barang,
                        'qty' => $item->qty,
                        'harga' => $item->harga,
                        'subtotal' => $item->subtotal,
                    ];
                });
                $subtotal = $so->subtotal ?? 0;
                $tax = $so->tax ?? 0;
                $grandTotal = $so->grand_total ?? ($subtotal + $tax);
            }

            $dpp = $tax > 0 ? round($subtotal * 100 / 111, 0) : 0;

            // Build SpreadsheetML XML
            $xml = '<?xml version="1.0"?>
    <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
    xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:x="urn:schemas-microsoft-com:office:excel"
    xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
    xmlns:html="http://www.w3.org/TR/REC-html40">
    <Styles>
        <Style ss:ID="header"><Font ss:Bold="1" ss:Size="28" ss:Color="#003399"/><Alignment ss:Horizontal="Center"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#003399"/></Borders></Style>
        <Style ss:ID="navy"><Font ss:Bold="1" ss:Color="#FFFFFF"/><Interior ss:Color="#1A3A6B" ss:Pattern="Solid"/><Alignment ss:Horizontal="Center"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1A3A6B"/></Borders></Style>
        <Style ss:ID="normal"><Alignment ss:Horizontal="Left"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CCCCCC"/></Borders></Style>
        <Style ss:ID="right"><Alignment ss:Horizontal="Right"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CCCCCC"/></Borders></Style>
        <Style ss:ID="bold"><Font ss:Bold="1"/><Alignment ss:Horizontal="Right"/></Style>
        <Style ss:ID="red"><Font ss:Bold="1" ss:Color="#e53e3e"/></Style>
        <Style ss:ID="label"><Font ss:Bold="1"/></Style>
    </Styles>
    <Worksheet ss:Name="Invoice">
    <Table>
        <Row><Cell ss:MergeAcross="4" ss:StyleID="header"><Data ss:Type="String">INVOICE</Data></Cell></Row>
        <Row>
          <Cell ss:StyleID="label"><Data ss:Type="String">'.htmlspecialchars(strtoupper($customerName)).'</Data></Cell>
          <Cell ss:Index="7"><Data ss:Type="String">No Invoice:</Data></Cell>
          <Cell><Data ss:Type="String">'.htmlspecialchars($invNumber).'</Data></Cell>
        </Row>
        '.(!empty($invAddress) ? '<Row><Cell ss:MergeAcross="3"><Data ss:Type="String">'.htmlspecialchars($invAddress).'</Data></Cell></Row>' : '').'
        <Row>
          <Cell><Data ss:Type="String">NPWP</Data></Cell>
          <Cell><Data ss:Type="String">'.htmlspecialchars($invNpwp).'</Data></Cell>
          <Cell ss:Index="7"><Data ss:Type="String">PO No :</Data></Cell>
          <Cell><Data ss:Type="String">'.htmlspecialchars($invPoNo).'</Data></Cell>
        </Row>
        <Row></Row>
        <Row>
            <Cell ss:StyleID="navy"><Data ss:Type="String">No</Data></Cell>
            <Cell ss:StyleID="navy"><Data ss:Type="String">Description</Data></Cell>
            <Cell ss:StyleID="navy"><Data ss:Type="String">Qty</Data></Cell>
            <Cell ss:StyleID="navy"><Data ss:Type="String">Unit Price</Data></Cell>
            <Cell ss:StyleID="navy"><Data ss:Type="String">Total</Data></Cell>
        </Row>
        ';
            foreach ($items as $i => $item) {
                $desc = $item['nama_barang'] ?? $item['description'] ?? '-';
                $qty = $item['qty'] ?? $item['quantity'] ?? 0;
                $harga = $item['harga'] ?? 0;
                $sub = $item['subtotal'] ?? 0;
                $xml .= '<Row>
                    <Cell ss:StyleID="normal"><Data ss:Type="Number">'.($i+1).'</Data></Cell>
                    <Cell ss:StyleID="normal"><Data ss:Type="String">'.$desc.'</Data></Cell>
                    <Cell ss:StyleID="right"><Data ss:Type="Number">'.$qty.'</Data></Cell>
                    <Cell ss:StyleID="right"><Data ss:Type="Number">'.$harga.'</Data></Cell>
                    <Cell ss:StyleID="right"><Data ss:Type="Number">'.$sub.'</Data></Cell>
                </Row>';
            }
            $xml .= '<Row></Row>
            <Row><Cell ss:MergeAcross="3" ss:StyleID="bold"><Data ss:Type="String">Subtotal</Data></Cell><Cell ss:StyleID="bold"><Data ss:Type="Number">'.$subtotal.'</Data></Cell></Row>
            <Row><Cell ss:MergeAcross="3" ss:StyleID="normal"><Data ss:Type="String">DPP</Data></Cell><Cell ss:StyleID="normal"><Data ss:Type="Number">'.$dpp.'</Data></Cell></Row>
            <Row><Cell ss:MergeAcross="3" ss:StyleID="normal"><Data ss:Type="String">PPN</Data></Cell><Cell ss:StyleID="normal"><Data ss:Type="Number">'.$tax.'</Data></Cell></Row>
            <Row><Cell ss:MergeAcross="3" ss:StyleID="bold"><Data ss:Type="String">Total</Data></Cell><Cell ss:StyleID="bold"><Data ss:Type="Number">'.$grandTotal.'</Data></Cell></Row>
            <Row></Row>
            <Row><Cell ss:MergeAcross="2" ss:StyleID="red"><Data ss:Type="String">PAYMENT INFORMATION</Data></Cell><Cell ss:MergeAcross="1" ss:StyleID="normal"><Data ss:Type="String">PT. Indonusa Jaya Bersama</Data></Cell></Row>
            <Row><Cell ss:MergeAcross="2" ss:StyleID="normal"><Data ss:Type="String">'.str_replace("\n", " ", $invPaymentNote).'</Data></Cell><Cell ss:MergeAcross="1" ss:StyleID="normal"><Data ss:Type="String">(Nama Penandatangan)</Data></Cell></Row>
        </Table>
    </Worksheet>
    </Workbook>';

                $filename = 'Invoice_' . str_replace(['/', ' '], ['_', '_'], $invNumber) . '.xls';
            return response($xml)
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    /**
     * Kirim RequestOrder ke Warehouse (buat Order & OrderItem) dari halaman SO
     */
    public function sentRequestOrderToWarehouse(Request $request, \App\Models\RequestOrder $requestOrder)
    {
        // Cek sudah pernah dikirim
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

            $orderNumber = 'DO-' . now()->format('Ymd') . '-' . str_pad(
                Order::whereDate('created_at', now()->toDateString())->count() + 1,
                4, '0', STR_PAD_LEFT
            );

            $order = Order::create([
                'order_number'       => $orderNumber,
                'sales_id'           => $requestOrder->sales_id ?? Auth::id(),
                'customer_name'      => $requestOrder->customer_name,
                'customer_id'        => $requestOrder->customer_id ?? null,
                'request_order_id'   => $requestOrder->id,
                'custom_penawaran_id'=> $requestOrder->custom_penawaran_id ?? null,
                'status'             => 'sent_to_warehouse',
                'tanggal_kebutuhan'  => $requestOrder->tanggal_kebutuhan ?? now()->toDateString(),
                'catatan_customer'   => $requestOrder->catatan_customer ?? null,
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

            // Update status RequestOrder
            $requestOrder->update(['status' => 'sent_to_warehouse']);

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
        $search = $request->input('search', '');
        $results = collect();
        $isSearch = $request->filled('search');

        if ($isSearch) {
            // Sales Orders
            $soResults = SalesOrder::query()
                ->leftJoin('request_orders', 'sales_orders.request_order_id', '=', 'request_orders.id')
                ->where('sales_orders.sales_id', Auth::id())
                ->where(function ($q) use ($search) {
                    $q->where('sales_orders.sales_order_number', 'like', "%$search%")
                      ->orWhere('sales_orders.customer_name', 'like', "%$search%")
                      ->orWhere('sales_orders.catatan_customer', 'like', "%$search%")
                      ->orWhere('request_orders.sales_order_number', 'like', "%$search%")
                      ->orWhere('request_orders.request_number', 'like', "%$search%")
                      ->orWhere('request_orders.nomor_penawaran', 'like', "%$search%")
                      ->orWhere('request_orders.customer_name', 'like', "%$search%");
                })
                ->select(
                    'sales_orders.*',
                    'request_orders.no_po as request_no_po',
                    'request_orders.request_number as request_request_number',
                    'request_orders.nomor_penawaran as request_nomor_penawaran',
                    'request_orders.expired_at as request_expired_at',
                    'request_orders.tanggal_berlaku as request_tanggal_berlaku' // ← TAMBAH INI
                )
                ->get()
                ->map(function ($so) {
                    $diskonPersen = 0;
                    $total = 0;
                    $tanggalPenawaran = null;
                    $penawaran = null;
                    $soModel = \App\Models\SalesOrder::find($so->id);
                    if ($soModel && $soModel->requestOrder && $soModel->requestOrder->nomor_penawaran) {
                        $penawaran = \App\Models\CustomPenawaran::where('penawaran_number', $soModel->requestOrder->nomor_penawaran)
                            ->with('items')
                            ->first();
                    }
                    if ($penawaran) {
                        $diskonPersen = $penawaran->items->first()->diskon ?? 0;
                        $total = $penawaran->grand_total;
                        $tanggalPenawaran = $penawaran->date ? date('d/m/Y', strtotime($penawaran->date)) : '-';
                    }

                    // ← PERBAIKAN: prioritas tanggal_berlaku, fallback ke expired_at
                    $berlakuSampai = '-';
                    if (!empty($so->request_tanggal_berlaku)) {
                        $berlakuSampai = \Carbon\Carbon::parse($so->request_tanggal_berlaku)->translatedFormat('d F Y');
                    } elseif (!empty($so->request_expired_at)) {
                        $berlakuSampai = \Carbon\Carbon::parse($so->request_expired_at)->translatedFormat('d F Y');
                    } elseif ($soModel && $soModel->requestOrder && $soModel->requestOrder->tanggal_berlaku) {
                        $berlakuSampai = \Carbon\Carbon::parse($soModel->requestOrder->tanggal_berlaku)->translatedFormat('d F Y');
                    }

                    return [
                        'id' => $so->id,
                        'type' => 'sales_order',
                        'no_request' => $so->request_request_number ?? '-',
                        'no_penawaran' => $so->request_nomor_penawaran ?? '-',
                        'no_po' => $so->request_no_po ?? '-',
                        'no_sales_order' => $so->sales_order_number,
                        'tanggal' => $tanggalPenawaran ?? ($so->tanggal_kebutuhan ? (is_string($so->tanggal_kebutuhan) ? date('d/m/Y', strtotime($so->tanggal_kebutuhan)) : $so->tanggal_kebutuhan->format('d/m/Y')) : '-'),
                        'customer_name' => $so->customer_name,
                        'jumlah_item' => $soModel ? $soModel->items->count() : 0,
                        'total' => $total,
                        'diskon' => $diskonPersen,
                        'status' => $so->status,
                        'berlaku_sampai' => $berlakuSampai, // ← SUDAH DIPERBAIKI
                        'catatan_customer' => $so->catatan_customer,
                        'aksi_url' => route('sales.sales-order.show', $so->id),
                        'image' => $so->image,
                        'image_url' => $soModel ? $soModel->image_url : null,
                        'image_so' => optional($soModel->requestOrder)->image_so,
                        'image_po' => optional($soModel->requestOrder)->image_po,
                    ];
                });

            // Request Orders
            $requestOrderResults = \App\Models\RequestOrder::where(function ($q) use ($search) {
                    $q->where('request_number', 'like', "%$search%")
                        ->orWhere('nomor_penawaran', 'like', "%$search%")
                        ->orWhere('sales_order_number', 'like', "%$search%")
                        ->orWhere('customer_name', 'like', "%$search%")
                        ->orWhere('no_po', 'like', "%$search%");
                })
                ->with('items')
                ->get()
                ->map(function ($ro) {
                    $diskonPersen = ($ro->items && $ro->items->count() > 0) ? ($ro->items->first()->diskon_percent ?? 0) : 0;
                    $total = $ro->grand_total ?? 0;

                    // ← PERBAIKAN: prioritas tanggal_berlaku, fallback ke expired_at
                    $berlakuSampai = '-';
                    if ($ro->tanggal_berlaku) {
                        $berlakuSampai = \Carbon\Carbon::parse($ro->tanggal_berlaku)->translatedFormat('d F Y');
                    } elseif ($ro->expired_at) {
                        $berlakuSampai = \Carbon\Carbon::parse($ro->expired_at)->translatedFormat('d F Y');
                    }

                    return [
                        'id' => $ro->id,
                        'type' => 'request_order',
                        'no_request' => $ro->request_number,
                        'no_penawaran' => $ro->nomor_penawaran,
                        'no_po' => $ro->no_po,
                        'no_sales_order' => $ro->sales_order_number,
                        'tanggal' => $ro->tanggal_kebutuhan ? $ro->tanggal_kebutuhan->format('d/m/Y') : '-',
                        'customer_name' => $ro->customer_name,
                        'jumlah_item' => $ro->items->count(),
                        'total' => $total,
                        'diskon' => $diskonPersen,
                        'status' => $ro->status,
                        'berlaku_sampai' => $berlakuSampai, // ← SUDAH DIPERBAIKI
                        'catatan_customer' => $ro->catatan_customer,
                        'aksi_url' => route('sales.request-order.show', $ro),
                        'image_so' => $ro->image_so,
                        'image_po' => $ro->image_po,
                    ];
                });

            $results = collect($soResults)->merge($requestOrderResults);

        } else {
            // Default: hanya SO, paginasi
            $salesOrders = SalesOrder::where('sales_id', Auth::id())
                ->with(['items', 'requestOrder', 'customer', 'requestOrder.items'])
                ->latest()
                ->paginate(20)
                ->appends(request()->query());

            $results = $salesOrders->map(function ($so) {
                $diskonPersen = 0;
                $total = 0;
                $tanggalPenawaran = null;
                $penawaran = null;

                if ($so->requestOrder && $so->requestOrder->nomor_penawaran) {
                    $penawaran = \App\Models\CustomPenawaran::where('penawaran_number', $so->requestOrder->nomor_penawaran)
                        ->with('items')
                        ->first();
                }
                if ($penawaran) {
                    $diskonPersen = $penawaran->items->first()->diskon ?? 0;
                    $total = $penawaran->grand_total;
                    $tanggalPenawaran = $penawaran->date ? date('d/m/Y', strtotime($penawaran->date)) : '-';
                }

                // ← PERBAIKAN UTAMA: ambil tanggal_berlaku dari requestOrder
                $berlakuSampai = '-';
                $requestOrderArr = [];

                if ($so->requestOrder) {
                    // Prioritas 1: tanggal_berlaku (14 hari setelah penawaran dibuat)
                    if ($so->requestOrder->tanggal_berlaku) {
                        $berlakuSampai = \Carbon\Carbon::parse(
                            $so->requestOrder->tanggal_berlaku
                        )->translatedFormat('d F Y');
                    }
                    // Prioritas 2: expired_at sebagai fallback
                    elseif ($so->requestOrder->expired_at) {
                        $berlakuSampai = \Carbon\Carbon::parse(
                            $so->requestOrder->expired_at
                        )->translatedFormat('d F Y');
                    }

                    $requestOrderArr['tanggal_berlaku_formatted'] = $berlakuSampai;
                }

                return [
                    'id' => $so->id,
                    'type' => 'sales_order',
                    'no_request' => optional($so->requestOrder)->request_number,
                    'no_penawaran' => optional($so->requestOrder)->nomor_penawaran,
                    'no_po' => optional($so->requestOrder)->no_po ?? '-',
                    'no_sales_order' => $so->sales_order_number,
                    'tanggal' => $tanggalPenawaran ?? ($so->tanggal_kebutuhan ? $so->tanggal_kebutuhan->format('d/m/Y') : '-'),
                    'customer_name' => $so->customer_name,
                    'jumlah_item' => $so->items->count(),
                    'total' => $total,
                    'diskon' => $diskonPersen,
                    'status' => $so->status,
                    'berlaku_sampai' => $berlakuSampai, // ← SUDAH DIPERBAIKI
                    'catatan_customer' => $so->catatan_customer,
                    'aksi_url' => route('sales.sales-order.show', $so),
                    'image' => $so->image,
                    'image_url' => $so->image_url,
                    'image_so' => optional($so->requestOrder)->image_so,
                    'image_po' => optional($so->requestOrder)->image_po,
                    'request_order' => $requestOrderArr,
                ];
            });
        }

        return view('admin.sales.sales-order.index', [
            'results' => $results,
            'search' => $search,
            'isSearch' => $isSearch,
            'salesOrders' => isset($salesOrders) ? $salesOrders : null,
        ]);
    }

    // ===== Semua method lain tidak diubah =====

    public function getPenawaranDetail(Request $request)
    {
        $penawaranId = $request->input('id');
        $penawaran = CustomPenawaran::where('sales_id', Auth::id())->findOrFail($penawaranId);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $penawaran->id,
                'penawaran_number' => $penawaran->penawaran_number,
                'to' => $penawaran->to,
                'up' => $penawaran->up,
                'email' => $penawaran->email,
                'subject' => $penawaran->subject,
                'our_ref' => $penawaran->our_ref,
                'date' => $penawaran->date ? \Carbon\Carbon::parse($penawaran->date)->format('d/m/Y') : '-',
                'intro_text' => $penawaran->intro_text,
                'status' => $penawaran->status,
                'subtotal' => $penawaran->subtotal,
                'tax' => $penawaran->tax,
                'grand_total' => $penawaran->grand_total,
                'items' => $penawaran->items->map(function ($item) {
                    return [
                        'nama_barang' => $item->nama_barang,
                        'qty' => $item->qty,
                        'satuan' => $item->satuan,
                        'harga' => $item->harga,
                        'diskon' => $item->diskon,
                        'subtotal' => $item->subtotal,
                        'keterangan' => $item->keterangan,
                        'images' => $item->images ?? [],
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

        $salesUsers = User::where('role', 'Sales')->pluck('name', 'name')->toArray();
        $currentUserName = Auth::user()->name;

        return view('admin.sales.sales-order.create', compact('customPenawarans', 'salesUsers', 'currentUserName'));
    }

    public function store(Request $request)
    {
        Log::info('Sales Order Store Request Incoming', [
            'auth_id' => Auth::id(),
            'request' => $request->all(),
            'items_count' => count($request->input('items', []))
        ]);

        $salesNames = User::where('role', 'Sales')->pluck('name')->toArray();

        $validated = $request->validate([
            'to' => 'required|string|max:255',
            'up' => ['required', 'string', 'max:255', Rule::in($salesNames)],
            'subject' => 'required|string|max:255',
            'email' => 'required|email',
            'our_ref' => 'nullable|string|max:255',
            'date' => 'required|date',
            'intro_text' => 'nullable|string',
            'tax' => 'nullable|numeric|min:0',
            'custom_penawaran_id' => 'nullable|integer|exists:custom_penawarans,id',
            'items' => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.satuan' => 'required|string|max:50',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.diskon' => 'required|integer|min:0|max:100',
            'items.*.keterangan' => 'nullable|string|max:255',
            'items.*.images' => 'nullable|array',
            'items.*.images.*' => 'nullable|image|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $salesOrder = SalesOrder::create([
                'sales_id' => Auth::id(),
                'sales_order_number' => SalesOrder::generateSONumber(),
                'to' => $validated['to'],
                'up' => $validated['up'],
                'subject' => $validated['subject'],
                'email' => $validated['email'],
                'our_ref' => $validated['our_ref'] ?? SalesOrder::generateUniqueRef(),
                'date' => $validated['date'],
                'intro_text' => $validated['intro_text'] ?? null,
                'tax' => $validated['tax'] ?? 0,
                'status' => 'pending_approval',
                'custom_penawaran_id' => $validated['custom_penawaran_id'] ?? null,
            ]);

            Log::info('Sales Order Created', ['id' => $salesOrder->id, 'sales_id' => $salesOrder->sales_id]);

            $subtotal = 0;
            foreach ($validated['items'] as $i => $itemData) {
                $itemImages = [];
                if ($request->hasFile("items.$i.images")) {
                    foreach ($request->file("items.$i.images") as $file) {
                        if ($file) {
                            $itemImages[] = $file->store('sales-order-images', 'public');
                        }
                    }
                }

                $itemSubtotal = $itemData['qty'] * $itemData['harga'] * (1 - ($itemData['diskon'] ?? 0) / 100);
                $subtotal += $itemSubtotal;

                SalesOrderItem::create([
                    'sales_order_id' => $salesOrder->id,
                    'nama_barang' => $itemData['nama_barang'],
                    'qty' => $itemData['qty'],
                    'satuan' => $itemData['satuan'],
                    'harga' => $itemData['harga'],
                    'subtotal' => $itemSubtotal,
                    'diskon' => $itemData['diskon'] ?? 0,
                    'keterangan' => $itemData['keterangan'] ?? null,
                    'images' => !empty($itemImages) ? $itemImages : null,
                    'quantity' => $itemData['qty'],
                ]);
            }

            $salesOrder->update([
                'subtotal' => $subtotal,
                'grand_total' => $subtotal + ($validated['tax'] ?? 0),
            ]);

            DB::commit();
            Log::info('Sales Order Commit Successful', ['id' => $salesOrder->id]);

            return redirect()->route('sales.sales-order.show', $salesOrder->id)
                ->with(['title' => 'Berhasil', 'text' => "Sales Order {$salesOrder->so_number} berhasil dibuat."]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Sales Order Store Error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors('Gagal membuat Sales Order: ' . $e->getMessage())->withInput();
        }
    }

    public function show(SalesOrder $salesOrder)
    {
        $userRole = trim(strtolower(Auth::user()->role ?? ''));
        $allowed = array_map('strtolower', ['Supervisor', 'Admin']);
        if ($salesOrder->sales_id !== Auth::id() && !in_array($userRole, $allowed)) {
            abort(403);
        }

        $salesOrder->load('items', 'sales', 'customPenawaran');
        return view('admin.sales.sales-order.show', compact('salesOrder'));
    }

    public function edit(SalesOrder $salesOrder)
    {
        if ($salesOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        $salesOrder->load('items');
        $salesUsers = User::where('role', 'Sales')->pluck('name', 'name')->toArray();
        $currentUserName = Auth::user()->name;

        return view('admin.sales.sales-order.edit', compact('salesOrder', 'salesUsers', 'currentUserName'));
    }

    public function update(Request $request, SalesOrder $salesOrder)
    {
        if ($salesOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        $salesNames = User::where('role', 'Sales')->pluck('name')->toArray();

        $validated = $request->validate([
            'to' => 'required|string|max:255',
            'up' => ['required', 'string', 'max:255', Rule::in($salesNames)],
            'subject' => 'required|string|max:255',
            'email' => 'required|email',
            'date' => 'required|date',
            'intro_text' => 'nullable|string',
            'tax' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.satuan' => 'required|string|max:50',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.diskon' => 'required|integer|min:0|max:100',
            'items.*.keterangan' => 'nullable|string|max:255',
            'items.*.images' => 'nullable|array',
            'items.*.images.*' => 'nullable|image|max:5120',
            'items.*.existing_images' => 'nullable|array',
            'items.*.existing_images.*' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $salesOrder->update([
                'to' => $validated['to'],
                'up' => $validated['up'],
                'subject' => $validated['subject'],
                'email' => $validated['email'],
                'date' => $validated['date'],
                'intro_text' => $validated['intro_text'] ?? null,
                'tax' => $validated['tax'] ?? 0,
            ]);

            $salesOrder->items()->delete();
            $subtotal = 0;

            foreach ($validated['items'] as $i => $itemData) {
                $itemImages = [];
                if ($request->hasFile("items.$i.images")) {
                    foreach ($request->file("items.$i.images") as $file) {
                        if ($file) {
                            $itemImages[] = $file->store('sales-order-images', 'public');
                        }
                    }
                }

                if (empty($itemImages) && isset($validated['items'][$i]['existing_images']) && !empty($validated['items'][$i]['existing_images'])) {
                    $itemImages = $validated['items'][$i]['existing_images'];
                }

                $itemSubtotal = $itemData['qty'] * $itemData['harga'] * (1 - ($itemData['diskon'] ?? 0) / 100);
                $subtotal += $itemSubtotal;

                SalesOrderItem::create([
                    'sales_order_id' => $salesOrder->id,
                    'nama_barang' => $itemData['nama_barang'],
                    'qty' => $itemData['qty'],
                    'satuan' => $itemData['satuan'],
                    'harga' => $itemData['harga'],
                    'subtotal' => $itemSubtotal,
                    'diskon' => $itemData['diskon'] ?? 0,
                    'keterangan' => $itemData['keterangan'] ?? null,
                    'images' => !empty($itemImages) ? $itemImages : null,
                    'quantity' => $itemData['qty'],
                ]);
            }

            $salesOrder->update([
                'subtotal' => $subtotal,
                'grand_total' => $subtotal + ($validated['tax'] ?? 0),
            ]);

            DB::commit();
            return redirect()->route('sales.sales-order.show', $salesOrder->id)
                ->with(['title' => 'Berhasil', 'text' => 'Sales Order berhasil diubah.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal mengubah Sales Order: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(SalesOrder $salesOrder)
    {
        if ($salesOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($salesOrder) {
                $salesOrder->items()->delete();
                $salesOrder->delete();
            });
            return redirect()->route('sales.sales-order.index')
                ->with(['title' => 'Berhasil', 'text' => 'Sales Order berhasil dihapus.']);
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal menghapus Sales Order: ' . $e->getMessage());
        }
    }

    public function uploadImage(Request $request, SalesOrder $salesOrder)
    {
        if ($salesOrder->sales_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('sales-order-so-images', 'public');
            if ($salesOrder->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($salesOrder->image);
            }
            $salesOrder->image = $path;
            $salesOrder->save();
            return response()->json(['status' => 'success', 'image_url' => \Illuminate\Support\Facades\Storage::url($path)]);
        }
        return response()->json(['status' => 'error', 'message' => 'No file uploaded']);
    }

    public function deleteImage(SalesOrder $salesOrder)
    {
        if ($salesOrder->sales_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        if ($salesOrder->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($salesOrder->image);
            $salesOrder->image = null;
            $salesOrder->save();
        }
        return response()->json(['status' => 'success']);
    }

    public function search(Request $request)
    {
        $search = $request->input('q', '');
        if (empty($search)) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $soResults = SalesOrder::query()
            ->where('sales_id', Auth::id())
            ->where(function ($q) use ($search) {
                $q->where('sales_order_number', 'like', "%$search%")
                  ->orWhere('customer_name', 'like', "%$search%");
            })
            ->limit(5)
            ->get()
            ->map(function ($so) {
                return [
                    'sales_order_number' => $so->sales_order_number,
                    'customer_name' => $so->customer_name,
                    'type' => 'sales_order',
                    'badge' => 'SO',
                    'url' => route('sales.sales-order.show', $so->id),
                    'no_po' => optional($so->requestOrder)->no_po,
                ];
            });

        $roResults = \App\Models\RequestOrder::where('sales_id', Auth::id())
            ->where(function ($q) use ($search) {
                $q->where('request_number', 'like', "%$search%")
                  ->orWhere('nomor_penawaran', 'like', "%$search%")
                  ->orWhere('customer_name', 'like', "%$search%")
                  ->orWhere('no_po', 'like', "%$search%");
            })
            ->limit(5)
            ->get()
            ->map(function ($ro) {
                return [
                    'sales_order_number' => $ro->nomor_penawaran ?: ($ro->request_number ?: 'Quotation'),
                    'customer_name' => $ro->customer_name,
                    'type' => 'penawaran',
                    'badge' => 'Quotation',
                    'url' => route('sales.request-order.show', $ro->id),
                    'no_po' => $ro->no_po,
                ];
            });

        $combined = collect($soResults)->merge($roResults);

        return response()->json(['success' => true, 'data' => $combined]);
    }
}