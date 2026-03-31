<dialog id="modalTolakGlobal" class="modal">
    <div class="modal-box max-w-2xl overflow-hidden rounded-2xl bg-white p-0 dark:bg-gray-800">
        {{-- Header --}}
        <div class="relative bg-gradient-to-r from-[#225A97] to-[#0D223A] px-6 py-5">
            <div class="flex items-center justify-between">
                <div>
                    <h5 id="modalTolakTitle" class="text-lg font-bold tracking-tight text-white">
                        Pembatalan Order
                    </h5>
                    <p class="mt-1 text-xs font-medium text-rose-100/80">Nomor Order: <span id="modalTolakNomor"></span></p>
                </div>
                <button type="button" onclick="closeTolakModal()" class="btn btn-ghost btn-sm btn-square text-white hover:bg-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Body --}}
        <form id="formTolakGlobal" method="POST" action="">
            @csrf
            <div class="max-h-[60vh] overflow-y-auto scrollbar-thin scrollbar-track-transparent scrollbar-thumb-gray-200 dark:scrollbar-thumb-gray-700">
                <div class="grid grid-cols-8 gap-4 px-7 py-6">
                <!-- Step 1: Opsi Pemilihan -->
                <div id="step1Container" class="col-span-8 space-y-6">
                    <div id="returnStockContainer" class="hidden">
                        <div id="step1Header" class="mb-2 text-center">
                            <h4 class="text-base font-bold text-gray-900 dark:text-white">Pilih Opsi Pembatalan</h4>
                            <p class="text-xs text-gray-500 mt-1">Tentukan bagaimana stok barang dikelola setelah pembatalan</p>
                        </div>
                        <div class="flex flex-col items-stretch justify-center gap-4 sm:flex-row">
                            <input type="radio" name="cancel_option" id="cancel_rest_input" value="cancel_rest" class="hidden" onchange="toggleReturnItems('cancel_rest')">
                            <input type="radio" name="cancel_option" id="cancel_return_input" value="cancel_return" class="hidden" onchange="toggleReturnItems('cancel_return')">

                            {{-- Cancel Rest Card --}}
                            <button type="button" onclick="selectCancelOption('cancel_rest')" id="card_cancel_rest" 
                                class="flex flex-1 flex-col items-center justify-center rounded-2xl border-2 border-primary-100 bg-gray-50 p-6 transition-all hover:border-primary-400 hover:bg-primary-50 dark:border-gray-700 dark:bg-gray-700/50">
                                <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-primary-100 text-primary-600 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                </div>
                                <span class="text-base font-bold text-gray-900 dark:text-white">Batalkan Sisa</span>
                                <span class="mt-2 text-center text-xs leading-relaxed text-gray-500 dark:text-gray-400">Batalkan barang belum terkirim</span>
                            </button>

                            {{-- Cancel Return Card --}}
                            <button type="button" onclick="selectCancelOption('cancel_return')" id="card_cancel_return"
                                class="flex flex-1 flex-col items-center justify-center rounded-2xl border-2 bg-gray-50 p-6 transition-all border-orange-100 hover:border-orange-400 hover:bg-orange-50 dark:border-gray-700 dark:bg-gray-700/50">
                                <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-orange-100 text-orange-600 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                                    </svg>
                                </div>
                                <span class="text-base font-bold text-gray-900 dark:text-white">Retur Barang</span>
                                <span class="mt-2 text-center text-xs leading-relaxed text-gray-500 dark:text-gray-400">Kembalikan stok yang terkirim</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Detail Barang (Returns) -->
                <div id="step2Container" class="col-span-8 !hidden space-y-4 animate-in fade-in slide-in-from-right-4 duration-300">
                    <div class="mb-3 flex items-center justify-between border-b pb-3 dark:border-gray-700">
                        <div>
                            <h4 class="text-base font-bold text-gray-900 dark:text-white">Detail Pengembalian Stok</h4>
                            <p class="text-xs text-gray-500 mt-1">Masukkan jumlah barang yang akan dikembalikan ke gudang</p>
                        </div>
                    </div>
                    <div class="overflow-auto rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Barang</th>
                                    <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Jml Terkirim</th>
                                    <th class="w-32 px-5 py-3 text-center text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Jml Kembali</th>
                                </tr>
                            </thead>
                            <tbody id="returnItemsWrapper" class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                <!-- rows added via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Step 3: Alasan -->
                <div id="step3Container" class="col-span-8 !hidden space-y-4 animate-in fade-in slide-in-from-right-4 duration-300">
                    <div class="rounded-lg border border-amber-100 bg-amber-50 p-4">
                        <div class="flex space-x-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-xs font-medium leading-relaxed text-amber-800" id="tolakNote2">Harap sertakan alasan yang konstruktif agar tim dapat melakukan revisi dengan tepat.</p>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-bold uppercase tracking-widest text-gray-400">
                            Alasan Pembatalan <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="modalTolakReason" name="reason" rows="6" required minlength="5" placeholder="Tuliskan alasan detail di sini..." class="textarea textarea-bordered w-full rounded-xl bg-gray-50 px-4 py-3 text-sm transition-all focus:border-rose-500 focus:bg-white focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:focus:border-rose-500"></textarea>
                    </div>

                    <div id="modalTolakError" class="mt-2 hidden items-center text-xs font-semibold text-rose-600">
                        <svg class="mr-1 h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Minimal 5 karakter wajib diisi.
                    </div>
                </div>
            </div> {{-- This div closes the scrolling body (max-h-60vh) --}}
        </div>

            <div class="flex items-center justify-end gap-3 bg-gray-50 px-7 py-5 dark:bg-gray-900/50">
                <button type="button" id="btnCancelTolak" onclick="closeTolakModal()" class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-bold text-gray-500 transition-all hover:bg-gray-100 hover:text-gray-700 active:scale-95 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                    Batal
                </button>
                <button type="button" id="btnBackTolak" onclick="goToStep(1)" class="!hidden rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-bold text-gray-500 transition-all hover:bg-gray-100 hover:text-gray-700 active:scale-95 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                    Kembali
                </button>
                <button type="button" id="btnNextTolak" onclick="goToStep(2)" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary-600/20 transition-all hover:bg-primary-700 hover:shadow-primary-600/30 active:scale-95">
                    Lanjut
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <button type="button" id="btnSubmitTolak" onclick="submitTolakModal()" class="!hidden inline-flex items-center gap-2 rounded-xl bg-rose-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-rose-600/20 transition-all hover:bg-rose-700 hover:shadow-rose-600/30 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Konfirmasi Pembatalan
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    function toggleReturnItems(option) {
        const note3 = document.getElementById('tolakNote2'); // Now updating note on Step 3
        const nomor = document.getElementById('modalTolakNomor').textContent;
        const returnStockContainer = document.getElementById('returnStockContainer');

        const cardRest = document.getElementById('card_cancel_rest');
        const cardReturn = document.getElementById('card_cancel_return');
        
        // Reset both to neutral
        if (cardRest) {
            cardRest.classList.remove('border-primary-400', 'bg-primary-50', 'dark:bg-primary-900/10');
            cardRest.classList.add('border-primary-100', 'bg-gray-50');
        }
        if (cardReturn) {
            cardReturn.classList.remove('border-orange-400', 'bg-orange-50', 'dark:bg-orange-900/10');
            cardReturn.classList.add('border-orange-100', 'bg-gray-50');
        }

        if (option === 'cancel_return') {
            if (cardReturn) {
                cardReturn.classList.add('border-orange-400', 'bg-orange-50', 'dark:bg-orange-900/10');
                cardReturn.classList.remove('border-orange-100', 'bg-gray-50');
            }
            if (note3) {
                note3.textContent = 'Order ' + nomor + ' akan dibatalkan sepenuhnya dan stok yang sudah terkirim akan dikembalikan ke gudang.';
            }
        } else if (option === 'cancel_rest') {
            if (cardRest) {
                cardRest.classList.add('border-primary-400', 'bg-primary-50', 'dark:bg-primary-900/10');
                cardRest.classList.remove('border-primary-100', 'bg-gray-50');
            }
            if (note3) {
                note3.textContent = 'Order ' + nomor + ' memiliki pengiriman yang sudah berjalan. Order akan ditandai sebagai Selesai untuk membatalkan sisanya.';
            }
        }
    }

    function selectCancelOption(option) {
        const radio = document.getElementById(option + '_input');
        if (radio) {
            radio.checked = true;
            toggleReturnItems(option);
            // After selection, go to next appropriate step
            setTimeout(() => {
                if (option === 'cancel_rest') goToStep(3);
                else goToStep(2);
            }, 300);
        }
    }

    function goToStep(step) {
        const s1 = document.getElementById('step1Container');
        const s2 = document.getElementById('step2Container');
        const s3 = document.getElementById('step3Container');
        
        const bNext = document.getElementById('btnNextTolak');
        const bBack = document.getElementById('btnBackTolak');
        const bSubmit = document.getElementById('btnSubmitTolak');
        const bCancel = document.getElementById('btnCancelTolak');

        const option = document.querySelector('input[name="cancel_option"]:checked')?.value;

        [s1, s2, s3, bNext, bBack, bSubmit, bCancel].forEach(el => el?.classList.add('!hidden'));

        if (step === 1) {
            s1.classList.remove('!hidden');
            // User requested no buttons on Step 1 (Batal and Lanjut are now hidden)
        } else if (step === 2) {
            s2.classList.remove('!hidden');
            bNext.classList.remove('!hidden');
            bBack.classList.remove('!hidden');
            bBack.onclick = () => goToStep(1);
            bNext.onclick = () => goToStep(3);
        } else if (step === 3) {
            s3.classList.remove('!hidden');
            bSubmit.classList.remove('!hidden');
            bBack.classList.remove('!hidden');
            bBack.onclick = () => {
                const opt = document.querySelector('input[name="cancel_option"]:checked')?.value;
                goToStep(opt === 'cancel_return' ? 2 : 1);
            };
            setTimeout(() => document.getElementById('modalTolakReason').focus(), 100);
        }
    }
    /**
     * Buka modal tolak.
     * @param {string} type  - 'request_order', 'custom', 'delivery_order', 'supply_order'
     * @param {string} id    - ID record
     * @param {string} nomor - Nomor penawaran / request number (untuk label)
     * @param {Array} items  - Order items (for delivery check)
     */
    function openTolakModal(type, id, nomor, items) {
        const modal = document.getElementById('modalTolakGlobal');
        if (!modal) return;

        // Tentukan action URL berdasarkan tipe
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

        const hasDeliveries = (items && Array.isArray(items)) ? items.some(
            (item) => item.delivered_quantity > 0,
        ) : false;

        const returnStockContainer = document.getElementById('returnStockContainer');
        const returnItemsWrapper = document.getElementById('returnItemsWrapper');

        if (hasDeliveries && type === 'delivery_order') {
            document.getElementById('tolakNote2').textContent = 'Order ' + nomor + ' memiliki pengiriman yang sudah berjalan. Order akan ditandai sebagai Selesai untuk membatalkan sisanya.';
            if (returnStockContainer) {
                returnStockContainer.classList.remove('hidden');
                // Removed default check for cancel_rest
                toggleReturnItems(null);

                returnItemsWrapper.innerHTML = '';
                items.forEach((item) => {
                    const deliveredQty = parseInt(item.delivered_quantity) || 0;
                    if (deliveredQty > 0) {
                        const itemName = item.barang ? item.barang.nama_barang : (item.nama_barang ? item.nama_barang : 'Barang');
                        const itemCode = item.barang ? item.barang.kode_barang : (item.kode_barang ? item.kode_barang : '-');
                        const itemUnit = item.barang ? item.barang.satuan : (item.satuan ? item.satuan : '-');
                        
                        returnItemsWrapper.innerHTML += `
                            <tr class="text-sm">
                                <td class="px-5 py-4">
                                    <p class="font-bold text-gray-900 dark:text-white">` + itemName + `</p>
                                    <p class="text-[10px] font-medium tracking-wider text-gray-400 uppercase mt-0.5">` + itemCode + `</p>
                                </td>
                                <td class="px-5 py-4 text-left">
                                    <p class="text-xs font-bold text-gray-900 dark:text-white">` + deliveredQty + ' ' + itemUnit +`</p>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex justify-center">
                                        <input type="number" name="return_items[` + item.id + `]" 
                                            class="return-qty-input w-24 rounded-lg border-gray-300 bg-gray-50 text-center text-sm font-bold focus:border-orange-500 focus:ring-orange-500 dark:border-gray-600 dark:bg-gray-700" 
                                            value="` + deliveredQty + `" min="0" max="` + deliveredQty + `"
                                            oninput="if(parseInt(this.value) > ` + deliveredQty + `) this.value = ` + deliveredQty + `; if(parseInt(this.value) < 0 || !this.value) this.value = 0;">
                                    </div>
                                </td>
                            </tr>
                        `;
                    }
                });
            }
            goToStep(1);
        } else {
            document.getElementById('tolakNote2').textContent = 'Apakah Anda yakin ingin menolak order ' + nomor + '? Harap sertakan alasan agar tim dapat melakukan revisi dengan tepat.';
            if (returnStockContainer) {
                returnStockContainer.classList.add('hidden');
                returnItemsWrapper.innerHTML = '';
                // Removed default check for cancel_rest
            }
            goToStep(3);
            // Single-step mode: ensure Back/Next are hidden and Submit is shown
            document.getElementById('btnBackTolak').classList.add('!hidden');
            document.getElementById('btnNextTolak').classList.add('!hidden');
            document.getElementById('btnSubmitTolak').classList.remove('!hidden');
            document.getElementById('btnCancelTolak').classList.remove('!hidden');
        }
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

        const cancelReturnRadio = document.querySelector('input[name="cancel_option"][value="cancel_return"]');
        if (cancelReturnRadio && cancelReturnRadio.checked && !document.getElementById('returnStockContainer').classList.contains('hidden')) {
            const returnInputs = document.querySelectorAll('.return-qty-input');
            let hasError = false;
            returnInputs.forEach((input) => {
                const val = parseInt(input.value) || 0;
                const max = parseInt(input.getAttribute('max')) || 0;
                if (val > max) {
                    alert('Jumlah pengembalian tidak boleh melebihi jumlah terkirim (' + max + ').');
                    input.focus();
                    hasError = true;
                } else if (val < 0) {
                    alert('Jumlah pengembalian tidak valid.');
                    input.focus();
                    hasError = true;
                }
            });
            if (hasError) return;
        }

        document.getElementById('modalTolakError').classList.add('hidden');
        document.getElementById('formTolakGlobal').submit();
    }
</script>
