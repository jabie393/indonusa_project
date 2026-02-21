<dialog id="editBarangModal" class="modal">
    <div
        class="modal-box relative flex h-full w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        <div
            class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">
                Ajukan Barang Rusak (Defect)
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
                                    <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kode
                                        Barang</label>
                                    <input type="text" id="edit_kode_barang"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-100 p-2.5 text-sm text-gray-900 dark:border-gray-500 dark:bg-gray-600 dark:text-white"
                                        readonly>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama
                                        Barang</label>
                                    <input type="text" id="edit_nama_barang"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-100 p-2.5 text-sm text-gray-900 dark:border-gray-500 dark:bg-gray-600 dark:text-white"
                                        readonly>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Stok
                                        Saat Ini</label>
                                    <input type="text" id="edit_stok"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-100 p-2.5 text-sm text-gray-900 dark:border-gray-500 dark:bg-gray-600 dark:text-white"
                                        readonly>
                                </div>
                                <div>
                                    <label for="stok_diajukan"
                                        class="mb-2 block text-sm font-bold text-primary-600 dark:text-primary-400">Jumlah
                                        Stok yang Diajukan Rusak</label>
                                    <input type="number" name="stok_diajukan" id="stok_diajukan"
                                        class="block w-full rounded-lg border-2 border-primary-500 bg-white p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:bg-gray-800 dark:text-white"
                                        placeholder="Jumlah unit..." required min="1">
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Harga
                                        Saat Ini</label>
                                    <input type="text" id="edit_harga_tampil"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-100 p-2.5 text-sm text-gray-900 dark:border-gray-500 dark:bg-gray-600 dark:text-white"
                                        readonly>
                                </div>
                                <div>
                                    <label for="harga_diajukan"
                                        class="mb-2 block text-sm font-bold text-primary-600 dark:text-primary-400">Harga
                                        yang
                                        Diajukan (Special Price for Defect)</label>
                                    <input type="number" name="harga_diajukan" id="harga_diajukan"
                                        class="block w-full rounded-lg border-2 border-primary-500 bg-white p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:bg-gray-800 dark:text-white"
                                        placeholder="Harga baru..." required>
                                </div>
                                <div>
                                    <label for="alasan_pengajuan"
                                        class="mb-2 block text-sm font-bold text-primary-600 dark:text-primary-400">Alasan
                                        Pengajuan</label>
                                    <textarea name="alasan_pengajuan" id="alasan_pengajuan" rows="3"
                                        class="block w-full rounded-lg border-2 border-primary-500 bg-white p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:bg-gray-800 dark:text-white"
                                        placeholder="Jelaskan alasan pengajuan barang rusak..." required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="h-full sm:col-span-1">
                        <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                        <textarea id="edit_deskripsi"
                            class="block h-[90%] w-full rounded-lg border border-gray-300 bg-gray-100 p-2.5 text-sm text-gray-900 dark:border-gray-500 dark:bg-gray-600 dark:text-white"
                            rows="3" readonly></textarea>
                    </div>
                </div>

            </div>
            <div class="">
                <button type="submit"
                    class="relative w-full rounded-lg bg-gradient-to-r from-[#225A97] to-[#0D223A] px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-[#225A97] dark:focus:ring-primary-800">Ajukan
                    Perubahan Harga & Laporkan Rusak
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
