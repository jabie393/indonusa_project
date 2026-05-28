<dialog id="historyModal" class="modal">
    <div class="modal-box relative flex w-full max-w-2xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-800 max-h-[95vh]">
        <!-- Header -->
        <header class="relative flex items-center justify-between px-7 py-5 text-white" style="background-image: linear-gradient(to right, #225A97, #0D223A)">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                    <svg class="h-5 w-5" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold leading-tight">Detail Riwayat</h3>
                    <p class="text-xs text-white/80" id="history_subtitle_name">&nbsp;</p>
                    <p class="text-xs text-white/80" id="history_subtitle_code">&nbsp;</p>
                </div>
            </div>
            <form method="dialog">
                <button aria-label="Tutup" class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </form>
        </header>
                <main class="space-y-6 px-6 py-6" data-purpose="modal-body">
                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-y-6">
                        <!-- Row 1 -->
                        <div>
                            <p class="mb-1 text-[10px] font-bold uppercase tracking-wide text-slate-400">Tanggal</p>
                            <p class="text-[15px] font-medium text-slate-800 dark:text-gray-200" id="history_date">-</p>
                        </div>
                        <div>
                            <p class="mb-1 text-[10px] font-bold uppercase tracking-wide text-slate-400">Stok</p>
                            <p class="text-[15px] font-bold text-slate-900 dark:text-white" id="history_stock">-</p>
                        </div>
                        <!-- Row 2 -->
                        <div>
                            <p class="mb-1 text-[10px] font-bold uppercase tracking-wide text-slate-400">Diubah Oleh</p>
                            <p class="text-[15px] font-medium text-slate-800 dark:text-gray-200" id="history_user">-</p>
                        </div>
                        <div>
                            <p class="mb-1 text-[10px] font-bold uppercase tracking-wide text-slate-400">Perubahan Status</p>
                            <div class="mt-1 flex items-center gap-2">
                                <div class="flex items-center gap-1 rounded-full border text-yellow-700 border-yellow-100 bg-yellow-100 px-2 py-1 text-[11px] font-bold dark:text-yellow-200"">
                                    <span class="text-xs font-medium" id="history_old_status">-</span>
                                </div>
                                <svg class="h-3 w-3 text-slate-400" fill="none" stroke="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                                <div class="flex items-center gap-1 rounded-full border border-green-100 bg-green-50 px-2 py-1 text-[11px] font-bold text-green-700">
                                    <span id="history_new_status">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Action Box -->
                    <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4" data-purpose="action-description">
                        <p class="mb-2 text-[10px] font-bold uppercase tracking-wide text-slate-400">Aksi</p>
                        <p class="text-sm leading-relaxed text-slate-700 dark:text-gray-300" id="history_action">-</p>
                    </div>
                    <!-- Notes -->
                    <div data-purpose="notes-section">
                        <p class="mb-1 text-[10px] font-bold uppercase tracking-wide text-slate-400">Catatan</p>
                        <p class="text-sm text-slate-400 dark:text-gray-400" id="history_note">Tidak ada catatan</p>
                    </div>
                </main>
                <!-- END: Content Body -->
                <!-- BEGIN: Footer -->
        <footer class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50 px-7 py-5 dark:bg-gray-800 dark:border-gray-700">
            <form method="dialog">
                <button class="px-6 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-200 active:scale-95 rounded-xl dark:text-gray-300 dark:hover:bg-gray-700" type="submit">Tutup</button>
            </form>
        </footer>
    </div>
    <form method="dialog" class="modal-backdrop bg-black/20"><button>Tutup</button></form>
</dialog>

<script>
    function openHistoryModal(data) {
        const setText = (id, value, fallback = '-') => {
            const el = document.getElementById(id);
            if (!el) return;
            const text = value && String(value).trim() ? String(value).trim() : fallback;
            el.textContent = text;
        };

        setText('history_subtitle_name', data.goodsName);
        setText('history_subtitle_code', data.goodsCode);
        setText('history_date', data.date);
        setText('history_stock', data.stock);
        setText('history_user', data.user);
        setText('history_old_status', data.oldStatus);
        setText('history_new_status', data.newStatus);
        setText('history_action', data.action);
        setText('history_note', data.note, 'Tidak ada catatan');

        const modal = document.getElementById('historyModal');
        if (modal && typeof modal.showModal === 'function') modal.showModal();
    }

    window.openHistoryModal = openHistoryModal;
</script>
