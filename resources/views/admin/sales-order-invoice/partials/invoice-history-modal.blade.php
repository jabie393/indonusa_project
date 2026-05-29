<!-- Modal Histori Invoice -->
<dialog id="invoiceHistoryModal" class="modal">
    <div class="modal-box max-w-4xl overflow-hidden rounded-2xl bg-white p-0 dark:bg-gray-800">
        <!-- Header -->
        <header class="relative flex items-center justify-between px-7 py-5 text-white"
            style="background-image: var(--gradient-header)">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold leading-tight">Histori Invoice</h3>
                    <p class="text-xs text-white/80">No. SO: <span id="historySalesOrderNumber"></span></p>
                </div>
            </div>
            <button type="button" aria-label="Tutup"
                class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
                data-modal-hide="invoiceHistoryModal">
                <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </header>

        <!-- Body -->
        <div class="p-6">
            <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-700">
                <table class="table-zebra table w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        <tr>
                            <th class="font-bold">Batch</th>
                            <th class="font-bold">Tanggal</th>
                            <th class="font-bold">Item Terkirim</th>
                            <th class="text-right font-bold">Action</th>
                        </tr>
                    </thead>
                    <tbody id="invoiceHistoryTableBody" class="text-gray-600 dark:text-gray-400">
                        <!-- Data will be populated by JS -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Backdrop shadow effect is handled by DaisyUI dialog naturally, but adding a form button for keyboard escape -->
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </div>
</dialog>
