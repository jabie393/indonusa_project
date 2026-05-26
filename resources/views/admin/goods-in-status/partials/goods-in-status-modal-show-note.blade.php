<dialog id="noteModal" class="modal">
    <div class="modal-box relative flex w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-800 max-h-[95vh]">
        <!-- Header -->
        <header class="relative flex items-center justify-between px-7 py-5 text-white" style="background-image: linear-gradient(to right, #225A97, #0D223A)">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                    <svg class="h-5 w-5" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold leading-tight">Alasan Penolakan</h3>
                    <p class="text-xs text-white/80" id="note_subtitle">&nbsp;</p>
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

        <!-- Content -->
        <div class="flex-1 overflow-y-auto p-7">
            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4 min-h-[120px]">
                <p id="catatanContent" class="text-slate-700 dark:text-gray-300 text-base leading-relaxed whitespace-pre-line italic">Memuat alasan...</p>
            </div>
        </div>

        <footer class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50 px-7 py-5 dark:bg-gray-800 dark:border-gray-700">
            <form method="dialog">
                <button class="px-6 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-200 active:scale-95 rounded-xl dark:text-gray-300 dark:hover:bg-gray-700" type="submit">Tutup</button>
            </form>
        </footer>
    </div>
    <form method="dialog" class="modal-backdrop bg-black/20"><button>Tutup</button></form>
</dialog>

<script>
    function openNoteModal(data) {
        const subtitleEl = document.getElementById('note_subtitle');
        const contentEl = document.getElementById('catatanContent');
        const nama = data && data.nama ? String(data.nama).trim() : '';
        const kode = data && data.kode ? String(data.kode).trim() : '';
        let subtitleText = '';
        if (nama && kode) subtitleText = nama + ' (' + kode + ')';
        else if (nama) subtitleText = nama;
        else if (kode) subtitleText = kode;

        if (subtitleEl) subtitleEl.textContent = subtitleText;
        if (contentEl) contentEl.textContent = (data && (data.catatan || data.note)) ? (data.catatan || data.note) : 'Tidak ada catatan.';

        const modal = document.getElementById('noteModal');
        if (modal && typeof modal.showModal === 'function') modal.showModal();
    }

    window.openNoteModal = openNoteModal;
</script>
