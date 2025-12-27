<dialog id="editBarangModalPrimary" class="modal">
    <div
        class="modal-box relative flex h-full w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        <div
            class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">
                Edit Barang
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

        <form id="editBarangForm" method="POST" class="flex h-full flex-col space-y-4 overflow-hidden p-4"
            enctype="multipart/form-data">
            <div class="h-full overflow-auto">
                <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-3">

                    <div class="md:col-span-2">
                        <!-- Gambar Barang -->
                        <div class="md:col-span-1">
                            <div class="mb-4">

                                <label for="edit_gambar"
                                    class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Gambar
                                    Barang
                                </label>
                                <input type="file" name="gambar" id="edit_gambar" class="hidden" accept="image/*" />

                                <div id="edit_gambar_preview"
                                    class="mx-auto mb-4 flex h-48 w-48 cursor-pointer items-center rounded-lg border-2 border-dashed border-gray-400 bg-gray-100 text-center">
                                    {{-- Input Gambar Dari js --}}
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="space-y-4">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" id="edit_id">
                                <div>
                                    <label for="edit_status_listing"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Status
                                        Listing</label>
                                    <select name="status_listing" id="edit_status_listing"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                        <option value="listing">Listing</option>
                                        <option value="non listing">Non Listing</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="edit_kategori"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                                    <select name="kategori" id="edit_kategori"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        data-initial-kategori="{{ $barang->kategori }}"
                                        data-initial-kode="{{ $barang->kode_barang }}" required>
                                        <option value="" disabled>Pilih Kategori</option>
                                        @foreach ($kategoriList as $kategori)
                                            <option value="{{ $kategori }}"
                                                {{ $barang->kategori == $kategori ? 'selected' : '' }}>
                                                {{ $kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="edit_kode_barang"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kode
                                        Barang</label>
                                    <div class="relative">
                                        <input type="text" name="kode_barang" id="edit_kode_barang"
                                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pr-10 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                            readonly>
                                        <button type="button" id="editRefreshKodeBarang"
                                            class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="h-5 w-5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M21 12C21 16.9706 16.9706 21 12 21C9.69494 21 7.59227 20.1334 6 18.7083L3 16M3 12C3 7.02944 7.02944 3 12 3C14.3051 3 16.4077 3.86656 18 5.29168L21 8M3 21V16M3 16H8M21 3V8M21 8H16"
                                                    stroke="#000000" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                    <!-- Warning container untuk kode barang -->
                                    <div id="kode-barang-warning-container"></div>
                                </div>
                                <div>
                                    <label for="edit_nama_barang"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama
                                        Barang</label>
                                    <input type="text" name="nama_barang" id="edit_nama_barang"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label for="edit_stok"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Stok</label>
                                    <input type="number" name="stok" id="edit_stok"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                </div>

                                <div>
                                    <label for="edit_satuan"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Satuan</label>
                                    <input type="text" name="satuan" id="edit_satuan"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                </div>
                                <div>
                                    <label for="edit_lokasi"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Lokasi</label>
                                    <input type="text" name="lokasi" id="edit_lokasi"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                </div>

                                <div>
                                    <label for="edit_harga"
                                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Harga</label>
                                    <input type="number" name="harga" id="edit_harga"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="h-full sm:col-span-1">
                        <label for="edit_deskripsi"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                        <textarea name="deskripsi" id="edit_deskripsi"
                            class="block h-[90%] w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            rows="3" placeholder="Deskripsi barang (opsional)"></textarea>
                    </div>
                </div>

            </div>
            <div class="">
                <button type="submit"
                    class="relative w-full rounded-lg bg-gradient-to-r from-[#225A97] to-[#0D223A] px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-[#225A97] dark:focus:ring-primary-800">Update
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

@vite(['resources/js/checker.js'])
