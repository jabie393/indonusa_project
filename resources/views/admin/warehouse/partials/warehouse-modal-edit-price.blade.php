<dialog id="editSellingPriceModal" class="modal">
    <div class="modal-box relative w-full max-w-3xl rounded-2xl bg-white p-0 shadow dark:bg-gray-800">
        <div class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
            <h3 class="text-lg font-semibold text-white">Ubah Harga Jual - <span id="edit_price_nama"></span> (<span id="edit_price_kode"></span>)</h3>
            <form method="dialog">
                <button class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
            </form>
        </div>

        <form id="editSellingPriceForm" method="POST" class="p-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit_price_id">

            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-slate-500">Harga Jual (IDR)</label>
                    <input id="edit_selling_price" name="selling_price" type="number" step="0.01" min="0" required
                        class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500">Catatan (opsional)</label>
                    <textarea name="note" class="w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white" rows="3" placeholder="Alasan perubahan harga..."></textarea>
                </div>
            </div>

            <div class="mt-4 flex justify-end gap-2">
                <form method="dialog">
                    <button class="px-4 py-2 rounded-xl bg-gray-200 text-sm">Batal</button>
                </form>
                <button type="submit" class="px-6 py-2 rounded-xl bg-green-600 text-white">Simpan</button>
            </div>
        </form>
    </div>

    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>
