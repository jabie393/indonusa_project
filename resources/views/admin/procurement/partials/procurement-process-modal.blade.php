<dialog id="procurement-modal" class="modal">
    <!-- Creation/Process View -->
    <div id="procurement-create-view" class="modal-box relative flex h-full w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-800 sm:max-h-[90vh]">
        <header class="relative flex items-center justify-between px-7 py-5 text-white"
            style="background-image: linear-gradient(135deg, #225A97 0%, #0D223A 100%)">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold leading-tight">Buat Procurement Baru</h3>
                    <p class="text-xs text-white/80">Quotation Asal: <span id="process-quotation-number"></span></p>
                </div>
            </div>
            <form method="dialog">
                <button type="button" class="js-procurement-modal-close ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </form>
        </header>

        <form id="modalProcurementCreateForm" class="flex flex-col flex-1 min-h-0">
            @csrf
            <input type="hidden" name="custom_quotation_id" id="process-quotation-id">

            <div class="overflow-y-auto p-6 flex-1 space-y-6">
                <!-- Validation Error Alert Container inside Modal -->
                <div id="modal-validation-errors" class="hidden rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-900/30 dark:bg-red-900/10">
                    <h4 class="mb-2 text-sm font-semibold text-red-800 dark:text-red-400">Harap perbaiki kesalahan berikut:</h4>
                    <ul class="list-disc pl-5 text-xs text-red-700 dark:text-red-300 space-y-1"></ul>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 rounded-xl border border-gray-100 p-4 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                    <div>
                        <span class="block text-xs font-semibold uppercase text-slate-400">Kepada / Customer</span>
                        <span id="process-customer-to" class="text-sm font-bold text-slate-800 dark:text-white"></span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold uppercase text-slate-400">Subject</span>
                        <span id="process-subject" class="text-sm font-bold text-slate-800 dark:text-white"></span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold uppercase text-slate-400">Tanggal Target Delivery</span>
                        <span id="process-delivery-date" class="text-sm font-bold text-slate-800 dark:text-white"></span>
                    </div>
                </div>

                <!-- Notes Section -->
                <div>
                    <label for="process-notes" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan Pengadaan (Notes)</label>
                    <textarea id="process-notes" name="notes" rows="2"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-black focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:border-gray-500 dark:bg-gray-600 dark:text-white"
                        placeholder="Masukkan catatan pembelian atau nama supplier..."></textarea>
                </div>

                <!-- Items Table -->
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700 text-slate-700 dark:text-slate-200 text-xs uppercase font-bold tracking-wider">
                                <th class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 text-left">Nama Barang</th>
                                <th class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 text-left">Kategori</th>
                                <th class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 text-center">Satuan</th>
                                <th class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 text-center">Qty Permintaan</th>
                                <th class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 text-center w-36">Qty Dipesan (GA)</th>
                                <th class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 text-right w-52">Harga Beli Satuan (Rp)</th>
                            </tr>
                        </thead>
                        <tbody id="process-items-body" class="text-sm divide-y divide-gray-200 dark:divide-gray-700">
                            <!-- Populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Buttons -->
            <footer class="flex items-center justify-end gap-3 border-t border-gray-200 px-7 py-4 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <button type="button" class="js-procurement-modal-close px-6 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-200 rounded-xl dark:text-gray-300 dark:hover:bg-gray-700">
                    Batal
                </button>
                <button type="submit" class="rounded-xl bg-[#225A97] px-6 py-2.5 text-sm font-bold text-white transition-all hover:bg-blue-800">
                    Simpan &amp; Buat Pengadaan
                </button>
            </footer>
        </form>
    </div>

    <!-- Selection / Options View -->
    <div id="procurement-options-view" class="modal-box relative hidden flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-800 max-w-lg">
        <header class="relative flex items-center justify-between px-7 py-5 text-white"
            style="background-image: linear-gradient(135deg, #225A97 0%, #0D223A 100%)">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold leading-tight">Pilih Tipe Pengadaan</h3>
                    <p class="text-xs text-white/80">Bagaimana barang kustom ini diterima?</p>
                </div>
            </div>
            <button type="button" class="js-procurement-modal-close ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </header>

        <div class="p-6 space-y-6">
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Silakan pilih salah satu opsi di bawah ini untuk memproses pengadaan barang kustom.
            </p>

            <div class="grid grid-cols-1 gap-4">
                <!-- Option Full -->
                <button type="button" id="btn-full-procurement" class="flex items-start gap-4 rounded-xl border border-gray-200 p-4 text-left hover:border-blue-500 hover:bg-blue-50/30 dark:border-gray-700 dark:hover:bg-gray-700/30 transition-all group">
                    <div class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-green-100 text-green-700 dark:bg-green-950/50 dark:text-green-400 group-hover:scale-105 transition-transform">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white">Full Procurement</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Sistem akan otomatis membuat record kedatangan (GoodsReceipt) dengan kuantitas penuh untuk semua item dan mengirimkannya langsung ke antrean Warehouse.
                        </p>
                    </div>
                </button>

                <!-- Option Partial -->
                <button type="button" id="btn-partial-procurement" class="flex items-start gap-4 rounded-xl border border-gray-200 p-4 text-left hover:border-blue-500 hover:bg-blue-50/30 dark:border-gray-700 dark:hover:bg-gray-700/30 transition-all group">
                    <div class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-950/50 dark:text-blue-400 group-hover:scale-105 transition-transform">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white">Partial Procurement</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Sistem hanya akan membuat pengadaan awal dengan status pending. Anda dapat mencatat kedatangan barang secara bertahap langsung di modal Detail Procurement.
                        </p>
                    </div>
                </button>
            </div>
        </div>

        <footer class="flex items-center justify-between border-t border-gray-200 px-7 py-4 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <button type="button" id="btn-back-to-form" class="px-6 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-200 rounded-xl dark:text-gray-300 dark:hover:bg-gray-700">
                Kembali ke Form
            </button>
        </footer>
    </div>

    <!-- Detail View (content dynamically injected via AJAX) -->
    <div id="procurement-detail-view" class="modal-box relative hidden flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-800 max-w-5xl h-full sm:max-h-[90vh]">
        <!-- Injected dynamic content here -->
    </div>
</dialog>
