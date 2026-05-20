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
                    <h1 id="editBarangModalTitle" class="text-lg font-semibold leading-tight">Edit Barang</h1>
                    <p class="text-xs text-white/80">Ubah gambar barang</p>
                </div>
            </div>
            <form method="dialog">
                <button aria-label="Tutup" class="rounded-xl p-1.5 transition hover:bg-white/10" type="submit">
                    <svg fill="none" height="20" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
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

            <!-- Left Column: Image Upload Section -->
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500">
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
                    <div class="group relative flex aspect-square cursor-pointer flex-col items-center justify-center overflow-hidden border-2 border-dashed border-slate-200 bg-slate-50 transition hover:border-blue-500 hover:bg-blue-50/30 rounded-2xl"
                        id="edit_image-dropzone">
                        <div class="flex flex-col items-center gap-2 text-slate-400 group-hover:text-blue-500"
                            id="edit_upload-placeholder">
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
                            <p class="text-sm font-semibold text-slate-700">Upload gambar baru</p>
                            <p class="text-xs">PNG, JPG hingga 5MB</p>
                        </div>
                        <img id="edit_image-preview" class="absolute inset-0 h-full w-full object-contain hidden" src="" alt="Preview">
                        <input accept="image/*" name="gambar" id="edit_gambar" class="absolute inset-0 opacity-0 cursor-pointer"
                            type="file" />
                    </div>
                </div>
            </div>

            <!-- Right Column: Detail Information (Read-Only) -->
            <div class="space-y-5">
                <!-- Kode Barang -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500"
                        for="edit_kode_barang">
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
                    <input
                        class="w-full border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm font-mono outline-none transition rounded-2xl dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-not-allowed"
                        id="edit_kode_barang" type="text" readonly />
                </div>

                <!-- Nama Barang -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500"
                        for="edit_nama_barang">
                        <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                            <path d="M12 22V12"></path>
                            <polyline points="3.29 7 12 12 20.71 7"></polyline>
                        </svg>
                        Nama Barang
                    </label>
                    <input
                        class="w-full border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm outline-none transition rounded-2xl dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-not-allowed"
                        id="edit_nama_barang" type="text" readonly />
                </div>

                <!-- Deskripsi -->
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500"
                        for="edit_deskripsi">Deskripsi</label>
                    <textarea
                        class="w-full border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm outline-none transition resize-none rounded-2xl dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-not-allowed"
                        id="edit_deskripsi" rows="4" readonly></textarea>
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
