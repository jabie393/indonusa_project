    <dialog id="modalTolakGlobal" class="modal">
        <div class="modal-box w-full max-w-md overflow-hidden rounded-2xl bg-white p-0 shadow-2xl ring-1 ring-black/5 dark:bg-gray-800">
            {{-- Header --}}
            <div class="relative bg-gradient-to-r from-[#225A97] to-[rgb(13,34,58)] px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h5 id="modalTolakTitle" class="text-lg font-bold tracking-tight text-white">
                            Penolakan Permintaan Barang
                        </h5>
                        <p class="mt-1 text-xs font-medium text-rose-100/80">Nama Barang: <span id="modalTolakNomor"></span></p>
                    </div>
                    <form method="dialog">
                        <button class="rounded-lg bg-white/10 p-2 text-white transition-colors hover:bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M18 6 6 18M6 6l12 12" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Body --}}
            <form id="formTolakGlobal" method="POST" action="">
                @csrf
                <div class="px-7 py-6">
                    <div class="mb-5 rounded-lg border border-amber-100 bg-amber-50 p-3">
                        <div class="flex space-x-2">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-[11px] font-medium leading-relaxed text-amber-800">Harap sertakan alasan yang konstruktif agar tim dapat melakukan revisi dengan tepat.</p>
                        </div>
                    </div>

                    <label class="mb-2 block text-xs font-bold uppercase tracking-widest text-gray-400">
                        Alasan Penolakan <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="modalTolakReason" name="reason" rows="4" required minlength="5" placeholder="Contoh: Harap sesuaikan dengan harga pasar..." class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm transition-all focus:border-rose-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-rose-500/10 dark:border-gray-600 dark:bg-gray-700 dark:focus:border-rose-500"></textarea>

                    <div id="modalTolakError" class="mt-2 hidden items-center text-xs font-semibold text-rose-600">
                        <svg class="mr-1 h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Minimal 5 karakter wajib diisi.
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 bg-gray-50 px-7 py-5 dark:bg-gray-900/50">
                    <button type="button" onclick="closeTolakModal()" class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-bold text-gray-500 transition-all hover:bg-gray-100 hover:text-gray-700 active:scale-95 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                        Batal
                    </button>
                    <button type="button" onclick="submitTolakModal()" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-rose-600/20 transition-all hover:bg-rose-700 hover:shadow-rose-600/30 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
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
        function openTolakModal(type, id, nomor) {
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
            document.getElementById('modalTolakNomor').textContent = nomor;
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
