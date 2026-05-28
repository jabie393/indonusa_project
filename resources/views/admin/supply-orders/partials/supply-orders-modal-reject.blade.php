<dialog id="modalTolakGlobal" class="modal">
    <div
        class="modal-box w-full max-w-md overflow-hidden rounded-2xl bg-white p-0 shadow-2xl ring-1 ring-black/5 dark:bg-gray-800">
        {{-- Header --}}
        <header class="relative flex items-center justify-between px-7 py-5 text-white"
            style="background-image: var(--gradient-header)">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div>
                    <h3 id="modalTolakTitle" class="text-lg font-semibold leading-tight">Penolakan Permintaan Barang
                    </h3>
                    <p class="text-xs text-white/80"><span id="modalTolakNama">&nbsp;</span> <span
                            id="modalTolakKodeBracket" class="hidden">(<span id="modalTolakKode"></span>)</span></p>
                </div>
            </div>
            <form method="dialog">
                <button aria-label="Tutup" type="button" onclick="closeTolakModal()"
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
        <form id="formTolakGlobal" method="POST" action="">
            @csrf
            <div class="px-7 py-6">
                <div class="mb-5 rounded-lg border border-amber-100 bg-amber-50 p-3">
                    <div class="flex space-x-2">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-[11px] font-medium leading-relaxed text-amber-800">Harap sertakan alasan yang
                            konstruktif agar tim dapat melakukan revisi dengan tepat.</p>
                    </div>
                </div>

                <label class="mb-2 block text-xs font-bold uppercase tracking-widest text-gray-400">
                    Alasan Penolakan <span class="text-rose-500">*</span>
                </label>
                <textarea id="modalTolakReason" name="reason" rows="4" required minlength="5"
                    placeholder="Contoh: Harap sesuaikan dengan harga pasar..."
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm transition-all focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-rose-500/10 dark:border-gray-600 dark:bg-gray-700 dark:focus:border-rose-500"></textarea>

                <div id="modalTolakError" class="mt-2 hidden items-center text-xs font-semibold text-rose-600">
                    <svg class="mr-1 h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Minimal 5 karakter wajib diisi.
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 bg-gray-50 px-7 py-5 dark:bg-gray-900/50">
                <button type="button" onclick="closeTolakModal()"
                    class="px-6 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-200 active:scale-95 rounded-xl dark:text-gray-300 dark:hover:bg-gray-700">
                    Batal
                </button>
                <button type="button" onclick="submitTolakModal()"
                    class="inline-flex items-center gap-2 rounded-xl bg-red-700 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-red-700/20 transition-all hover:bg-red-800 hover:shadow-red-800/30 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Konfirmasi Tolak
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    /**
     * Buka modal tolak.
     */
    function openTolakModal(type, id, nomor, kode) {
        const modal = document.getElementById('modalTolakGlobal');
        if (!modal) return;

        let actionUrl = '';
        if (type === 'request_order') {
            actionUrl = '/request-order/' + id + '/reject';
        } else if (type === 'custom') {
            actionUrl = '/supervisor/custom-penawaran/' + id + '/approval?action=reject';
        } else if (type === 'delivery_order') {
            actionUrl = '/delivery-orders/' + id + '/reject';
        } else if (type === 'supply_order') {
            actionUrl = '/supply-orders/' + id + '/reject';
        }

        document.getElementById('formTolakGlobal').action = actionUrl;

        const namaEl = document.getElementById('modalTolakNama');
        const kodeEl = document.getElementById('modalTolakKode');
        const bracketEl = document.getElementById('modalTolakKodeBracket');

        if (namaEl) namaEl.textContent = nomor || '';
        if (kodeEl) kodeEl.textContent = kode || '';
        if (bracketEl) {
            if (kode) {
                bracketEl.classList.remove('hidden');
            } else {
                bracketEl.classList.add('hidden');
            }
        }

        document.getElementById('modalTolakReason').value = '';
        document.getElementById('modalTolakError').classList.add('hidden');

        if (typeof modal.showModal === "function") {
            modal.showModal();
        } else {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        document.getElementById('modalTolakReason').focus();
    }

    function closeTolakModal() {
        const modal = document.getElementById('modalTolakGlobal');
        if (!modal) return;

        if (typeof modal.close === "function") {
            modal.close();
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function submitTolakModal() {
        const reason = document.getElementById('modalTolakReason').value.trim();
        if (reason.length < 5) {
            document.getElementById('modalTolakError').classList.remove('hidden');
            document.getElementById('modalTolakReason').focus();
            return;
        }
        document.getElementById('modalTolakError').classList.add('hidden');
        document.getElementById('formTolakGlobal').submit();
    }
</script>