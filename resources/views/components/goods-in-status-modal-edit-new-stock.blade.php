<dialog id="editBarangModalNewStock" class="modal">
    <div class="modal-box relative flex h-full w-full max-w-5xl flex-col overflow-hidden rounded-lg bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        <div class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0B1D31] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">
                Detail Barang
            </h3>
            <form method="dialog">
                <!-- if there is a button in form, it will close the modal -->
                <button
                    class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </form>
        </div>

        <!-- Konten Detail Barang -->
        <div class="flex h-full flex-col space-y-4 overflow-auto p-4">
            <div class="h-full overflow-auto">

                <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div class="md:col-span-2">
                        <!-- Gambar Barang -->
                        <div class="md:col-span-1">
                            <div class="mb-4">
                                <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Gambar
                                    Barang
                                </label>
                                <div id="edit_gambar_preview_new_stock" class="mt-2 flex justify-center">
                                    <svg fill="#a1a1a1" height="200px" width="200px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        viewBox="0 0 60 60" xml:space="preserve" stroke="#a1a1a1">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <g>
                                                <path
                                                    d="M47,31c-7.168,0-13,5.832-13,13s5.832,13,13,13s13-5.832,13-13S54.168,31,47,31z M47,55c-6.065,0-11-4.935-11-11 s4.935-11,11-11s11,4.935,11,11S53.065,55,47,55z">
                                                </path>
                                                <path
                                                    d="M51.95,39.051c-0.391-0.391-1.023-0.391-1.414,0L47,42.586l-3.536-3.535c-0.391-0.391-1.023-0.391-1.414,0 s-0.391,1.023,0,1.414L45.586,44l-3.536,3.535c-0.391,0.391-0.391,1.023,0,1.414c0.195,0.195,0.451,0.293,0.707,0.293 s0.512-0.098,0.707-0.293L47,45.414l3.536,3.535c0.195,0.195,0.451,0.293,0.707,0.293s0.512-0.098,0.707-0.293 c0.391-0.391,0.391-1.023,0-1.414L48.414,44l3.536-3.535C52.34,40.074,52.34,39.441,51.95,39.051z">
                                                </path>
                                                <path d="M46.201,28.041l-6.138-5.626l-9.181,10.054l2.755,2.755C36.363,31.088,40.952,28.302,46.201,28.041z"></path>
                                                <path
                                                    d="M23.974,28.389L7.661,42.751C7.471,42.918,7.235,43,7,43c-0.277,0-0.553-0.114-0.751-0.339 c-0.365-0.415-0.325-1.047,0.09-1.412l17.017-14.982c0.396-0.348,0.994-0.329,1.368,0.044l4.743,4.743l9.794-10.727 c0.179-0.196,0.429-0.313,0.694-0.325c0.264-0.006,0.524,0.083,0.72,0.262l8.646,7.925c3.338,0.489,6.34,2.003,8.678,4.223V4 c0-0.553-0.448-1-1-1H1C0.448,3,0,3.447,0,4v44c0,0.553,0.448,1,1,1h30.811C31.291,47.425,31,45.747,31,44 c0-2.5,0.593-4.858,1.619-6.967L23.974,28.389z M16,14c3.071,0,5.569,2.498,5.569,5.569c0,3.07-2.498,5.568-5.569,5.568 s-5.569-2.498-5.569-5.568C10.431,16.498,12.929,14,16,14z">
                                                </path>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Barang -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="space-y-4 sm:place-items-end">
                                <div class="w-50 rounded-xl border">
                                    <div class="px-4 py-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="rounded-xl bg-gradient-to-r from-[#225A97] to-[#0B1D31] p-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                    <path
                                                        d="M12.58 5.8L11.82 9.4H14.58V11H11.48L10.62 15H8.88L9.74 11H5.54L4.68 15H2.94L3.8 11H0.66V9.4H4.14L4.9 5.8H1.8V4.2H5.24L6.1 0.2H7.84L6.98 4.2H11.18L12.04 0.2H13.78L12.92 4.2H15.7V5.8H12.58ZM10.84 5.8H6.64L5.88 9.4H10.08L10.84 5.8Z"
                                                        fill="white" />
                                                </svg>
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Kode
                                                    Barang
                                                </label>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white" id="edit_kode_barang_new_stock">-</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-50 rounded-xl border">
                                    <div class="px-4 py-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="rounded-xl bg-gradient-to-r from-[#225A97] to-[#0B1D31] p-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                    <path
                                                        d="M12.58 5.8L11.82 9.4H14.58V11H11.48L10.62 15H8.88L9.74 11H5.54L4.68 15H2.94L3.8 11H0.66V9.4H4.14L4.9 5.8H1.8V4.2H5.24L6.1 0.2H7.84L6.98 4.2H11.18L12.04 0.2H13.78L12.92 4.2H15.7V5.8H12.58ZM10.84 5.8H6.64L5.88 9.4H10.08L10.84 5.8Z"
                                                        fill="white" />
                                                </svg>
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Nama
                                                    Barang</label>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white" id="edit_nama_barang_new_stock">-</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-50 rounded-xl border">
                                    <div class="px-4 py-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="rounded-xl bg-gradient-to-r from-[#225A97] to-[#0B1D31] p-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                    <path
                                                        d="M12.58 5.8L11.82 9.4H14.58V11H11.48L10.62 15H8.88L9.74 11H5.54L4.68 15H2.94L3.8 11H0.66V9.4H4.14L4.9 5.8H1.8V4.2H5.24L6.1 0.2H7.84L6.98 4.2H11.18L12.04 0.2H13.78L12.92 4.2H15.7V5.8H12.58ZM10.84 5.8H6.64L5.88 9.4H10.08L10.84 5.8Z"
                                                        fill="white" />
                                                </svg>
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Kategori</label>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white" id="edit_kategori_new_stock">
                                                    -</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-50 rounded-xl border">
                                    <div class="px-4 py-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="rounded-xl bg-gradient-to-r from-[#225A97] to-[#0B1D31] p-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                    <path
                                                        d="M12.58 5.8L11.82 9.4H14.58V11H11.48L10.62 15H8.88L9.74 11H5.54L4.68 15H2.94L3.8 11H0.66V9.4H4.14L4.9 5.8H1.8V4.2H5.24L6.1 0.2H7.84L6.98 4.2H11.18L12.04 0.2H13.78L12.92 4.2H15.7V5.8H12.58ZM10.84 5.8H6.64L5.88 9.4H10.08L10.84 5.8Z"
                                                        fill="white" />
                                                </svg>
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Lokasi</label>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white" id="edit_lokasi_new_stock">-
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="w-50 rounded-xl border">
                                    <div class="px-4 py-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="rounded-xl bg-gradient-to-r from-[#225A97] to-[#0B1D31] p-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                    <path
                                                        d="M12.58 5.8L11.82 9.4H14.58V11H11.48L10.62 15H8.88L9.74 11H5.54L4.68 15H2.94L3.8 11H0.66V9.4H4.14L4.9 5.8H1.8V4.2H5.24L6.1 0.2H7.84L6.98 4.2H11.18L12.04 0.2H13.78L12.92 4.2H15.7V5.8H12.58ZM10.84 5.8H6.64L5.88 9.4H10.08L10.84 5.8Z"
                                                        fill="white" />
                                                </svg>
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Status
                                                    Listing</label>
                                                <div id="edit_status_listing_new_stock" class="text-sm font-semibold text-gray-900 dark:text-white">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-50 rounded-xl border">
                                    <div class="px-4 py-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="rounded-xl bg-gradient-to-r from-[#225A97] to-[#0B1D31] p-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                    <path
                                                        d="M12.58 5.8L11.82 9.4H14.58V11H11.48L10.62 15H8.88L9.74 11H5.54L4.68 15H2.94L3.8 11H0.66V9.4H4.14L4.9 5.8H1.8V4.2H5.24L6.1 0.2H7.84L6.98 4.2H11.18L12.04 0.2H13.78L12.92 4.2H15.7V5.8H12.58ZM10.84 5.8H6.64L5.88 9.4H10.08L10.84 5.8Z"
                                                        fill="white" />
                                                </svg>
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Harga</label>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white" id="edit_harga_new_stock">-
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-50 rounded-xl border">
                                    <div class="px-4 py-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="rounded-xl bg-gradient-to-r from-[#225A97] to-[#0B1D31] p-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                    <path
                                                        d="M12.58 5.8L11.82 9.4H14.58V11H11.48L10.62 15H8.88L9.74 11H5.54L4.68 15H2.94L3.8 11H0.66V9.4H4.14L4.9 5.8H1.8V4.2H5.24L6.1 0.2H7.84L6.98 4.2H11.18L12.04 0.2H13.78L12.92 4.2H15.7V5.8H12.58ZM10.84 5.8H6.64L5.88 9.4H10.08L10.84 5.8Z"
                                                        fill="white" />
                                                </svg>
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Satuan</label>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white" id="edit_satuan_new_stock">-
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-50 rounded-xl border">
                                    <div class="px-4 py-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="rounded-xl bg-gradient-to-r from-[#225A97] to-[#0B1D31] p-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                    <path
                                                        d="M12.58 5.8L11.82 9.4H14.58V11H11.48L10.62 15H8.88L9.74 11H5.54L4.68 15H2.94L3.8 11H0.66V9.4H4.14L4.9 5.8H1.8V4.2H5.24L6.1 0.2H7.84L6.98 4.2H11.18L12.04 0.2H13.78L12.92 4.2H15.7V5.8H12.58ZM10.84 5.8H6.64L5.88 9.4H10.08L10.84 5.8Z"
                                                        fill="white" />
                                                </svg>
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Stok
                                                    Saat Ini</label>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white" id="current_stok_new_stock">-
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi Barang -->
                    <div class="mt-6">
                        <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Deskripsi</label>
                        <div class="text-sm text-gray-900 dark:text-white bg-[#6B728033] border border-black rounded-md p-5" id="edit_deskripsi_new_stock">-</div>
                    </div>
                </div>
            </div>

            <!-- Form Edit Stok -->
            <div class="mt-6 rounded-lg border border-gray-200 bg-gradient-to-r from-[#225A97] to-[#0B1D31] p-5 text-white">
                <form id="tambahStockForm" method="POST" class="space-y-4" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_id_new_stock">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <label for="edit_stok" class="mb-2 block text-sm font-medium text-white">Request Stok
                                Baru</label>
                            <input type="number" name="stok" id="edit_stok_new_stock"
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
