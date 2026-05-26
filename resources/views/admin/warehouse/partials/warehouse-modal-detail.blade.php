<style>
    :root {
        --gradient-header: linear-gradient(to right, #225A97, #0D223A);
        --gradient-brand: linear-gradient(to right, #225A97, #0D223A);
    }
</style>

<dialog id="detailBarangModal" class="modal">
    <div class="modal-box relative flex w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-800 max-h-[95vh]">
        <header class="relative flex items-center justify-between px-7 py-5 text-white" style="background-image: var(--gradient-header)">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                    <svg class="h-5 w-5" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                        <path d="M12 22V12"></path>
                        <polyline points="3.29 7 12 12 20.71 7"></polyline>
                        <path d="m7.5 4.27 9 5.15"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold leading-tight">Detail Barang</h3>
                    <p class="text-xs text-white/80" id="detail_subtitle">&nbsp;</p>
                </div>
            </div>
            <form method="dialog">
                <button aria-label="Tutup" class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </form>
        </header>

        <div class="flex-1 overflow-y-auto p-7">
            <div class="grid gap-6 h-full items-stretch md:grid-cols-[300px_1fr]">
                <div class="flex flex-col justify-between items-start h-full">
                    <div>
                        <label class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-400 mt-1 mb-2">Gambar Barang</label>
                        <div id="detail_image_container" class="group relative aspect-square w-full md:w-[280px] max-w-full flex items-center justify-center overflow-hidden border-2 border-dashed border-slate-200 bg-slate-50 transition hover:border-blue-500 hover:bg-blue-50/30 rounded-2xl p-2">
                            <img id="detail_image" src="" alt="Gambar Barang" class="h-full w-full object-contain hidden" />
                            <div id="detail_image_placeholder" class="flex h-full w-full items-center justify-center text-slate-300 z-10">
                                <svg class="h-16 w-16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                    <circle cx="9" cy="9" r="2"></circle>
                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4 mt-1 md:mt-0 w-full md:w-[280px] md:ml-0">
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Status Listing</label>
                        <p class="text-sm font-medium text-slate-700" id="detail_status">-</p>
                    </div>
                </div>

                <div class="flex flex-col justify-between h-full">
                    <div>
                        <div class="space-y-5">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama Barang</label>
                                <div class="text-lg font-bold text-slate-800" id="detail_nama_display">-</div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi</label>
                                <div class="min-h-[60px] text-sm leading-relaxed text-slate-600 italic" id="detail_deskripsi">-</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Kode Barang</label>
                                <p class="text-sm font-mono font-bold text-slate-800" id="detail_kode_display">-</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Kategori</label>
                                <p class="text-sm font-medium text-slate-700" id="detail_kategori">-</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Stok Saat Ini</label>
                                <p class="text-sm font-bold text-blue-600" id="detail_stock">-</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Satuan</label>
                                <p class="text-sm font-medium text-slate-700" id="detail_unit">-</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Lokasi</label>
                                <p class="text-sm font-medium text-slate-700" id="detail_location">-</p>
                            </div>
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                                <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Harga Jual</label>
                                <p class="text-sm font-medium text-slate-700" id="detail_selling_price">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50 px-7 py-5 dark:bg-gray-800 dark:border-gray-700">
            <form method="dialog">
                <button class="px-6 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-200 active:scale-95 rounded-xl dark:text-gray-300 dark:hover:bg-gray-700" type="submit">Tutup</button>
            </form>
        </footer>
    </div>
</dialog>

<script>
    function openDetailModal(data) {
        // helper to set text safely
        function setText(id, value) {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        }

        // subtitle
        const subtitleEl = document.getElementById('detail_subtitle');
        if (subtitleEl) subtitleEl.textContent = (data.nama ? data.nama : '') + (data.kode ? ' (' + data.kode + ')' : '');

        // basic fields (guarded)
        setText('detail_nama', data.nama || '');
        setText('detail_kode', data.kode || '');
        setText('detail_nama_display', data.nama || '-');
        setText('detail_deskripsi', data.deskripsi || '-');
        setText('detail_kategori', data.kategori || '-');
        setText('detail_status', data.status || data.status_listing || '-');
        setText('detail_stock', data.stok !== undefined ? data.stok : '-');
        setText('detail_unit', data.satuan || '-');
        setText('detail_location', data.lokasi || '-');
        setText('detail_kode_display', data.kode || '-');

        // prices (formatted)
        const formattedSelling = data.selling_price
            ? new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(data.selling_price)
            : '-';
        setText('detail_selling_price', formattedSelling);

        // image handling
        const img = document.getElementById('detail_image');
        const placeholder = document.getElementById('detail_image_placeholder');
        if (img && placeholder) {
            if (data.gambar) {
                img.src = 'files/' + data.gambar;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                img.src = '';
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        }

        // show modal
        const modal = document.getElementById('detailBarangModal');
        if (modal && typeof modal.showModal === 'function') modal.showModal();
    }

    window.openDetailModal = openDetailModal;
</script>
