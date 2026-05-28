<dialog id="historyModal" class="modal">
    <div
        class="modal-box relative flex h-full w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[80vh]">
        <header class="relative flex items-center justify-between px-7 py-5 text-white"
            style="background-image: var(--gradient-header)">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold leading-tight">Riwayat Harga Beli</h3>
                    <p class="text-xs text-white/80"><span id="modal_nama_barang"></span> (<span id="modal_kode_barang"></span>)</p>
                </div>
            </div>
            <form method="dialog">
                <button aria-label="Tutup"
                    class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </form>
        </header>

        <div class="flex h-full flex-col space-y-4 overflow-auto p-4">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Tanggal Diterima</th>
                            <th class="px-4 py-3 text-center">Jumlah (Qty)</th>
                            <th class="px-4 py-3 text-right">Harga Beli (Unit Cost)</th>
                            <th class="px-4 py-3">Supplier (GA)</th>
                            <th class="px-4 py-3">Oleh</th>
                        </tr>
                    </thead>
                    <tbody id="history_log_body">
                        {{-- Log data will be appended here via JS --}}
                    </tbody>
                </table>
            </div>

            <div id="no_history_message" class="hidden py-10 text-center text-gray-500">
                Belum ada riwayat harga beli untuk barang ini.
            </div>

            <div id="loading_spinner" class="flex items-center justify-center py-10">
                <div class="h-8 w-8 animate-spin rounded-full border-4 border-blue-600 border-t-transparent"></div>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
