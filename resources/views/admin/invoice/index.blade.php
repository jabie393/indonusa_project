<x-app-layout>
    @php
        // Helper function to get base64 encoded image from public/images
        $getPublicImageBase64 = function ($filename) {
            try {
                $path = public_path('images/' . $filename);
                if (file_exists($path) && is_readable($path)) {
                    $mime = mime_content_type($path);
                    $data = base64_encode(file_get_contents($path));
                    return 'data:' . $mime . ';base64,' . $data;
                }
            } catch (\Exception $e) {
                // Log error if needed
            }
            return '';
        };
    @endphp
    <div
        class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-6">

            @if (empty($batch) && !empty($batches) && $batches->isNotEmpty())
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Partial Invoice Batch</h2>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                        This request order was shipped in multiple partial batches. Print each batch invoice separately
                        below.
                    </p>
                    <div
                        class="mt-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900">
                        <table class="w-full border-collapse text-sm">
                            <thead class="bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left">Batch</th>
                                    <th class="px-4 py-3 text-left">Tanggal</th>
                                    <th class="px-4 py-3 text-left">Item Terkirim</th>
                                    <th class="px-4 py-3 text-right">Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($batches as $batchItem)
                                    <tr class="border-t border-slate-200 dark:border-slate-700">
                                        <td class="px-4 py-3">Batch #{{ $batchItem->batch_number }}</td>
                                        <td class="px-4 py-3">{{ optional($batchItem->created_at)->format('Y-m-d H:i') }}</td>
                                        <td class="px-4 py-3">
                                            <ul class="list-disc space-y-1 pl-5 text-slate-700 dark:text-slate-300">
                                                @foreach ($batchItem->items as $item)
                                                    <li>{{ $item->orderItem->barang->goods_name ?? ($item->orderItem->nama_barang ?? '-') }}
                                                        ({{ $item->quantity_sent }})</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            @php
                                                $batchInvoiceUrl = strtolower(Auth::user()->role ?? '') === 'general affair' ? route('invoice.batch.invoice', $batchItem->id) : route('delivery-orders.batch.invoice', $batchItem->id);
                                            @endphp
                                            <a href="{{ $batchInvoiceUrl }}" target="_blank"
                                                class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#225A97] px-4 py-2 text-xs font-bold uppercase tracking-wider text-white shadow-md shadow-blue-100/30 transition-all hover:bg-[#1a4675] active:scale-[0.98] dark:shadow-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Invoice
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                        {{-- Breadcrumb/back button --}}
                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                            <!-- Panel : Preview Invoice (id="invoice-preview", class="print-area") -->
                            <div class="lg:col-span-2">
                                @php $isGa = strtolower(auth()->user()->role ?? '') === 'general affair'; @endphp

                                @if($isGa)
                                    <style>
                                        #invoice-preview, #invoice-preview * {
                                            font-family: 'Times New Roman', serif !important;
                                            font-size: 12pt !important;
                                            line-height: 1.5 !important;
                                        }
                                    </style>
                                @endif

                                <div id="invoice-preview" class="print-area"
                                    style="position: relative; font-family: 'Times New Roman', serif; padding: 32px; background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

                                    <!-- WATERMARK IMAGE -->
                                    @if ($getPublicImageBase64('LogoText_transparent.png'))
                                        <div
                                            style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); opacity: 0.08; pointer-events: none; z-index: 0;">
                                            <img src="{{ $getPublicImageBase64('LogoText_transparent.png') }}" alt=""
                                                style="width: 500px; height: auto;" />
                                        </div>
                                    @endif

                                    <!-- KOP / HEADER -->
                                    <div
                                        style="display: flex; align-items: center; border-bottom: 6px solid #2f5496; padding-bottom: 12px; margin-bottom: 24px; position: relative; z-index: 10;">
                                        @if ($getPublicImageBase64('Logo_transparent.png'))
                                            <img src="{{ $getPublicImageBase64('Logo_transparent.png') }}" alt="Indonusa Jaya Bersama"
                                                style="width: 80px; height: auto; margin-right: 20px; object-fit: contain;" />
                                        @endif
                                        <div style="flex: 1; font-family: 'Times New Roman', serif;">
                                            <h1
                                                style="font-size: 28px; font-weight: bold; color: #1f3864; margin: 0; line-height: 1.2;">
                                                PT. INDONUSA JAYA BERSAMA</h1>
                                            <table
                                                style="font-size: 12px; color: #1f3864; font-weight: bold; margin-top: 4px; border-collapse: collapse; width: 100%;">
                                                <tr>
                                                    <td style="width: 60px; vertical-align: top; padding: 2px 0;">Alamat</td>
                                                    <td style="width: 10px; vertical-align: top; padding: 2px 0;">:</td>
                                                    <td style="vertical-align: top; padding: 2px 0;">Wonorejo Selatan VB No. 50
                                                        Rungkut, Surabaya - 60296</td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top; padding: 2px 0;">Telp</td>
                                                    <td style="vertical-align: top; padding: 2px 0;">:</td>
                                                    <td style="vertical-align: top; padding: 2px 0;">08121634173</td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: top; padding: 2px 0;">Fax</td>
                                                    <td style="vertical-align: top; padding: 2px 0;">:</td>
                                                    <td style="vertical-align: top; padding: 2px 0;">03187857885</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- INVOICE TITLE & BATCHES -->
                                    <div style="position: relative; z-index: 10; margin-bottom: 24px;">
                                        <span
                                            style="font-size:32px; font-weight:bold; color:#000000; text-decoration:underline;">INVOICE</span>
                                        @if (!empty($batch))
                                            <div style="margin-top: 8px; font-size: 14px; font-weight: 700; color: #0D223A;">
                                                Partial shipment invoice for Batch #{{ $batch->batch_number }}
                                            </div>
                                        @elseif(!empty($batches) && $batches->isNotEmpty())
                                            <div style="margin-top: 8px; font-size: 14px; font-weight: 700; color: #0D223A;">
                                                This request order was shipped in multiple partial batches. Please print each batch
                                                invoice separately below.
                                            </div>
                                            <div
                                                style="margin-top: 16px; border:1px solid #d1d5db; border-radius:12px; overflow:hidden;">
                                                <table
                                                    style="width:100%; border-collapse:collapse; font-family: 'Times New Roman', serif; font-size:14px;">
                                                    <thead style="background:#f1f5f9; color:#0f172a;">
                                                        <tr>
                                                            <th style="padding:12px; border-bottom:1px solid #cbd5e1; text-align:left;">
                                                                Batch</th>
                                                            <th style="padding:12px; border-bottom:1px solid #cbd5e1; text-align:left;">
                                                                Tanggal</th>
                                                            <th style="padding:12px; border-bottom:1px solid #cbd5e1; text-align:left;">
                                                                Item Terkirim</th>
                                                            <th
                                                                style="padding:12px; border-bottom:1px solid #cbd5e1; text-align:right;">
                                                                Invoice</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($batches as $batchItem)
                                                            <tr style="border-top:1px solid #e2e8f0;">
                                                                <td style="padding:12px; vertical-align:top;">Batch
                                                                    #{{ $batchItem->batch_number }}</td>
                                                                <td style="padding:12px; vertical-align:top;">
                                                                    {{ optional($batchItem->created_at)->format('Y-m-d H:i') }}
                                                                </td>
                                                                <td style="padding:12px; vertical-align:top;">
                                                                    <ul
                                                                        style="margin:0; padding-left:18px; color:#0f172a; list-style:disc;">
                                                                        @foreach ($batchItem->items as $item)
                                                                            <li style="margin-bottom:4px;">
                                                                                {{ $item->orderItem->barang->goods_name ?? ($item->orderItem->nama_barang ?? '-') }}
                                                                                ({{ $item->quantity_sent }})
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </td>
                                                                <td style="padding:12px; vertical-align:top; text-align:right;">
                                                                    @php
                                                                        $batchInvoiceUrl = strtolower(Auth::user()->role ?? '') === 'general affair' ? route('invoice.batch.invoice', $batchItem->id) : route('delivery-orders.batch.invoice', $batchItem->id);
                                                                    @endphp
                                                                    <a href="{{ $batchInvoiceUrl }}" target="_blank"
                                                                        style="display:inline-block; padding:8px 16px; border-radius:6px; background:#225A97; color:#ffffff; text-decoration:none; font-weight:700; font-size:12px; text-transform:uppercase; letter-spacing:0.05em;">Invoice</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- METADATA SECTION (Invoice To & Date info) -->
                                    <div
                                        style="display: flex; justify-content: space-between; align-items: flex-start; position: relative; z-index: 10;">
                                        <div>
                                            <span
                                                style="font-weight:bold; font-size: 16px; font-family: 'Times New Roman', serif; display: block; margin-bottom: 8px;">Invoice
                                                To:</span>
                                            <p id="preview_customer"
                                                style="font-weight: bold; font-size: 16px; font-family: 'Times New Roman', serif; margin: 0 0 4px 0;">
                                                {{ strtoupper($customerName) }}
                                            </p>
                                            <p id="preview_address"
                                                style="margin: 0 0 8px 0; line-height: 1.6; color: #000000; font-size: 16px; font-family: 'Times New Roman', serif;">
                                                {{ $customerAddress ?: '-' }}
                                            </p>
                                            <span id="preview-npwp"
                                                style="font-size: 16px; color: #000000; font-family: 'Times New Roman', serif;"><strong
                                                    style="font-weight: bold;">NPWP</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                                <span>{{ old('inv_npwp', $customerNpwp) }}</span></span>
                                        </div>
                                        <div style="text-align:left; min-width:240px; padding-top: 0;">
                                            <table
                                                style="font-size: 16px; font-family: 'Times New Roman', serif; border-collapse: collapse; width: 100%; color: #000000;">
                                                <tr>
                                                    <td style="padding: 4px 0; font-weight: bold; vertical-align: top;">Date</td>
                                                    <td style="padding: 4px 0; vertical-align: top;">:</td>
                                                    <td style="padding: 4px 0 4px 12px; vertical-align: top;"><span
                                                            id="preview-date">{{ date('d/m/y') }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 4px 0; font-weight: bold; vertical-align: top;">No Invoice
                                                    </td>
                                                    <td style="padding: 4px 0; vertical-align: top;">:</td>
                                                    <td style="padding: 4px 0 4px 12px; vertical-align: top;"><span
                                                            id="preview-number">{{ $invoiceNumber }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 4px 0; font-weight: bold; vertical-align: top;">PO No</td>
                                                    <td style="padding: 4px 0; vertical-align: top;">:</td>
                                                    <td style="padding: 4px 0 4px 12px; vertical-align: top;"><span
                                                            id="preview-po">{{ $noPoDisplay }}</span></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div style="margin-top: 32px;">
                                        <table
                                            style="width:100%; border-collapse:collapse; font-size: 16px; font-family: 'Times New Roman', serif; color: #000000;">
                                            <thead>
                                                <tr style="background:#1A3A6B; color:white;">
                                                    <th
                                                        style="padding:12px; border:2px solid #000000; text-align: center; width: 5%; font-size: 16px; font-weight: 900; color: #ffffff; text-shadow: 0 0 1px rgba(0,0,0,0.3);">
                                                        No</th>
                                                    <th
                                                        style="padding:12px; border:2px solid #000000; text-align: left; width: 12%; font-size: 16px; font-weight: 900; color: #ffffff; text-shadow: 0 0 1px rgba(0,0,0,0.3);">
                                                        Kode Barang</th>
                                                    <th
                                                        style="padding:12px; border:2px solid #000000; text-align: left; width: 20%; font-size: 16px; font-weight: 900; color: #ffffff; text-shadow: 0 0 1px rgba(0,0,0,0.3);">
                                                        Nama Barang</th>
                                                    <th
                                                        style="padding:12px; border:2px solid #000000; text-align: left; width: 25%; font-size: 16px; font-weight: 900; color: #ffffff; text-shadow: 0 0 1px rgba(0,0,0,0.3);">
                                                        Deskripsi</th>
                                                    <th
                                                        style="padding:12px; border:2px solid #000000; text-align: center; width: 10%; font-size: 16px; font-weight: 900; color: #ffffff; text-shadow: 0 0 1px rgba(0,0,0,0.3);">
                                                        Qty</th>
                                                    <th
                                                        style="padding:12px; border:2px solid #000000; text-align: right; width: 20%; font-size: 16px; font-weight: 900; color: #ffffff; text-shadow: 0 0 1px rgba(0,0,0,0.3);">
                                                        Unit Price</th>
                                                    <th
                                                        style="padding:12px; border:2px solid #000000; text-align: right; width: 20%; font-size: 16px; font-weight: 900; color: #ffffff; text-shadow: 0 0 1px rgba(0,0,0,0.3);">
                                                        Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($items as $i => $item)
                                                    <tr>

                                                        <td
                                                            style="padding:12px; border:1px solid #000000; text-align: center; color: #000000;">
                                                            {{ $i + 1 }}
                                                        </td>
                                                        <td
                                                            style="padding:12px; border:1px solid #000000; text-align: left; color: #000000;">
                                                            {{ $item['goods_code'] ?? '-' }}
                                                        </td>
                                                        <td
                                                            style="padding:12px; border:1px solid #000000; text-align: left; color: #000000;">
                                                            {{ $item['nama_barang'] ?? ($item['description'] ?? '-') }}
                                                        </td>
                                                        <td
                                                            style="padding:12px; border:1px solid #000000; text-align: left; font-size:16px; color: #000000;">
                                                            {{ $item['deskripsi'] ?? '-' }}
                                                        </td>
                                                        <td
                                                            style="padding:12px; border:1px solid #000000; text-align: center; color: #000000;">
                                                            {{ $item['qty'] ?? ($item['quantity'] ?? 0) }}
                                                        </td>
                                                        <td
                                                            style="padding:12px; border:1px solid #000000; text-align: right; color: #000000;">
                                                            {{ number_format($item['harga'] ?? 0, 0, '.', ',') }}
                                                        </td>
                                                        <td
                                                            style="padding:12px; border:1px solid #000000; text-align: right; color: #000000;">
                                                            {{ number_format($item['subtotal'] ?? 0, 0, '.', ',') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div style="margin-top: 32px; display: flex; justify-content: flex-end;">
                                        <table
                                            style="min-width:300px; font-family: 'Times New Roman', serif; font-size: 16px; border-collapse: collapse; color: #000000;">
                                            <tr>
                                                <td style="padding: 8px 0; text-align: right; width: 60%;">Subtotal</td>
                                                <td style="padding: 8px 0;">:</td>
                                                <td
                                                    style="padding: 8px 0 8px 12px; text-align: right; width: 40%; font-weight:bold;">
                                                    {{ number_format($subtotal ?? 0, 0, '.', ',') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; text-align: right;">DPP</td>
                                                <td style="padding: 8px 0;">:</td>
                                                <td style="padding: 8px 0 8px 12px; text-align: right;">
                                                    {{ $tax > 0 ? number_format(round(($subtotal * 100) / 111), 0, '.', ',') : '0' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; text-align: right;">PPN (Tax)</td>
                                                <td style="padding: 8px 0;">:</td>
                                                <td style="padding: 8px 0 8px 12px; text-align: right;">
                                                    {{ number_format($tax ?? 0, 0, '.', ',') }}
                                                </td>
                                            </tr>
                                            <tr style="border-top:3px solid #000000;">
                                                <td style="padding: 10px 0; text-align: right; font-size:18px; font-weight:bold;">
                                                    Total</td>
                                                <td style="padding: 10px 0; font-weight:bold;">:</td>
                                                <td
                                                    style="padding: 10px 0 10px 12px; text-align: right; font-size:18px; font-weight:bold;">
                                                    {{ number_format($grandTotal ?? 0, 0, '.', ',') }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div style="margin-top: 32px; display: flex; justify-content: space-between;">
                                        <div
                                            style="border:3px solid #000000; border-radius:8px; padding:12px; max-width:340px; font-family: 'Times New Roman', serif; font-size: 16px; color: #000000;">
                                            <span style="color:#000000; font-weight:bold; font-size: 16px;">PAYMENT
                                                INFORMATION</span><br>
                                            <span id="preview-payment-note"
                                                style="line-height: 1.6; color: #000000; font-size: 16px;">• BCA a/c. 7881213501<br>
                                                a/n. PT. Indonusa Jaya Bersama<br><br>Thank you for your support.<br>We look forward
                                                to serve you again</span>
                                        </div>
                                        <div
                                            style="text-align:center; min-width:240px; font-family: 'Times New Roman', serif; font-size: 16px; color: #000000;">
                                            <span style="font-weight:bold; font-size:18px; color: #000000;">PT. Indonusa Jaya
                                                Bersama</span><br>
                                            <div style="height:100px;"></div>
                                            <span
                                                style="font-weight:bold; text-decoration:underline; color: #000000; font-size: 16px;">Alimul
                                                Imam, S.AP</span><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Panel: Form + Tombol (class "no-print") -->
                            <div class="gap-2 lg:col-span-1">

                                <div class="no-print sticky top-5 space-y-6 lg:col-span-1">
                                    <div
                                        class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">

                                        <div
                                            class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
                                            <h2 class="flex items-center font-semibold text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                                </svg>
                                                Panel Action
                                            </h2>
                                        </div>
                                        <div class="flex flex-col gap-3 p-6 dark:bg-gray-800">
                                            <button type="button" id="btn-print"
                                                class="flex w-full items-center justify-center gap-2.5 rounded-2xl bg-[#102A47] py-3.5 text-xs font-bold uppercase tracking-wider text-white shadow-lg shadow-indigo-100/30 hover:bg-[#0d223a] hover:shadow-none transition-all active:scale-[0.98] dark:shadow-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                Cetak PDF
                                            </button>
                                            <button type="button" id="btn-excel"
                                                class="flex w-full items-center justify-center gap-2.5 rounded-2xl bg-emerald-600 py-3.5 text-xs font-bold uppercase tracking-wider text-white shadow-lg shadow-emerald-100 hover:bg-emerald-700 hover:shadow-none transition-all active:scale-[0.98] dark:shadow-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 shrink-0"
                                                    fill="currentColor">
                                                    <path
                                                        d="M12.5 2.25L3 4.25v15.5l9.5 2V2.25zM9.5 14.8L8.1 12l-1.4 2.8H5.2l2.2-4.1L5.3 6.8h1.5l1.3 2.7 1.3-2.7h1.5L8.7 10.7l2.2 4.1H9.5zM21 4.75h-7.5v2.5H19v2h-5.5v2H19v2h-5.5v2H19v2.5h-5.5v2.5H21v-15.5z" />
                                                </svg>
                                                Download Excel
                                            </button>
                                            @if($isGa)
                                            <button type="button" id="btn-print-receipt"
                                                class="flex w-full items-center justify-center gap-2.5 rounded-2xl bg-yellow-600 py-3.5 text-xs font-bold uppercase tracking-wider text-white shadow-lg hover:bg-yellow-700 hover:shadow-none transition-all active:scale-[0.98]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                Cetak Kuitansi
                                            </button>
                                            @endif
                                        </div>
                                    </div>

                                    <div
                                        class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">
                                        <div
                                            class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
                                            <h2 class="flex items-center font-semibold text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                                </svg>
                                                Data Invoice
                                            </h2>
                                        </div>
                                        <div class="space-y-4 p-6">
                                            <div>
                                                <label
                                                    class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1.5">No
                                                    Invoice</label>
                                                <input type="text" id="inv_number" name="inv_number"
                                                    class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm font-medium text-slate-400 dark:border-gray-700 dark:bg-gray-800/80 dark:text-gray-500"
                                                    value="{{ $invoiceNumber }}" readonly>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1.5">Tanggal
                                                    Invoice</label>
                                                <input type="date" id="inv_date" name="inv_date"
                                                    class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition-all duration-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:bg-white dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-200 dark:focus:border-indigo-500 dark:focus:ring-indigo-950"
                                                    value="{{ date('Y-m-d') }}">
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1.5">NPWP</label>
                                                <input type="text" id="inv_npwp" name="inv_npwp"
                                                    class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm font-medium text-slate-400 dark:border-gray-700 dark:bg-gray-800/80 dark:text-gray-500"
                                                    value="{{ $customerNpwp }}" minlength="15" maxlength="16" readonly>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1.5">PO
                                                    No</label>
                                                <input type="text" id="inv_po_no" name="inv_po_no"
                                                    class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition-all duration-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:bg-white dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-200 dark:focus:border-indigo-500 dark:focus:ring-indigo-950"
                                                    value="{{ $noPoDisplay }}">
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1.5">Catatan
                                                    Pembayaran</label>
                                                <textarea id="inv_payment_note" name="inv_payment_note" rows="4"
                                                    class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium text-slate-800 transition-all duration-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 focus:bg-white dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-200 dark:focus:border-indigo-500 dark:focus:ring-indigo-950">• BCA a/c. 7881213501
                                                a/n. PT. Indonusa Jaya Bersama

                                                Thank you for your support.
                                                We look forward to serve you again
                                            </textarea>
                                            </div>

                                            <button type="button" id="btn-update-preview"
                                                class="flex w-full items-center justify-center gap-2.5 rounded-2xl bg-[#225A97] py-3.5 text-xs font-bold uppercase tracking-wider text-white shadow-lg shadow-blue-100/30 hover:bg-[#1a4675] hover:shadow-none transition-all active:scale-[0.98] dark:shadow-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 shrink-0"
                                                    fill="currentColor">
                                                    <path
                                                        d="M19 8l-4 4h3c0 3.31-2.69 6-6 6-1.01 0-1.97-.25-2.8-.7l-1.46 1.46C8.97 19.54 10.43 20 12 20c4.42 0 8-3.58 8-8h3l-4-4zM6 12c0-3.31 2.69-6 6-6 1.01 0 1.97.25 2.8.7l1.46-1.46C15.03 4.46 13.57 4 12 4c-4.42 0-8 3.58-8 8H1l4 4 4-4H6z" />
                                                </svg>
                                                Update Preview
                                            </button>
                                            <div id="update-toast" style="display:none;"
                                                class="mt-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-xs font-bold text-emerald-700 dark:border-emerald-900/30 dark:bg-emerald-950/20 dark:text-emerald-400">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <span>Berhasil diupdate</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        class="h-4 w-4 shrink-0" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.5 2.5a.75.75 0 001.14-.082l3.75-5.25z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                @if (!empty($batches) && $batches->isNotEmpty())
                                    <div
                                        class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">
                                        <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
                                            <h2 class="flex items-center font-semibold text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 7h18M3 12h18M3 17h18" />
                                                </svg>
                                                Partial Invoice Batch
                                            </h2>
                                        </div>
                                        <div class="space-y-3 p-6 dark:bg-gray-800">
                                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Pilih batch
                                                untuk mencetak invoice:</p>
                                            <div class="space-y-2">
                                                @foreach ($batches as $batchItem)
                                                    <a href="{{ route('invoice.batch.invoice', $batchItem->id) }}" target="_blank"
                                                        class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-700 transition-all hover:bg-slate-100 hover:text-[#225A97] dark:border-gray-700 dark:bg-gray-900/30 dark:text-gray-300 dark:hover:bg-gray-900/60 dark:hover:text-white active:scale-[0.98]">
                                                        <span>Batch #{{ $batchItem->batch_number }} - Cetak Invoice</span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                        </svg>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            @endif

    {{-- Hidden form untuk Excel --}}
    <form id="excel-form" action="{{ $invoiceExcelRoute }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="row_type" value="{{ $rowType }}">
        <input type="hidden" name="inv_number" id="ef_inv_number">
        <input type="hidden" name="inv_date" id="ef_inv_date">
        <input type="hidden" name="inv_npwp" id="ef_inv_npwp">
        <input type="hidden" name="inv_po_no" id="ef_inv_po_no">
        <input type="hidden" name="inv_payment_note" id="ef_inv_payment_note">
        <input type="hidden" name="inv_address" id="ef_inv_address" value="{{ $customerAddress }}">
        <input type="hidden" name="inv_npwp_val" id="ef_inv_npwp_val">
    </form>

    <style>
        /* Print CSS dihandle oleh window print khusus, tidak perlu di sini */
    </style>

    <script>
        function updatePreview() {
            document.getElementById('preview-number').textContent = document.getElementById('inv_number').value;
            document.getElementById('preview-date').textContent = formatDate(document.getElementById('inv_date').value);
            document.getElementById('preview-npwp').innerHTML = 'NPWP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span>' + document.getElementById('inv_npwp').value + '</span>';
            document.getElementById('preview-po').textContent = document.getElementById('inv_po_no').value;
            document.getElementById('preview-payment-note').innerHTML = document.getElementById('inv_payment_note').value.replace(/\n/g, '<br>');
            document.getElementById('preview_address').textContent = document.getElementById('ef_inv_address').value || '-';
        }

        function formatDate(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr);
            return d.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: '2-digit'
            });
        }
        document.querySelectorAll('#inv_number, #inv_date, #inv_npwp, #inv_po_no, #inv_payment_note').forEach(function (el) {
            el.addEventListener('input', updatePreview);
        });
        document.getElementById('btn-update-preview').addEventListener('click', function () {
            updatePreview();
            // Tampilkan toast notifikasi
            const toast = document.getElementById('update-toast');
            toast.style.display = 'block';
            setTimeout(function () {
                toast.style.display = 'none';
            }, 2500);
        });

        function printInvoicePDF() {
            updatePreview();
            // Ambil HTML konten invoice
            const invoiceContent = document.getElementById('invoice-preview').innerHTML;
            // Buka window baru khusus print
            const printWindow = window.open('', '_blank', 'width=900,height=700');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Invoice</title>
                    <style>
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        html, body { width: 100%; min-height: 100%; background: #ffffff !important; color: #000000 !important; font-family: 'Times New Roman', serif; }
                        body { padding: 32px; }
                        table { border-collapse: collapse; }
                        #print-container, #print-container * {
                            color: #000000 !important;
                            background-color: #ffffff !important;
                            background: #ffffff !important;
                            text-shadow: none !important;
                            fill: #000000 !important;
                        }
                        #print-container { background: #ffffff !important; }
                        #print-container thead tr, #print-container thead th {
                            background: #1A3A6B !important;
                            color: #ffffff !important;
                        }
                        #print-container thead th {
                            border-color: #000000 !important;
                        }
                        th, td { border-color: #000000 !important; }
                        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
                        @media print {
                            body { padding: 20px; }
                            @page { margin: 1cm; size: A4; }
                        }
                    </style>
                </head>
                <body>
                    <div id="print-container">
                        ${invoiceContent}
                    </div>
                    <script>
                        // Wait for window rendering, print, then close
                        setTimeout(function() {
                            window.print();
                            window.close();
                        }, 250);
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
        }
        document.getElementById('btn-print').addEventListener('click', printInvoicePDF);

        function downloadExcel() {
            document.getElementById('ef_inv_number').value = document.getElementById('inv_number').value;
            document.getElementById('ef_inv_date').value = document.getElementById('inv_date').value;
            document.getElementById('ef_inv_npwp').value = document.getElementById('inv_npwp').value;
            document.getElementById('ef_inv_npwp_val').value = document.getElementById('inv_npwp').value;
            document.getElementById('ef_inv_po_no').value = document.getElementById('inv_po_no').value;
            document.getElementById('ef_inv_payment_note').value = document.getElementById('inv_payment_note').value;
            document.getElementById('excel-form').submit();
        }
        document.getElementById('btn-excel').addEventListener('click', downloadExcel);
        // Cetak Kuitansi (GA only) - open server-rendered kuitansi/receipt which persists no_receipt
        const btnReceipt = document.getElementById('btn-print-receipt');
        if (btnReceipt) {
            btnReceipt.addEventListener('click', function () {
                updatePreview();
                const kwUrl = "{{ route('invoice.receipt', ['id' => $rowId ?? '']) }}";
                window.open(kwUrl, '_blank', 'width=900,height=700');
            });
        }
        window.addEventListener('DOMContentLoaded', updatePreview);
    </script>
</x-app-layout>