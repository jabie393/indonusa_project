<dialog id="noteModal" class="modal">
    <div
        class="modal-box bg-white dark:bg-gray-800 border dark:border-gray-700 shadow-2xl rounded-2xl p-0 overflow-hidden max-w-md w-full">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#225A97] to-[#0D223A] px-6 py-4 flex items-center justify-between text-white">
            <h3 class="text-xl font-bold flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Alasan Penolakan
            </h3>
            <button id="closeNoteModal" class="hover:bg-white/20 rounded-lg p-1 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div
                class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-4 min-h-[100px]">
                <p id="catatanContent"
                    class="text-gray-700 dark:text-gray-300 text-base leading-relaxed whitespace-pre-line italic">
                    Memuat alasan...
                </p>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop bg-black/40 backdrop-blur-sm">
        <button>close</button>
    </form>
</dialog>
