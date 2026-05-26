<style>
    :root {
        --gradient-header: linear-gradient(to right, #225A97, #0D223A);
        --gradient-brand: linear-gradient(to right, #225A97, #0D223A);
        --field: #f8fafc;
        --shadow-soft: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
    }
</style>

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
                        <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                        <path d="M12 22V12"></path>
                        <polyline points="3.29 7 12 12 20.71 7"></polyline>
                        <path d="m7.5 4.27 9 5.15"></path>
                    </svg>
                </div>
                <div>
                    <h1 id="editBarangModalTitle" class="text-lg font-semibold leading-tight">Edit Gambar Barang</h1>
                    <p id="edit_subtitle" class="text-xs text-white/80">Ubah gambar barang</p>
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

        <!-- Form Content -->
            <form id="editBarangForm" method="POST" enctype="multipart/form-data"
            class="grid flex-1 gap-8 overflow-y-auto p-7 md:grid-cols-[300px_1fr]">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit_id">

            <!-- Left Column: Image Upload Section -->
            <div class="flex flex-col justify-between items-start h-full">
                <div>
                    <label class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500 mt-1 mb-2">
                        <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M16 5h6"></path>
                            <path d="M19 2v6"></path>
                            <path d="M21 11.5V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7.5"></path>
                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                            <circle cx="9" cy="9" r="2"></circle>
                        </svg>
                        Gambar Barang
                    </label>
                    <div id="edit_image-dropzone" class="group relative aspect-square w-full md:w-[280px] max-w-full flex items-center justify-center overflow-hidden border-2 border-dashed border-slate-200 bg-slate-50 transition hover:border-blue-500 hover:bg-blue-50/30 rounded-2xl p-2 cursor-pointer">
                        <div id="edit_upload-placeholder" class="flex h-full w-full flex-col items-center justify-center text-slate-300 z-10">
                            <svg class="h-16 w-16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                <circle cx="9" cy="9" r="2"></circle>
                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                            </svg>
                            <p class="text-sm font-semibold text-slate-700">Upload gambar baru</p>
                            <p class="text-xs text-slate-500">PNG, JPG hingga 5MB</p>
                        </div>
                        <img id="edit_image-preview" class="h-full w-full object-contain hidden" src="" alt="Preview">
                        <input accept="image/*" name="gambar" id="edit_gambar" class="absolute inset-0 opacity-0 cursor-pointer z-50" type="file" />
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4 mt-1 md:mt-0 w-full md:w-[280px] md:ml-0">
                    <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Status Listing</label>
                    <p class="text-sm font-medium text-slate-700" id="edit_status">-</p>
                </div>
            </div>

            <!-- Right Column: Detail Information (Read-Only, display-only) -->
            <div class="flex flex-col justify-between h-full">
                <div>
                    <div class="space-y-5">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama Barang</label>
                            <div class="text-lg font-bold text-slate-800" id="edit_goods_name">-</div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi</label>
                            <div class="min-h-[60px] text-sm leading-relaxed text-slate-600 italic" id="edit_deskripsi">-</div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Kode Barang</label>
                            <p class="text-sm font-mono font-bold text-slate-800" id="edit_goods_code">-</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Kategori</label>
                            <p class="text-sm font-medium text-slate-700" id="edit_kategori">-</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Stok Saat Ini</label>
                            <p class="text-sm font-bold text-blue-600" id="edit_stock">-</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Satuan</label>
                            <p class="text-sm font-medium text-slate-700" id="edit_unit">-</p>
                        </div>
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                            <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-400">Lokasi</label>
                            <p class="text-sm font-medium text-slate-700" id="edit_location">-</p>
                        </div>
                        <!-- Harga Jual intentionally removed for edit modal -->
                    </div>
                </div>
            </div>
        </form>

        <!-- Footer -->
        <footer class="flex items-center justify-between gap-3 border-t border-slate-100 bg-slate-50 px-7 py-5 dark:bg-gray-800 dark:border-gray-700">
            <p class="hidden text-xs text-slate-500 sm:block dark:text-gray-400">Pastikan data sudah benar sebelum menyimpan.</p>
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
</dialog>

<script>
    window.CSRF_TOKEN = "{{ csrf_token() }}";
    window.CHECK_KODE_BARANG_URL = "{{ route('check.kode.barang') }}";
</script>

@vite(['resources/js/checker.js'])

<script>
    function openEditModal(data) {
        function setText(id, value) {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        }

        // fill header title (keep main title), subtitle and hidden id
        const titleEl = document.getElementById('editBarangModalTitle');
        if (titleEl) titleEl.textContent = 'Edit Gambar Barang';
        const subtitleEl = document.getElementById('edit_subtitle');
        if (subtitleEl) subtitleEl.textContent = (data.nama ? data.nama : '') + (data.kode ? ' (' + data.kode + ')' : '');
        const idInput = document.getElementById('edit_id');
        if (idInput) idInput.value = data.id || '';

        // basic fields (display-only)
        setText('edit_goods_name', data.nama || '-');
        setText('edit_goods_code', data.kode || '-');
        setText('edit_deskripsi', data.deskripsi || '-');
        setText('edit_kategori', data.kategori || '-');
        setText('edit_stock', data.stok !== undefined ? data.stok : '-');
        setText('edit_unit', data.satuan || '-');
        setText('edit_location', data.lokasi || '-');
        setText('edit_status', data.status || data.status_listing || '-');

        // image handling
        const img = document.getElementById('edit_image-preview');
        const placeholder = document.getElementById('edit_upload-placeholder');
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

        // open modal
        const modal = document.getElementById('editBarangModal');
        if (modal && typeof modal.showModal === 'function') modal.showModal();
    }

    window.openEditModal = openEditModal;
</script>

<script>
    // preview selected image immediately in edit modal
    (function () {
        const input = document.getElementById('edit_gambar');
        const img = document.getElementById('edit_image-preview');
        const placeholder = document.getElementById('edit_upload-placeholder');

        if (input) {
            input.addEventListener('change', function (e) {
                const file = (e.target.files && e.target.files[0]) || null;
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function (ev) {
                    if (img && placeholder) {
                        img.src = ev.target.result;
                        img.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            });
        }
    })();
</script>
