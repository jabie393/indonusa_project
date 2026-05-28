<dialog id="editSellingPriceModal" class="modal">
    <div class="modal-box relative w-full max-w-3xl rounded-2xl bg-white p-0 shadow dark:bg-gray-800">
        <header class="relative flex items-center justify-between px-7 py-5 text-white"
            style="background-image: var(--gradient-header)">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <rect width="20" height="12" x="2" y="6" rx="2" />
                        <circle cx="12" cy="12" r="2" />
                        <path d="M6 12h.01M18 12h.01" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold leading-tight">Edit Harga Jual</h3>
                    <p class="text-xs text-white/80"><span id="edit_price_nama"></span> (<span id="edit_price_kode"></span>)</p>
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

        <form id="editSellingPriceForm" method="POST" class="p-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit_price_id">

            <div class="space-y-4">
                <div>
                    <label class="flex items-center mb-2 gap-2 text-xs font-bold text-slate-500" for="edit_selling_price">
                        <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg">
                            <rect width="20" height="12" x="2" y="6" rx="2"></rect>
                            <circle cx="12" cy="12" r="2"></circle>
                            <path d="M6 12h.01M18 12h.01"></path>
                        </svg>
                        Harga Jual (IDR)
                    </label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-medium text-slate-400">Rp</span>
                        <input id="edit_selling_price" name="selling_price" type="text" inputmode="decimal" required
                            class="w-full rounded-2xl border border-slate-200 px-4 py-2 pl-9 text-sm outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                    </div>
                </div>
                <div>
                    <label class="flex items-center mb-2 gap-2 text-xs font-bold text-slate-500" for="note">
                        <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h4"></path>
                            <path d="M7 7V3h10v4"></path>
                            <path d="M8 11h8"></path>
                            <path d="M8 15h6"></path>
                        </svg>
                        Catatan
                    </label>
                    <textarea name="note" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white" rows="3"
                        placeholder="Alasan perubahan harga..." required></textarea>
                </div>
            </div>

        </form>

        <footer
            class="flex items-center justify-between gap-3 border-t border-slate-100 bg-slate-50 px-7 py-5 dark:bg-gray-800 dark:border-gray-700">
            <p class="hidden text-xs text-slate-500 sm:block dark:text-gray-400">Pastikan data sudah akurat sebelum memperbarui.</p>
            <div class="flex flex-1 justify-end gap-3 sm:flex-none">
                <form method="dialog">
                    <button
                        class="px-6 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-200 active:scale-95 rounded-xl dark:text-gray-300 dark:hover:bg-gray-700"
                        type="submit">
                        Batal
                    </button>
                </form>
                <button
                    class="px-8 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:opacity-90 active:scale-95 rounded-xl"
                    form="editSellingPriceForm" style="background-image: var(--gradient-brand)" type="submit">
                    Simpan Perubahan
                </button>
            </div>
        </footer>
    </div>

    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>
