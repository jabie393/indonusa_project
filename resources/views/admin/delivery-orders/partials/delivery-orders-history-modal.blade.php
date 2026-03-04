<!-- Modal Histori PDO -->
<dialog id="historyModal" class="modal">
    <div class="modal-box max-w-4xl overflow-hidden rounded-2xl bg-white p-0 dark:bg-gray-800">
        <!-- Header -->
        <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 text-white">
            <h3 class="text-xl font-bold">
                Histori Pengiriman - <span id="historyOrderNumber"></span>
            </h3>
            <button type="button" class="btn btn-ghost rounded-lg btn-sm p-2 text-white hover:bg-white/20" data-modal-hide="historyModal">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6">
            <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-700">
                <table class="table-zebra table w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        <tr>
                            <th class="font-bold">Batch</th>
                            <th class="font-bold">Tanggal</th>
                            <th class="font-bold">Item Terkirim</th>
                            <th class="text-right font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody" class="text-gray-600 dark:text-gray-400">
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
