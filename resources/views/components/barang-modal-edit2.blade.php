<!-- Modal Edit Barang -->
<div x-data="editBarangModal()" x-cloak x-show="isOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="isOpen" x-on:keydown.esc.window="close()" x-on:click.self="close()"
    @open-edit-modal.window="open($event.detail)" class="z-100 fixed inset-0 flex items-end justify-center bg-black/20 p-4 pb-8 backdrop-blur-md sm:items-center lg:p-8" role="dialog" aria-modal="true"
    aria-labelledby="defaultModalTitle">

    <!-- Modal -->
    <div x-show="isOpen" x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity" x-transition:enter-start="opacity-0 -translate-y-8"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="flex w-full max-w-3xl flex-col gap-4 overflow-hidden rounded-xl border border-neutral-300 bg-white text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">

        <div class="flex items-center justify-between rounded-t border-b p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Edit Barang
            </h3>
            <button type="button" @click="close()"
                class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
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
                <input type="hidden" name="id" id="edit_id" x-model="form.edit_id">
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
                    <input type="text" name="kode_barang" id="edit_kode_barang" x-model="form.edit_kode_barang"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                        required>
                </div>
                <div>
                    <label for="edit_nama_barang" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama Barang</label>
                    <input type="text" name="nama_barang" id="edit_nama_barang" x-model="form.edit_nama_barang"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                        required>
                </div>
                <div>
                    <label for="edit_kategori" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                    <input type="text" name="kategori" id="edit_kategori" x-model="form.edit_kategori"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                        required>
                </div>
                <div>
                    <label for="edit_stok" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Stok</label>
                    <input type="number" name="stok" id="edit_stok" x-model="form.edit_stok"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                        required>
                </div>
                <div>
                    <label for="edit_satuan" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Satuan</label>
                    <input type="text" name="satuan" id="edit_satuan" x-model="form.edit_satuan"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                        required>
                </div>
                <div>
                    <label for="edit_lokasi" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Lokasi</label>
                    <input type="text" name="lokasi" id="edit_lokasi" x-model="form.edit_lokasi"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                        required>
                </div>
                <div>
                    <label for="edit_harga" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Harga</label>
                    <input type="number" name="harga" id="edit_harga" x-model="form.edit_harga"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                        required>
                </div>
                <div class="sm:col-span-2">
                    <label for="edit_deskripsi" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                    <textarea name="deskripsi" id="edit_deskripsi" x-model="form.edit_deskripsi"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                        rows="3" placeholder="Deskripsi barang (opsional)"></textarea>
                </div>
                <div class="sm:col-span-2">
                    <label for="edit_gambar" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Gambar
                        Barang</label>
                    <input type="file" name="gambar" id="edit_gambar" x-model="form.edit_gambar"
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

<script>
function editBarangModal() {
    return {
        isOpen: false,
        form: {
            edit_id: '',
            edit_kode_barang: '',
            edit_nama_barang: '',
            edit_kategori: '',
            edit_stok: '',
            edit_satuan: '',
            edit_lokasi: '',
            edit_harga: '',
            edit_deskripsi: ''
        },
        open(data) {
            this.isOpen = true
            this.form = { ...this.form, ...data }
        },
        close() {
            this.isOpen = false
        }
    }
}
</script>
<!-- End Modal Edit Barang -->
