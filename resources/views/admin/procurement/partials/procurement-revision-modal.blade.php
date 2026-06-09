<!-- Revision Modal -->
<dialog id="modalRevisiReceipt" class="modal">
    <div class="modal-box w-full max-w-md overflow-hidden rounded-2xl bg-white p-0 shadow-2xl ring-1 ring-black/5 dark:bg-gray-800 animate-zoom-in">
        {{-- Header --}}
        <header class="relative flex items-center justify-between px-7 py-5 text-white"
            style="background-image: linear-gradient(135deg, #225A97 0%, #0D223A 100%)">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold leading-tight">Revisi Kedatangan Barang</h3>
                    <p class="text-xs text-white/80"><span id="revisionGoodsName">&nbsp;</span></p>
                </div>
            </div>
            <form method="dialog">
                <button type="button" onclick="closeRevisionModal()"
                    class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </form>
        </header>

        {{-- Body --}}
        <form id="formRevisiReceipt" method="POST" action="">
            @csrf
            <div class="px-7 py-6">
                <!-- Qty Input -->
                <div class="mb-5">
                    <label class="mb-2 block text-xs font-bold uppercase tracking-widest text-gray-400">
                        Kuantitas Datang <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" id="revisionQuantity" name="quantity" required min="1"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm transition-all focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 dark:border-gray-600 dark:bg-gray-700 dark:focus:border-blue-500">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Maksimal kuantitas yang diperbolehkan: <span id="revisionQuantityMaxLabel" class="font-bold text-[#225A97] dark:text-[#2798e6]">0</span>
                    </p>
                </div>

                <!-- Price Input -->
                <div class="mb-5">
                    <label class="mb-2 block text-xs font-bold uppercase tracking-widest text-gray-400">
                        Harga Beli Final (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3 text-sm text-gray-500 dark:text-gray-400 font-semibold">Rp</span>
                        <input type="text" id="revisionUnitCost" name="unit_cost" required
                            class="buy-price-input w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 pl-9 text-sm transition-all focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 dark:border-gray-600 dark:bg-gray-700 dark:focus:border-blue-500">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 bg-gray-50 px-7 py-5 dark:bg-gray-900/50">
                <button type="button" onclick="closeRevisionModal()"
                    class="px-6 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-200 active:scale-95 rounded-xl dark:text-gray-300 dark:hover:bg-gray-700">
                    Batal
                </button>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-[#225A97] px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-700/20 transition-all hover:bg-blue-800 hover:shadow-blue-800/30 active:scale-95">
                    Simpan Revisi
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button onclick="closeRevisionModal()">close</button>
    </form>
</dialog>
