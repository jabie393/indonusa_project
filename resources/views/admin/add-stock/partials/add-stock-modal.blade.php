<dialog id="editBarangModal" class="modal">
    <div
        class="modal-box relative flex w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-800 max-h-[95vh]">

        <!-- Header -->
        <header class="relative flex items-center justify-between px-7 py-5 text-white"
            style="background-image: var(--gradient-header)">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                    <svg class="h-5 w-5" fill="none" height="24" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z">
                        </path>
                        <path d="M12 22V12"></path>
                        <polyline points="3.29 7 12 12 20.71 7"></polyline>
                        <path d="m7.5 4.27 9 5.15"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-semibold leading-tight">Detail Barang & Update Stok</h1>
                    <p class="text-xs text-white/80">Lihat detail produk dan perbarui persediaan</p>
                </div>
            </div>
            <form method="dialog">
                <button aria-label="Tutup"
                    class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </form>
        </header>

        <!-- Body Content -->
        <div class="flex-1 overflow-y-auto p-7">
            <div class="grid gap-8 md:grid-cols-[1fr_1.4fr]">

                <!-- Left Column: Media & Primary Info -->
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label
                            class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500">
                            <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect height="18" rx="2" ry="2" width="18" x="3" y="3"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                            Gambar Barang
                        </label>
                        <div class="relative aspect-square overflow-hidden rounded-2xl border-2 border-slate-100 bg-slate-50 shadow-inner"
                            id="gambar_preview">
                            <div class="flex h-full w-full items-center justify-center text-slate-300">
                                <svg class="h-24 w-24" fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="1" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                    <circle cx="9" cy="9" r="2"></circle>
                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Status
                            Listing</label>
                        <p class="text-sm font-medium text-slate-700" id="status_listing">-</p>
                    </div>
                </div>

                <!-- Right Column: Details & Action Form -->
                <div class="space-y-6">
                    <!-- Info Section -->
                    <div class="space-y-5">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama Barang</label>
                            <div class="text-lg font-bold text-slate-800" id="goods_name">-</div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi</label>
                            <div class="min-h-[60px] text-sm leading-relaxed text-slate-600 italic" id="description">-
                            </div>
                        </div>

                        <!-- Detail Grid -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                                <label
                                    class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Kategori</label>
                                <p class="text-sm font-medium text-slate-700" id="category">-</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Stok
                                    Saat Ini</label>
                                <p class="text-sm font-bold text-blue-600" id="current_stock">-</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                                <label
                                    class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Satuan</label>
                                <p class="text-sm font-medium text-slate-700" id="unit">-</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                                <label
                                    class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Lokasi</label>
                                <p class="text-sm font-medium text-slate-700" id="location">-</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Kode
                                    Barang</label>
                                <p class="text-sm font-mono font-bold text-slate-800" id="goods_code">-</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                                <label
                                    class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Harga
                                    Jual</label>
                                <p class="text-sm font-medium text-slate-700" id="selling_price">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Update Form Area -->
                    <div class="relative overflow-hidden rounded-2xl bg-slate-900 p-6 text-white shadow-lg">
                        <!-- Decorative Background Gradient -->
                        <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-blue-500/20 blur-2xl"></div>

                        <div class="relative z-10 space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2.5"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-bold uppercase tracking-widest">Update Persediaan</h3>
                            </div>

                            <form id="tambahStockForm" method="POST" class="grid gap-4 sm:grid-cols-2">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" id="id">

                                <div class="space-y-2">
                                    <label for="stock" class="text-xs font-semibold text-slate-300">Stok Baru</label>
                                    <input type="number" name="stock" id="stock"
                                        class="w-full rounded-xl border-0 bg-white/10 px-4 py-2.5 text-sm text-white ring-1 ring-white/20 focus:bg-white/20 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                        required min="0" placeholder="0">
                                </div>

                                <div class="space-y-2">
                                    <label for="unit_cost_display" class="text-xs font-semibold text-slate-300">Harga
                                        Beli Baru</label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                                        <input type="text" id="unit_cost_display"
                                            class="w-full rounded-xl border-0 bg-white/10 pl-10 pr-4 py-2.5 text-sm text-white ring-1 ring-white/20 focus:bg-white/20 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                            required placeholder="0">
                                        <input type="hidden" name="unit_cost" id="unit_cost">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer
            class="flex items-center justify-between gap-3 border-t border-slate-100 bg-slate-50 px-7 py-5 dark:bg-gray-800 dark:border-gray-700">
            <p class="hidden text-xs text-slate-500 sm:block dark:text-gray-400">Pastikan data stok dan harga sudah
                akurat.</p>
            <div class="flex flex-1 justify-end gap-3 sm:flex-none">
                <form method="dialog">
                    <button
                        class="px-6 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-200 active:scale-95 rounded-xl dark:text-gray-300 dark:hover:bg-gray-700"
                        type="submit">
                        Batal
                    </button>
                </form>
                <button
                    class="px-8 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:opacity-90 active:scale-95 rounded-xl"
                    form="tambahStockForm" style="background-image: var(--gradient-brand)" type="submit">
                    Tambah Stok
                </button>
            </div>
        </footer>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const displayInput = document.getElementById('unit_cost_display');
        const hiddenInput = document.getElementById('unit_cost');

        if (displayInput && hiddenInput) {
            const formatValue = (val) => {
                let cleanVal = val.toString().split('.')[0];
                let value = cleanVal.replace(/\D/g, '');
                return value ? parseInt(value).toLocaleString('en-US') : '';
            };

            displayInput.addEventListener('input', function () {
                let rawValue = this.value.replace(/\D/g, '');
                hiddenInput.value = rawValue;
                this.value = formatValue(this.value);
            });

            // Sync display when hidden input is changed programmatically
            const originalValueSetter = Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, 'value').set;
            Object.defineProperty(hiddenInput, 'value', {
                set: function (val) {
                    originalValueSetter.call(this, val);
                    displayInput.value = formatValue(val.toString());
                },
                get: function () {
                    return Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, 'value').get.call(this);
                },
                configurable: true
            });
        }
    });
</script>