<dialog id="importExcel" class="modal">
    <div class="modal-box relative flex h-fit w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        <div class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">
                Import Dari Excel
            </h3>
            <div class="modal-action m-0">
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
        </div>

        <form action="{{ route('goods-in.store') }}" method="POST" class="flex h-fit flex-col space-y-4 overflow-auto p-4" enctype="multipart/form-data">
            <div class="h-full overflow-auto">
                <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div class="col-span-3">
                        <div class="mb-4 w-full">
                            <label for="gambar" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                                File Excel
                            </label>
                            <input type="file" name="excel" id="excel" class="hidden" accept=".xlsx,.xls" />

                            <div id="upload-area" class="mx-auto mb-4 flex h-48 w-full cursor-pointer items-center rounded-2xl border-2 border-dashed border-gray-400 bg-gray-100 text-center">
                                <label id="excel_label" for="excel" class="m-auto w-full cursor-pointer">
                                    <div id="excel_filename" class="mx-auto hidden text-sm text-gray-700"></div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-auto mb-4 h-8 w-8 text-gray-700">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                    </svg>
                                    <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-700">Upload File
                                    </h5>
                                    <p class="text-gray-500">Support Format .Excel</p>
                                </label>
                            </div>
                        </div>
                        <!-- Progress bar section - hidden by default -->
                        <div id="progress-section" class="mb-4 hidden">
                            <div class="mb-2 flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Uploading...</span>
                                <span id="progress-text" class="text-sm font-medium text-gray-700 dark:text-gray-300">0%</span>
                            </div>
                            <div class="h-3 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                                <div id="progress-bar" class="h-3 rounded-full bg-gradient-to-r from-[#225A97] to-[#0D223A] transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>
                        <!-- Upload result: show stored path and public URL -->
                        <div id="upload-result" class="mt-3 hidden">
                            <div class="text-sm text-gray-700 dark:text-gray-300">File tersimpan di: <span id="upload-path" class="font-mono text-xs text-gray-800 dark:text-gray-100"></span></div>
                            <div class="mt-1 text-sm"><a id="upload-url" class="text-blue-600 hover:underline" target="_blank" rel="noopener">Buka file</a></div>
                        </div>
                    </div>
                    <div class="col-span-3">
                        <label for="" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Map
                        </label>
                        <div>

                        </div>
                    </div>

                </div>
                <div class="table-responsive mb-3">
                    <table class="table-bordered table" id="DataTableExcel">
                        <thead class="table-light">
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Satuan</th>
                                <th>Status Listing</th>
                                <th>Lokasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="overflow-x-scroll">
                            <tr>
                                <td>
                                    <input type="text"
                                        class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                </td>
                                <td>
                                    <input type="text"
                                        class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                </td>
                                <td>
                                    <input type="text"
                                        class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                </td>
                                <td>
                                    <input type="text"
                                        class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                </td>
                                <td>
                                    <input type="text"
                                        class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                </td>
                                <td>
                                    <input type="text"
                                        class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                </td>
                                <td>
                                    <input type="text"
                                        class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                </td>
                                <td>
                                    <input type="text"
                                        class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                </td>
                                <td>
                                    <button type="button" class="btn remove-row rounded-md bg-red-500 text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 h-4 w-4">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text"
                                        class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                </td>
                                <td>
                                    <input type="text"
                                        class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                </td>
                                <td>
                                    <input type="text"
                                        class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                </td>
                                <td>
                                    <input type="text"
                                        class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                </td>
                                <td>
                                    <input type="text"
                                        class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                </td>
                                <td>
                                    <input type="text"
                                        class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                </td>
                                <td>
                                    <input type="text"
                                        class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                </td>
                                <td>
                                    <input type="text"
                                        class="form-control barang-nama-display block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                </td>
                                <td>
                                    <button type="button" class="btn remove-row rounded-md bg-red-500 text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 h-4 w-4">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                            <line x1="10" x2="10" y1="11" y2="17"></line>
                                            <line x1="14" x2="14" y1="11" y2="17"></line>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="">

                <button type="submit"
                    class="submit-btn relative w-full rounded-lg bg-gradient-to-r from-[#225A97] to-[#0D223A] px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Tambah
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    window.CSRF_TOKEN = "{{ csrf_token() }}";
    window.CHECK_KODE_BARANG_URL = "{{ route('check.kode.barang') }}";
</script>

@vite(['resources/js/checker.js', 'resources/js/excel-upload.js'])
