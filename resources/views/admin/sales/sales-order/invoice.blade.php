<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-6">

            {{-- Breadcrumb/back button --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Panel : Preview Invoice (id="invoice-preview", class="print-area") -->
                <div class="lg:col-span-2">
                    <div id="invoice-preview" class="print-area" style="font-family: Arial, sans-serif; padding: 32px; background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <span style="font-size:28px; font-weight:bold; color:#003399; text-decoration:underline;">INVOICE</span>
                                <div class="mt-6">
                                    <span style="font-weight:bold;">Invoice To:</span><br>
                                    <p id="preview_customer" style="font-weight: bold; font-size: 13px; margin: 0 0 4px 0;">{{ strtoupper($customerName) }}</p>
                                    <p id="preview_address" style="margin: 0 0 8px 0; line-height: 1.4; color: #374151;">{{ $customerAddress ?: '-' }}</p>
                                    <span id="preview-npwp">NPWP: <span>{{ old('inv_npwp', $customerNpwp) }}</span></span>
                                </div>
                            </div>
                            <div style="text-align:right; min-width:220px;">
                                <div>Date: <span id="preview-date">{{ date('d/m/y') }}</span></div>
                                <div>No Invoice: <span id="preview-number">{{ $invoiceNumber }}</span></div>
                                <div>PO No: <span id="preview-po">{{ $noPoDisplay }}</span></div>
                            </div>
                        </div>
                        <div class="mt-8">
                            <table style="width:100%; border-collapse:collapse;">
                                <thead>
                                    <tr style="background:#1A3A6B; color:white;">
                                        <th style="padding:8px; border:1px solid #1A3A6B;">No</th>
                                        <th style="padding:8px; border:1px solid #1A3A6B;">Description</th>
                                        <th style="padding:8px; border:1px solid #1A3A6B;">Qty</th>
                                        <th style="padding:8px; border:1px solid #1A3A6B;">Unit Price</th>
                                        <th style="padding:8px; border:1px solid #1A3A6B;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $i => $item)
                                        <tr>
                                            <td style="padding:8px; border:1px solid #ddd;">{{ $i + 1 }}</td>
                                            <td style="padding:8px; border:1px solid #ddd;">{{ $item['nama_barang'] ?? ($item['description'] ?? '-') }}</td>
                                            <td style="padding:8px; border:1px solid #ddd; text-align:right;">{{ $item['qty'] ?? ($item['quantity'] ?? 0) }}</td>
                                            <td style="padding:8px; border:1px solid #ddd; text-align:right;">{{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
                                            <td style="padding:8px; border:1px solid #ddd; text-align:right;">{{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr style="background:#1A3A6B; height:6px;">
                                        <td colspan="5"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <table style="min-width:320px;">
                                <tr>
                                    <td style="text-align:right;">Subtotal:</td>
                                    <td style="text-align:right; font-weight:bold;">{{ number_format($subtotal ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;">DPP:</td>
                                    <td style="text-align:right;">{{ $tax > 0 ? number_format(round(($subtotal * 100) / 111), 0, ',', '.') : '0' }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;">PPN:</td>
                                    <td style="text-align:right;">{{ number_format($tax ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr style="border-top:3px solid #1A3A6B;">
                                    <td style="text-align:right; font-size:16px; font-weight:bold;">Total:</td>
                                    <td style="text-align:right; font-size:16px; font-weight:bold;">{{ number_format($grandTotal ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="mt-8 flex justify-between">
                            <div style="border:2px solid #e53e3e; border-radius:8px; padding:16px; max-width:320px;">
                                <span style="color:#e53e3e; font-weight:bold;">PAYMENT INFORMATION</span><br>
                                <span id="preview-payment-note">• BCA a/c. 7881213501<br> a/n. PT. Indonusa Jaya Bersama<br><br>Thank you for your support.<br>We look forward to serve you again</span>
                            </div>
                            <div style="text-align:right; min-width:220px;">
                                <span style="font-weight:bold; font-size:16px;">PT. Indonusa Jaya Bersama</span><br>
                                <div style="height:60px;"></div>
                                <span style="font-size:14px; font-weight:bold; text-decoration:underline;">Alimul Imam.S.AP</span><br>
                                <span style="font-size:13px;">(Tanda Tangan)</span>
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
                                <input type="text" id="inv_number" name="inv_number" class="w-full rounded border-gray-300 p-2" value="{{ $invoiceNumber }}">

                                <label class="block text-sm font-semibold">Tanggal Invoice</label>
                                <input type="date" id="inv_date" name="inv_date" class="w-full rounded border-gray-300 p-2" value="{{ date('Y-m-d') }}">

                                <label class="block text-sm font-semibold">NPWP</label>
                                <input type="text" id="inv_npwp" name="inv_npwp" class="w-full rounded border-gray-300 p-2" value="{{ $customerNpwp }}" minlength="15" maxlength="16">

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
            document.getElementById('preview-npwp').innerHTML = 'NPWP: <span>' + document.getElementById('inv_npwp').value + '</span>';
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
                        body { font-family: Arial, sans-serif; padding: 32px; background: white; }
                        table { border-collapse: collapse; }
                        @media print {
                            body { padding: 20px; }
                            @page { margin: 1cm; size: A4; }
                        }
                    </style>
                </head>
                <body>
                    ${invoiceContent}
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
