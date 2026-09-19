<dialog id="stockProcurementModal" class="modal">
    <div class="modal-box relative flex h-full w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow-2xl dark:bg-gray-800 sm:max-h-[90vh]">
        <!-- Header -->
        <header class="relative flex items-center justify-between px-7 py-5 text-white shrink-0"
            style="background-image: linear-gradient(135deg, #225A97 0%, #0D223A 100%)">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold leading-tight">Buat Pengadaan Stok Baru</h3>
                    <p class="text-xs text-white/80">Pengadaan barang persediaan katalog gudang (Listing)</p>
                </div>
            </div>
            <button type="button" onclick="stockProcurementModal.close()"
                class="inline-flex items-center rounded-lg p-1.5 text-white/80 hover:bg-white/10 hover:text-white transition">
                <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </header>

        <!-- Form Body -->
        <form id="createStockProcurementForm" action="{{ route('general-affair.procurement.store-stock') }}" method="POST" class="flex flex-col flex-1 min-h-0">
            @csrf

            <div class="overflow-y-auto p-6 flex-1 space-y-5">
                <!-- Info Vendor & Catatan Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 rounded-xl border border-gray-200 p-4 bg-gray-50/70 dark:border-gray-700 dark:bg-gray-900/40">
                    <!-- Vendor -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="stock_vendor_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-gray-300">
                                Pilih Vendor / Supplier <span class="text-red-500">*</span>
                            </label>
                            <a href="{{ route('vendors.index') }}" target="_blank" class="text-[11px] font-semibold text-blue-600 hover:underline dark:text-blue-400 flex items-center gap-1">
                                <span>+ Kelola Vendor</span>
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                        <div class="relative">
                            <select id="stock_vendor_id" name="vendor_id" required
                                class="w-full rounded-xl border border-gray-300 bg-white p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="" disabled selected>-- Pilih Vendor Terdaftar --</option>
                                @forelse($vendors ?? [] as $vnd)
                                    <option value="{{ $vnd->id }}">
                                        {{ $vnd->vendor_name }} ({{ $vnd->vendor_code ?: 'Vendor' }}){{ $vnd->city ? ' - ' . $vnd->city : '' }}
                                    </option>
                                @empty
                                    <option value="" disabled>Belum ada vendor terdaftar (Tambahkan di Entity Management > Vendors)</option>
                                @endforelse
                            </select>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label for="stock_proc_notes" class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-gray-300 mb-1.5">
                            Catatan Pengadaan (Notes)
                        </label>
                        <input type="text" id="stock_proc_notes" name="notes"
                            class="w-full rounded-xl border border-gray-300 bg-white p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="Contoh: Restock rutin gudang kuartal 3, pesanan tambahan..." />
                    </div>
                </div>

                <!-- Section Items Header -->
                <div class="flex items-center justify-between pt-2">
                    <div>
                        <h4 class="text-sm font-bold text-gray-800 dark:text-white">Daftar Barang yang Dipesan</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pilih barang katalog listing dan tentukan kuantitas serta estimasi harga beli</p>
                    </div>
                    <button type="button" id="btnAddStockItemRow"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-blue-50 px-3.5 py-1.5 text-xs font-bold text-[#225A97] transition hover:bg-blue-100 hover:shadow-xs active:scale-95 dark:bg-blue-950/40 dark:text-blue-300 dark:hover:bg-blue-900/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Barang</span>
                    </button>
                </div>

                <!-- Table Container -->
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-xs">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse text-left text-sm" id="stockItemsTable">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700/80 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-200">
                                    <th class="px-4 py-3 min-w-[280px]">Pilih Barang (Katalog)</th>
                                    <th class="px-3 py-3 text-center w-24">Satuan</th>
                                    <th class="px-3 py-3 text-center w-32">Qty Order</th>
                                    <th class="px-4 py-3 text-right min-w-[160px]">Harga Beli Satuan (Rp)</th>
                                    <th class="px-4 py-3 text-right min-w-[160px]">Subtotal (Rp)</th>
                                    <th class="px-3 py-3 text-center w-14">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="stockItemsTableBody" class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                <!-- Default Row 0 -->
                                <tr class="stock-item-row hover:bg-gray-50/50 dark:hover:bg-gray-700/30">
                                    <!-- Pilih Barang -->
                                    <td class="p-3">
                                        <div class="barang-dropdown-container relative">
                                            <!-- Trigger Button -->
                                            <button type="button"
                                                class="dropdown-toggle-btn flex w-full items-center justify-between rounded-lg border border-gray-300 bg-white p-2 text-xs text-gray-700 hover:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 transition">
                                                <span class="selected-barang-label truncate font-medium text-gray-400">-- Pilih Barang Katalog --</span>
                                                <span class="shrink-0 ml-2 text-gray-400">
                                                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </button>

                                            <!-- Hidden Select -->
                                            <select name="items[0][goods_id]" class="js-stock-goods-select hidden">
                                                <option value="">-- Pilih Barang Katalog --</option>
                                                @foreach($availableListingGoods ?? [] as $g)
                                                    <option value="{{ $g->id }}" 
                                                        data-kode="{{ $g->goods_code }}"
                                                        data-nama="{{ $g->goods_name }}"
                                                        data-unit="{{ $g->unit ?? 'PCS' }}"
                                                        data-stock="{{ $g->stock ?? 0 }}"
                                                        data-price="{{ (float)($g->buy_price ?? 0) }}">
                                                        [{{ $g->goods_code }}] {{ $g->goods_name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <!-- Dropdown Menu -->
                                            <div class="dropdown-menu-container fixed z-[9999] hidden w-[560px] max-w-[90vw] overflow-hidden rounded-xl border border-gray-200 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-800">
                                                <!-- Search Header -->
                                                <div class="border-b border-gray-100 bg-gray-50/90 p-2.5 dark:border-gray-700 dark:bg-gray-900/60">
                                                    <div class="relative">
                                                        <span class="text-gray-400 absolute left-3 top-1/2 -translate-y-1/2">
                                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <circle cx="11" cy="11" r="8"></circle>
                                                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                                            </svg>
                                                        </span>
                                                        <input
                                                            class="search-barang-input w-full rounded-lg border border-gray-300 bg-white py-1.5 pl-9 pr-4 text-xs text-gray-900 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                                            placeholder="Cari kode atau nama barang..." type="text">
                                                    </div>
                                                </div>
                                                <!-- Dropdown Table -->
                                                <div class="max-h-[260px] overflow-y-auto">
                                                    <table class="w-full text-left text-xs">
                                                        <thead class="sticky top-0 border-b border-gray-200 bg-gray-50 text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                                            <tr>
                                                                <th class="px-3 py-2 whitespace-nowrap">Kode Barang</th>
                                                                <th class="px-3 py-2">Nama Barang</th>
                                                                <th class="px-3 py-2 text-center whitespace-nowrap">Stok</th>
                                                                <th class="px-3 py-2 text-center whitespace-nowrap">Satuan</th>
                                                                <th class="w-8"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="barang-options-body divide-y divide-gray-100 dark:divide-gray-700">
                                                            @foreach($availableListingGoods ?? [] as $b)
                                                                <tr class="barang-option-row hover:bg-blue-50/70 dark:hover:bg-gray-700/60 cursor-pointer transition"
                                                                    data-id="{{ $b->id }}"
                                                                    data-kode="{{ $b->goods_code }}"
                                                                    data-nama="{{ $b->goods_name }}"
                                                                    data-stok="{{ $b->stock ?? 0 }}"
                                                                    data-satuan="{{ $b->unit ?? 'PCS' }}"
                                                                    data-price="{{ (float)($b->buy_price ?? 0) }}">
                                                                    <td class="px-3 py-2 font-mono font-bold text-[#225A97] dark:text-blue-400 whitespace-nowrap">{{ $b->goods_code }}</td>
                                                                    <td class="px-3 py-2 font-medium text-gray-900 dark:text-gray-100">{{ $b->goods_name }}</td>
                                                                    <td class="px-3 py-2 text-center font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">{{ $b->stock ?? 0 }}</td>
                                                                    <td class="px-3 py-2 text-center text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $b->unit ?? 'PCS' }}</td>
                                                                    <td class="pr-3 text-right">
                                                                        <span class="checked-icon hidden text-blue-600 dark:text-blue-400">
                                                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                                                <polyline points="20 6 9 17 4 12"></polyline>
                                                                            </svg>
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            <tr class="no-goods-found-row hidden">
                                                                <td colspan="5" class="py-6 text-center text-xs text-gray-400 dark:text-gray-500">
                                                                    Barang tidak ditemukan
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Satuan -->
                                    <td class="p-3 text-center">
                                        <span class="js-stock-unit-label inline-block rounded-md bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                            -
                                        </span>
                                    </td>

                                    <!-- Qty Order -->
                                    <td class="p-3 text-center">
                                        <input type="number" name="items[0][qty_ordered]" min="1" value="1" required
                                            class="js-stock-qty-input w-24 rounded-lg border border-gray-300 bg-white p-2 text-center text-xs font-bold text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                                    </td>

                                    <!-- Harga Beli -->
                                    <td class="p-3 text-right">
                                        <input type="text" inputmode="numeric" name="items[0][buy_price]" value="0" required
                                            class="js-stock-price-input w-full rounded-lg border border-gray-300 bg-white p-2 text-right text-xs font-semibold text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                                    </td>

                                    <!-- Subtotal -->
                                    <td class="p-3 text-right font-bold text-gray-900 dark:text-white">
                                        <span class="js-stock-subtotal-label">Rp 0</span>
                                    </td>

                                    <!-- Remove Button -->
                                    <td class="p-3 text-center">
                                        <button type="button" class="js-btn-remove-row text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 dark:hover:bg-red-950/30 transition">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Grand Total & Summary Box -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 rounded-xl border border-blue-100 bg-blue-50/60 p-4 dark:border-blue-900/40 dark:bg-blue-950/20">
                    <div class="flex items-center gap-2 text-xs text-blue-900 dark:text-blue-300">
                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Total: <strong id="stockTotalItemCount" class="font-bold">1</strong> jenis barang</span>
                    </div>
                    <div class="text-right">
                        <span class="block text-[11px] font-bold uppercase text-slate-500 dark:text-slate-400">Total Estimasi Pengadaan</span>
                        <span id="stockGrandTotalLabel" class="text-lg font-extrabold text-[#225A97] dark:text-blue-400">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="flex items-center justify-end gap-3 border-t border-gray-200 px-7 py-4 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 shrink-0">
                <button type="button" onclick="stockProcurementModal.close()"
                    class="rounded-xl px-5 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 dark:text-gray-300 dark:hover:bg-gray-700 transition">
                    Batal
                </button>
                <button type="submit"
                    class="rounded-xl bg-gradient-to-r from-[#225A97] to-[#0D223A] px-6 py-2.5 text-sm font-bold text-white shadow-md transition hover:opacity-90 active:scale-95">
                    Simpan &amp; Buat Pengadaan
                </button>
            </footer>
        </form>
    </div>
</dialog>

<script>
    (function() {
        let stockRowIndex = 1;

        function formatRupiah(amount) {
            return 'Rp ' + Number(amount).toLocaleString('id-ID');
        }

        function formatNumberString(val) {
            let clean = String(val || '').replace(/\D/g, '');
            if (!clean) return '';
            return parseInt(clean, 10).toLocaleString('id-ID');
        }

        function formatRupiahInput(input) {
            let cursorPosition = input.selectionStart;
            let oldLength = input.value.length;
            let raw = input.value.replace(/\D/g, '');

            if (!raw) {
                input.value = '';
                return 0;
            }

            let formatted = parseInt(raw, 10).toLocaleString('id-ID');
            input.value = formatted;

            let newLength = formatted.length;
            let newCursorPosition = cursorPosition + (newLength - oldLength);
            if (newCursorPosition < 0) newCursorPosition = 0;
            input.setSelectionRange(newCursorPosition, newCursorPosition);

            return parseInt(raw, 10);
        }

        function recalculateRow(row) {
            const qtyInput = row.querySelector('.js-stock-qty-input');
            const priceInput = row.querySelector('.js-stock-price-input');
            const subtotalLabel = row.querySelector('.js-stock-subtotal-label');

            const qty = parseFloat(qtyInput ? qtyInput.value : 0) || 0;
            const rawPrice = (priceInput ? priceInput.value : '').replace(/\D/g, '');
            const price = parseFloat(rawPrice) || 0;
            const subtotal = qty * price;

            if (subtotalLabel) {
                subtotalLabel.textContent = formatRupiah(subtotal);
            }
            return subtotal;
        }

        function recalculateAll() {
            const rows = document.querySelectorAll('#stockItemsTableBody .stock-item-row');
            let grandTotal = 0;
            let count = 0;

            rows.forEach(row => {
                count++;
                grandTotal += recalculateRow(row);
            });

            const totalItemCountEl = document.getElementById('stockTotalItemCount');
            const grandTotalLabelEl = document.getElementById('stockGrandTotalLabel');

            if (totalItemCountEl) totalItemCountEl.textContent = count;
            if (grandTotalLabelEl) grandTotalLabelEl.textContent = formatRupiah(grandTotal);
        }

        function positionDropdownMenu(toggleBtn, menu) {
            const modalBox = toggleBtn.closest('.modal-box') || toggleBtn.closest('dialog') || document.body;
            const modalRect = modalBox.getBoundingClientRect();
            const btnRect = toggleBtn.getBoundingClientRect();

            // Calculate width bounded by modalBox
            const maxAllowedWidth = Math.min(560, modalBox.clientWidth - 24);
            const menuWidth = Math.max(280, Math.min(maxAllowedWidth, modalRect.width - 24));
            menu.style.width = menuWidth + 'px';

            // Calculate left relative to modalBox containing block
            let left = btnRect.left - modalRect.left;
            left = Math.max(12, Math.min(left, modalBox.clientWidth - menuWidth - 12));
            menu.style.left = left + 'px';

            // Calculate top relative to modalBox containing block
            const spaceBelow = modalRect.bottom - btnRect.bottom;
            const menuHeight = 300;
            if (spaceBelow < 260 && (btnRect.top - modalRect.top) > spaceBelow) {
                // Open upward if tight space below
                menu.style.top = Math.max(12, (btnRect.top - modalRect.top - menuHeight - 4)) + 'px';
            } else {
                // Open downward
                menu.style.top = (btnRect.bottom - modalRect.top + 4) + 'px';
            }
        }

        function attachCustomDropdownEvents(row) {
            const container = row.querySelector('.barang-dropdown-container');
            if (!container) return;

            const toggleBtn = container.querySelector('.dropdown-toggle-btn');
            const menu = container.querySelector('.dropdown-menu-container');
            const searchInput = container.querySelector('.search-barang-input');
            const optionRows = container.querySelectorAll('.barang-option-row');
            const noFoundRow = container.querySelector('.no-goods-found-row');
            const backingSelect = row.querySelector('.js-stock-goods-select');

            // Open/close menu on button click
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                // Close all other dropdown menus
                document.querySelectorAll('.dropdown-menu-container').forEach(m => {
                    if (m !== menu) {
                        m.classList.add('hidden');
                    }
                });

                menu.classList.toggle('hidden');
                if (!menu.classList.contains('hidden')) {
                    positionDropdownMenu(toggleBtn, menu);
                    searchInput.value = '';
                    searchInput.dispatchEvent(new Event('input'));
                    setTimeout(() => searchInput.focus({ preventScroll: true }), 50);
                }
            });

            // Handle search input filtering
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                let matchCount = 0;

                optionRows.forEach(optRow => {
                    const kode = (optRow.getAttribute('data-kode') || '').toLowerCase();
                    const nama = (optRow.getAttribute('data-nama') || '').toLowerCase();
                    const matches = !query || kode.includes(query) || nama.includes(query);

                    if (matches) {
                        optRow.style.display = '';
                        matchCount++;
                    } else {
                        optRow.style.display = 'none';
                    }
                });

                if (noFoundRow) {
                    noFoundRow.classList.toggle('hidden', matchCount > 0);
                }
            });

            // Prevent click inside search input or menu from closing dropdown
            searchInput.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            menu.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Option row selection click handler
            optionRows.forEach(optRow => {
                optRow.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const id = this.getAttribute('data-id');
                    const kode = this.getAttribute('data-kode');
                    const nama = this.getAttribute('data-nama');
                    const unit = this.getAttribute('data-satuan');
                    const price = parseFloat(this.getAttribute('data-price')) || 0;

                    // Set backing select value
                    if (backingSelect) {
                        backingSelect.value = id;
                        backingSelect.dispatchEvent(new Event('change', { bubbles: true }));
                    }

                    // Update toggle button text/label
                    const labelSpan = container.querySelector('.selected-barang-label');
                    if (labelSpan) {
                        labelSpan.textContent = `[${kode}] ${nama}`;
                        labelSpan.classList.remove('text-gray-400');
                        labelSpan.classList.add('text-gray-900', 'dark:text-white', 'font-semibold');
                    }
                    toggleBtn.classList.remove('border-red-500', 'ring-1', 'ring-red-500');

                    // Update unit
                    const unitLabel = row.querySelector('.js-stock-unit-label');
                    if (unitLabel) unitLabel.textContent = unit || 'PCS';

                    // Update price if 0 or empty
                    const priceInput = row.querySelector('.js-stock-price-input');
                    const currentRaw = (priceInput ? priceInput.value : '').replace(/\D/g, '');
                    if (priceInput && (!currentRaw || parseFloat(currentRaw) === 0) && price > 0) {
                        priceInput.value = formatNumberString(price);
                    }

                    // Update active styles
                    optionRows.forEach(r => {
                        r.classList.remove('bg-blue-50', 'dark:bg-gray-700/80');
                        const icon = r.querySelector('.checked-icon');
                        if (icon) icon.classList.add('hidden');
                    });
                    this.classList.add('bg-blue-50', 'dark:bg-gray-700/80');
                    const checkIcon = this.querySelector('.checked-icon');
                    if (checkIcon) checkIcon.classList.remove('hidden');

                    // Hide dropdown menu
                    menu.classList.add('hidden');
                    recalculateAll();
                });
            });
        }

        function bindRowEvents(row) {
            const qtyInput = row.querySelector('.js-stock-qty-input');
            const priceInput = row.querySelector('.js-stock-price-input');
            const removeBtn = row.querySelector('.js-btn-remove-row');

            if (qtyInput) {
                qtyInput.addEventListener('input', recalculateAll);
            }

            if (priceInput) {
                priceInput.addEventListener('input', function() {
                    formatRupiahInput(this);
                    recalculateAll();
                });
            }

            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    const allRows = document.querySelectorAll('#stockItemsTableBody .stock-item-row');
                    if (allRows.length > 1) {
                        row.remove();
                        recalculateAll();
                    } else {
                        // Reset first row
                        const selectEl = row.querySelector('.js-stock-goods-select');
                        if (selectEl) selectEl.value = '';
                        const labelSpan = row.querySelector('.selected-barang-label');
                        if (labelSpan) {
                            labelSpan.textContent = '-- Pilih Barang Katalog --';
                            labelSpan.classList.add('text-gray-400');
                            labelSpan.classList.remove('text-gray-900', 'dark:text-white', 'font-semibold');
                        }
                        const unitLabel = row.querySelector('.js-stock-unit-label');
                        if (unitLabel) unitLabel.textContent = '-';
                        if (qtyInput) qtyInput.value = '1';
                        if (priceInput) priceInput.value = '0';
                        row.querySelectorAll('.barang-option-row').forEach(r => {
                            r.classList.remove('bg-blue-50', 'dark:bg-gray-700/80');
                            const icon = r.querySelector('.checked-icon');
                            if (icon) icon.classList.add('hidden');
                        });
                        recalculateAll();
                    }
                });
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.barang-dropdown-container') && !e.target.closest('.dropdown-menu-container')) {
                document.querySelectorAll('.dropdown-menu-container').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });

        // Reposition or close dropdowns on window resize
        window.addEventListener('resize', function() {
            document.querySelectorAll('.dropdown-menu-container:not(.hidden)').forEach(menu => {
                const container = menu.closest('.barang-dropdown-container');
                const toggleBtn = container?.querySelector('.dropdown-toggle-btn');
                if (toggleBtn && menu) {
                    positionDropdownMenu(toggleBtn, menu);
                }
            });
        });

        // Reposition dropdowns when scrolling modal container
        document.addEventListener('scroll', function(e) {
            const target = e.target;
            const isInsideDropdown =
                target instanceof Element &&
                (target.closest('.dropdown-menu-container') || target.closest('.barang-dropdown-container'));

            if (!isInsideDropdown) {
                document.querySelectorAll('.dropdown-menu-container:not(.hidden)').forEach(menu => {
                    const container = menu.closest('.barang-dropdown-container');
                    const toggleBtn = container?.querySelector('.dropdown-toggle-btn');
                    if (toggleBtn && menu) {
                        positionDropdownMenu(toggleBtn, menu);
                    } else {
                        menu.classList.add('hidden');
                    }
                });
            }
        }, true);

        // Bind initial setup
        document.addEventListener('DOMContentLoaded', function() {
            const firstRow = document.querySelector('#stockItemsTableBody .stock-item-row');
            if (firstRow) {
                bindRowEvents(firstRow);
                attachCustomDropdownEvents(firstRow);
            }

            const modalEl = document.getElementById('stockProcurementModal');
            if (modalEl) {
                modalEl.addEventListener('close', function() {
                    document.querySelectorAll('.dropdown-menu-container').forEach(menu => {
                        menu.classList.add('hidden');
                    });
                });
            }

            const btnAdd = document.getElementById('btnAddStockItemRow');
            if (btnAdd) {
                btnAdd.addEventListener('click', function() {
                    const tbody = document.getElementById('stockItemsTableBody');
                    if (!tbody) return;

                    const currentFirstRow = tbody.querySelector('.stock-item-row');
                    if (!currentFirstRow) return;

                    const newRow = currentFirstRow.cloneNode(true);
                    newRow.className = 'stock-item-row hover:bg-gray-50/50 dark:hover:bg-gray-700/30';

                    // Update inputs
                    const select = newRow.querySelector('.js-stock-goods-select');
                    if (select) {
                        select.name = `items[${stockRowIndex}][goods_id]`;
                        select.value = '';
                    }

                    const labelSpan = newRow.querySelector('.selected-barang-label');
                    if (labelSpan) {
                        labelSpan.textContent = '-- Pilih Barang Katalog --';
                        labelSpan.classList.add('text-gray-400');
                        labelSpan.classList.remove('text-gray-900', 'dark:text-white', 'font-semibold');
                    }

                    const toggleBtn = newRow.querySelector('.dropdown-toggle-btn');
                    if (toggleBtn) {
                        toggleBtn.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                    }

                    const menu = newRow.querySelector('.dropdown-menu-container');
                    if (menu) {
                        menu.classList.add('hidden');
                    }

                    const searchInput = newRow.querySelector('.search-barang-input');
                    if (searchInput) {
                        searchInput.value = '';
                    }

                    newRow.querySelectorAll('.barang-option-row').forEach(r => {
                        r.style.display = '';
                        r.classList.remove('bg-blue-50', 'dark:bg-gray-700/80');
                        const icon = r.querySelector('.checked-icon');
                        if (icon) icon.classList.add('hidden');
                    });

                    const noFound = newRow.querySelector('.no-goods-found-row');
                    if (noFound) noFound.classList.add('hidden');

                    const unitLabel = newRow.querySelector('.js-stock-unit-label');
                    if (unitLabel) unitLabel.textContent = '-';

                    const qtyInput = newRow.querySelector('.js-stock-qty-input');
                    if (qtyInput) {
                        qtyInput.name = `items[${stockRowIndex}][qty_ordered]`;
                        qtyInput.value = '1';
                    }

                    const priceInput = newRow.querySelector('.js-stock-price-input');
                    if (priceInput) {
                        priceInput.name = `items[${stockRowIndex}][buy_price]`;
                        priceInput.value = '0';
                    }

                    const subtotalLabel = newRow.querySelector('.js-stock-subtotal-label');
                    if (subtotalLabel) {
                        subtotalLabel.textContent = 'Rp 0';
                    }

                    tbody.appendChild(newRow);
                    bindRowEvents(newRow);
                    attachCustomDropdownEvents(newRow);
                    stockRowIndex++;
                    recalculateAll();
                });
            }

            // Form validation before submit
            const form = document.getElementById('createStockProcurementForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    let hasError = false;
                    const rows = document.querySelectorAll('#stockItemsTableBody .stock-item-row');
                    if (rows.length === 0) {
                        alert('Minimal satu barang harus ditambahkan.');
                        e.preventDefault();
                        return;
                    }

                    rows.forEach(row => {
                        const select = row.querySelector('.js-stock-goods-select');
                        const toggleBtn = row.querySelector('.dropdown-toggle-btn');
                        if (!select || !select.value) {
                            hasError = true;
                            if (toggleBtn) {
                                toggleBtn.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                            }
                        } else {
                            if (toggleBtn) {
                                toggleBtn.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                            }
                        }
                    });

                    if (hasError) {
                        e.preventDefault();
                        alert('Harap pilih barang katalog untuk setiap baris.');
                        return false;
                    }

                    // Bersihkan format titik pemisah ribuan sebelum dikirim ke backend
                    form.querySelectorAll('.js-stock-price-input').forEach(input => {
                        input.value = input.value.replace(/\D/g, '') || '0';
                    });
                });
            }
        });
    })();
</script>
