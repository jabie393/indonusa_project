<dialog id="editBarangModalNewStock" class="modal">
    <div class="modal-box relative mx-auto max-h-full w-full max-w-3xl p-0">
        <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
            <!-- Header Modal -->
            <div class="flex items-center justify-between rounded-t border-b p-4 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Detail Barang
                </h3>
                <button type="button"
                    class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Tutup modal</span>
                </button>
            </div>

            <!-- Konten Detail Barang -->
            <div class="p-6">
                <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <!-- Gambar Barang -->
                    <div class="md:col-span-1">
                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Gambar
                                Barang</label>
                            <div id="edit_gambar_preview" class="mt-2 flex justify-center">
                                <div class="flex h-48 w-48 items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-800">
                                    <span class="text-gray-500 dark:text-gray-400">Gambar tidak tersedia</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Barang -->
                    <div class="md:col-span-2">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="space-y-4">
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Kode
                                        Barang</label>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white" id="edit_kode_barang">-</div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Nama
                                        Barang</label>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white" id="edit_nama_barang">-</div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Kategori</label>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white" id="edit_kategori">
                                        -</div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Lokasi</label>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white" id="edit_lokasi">-
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Status
                                        Listing</label>
                                    <span id="edit_status_listing" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                                        <!-- Status akan ditampilkan dengan warna yang sesuai -->
                                    </span>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Harga</label>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white" id="edit_harga">-
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Satuan</label>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white" id="edit_satuan">-
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Stok
                                        Saat Ini</label>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white" id="current_stok">-
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi Barang -->
                        <div class="mt-6">
                            <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Deskripsi</label>
                            <div class="text-sm text-gray-900 dark:text-white" id="edit_deskripsi">-</div>
                        </div>
                    </div>
                </div>

                <!-- Form Edit Stok -->
                <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-600 dark:bg-gray-800">
                    <h4 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Update Stok Barang</h4>
                    <form id="tambahStockForm" method="POST" class="space-y-4" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="edit_id">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div>
                                <label for="edit_stok" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Stok
                                    Baru</label>
                                <input type="number" name="stok" id="edit_stok"
                                    class="block w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                                    required min="0">
                            </div>
                            <div class="flex items-end">
                                <button type="submit"
                                    class="w-full rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Update
                                    Stok</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
