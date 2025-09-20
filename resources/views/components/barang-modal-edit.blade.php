    <!-- Modal Edit Barang -->
    <div id="editBarangModal" tabindex="-1" aria-hidden="true"
        class="h-modal fixed left-0 right-0 top-0 z-50 hidden w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0 md:h-full">
        <div class="relative mx-auto max-h-full w-full max-w-2xl">
            <div class="relative rounded-lg bg-white shadow dark:bg-gray-700">
                <div class="flex items-center justify-between rounded-t border-b p-4 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Edit Barang
                    </h3>
                    <button type="button"
                        class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="editBarangModal">
                        <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form id="editBarangForm" method="POST" class="space-y-4 p-4" enctype="multipart/form-data">
                    <div class="mb-4 grid gap-4 sm:grid-cols-2">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="edit_id">
                        <div>
                            <label for="edit_status_listing" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Status Listing</label>
                            <select name="status_listing" id="edit_status_listing"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                required>
                                <option value="listing">Listing</option>
                                <option value="non listing">Non Listing</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit_kode_barang" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kode Barang</label>
                            <input type="text" name="kode_barang" id="edit_kode_barang"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                required>
                        </div>
                        <div>
                            <label for="edit_nama_barang" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama Barang</label>
                            <input type="text" name="nama_barang" id="edit_nama_barang"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                required>
                        </div>
                        <div>
                            <label for="edit_kategori" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                            <input type="text" name="kategori" id="edit_kategori"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                required>
                        </div>
                        <div>
                            <label for="edit_stok" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Stok</label>
                            <input type="number" name="stok" id="edit_stok"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                required>
                        </div>
                        <div>
                            <label for="edit_satuan" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Satuan</label>
                            <input type="text" name="satuan" id="edit_satuan"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                required>
                        </div>
                        <div>
                            <label for="edit_lokasi" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Lokasi</label>
                            <input type="text" name="lokasi" id="edit_lokasi"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                required>
                        </div>
                        <div>
                            <label for="edit_harga" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Harga</label>
                            <input type="number" name="harga" id="edit_harga"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                required>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="edit_deskripsi" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                            <textarea name="deskripsi" id="edit_deskripsi"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                rows="3" placeholder="Deskripsi barang (opsional)"></textarea>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="edit_gambar" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Gambar
                                Barang</label>
                            <input type="file" name="gambar" id="edit_gambar"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                accept="image/*">
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Update</button>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Edit Barang -->
