<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-6">

            {{-- Breadcrumb/back button --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Panel : Preview Invoice (id="invoice-preview", class="print-area") -->
                <div class="lg:col-span-2">
                    <div id="invoice-preview" class="print-area" style="font-family: 'Times New Roman', serif; padding: 32px; background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <span style="font-size:32px; font-weight:bold; color:#000000; text-decoration:underline;">INVOICE</span>
                                <div style="margin-top: 24px;">
                                    <span style="font-weight:bold; font-size: 16px; font-family: 'Times New Roman', serif; display: block; margin-bottom: 8px;">Invoice To:</span>
                                    <p id="preview_customer" style="font-weight: bold; font-size: 16px; font-family: 'Times New Roman', serif; margin: 0 0 4px 0;">{{ strtoupper($customerName) }}</p>
                                    <p id="preview_address" style="margin: 0 0 8px 0; line-height: 1.6; color: #000000; font-size: 16px; font-family: 'Times New Roman', serif;">{{ $customerAddress ?: '-' }}</p>
                                    <span id="preview-npwp" style="font-size: 16px; color: #000000; font-family: 'Times New Roman', serif;">NPWP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span>{{ old('inv_npwp', $customerNpwp) }}</span></span>
                                </div>
                            </div>
                            <div style="text-align:left; min-width:240px;">
                                <table style="font-size: 16px; font-family: 'Times New Roman', serif; border-collapse: collapse; width: 100%; color: #000000;">
                                    <tr>
                                        <td style="padding: 4px 0; font-weight: bold;">Date</td>
                                        <td style="padding: 4px 0;">:</td>
                                        <td style="padding: 4px 0 4px 12px;"><span id="preview-date">{{ date('d/m/y') }}</span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 4px 0; font-weight: bold;">No Invoice</td>
                                        <td style="padding: 4px 0;">:</td>
                                        <td style="padding: 4px 0 4px 12px;"><span id="preview-number">{{ $invoiceNumber }}</span></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 4px 0; font-weight: bold;">PO No</td>
                                        <td style="padding: 4px 0;">:</td>
                                        <td style="padding: 4px 0 4px 12px;"><span id="preview-po">{{ $noPoDisplay }}</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div style="margin-top: 32px;">
                            <table style="width:100%; border-collapse:collapse; font-size: 16px; font-family: 'Times New Roman', serif; color: #000000;">
                                <thead>
                                    <tr style="background:#1A3A6B; color:white;">
                                        <th style="padding:12px; border:2px solid #000000; text-align: center; width: 5%; font-size: 16px; font-weight: 900; color: #ffffff; text-shadow: 0 0 1px rgba(0,0,0,0.3);">No</th>
                                        <th style="padding:12px; border:2px solid #000000; text-align: left; width: 20%; font-size: 16px; font-weight: 900; color: #ffffff; text-shadow: 0 0 1px rgba(0,0,0,0.3);">Nama Barang</th>
                                        <th style="padding:12px; border:2px solid #000000; text-align: left; width: 25%; font-size: 16px; font-weight: 900; color: #ffffff; text-shadow: 0 0 1px rgba(0,0,0,0.3);">Deskripsi</th>
                                        <th style="padding:12px; border:2px solid #000000; text-align: center; width: 10%; font-size: 16px; font-weight: 900; color: #ffffff; text-shadow: 0 0 1px rgba(0,0,0,0.3);">Qty</th>
                                        <th style="padding:12px; border:2px solid #000000; text-align: right; width: 20%; font-size: 16px; font-weight: 900; color: #ffffff; text-shadow: 0 0 1px rgba(0,0,0,0.3);">Unit Price</th>
                                        <th style="padding:12px; border:2px solid #000000; text-align: right; width: 20%; font-size: 16px; font-weight: 900; color: #ffffff; text-shadow: 0 0 1px rgba(0,0,0,0.3);">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $i => $item)
                                        <tr>
                                            
                                            <td style="padding:12px; border:1px solid #000000; text-align: center; color: #000000;">{{ $i + 1 }}</td>
                                            <td style="padding:12px; border:1px solid #000000; text-align: left; color: #000000;">{{ $item['nama_barang'] ?? ($item['description'] ?? '-') }}</td>
                                            <td style="padding:12px; border:1px solid #000000; text-align: left; font-size:16px; color: #000000;">{{ $item['deskripsi'] ?? '-' }}</td>
                                            <td style="padding:12px; border:1px solid #000000; text-align: center; color: #000000;">{{ $item['qty'] ?? ($item['quantity'] ?? 0) }}</td>
                                            <td style="padding:12px; border:1px solid #000000; text-align: right; color: #000000;">{{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
                                            <td style="padding:12px; border:1px solid #000000; text-align: right; color: #000000;">{{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="margin-top: 32px; display: flex; justify-content: flex-end;">
                            <table style="min-width:300px; font-family: 'Times New Roman', serif; font-size: 16px; border-collapse: collapse; color: #000000;">
                                <tr>
                                    <td style="padding: 8px 0; text-align: right; width: 60%;">Subtotal</td>
                                    <td style="padding: 8px 0;">:</td>
                                    <td style="padding: 8px 0 8px 12px; text-align: right; width: 40%; font-weight:bold;">{{ number_format($subtotal ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0; text-align: right;">DPP</td>
                                    <td style="padding: 8px 0;">:</td>
                                    <td style="padding: 8px 0 8px 12px; text-align: right;">{{ $tax > 0 ? number_format(round(($subtotal * 100) / 111), 0, ',', '.') : '0' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0; text-align: right;">PPN (Tax)</td>
                                    <td style="padding: 8px 0;">:</td>
                                    <td style="padding: 8px 0 8px 12px; text-align: right;">{{ number_format($tax ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr style="border-top:3px solid #000000;">
                                    <td style="padding: 10px 0; text-align: right; font-size:18px; font-weight:bold;">Total</td>
                                    <td style="padding: 10px 0; font-weight:bold;">:</td>
                                    <td style="padding: 10px 0 10px 12px; text-align: right; font-size:18px; font-weight:bold;">{{ number_format($grandTotal ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div style="margin-top: 32px; display: flex; justify-content: space-between;">
                            <div style="border:3px solid #000000; border-radius:8px; padding:12px; max-width:340px; font-family: 'Times New Roman', serif; font-size: 16px; color: #000000;">
                                <span style="color:#000000; font-weight:bold; font-size: 16px;">PAYMENT INFORMATION</span><br>
                                <span id="preview-payment-note" style="line-height: 1.6; color: #000000; font-size: 16px;">• BCA a/c. 7881213501<br> a/n. PT. Indonusa Jaya Bersama<br><br>Thank you for your support.<br>We look forward to serve you again</span>
                            </div>
                            <div style="text-align:center; min-width:240px; font-family: 'Times New Roman', serif; font-size: 16px; color: #000000;">
                                <span style="font-weight:bold; font-size:18px; color: #000000;">PT. Indonusa Jaya Bersama</span><br>
                                <div style="height:50px;"></div>
                                <span style="font-weight:bold; text-decoration:underline; color: #000000; font-size: 16px;">Alimul Imam, S.AP</span><br>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Panel: Form + Tombol (class "no-print") -->
                <div class="gap-2 lg:col-span-1">

                    <div class="no-print sticky top-5 space-y-6 lg:col-span-1">
                        <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">

                            <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
                                <h2 class="flex items-center font-semibold text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                    Panel Aksi
                                </h2>
                            </div>
                            <div class="flex flex-col gap-3 rounded-xl bg-white p-6 shadow">
                                <button type="button" id="btn-print" class="flex w-full items-center justify-center space-x-2 rounded-xl bg-emerald-600 py-2.5 text-xs font-bold text-white shadow-lg shadow-emerald-200 transition-all hover:bg-emerald-700 hover:shadow-none dark:shadow-none">Cetak PDF</button>
                                <button type="button" id="btn-excel" class="flex w-full items-center justify-center space-x-2 rounded-xl bg-emerald-600 py-2.5 text-xs font-bold text-white shadow-lg shadow-emerald-200 transition-all hover:bg-emerald-700 hover:shadow-none dark:shadow-none">Download Excel</button>
                            </div>
                        </div>

                        <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
                                <h2 class="flex items-center font-semibold text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                    Data Invoice
                                </h2>
                            </div>
                            <div class="space-y-3 p-6">
                                <label class="block text-sm font-semibold">No Invoice</label>
                                <input type="text" id="inv_number" name="inv_number" class="w-full rounded border-gray-300 bg-gray-100 p-2 text-gray-600 cursor-not-allowed dark:bg-gray-700 dark:text-gray-400" value="{{ $invoiceNumber }}" readonly>

                                <label class="block text-sm font-semibold">Tanggal Invoice</label>
                                <input type="date" id="inv_date" name="inv_date" class="w-full rounded border-gray-300 p-2" value="{{ date('Y-m-d') }}">

                                <label class="block text-sm font-semibold">NPWP</label>
                                <input type="text" id="inv_npwp" name="inv_npwp" class="w-full rounded border-gray-300 bg-gray-100 p-2 text-gray-600 cursor-not-allowed dark:bg-gray-700 dark:text-gray-400" value="{{ $customerNpwp }}" minlength="15" maxlength="16" readonly>

                                <label class="block text-sm font-semibold">PO No</label>
                                <input type="text" id="inv_po_no" name="inv_po_no" class="w-full rounded border-gray-300 p-2" value="{{ $noPoDisplay }}">

                                <label class="block text-sm font-semibold">Catatan Pembayaran</label>
                                <textarea id="inv_payment_note" name="inv_payment_note" rows="4" class="w-full rounded border-gray-300 p-2">• BCA a/c. 7881213501
                        a/n. PT. Indonusa Jaya Bersama

                        Thank you for your support.
                        We look forward to serve you again
                    </textarea>
                                <button type="button" id="btn-update-preview" class="mt-4 w-full rounded bg-blue-600 py-2 font-bold text-white">🔄 Update Preview</button>
                                <div id="update-toast" style="display:none;" class="mt-2 rounded-lg border border-green-400 bg-green-100 px-4 py-2 text-center text-sm font-semibold text-green-700">
                                    ✅ Berhasil diupdate
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>

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
        document.querySelectorAll('#inv_number, #inv_date, #inv_npwp, #inv_po_no, #inv_payment_note').forEach(function(el) {
            el.addEventListener('input', updatePreview);
        });
        document.getElementById('btn-update-preview').addEventListener('click', function() {
            updatePreview();
            // Tampilkan toast notifikasi
            const toast = document.getElementById('update-toast');
            toast.style.display = 'block';
            setTimeout(function() {
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
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
            setTimeout(function() {
                try {
                    printWindow.print();
                } catch (e) {}
            }, 500);
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
        window.addEventListener('DOMContentLoaded', updatePreview);
    </script>
</x-app-layout>
