    <!-- Modal tambah barang -->
    <div x-cloak x-show="tambahModalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="tambahModalIsOpen" x-on:keydown.esc.window="tambahModalIsOpen = false"
        x-on:click.self="tambahModalIsOpen = false" class="z-100 fixed inset-0 flex items-end justify-center bg-black/20 p-4 pb-8 backdrop-blur-md sm:items-center lg:p-8" role="dialog" aria-modal="true"
        aria-labelledby="defaultModalTitle">
        <!-- Modal -->
        <div x-show="tambahModalIsOpen" x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity" x-transition:enter-start="opacity-0 -translate-y-8"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="flex h-full w-full max-w-3xl flex-col gap-4 overflow-hidden rounded-xl border border-neutral-300 bg-white text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">

            <div class="flex items-center justify-between rounded-t border-b p-4 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Tambah Barang
                </h3>
                <button type="button" x-on:click="tambahModalIsOpen = false"
                    class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form action="{{ route('warehouse.store') }}" method="POST" class="flex h-full flex-col space-y-4 overflow-auto p-4" enctype="multipart/form-data">
                <div class="mb-4 grid h-full gap-4 overflow-auto sm:grid-cols-2">
                    @csrf
                    <div>
                        <label for="status_listing" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Status Listing</label>
                        <select name="status_listing" id="status_listing"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            required>
                            <option value="listing">Listing</option>
                            <option value="non listing">Non Listing</option>
                        </select>
                    </div>
                    <div>
                        <label for="kode_barang" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kode Barang</label>
                        <input type="text" name="kode_barang" id="kode_barang"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            required>
                    </div>
                    <div>
                        <label for="nama_barang" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama Barang</label>
                        <input type="text" name="nama_barang" id="nama_barang"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            required>
                    </div>
                    <div>
                        <label for="kategori" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                        <input type="text" name="kategori" id="kategori"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            required>
                    </div>
                    <div>
                        <label for="stok" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Stok</label>
                        <input type="number" name="stok" id="stok"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            required>
                    </div>
                    <div>
                        <label for="satuan" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Satuan</label>
                        <input type="text" name="satuan" id="satuan"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            required>
                    </div>
                    <div>
                        <label for="lokasi" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            required>
                    </div>
                    <div>
                        <label for="harga" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Harga</label>
                        <input type="number" name="harga" id="harga"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            required>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="deskripsi" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            rows="3" placeholder="Deskripsi barang (opsional)"></textarea>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="gambar" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Gambar
                            Barang</label>
                        <input type="file" name="gambar" id="gambar"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            accept="image/*">
                    </div>
                </div>

                <button type="submit"
                    class="w-full rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Simpan</button>
            </form>
        </div>
    </div>
    <!-- End Modal tambah barang -->
