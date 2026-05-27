<dialog id="editBarangModalPrimary" class="modal">
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
                    <h1 class="text-lg font-semibold leading-tight">Edit Detail Barang</h1>
                    <p class="text-xs text-white/80">Perbarui informasi produk di dalam sistem</p>
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

        <!-- Form Content -->
        <form id="editBarangForm" method="POST" enctype="multipart/form-data"
            class="grid flex-1 gap-8 overflow-y-auto p-7 md:grid-cols-[1fr_1.4fr]">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit_id">

            <!-- Left Column: Media & Core -->
            <div class="space-y-6">
                <!-- Image Upload Section -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14"
                            xmlns="http://www.w3.org/2000/svg">
                            <rect height="18" rx="2" ry="2" width="18" x="3" y="3"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                        Gambar Barang
                    </label>
                    <div id="edit_gambar_preview"
                        class="group relative flex aspect-square cursor-pointer flex-col items-center justify-center overflow-hidden border-2 border-dashed border-slate-200 bg-slate-50 transition hover:border-blue-500 hover:bg-blue-50/30 rounded-2xl">
                        <input id="edit_gambar" name="image" type="file" class="hidden" accept="image/*" />
                        <label id="edit_gambar_label" for="edit_gambar"
                            class="relative flex h-full w-full cursor-pointer flex-col items-center justify-center">
                            <!-- Existing Image Preview -->
                            <img id="edit_image_display" src="" alt="Gambar Barang"
                                class="absolute inset-0 h-full w-full object-contain hidden z-10" />

                            <!-- Placeholder / Upload UI -->
                            <div id="edit_upload_placeholder"
                                class="flex flex-col items-center gap-2 text-slate-400 group-hover:text-blue-500 z-20">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition group-hover:bg-blue-100 group-hover:text-blue-600">
                                    <svg fill="none" height="20" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 3v12"></path>
                                        <path d="m17 8-5-5-5 5"></path>
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-700">Ganti gambar</p>
                                <p class="text-xs text-slate-400">Klik atau seret file</p>
                            </div>

                            <!-- Hover Overlay (Glassmorphism) -->
                            <div id="edit_image_overlay"
                                class="absolute inset-0 z-30 flex flex-col items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100 backdrop-blur-[2px] hidden">
                                <svg fill="none" height="24" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24"
                                    class="text-white">
                                    <path d="M12 3v12"></path>
                                    <path d="m17 8-5-5-5 5"></path>
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                </svg>
                                <span class="mt-2 text-xs font-bold uppercase tracking-wider text-white">Upload
                                    Baru</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Status Listing -->
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500"
                        for="edit_status_listing">Status Listing</label>
                    <select
                        class="w-full appearance-none border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition rounded-2xl dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        id="edit_status_listing" name="status_listing" required>
                        <option value="listing">🟢 Listing</option>
                        <option value="non listing">🔴 Non Listing</option>
                    </select>
                </div>
            </div>

            <!-- Right Column: Details -->
            <div class="space-y-5">
                <!-- Nama Barang -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500"
                        for="edit_goods_name">
                        <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z">
                            </path>
                            <path d="M12 22V12"></path>
                            <polyline points="3.29 7 12 12 20.71 7"></polyline>
                            <path d="m7.5 4.27 9 5.15"></path>
                        </svg>
                        Nama Barang
                    </label>
                    <input
                        class="w-full border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition rounded-2xl dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        id="edit_goods_name" name="goods_name" type="text" placeholder="cth. Kopi Arabika 250g"
                        required />
                </div>

                <!-- Deskripsi -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500"
                        for="edit_deskripsi">
                        <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h4"></path>
                            <path d="M7 7V3h10v4"></path>
                            <path d="M8 11h8"></path>
                            <path d="M8 15h6"></path>
                        </svg>
                        Deskripsi
                    </label>
                    <textarea
                        class="w-full border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition resize-none rounded-2xl dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        id="edit_deskripsi" name="deskripsi" rows="3"
                        placeholder="Tulis deskripsi singkat tentang barang…" required></textarea>
                </div>

                <!-- Kategori -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500"
                        for="edit_category">
                        <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z">
                            </path>
                            <circle cx="7.5" cy="7.5" fill="currentColor" r=".5"></circle>
                        </svg>
                        Kategori
                    </label>
                    <select
                        class="w-full border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition rounded-2xl dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        id="edit_category" name="category" required>
                        <option disabled value="">Pilih kategori</option>
                        @foreach ($kategoriList as $kategori)
                            <option value="{{ $kategori }}">{{ $kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Grid Details -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <!-- Kode Barang -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500"
                            for="edit_goods_code">
                            <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14"
                                xmlns="http://www.w3.org/2000/svg">
                                <line x1="4" x2="20" y1="9" y2="9"></line>
                                <line x1="4" x2="20" y1="15" y2="15"></line>
                                <line x1="10" x2="8" y1="3" y2="21"></line>
                                <line x1="16" x2="14" y1="3" y2="21"></line>
                            </svg>
                            Kode Barang
                        </label>
                        <div class="relative">
                            <input
                                class="w-full border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm font-mono text-slate-600 outline-none transition pr-10 rounded-2xl dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                id="edit_goods_code" name="goods_code" type="text" readonly />
                            <button aria-label="Refresh kode" id="editRefreshKodeBarang"
                                class="absolute right-2 top-1/2 -translate-y-1/2 rounded-md p-1.5 text-slate-400 transition hover:bg-blue-100 hover:text-blue-600"
                                type="button">
                                <svg fill="none" height="16" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="16"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                    <path d="M21 3v5h-5"></path>
                                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                    <path d="M8 16H3v5"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Stok -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500"
                            for="edit_stock">
                            <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83z">
                                </path>
                                <path d="M2 12a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 12">
                                </path>
                                <path d="M2 17a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 17">
                                </path>
                            </svg>
                            Stok
                        </label>
                        <input
                            class="w-full border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition rounded-2xl dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            id="edit_stock" name="stock" type="number" placeholder="0" required />
                    </div>

                    <!-- Satuan -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500"
                            for="edit_unit">
                            <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z">
                                </path>
                                <path d="m14.5 12.5 2-2"></path>
                                <path d="m11.5 9.5 2-2"></path>
                                <path d="m8.5 6.5 2-2"></path>
                                <path d="m17.5 15.5 2-2"></path>
                            </svg>
                            Satuan
                        </label>
                        <input
                            class="w-full border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition rounded-2xl dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            id="edit_unit" name="unit" type="text" placeholder="pcs, kg, box…" required />
                    </div>

                    <!-- Lokasi -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500"
                            for="edit_location">
                            <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0">
                                </path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            Lokasi (Opsional)
                        </label>
                        <input
                            class="w-full border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition rounded-2xl dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            id="edit_location" name="location" type="text" placeholder="Gudang A — Rak 3" />
                    </div>

                    <!-- Harga Beli -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500"
                            for="edit_buy_price_display">
                            <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect width="20" height="12" x="2" y="6" rx="2"></rect>
                                <circle cx="12" cy="12" r="2"></circle>
                                <path d="M6 12h.01M18 12h.01"></path>
                            </svg>
                            Harga Beli
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-medium text-slate-400">Rp</span>
                            <input
                                class="w-full border border-slate-200 bg-slate-50 pl-10 pr-4 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition rounded-2xl dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                id="edit_buy_price_display" type="text" placeholder="0" required />
                            <input type="hidden" name="buy_price" id="edit_buy_price" />
                        </div>
                    </div>

                    <!-- Harga Jual -->
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500"
                            for="edit_selling_price_display">
                            <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect width="20" height="12" x="2" y="6" rx="2"></rect>
                                <circle cx="12" cy="12" r="2"></circle>
                                <path d="M6 12h.01M18 12h.01"></path>
                            </svg>
                            Harga Jual
                        </label>
                        <div class="relative">
                            <span
                                class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-medium text-slate-400">Rp</span>
                            <input
                                class="w-full border border-slate-200 bg-slate-50 pl-10 pr-4 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition rounded-2xl dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                id="edit_selling_price_display" type="text" placeholder="Otomatis +15%" />
                            <input type="hidden" name="selling_price" id="edit_selling_price" />
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Footer -->
        <footer
            class="flex items-center justify-between gap-3 border-t border-slate-100 bg-slate-50 px-7 py-5 dark:bg-gray-800 dark:border-gray-700">
            <p class="hidden text-xs text-slate-500 sm:block dark:text-gray-400">Pastikan data sudah akurat sebelum
                memperbarui.</p>
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
                    form="editBarangForm" style="background-image: var(--gradient-brand)" type="submit">
                    Simpan Perubahan
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
        const displayInput = document.getElementById('edit_buy_price_display');
        const hiddenInput = document.getElementById('edit_buy_price');

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

        const sellingDisplayInput = document.getElementById('edit_selling_price_display');
        const sellingHiddenInput = document.getElementById('edit_selling_price');

        if (sellingDisplayInput && sellingHiddenInput) {
            const formatValue = (val) => {
                let cleanVal = val.toString().split('.')[0];
                let value = cleanVal.replace(/\D/g, '');
                return value ? parseInt(value).toLocaleString('en-US') : '';
            };

            sellingDisplayInput.addEventListener('input', function () {
                let rawValue = this.value.replace(/\D/g, '');
                sellingHiddenInput.value = rawValue;
                this.value = formatValue(this.value);
            });

            // Sync display when hidden input is changed programmatically
            const originalValueSetter = Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, 'value').set;
            Object.defineProperty(sellingHiddenInput, 'value', {
                set: function (val) {
                    originalValueSetter.call(this, val);
                    sellingDisplayInput.value = formatValue(val.toString());
                },
                get: function () {
                    return Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, 'value').get.call(this);
                },
                configurable: true
            });
        }
    });

    window.CSRF_TOKEN = "{{ csrf_token() }}";
    window.CHECK_KODE_BARANG_URL = "{{ route('check.kode.barang') }}";
</script>

@vite(['resources/js/checker.js'])