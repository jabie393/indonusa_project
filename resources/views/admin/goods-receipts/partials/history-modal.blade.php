<dialog id="historyModal" class="modal">
    <div
        class="modal-box relative flex h-full w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[80vh]">
        <div
            class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">
                Riwayat Harga Beli - <span id="modal_nama_barang"></span> (<span id="modal_kode_barang"></span>)
            </h3>
            <form method="dialog">
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
