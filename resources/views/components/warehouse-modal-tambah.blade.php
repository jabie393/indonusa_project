<dialog id="tambahBarang" class="modal">
    <div
        class="modal-box relative flex h-full w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        <div
            class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">
                Tambah Barang
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

        <form action="{{ route('warehouse.store') }}" method="POST"
            class="flex h-full flex-col space-y-4 overflow-auto p-4" enctype="multipart/form-data">
            <div class="h-full overflow-auto">
                <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-3">
                    @csrf
                    <div class="md:col-span-2">
                        <!-- Gambar Barang -->
                        <div class="md:col-span-1">
                            <div class="mb-4">

                                <label for="gambar"
                                    class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Gambar
                                    Barang
                                </label>
                                <input type="file" name="gambar" id="gambar" class="hidden" accept="image/*" />

                                <div id="gambar_preview"
                                    class="mx-auto mb-4 flex h-48 w-48 cursor-pointer items-center rounded-lg border-2 border-dashed border-gray-400 bg-gray-100 text-center">
                                    <input id="gambar" type="file" class="hidden" accept="image/*" />
                                    <label id="gambar_label" for="gambar" class="m-auto cursor-pointer">
                                        <img class="mx-auto hidden h-full max-h-48 max-w-48" id="modified_image" src=""
                                            alt="" />
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="mx-auto mb-4 h-8 w-8 text-gray-700">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                        </svg>
                                        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-700">Upload picture
                                        </h5>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="space-y-4">
                                <div>
                                    <label for="status_listing"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Status
                                        Listing</label>
                                    <select name="status_listing" id="status_listing"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                        <option value="listing">Listing</option>
                                        <option value="non listing">Non Listing</option>
                                    </select>
                                </div>
                                <div class="relative">
                                    <label for="kode_barang"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kode
                                        Barang</label>
                                    <input type="text" name="kode_barang" id="kode_barang"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pr-10 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        readonly>
                                    <button type="button" id="refreshKodeBarang"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="h-5 w-5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4.5 19.5l3-3m0 0l3 3m-3-3v-6m12-3l-3 3m0 0l-3-3m3 3v6M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                                        </svg>
                                    </button>
                                </div>
                                <div>
                                    <label for="nama_barang"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama
                                        Barang</label>
                                    <input type="text" name="nama_barang" id="nama_barang"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                </div>
                                <div>
                                    <label for="kategori"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                                    <select id="kategori" name="kategori"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                                        <option value="" disabled selected>Pilih Kategori</option>
                                        @foreach ($kategoriList as $kategori)
                                            <option value="{{ $kategori }}">{{ $kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-4">

                                <div>
                                    <label for="stok"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Stok</label>
                                    <input type="number" name="stok" id="stok"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                </div>
                                <div>
                                    <label for="satuan"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Satuan</label>
                                    <input type="text" name="satuan" id="satuan"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                </div>
                                <div>
                                    <label for="lokasi"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Lokasi</label>
                                    <input type="text" name="lokasi" id="lokasi"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                </div>
                                <div>
                                    <label for="harga"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Harga</label>
                                    <input type="number" name="harga" id="harga"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="h-full sm:col-span-1">

                        <label for="deskripsi"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi"
                            class="block h-[90%] w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            rows="3" placeholder="Deskripsi barang (opsional)"></textarea>
                    </div>
                </div>
            </div>
            <div class="">

                <button type="submit"
                    class="relative w-full rounded-lg bg-gradient-to-r from-[#225A97] to-[#0D223A] px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Tambah
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

@vite(['resources/js/checker.js'])
