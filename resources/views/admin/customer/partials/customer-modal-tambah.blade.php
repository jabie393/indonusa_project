<!-- Create Customer Modal -->
<dialog id="createCustomerModal" class="modal">
    <div
        class="modal-box relative flex max-w-xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh] inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">
        <div
            class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600 inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">
            <h3 class="text-lg font-semibold text-white">
                Tambah Customer </h3>
            <div class="modal-action m-0">
                <form method="dialog">
                    <!-- if there is a button in form, it will close the modal -->
                    <button
                        class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </form>
            </div>
        </div>
        <form action="{{ route('customer.store') }}" method="POST"
            class="flex h-full flex-col space-y-4 overflow-hidden relative p-4">
            <div class="h-full relative overflow-auto">
                <div class="mb-6 grid grid-cols-1 gap-2 md:grid-cols-2">

                    @csrf
                    <div class="col-span-2 mb-4">
                        <label for="createName"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                        <input type="text" id="createName" name="name" placeholder="Nama"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            required>
                    </div>
                    <div class="col-span-2 mb-4">
                        <label for="npwp" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">No.
                            NPWP</label>
                        <input type="number" id="npwp" name="npwp" placeholder="No. NPWP"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                    </div>
                    <div class="col-span-1 mb-4">
                        <label for="tipe_customer"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                        <select name="tipe_customer" id="tipe_customer"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            required>
                            <option value="pribadi">Pribadi</option>
                            <option value="gov">GOV</option>
                            <option value="bumn">BUMN</option>
                            <option value="swasta">Swasta</option>
                        </select>
                    </div>
                    <div class="col-span-1 mb-4">
                        <label for="term_of_payments"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Term of
                            Payments</label>
                        <div class="flex items-center gap-2">
                            <input type="number" id="term_of_payments" name="term_of_payments" placeholder="30"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                min="0">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Hari</span>
                        </div>
                    </div>
                    <div class="col-span-2 mb-4">
                        <label for="kredit_limit"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kredit Limit</label>
                        <input type="text" id="kredit_limit" name="kredit_limit" placeholder="Kredit Limit"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                    </div>
                    <div class="col-span-2 mb-4">
                        <label for="alamat_penagihan"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Alamat Penagihan</label>
                        <textarea id="alamat_penagihan" name="alamat_penagihan" placeholder="Alamat Penagihan"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" rows="2"></textarea>
                    </div>
                    <div class="col-span-2 mb-4">
                        <label for="alamat_pengiriman"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Alamat Pengiriman</label>
                        <textarea id="alamat_pengiriman" name="alamat_pengiriman" placeholder="Alamat Pengiriman"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" rows="2"></textarea>
                    </div>
                    <div class="col-span-1 mb-4">
                        <label for="kota"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kota</label>
                        <input type="text" id="kota" name="kota" placeholder="Kota"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                    </div>
                    <div class="col-span-1 mb-4">
                        <label for="provinsi"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Provinsi</label>
                        <input type="text" id="provinsi" name="provinsi" placeholder="Provinsi"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                    </div>
                    <div class="col-span-1 mb-4">
                        <label for="kode_pos" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kode
                            Pos</label>
                        <input type="text" id="kode_pos" name="kode_pos" placeholder="Kode Pos"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                    </div>

                    <div class="col-span-2 mb-4">
                        <label for="telepon" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">No.
                            HP</label>
                        <input type="tel" id="telepon" name="telepon" placeholder="08xxxxxxxxx"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            required>
                    </div>
                    <div class="col-span-2 mb-4">
                        <label for="email"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" id="email" name="email" placeholder="example@example.com"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                    </div>
                    <!-- Dynamic PIC Fields -->
                    <div class="col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">PIC (Minimal
                            1)</label>
                        <div id="pic-container" class="space-y-4">
                            <!-- First Row (Default & Required) -->
                            <div
                                class="pic-row grid grid-cols-1 gap-2 rounded-lg border border-gray-200 p-3 dark:border-gray-600 md:grid-cols-2">
                                <div class="col-span-1">
                                    <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Nama
                                        PIC <span class="text-red-500">*</span></label>
                                    <input type="text" name="pics[0][name]" placeholder="Nama Lengkap"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white"
                                        required>
                                </div>
                                <div class="col-span-1">
                                    <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">No.
                                        HP <span class="text-red-500">*</span></label>
                                    <input type="tel" name="pics[0][phone]" placeholder="08xxxxxxxx"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white"
                                        required>
                                </div>
                                <div class="col-span-1">
                                    <label
                                        class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Email
                                        <span class="text-red-500">*</span></label>
                                    <input type="email" name="pics[0][email]" placeholder="email@example.com"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white"
                                        required>
                                </div>
                                <div class="col-span-1">
                                    <label
                                        class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Posisi/Jabatan
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="pics[0][position]" placeholder="Manager, Staff, dll"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white"
                                        required>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-pic-btn"
                            class="mt-2 flex items-center rounded-lg border border-dashed border-blue-500 px-3 py-2 text-xs font-medium text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-gray-700">
                            <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah PIC Lain
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let picIndex = 1;
        const picContainer = document.getElementById('pic-container');
        const addPicBtn = document.getElementById('add-pic-btn');

        addPicBtn.addEventListener('click', function() {
            const newRow = document.createElement('div');
            newRow.className =
                'pic-row grid grid-cols-1 gap-2 rounded-lg border border-gray-200 p-3 relative dark:border-gray-600 md:grid-cols-2 mt-2';
            newRow.innerHTML = `
                <button type="button" class="remove-pic-btn absolute -right-2 -top-2 rounded-full bg-red-100 p-1 text-red-600 hover:bg-red-200">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div class="col-span-1">
                    <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Nama PIC <span class="text-red-500">*</span></label>
                    <input type="text" name="pics[${picIndex}][name]" placeholder="Nama Lengkap" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white" required>
                </div>
                <div class="col-span-1">
                    <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">No. HP <span class="text-red-500">*</span></label>
                    <input type="tel" name="pics[${picIndex}][phone]" placeholder="08xxxxxxxx" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white" required>
                </div>
                <div class="col-span-1">
                    <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="pics[${picIndex}][email]" placeholder="email@example.com" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white" required>
                </div>
                <div class="col-span-1">
                    <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Posisi/Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" name="pics[${picIndex}][position]" placeholder="Manager, Staff, dll" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white" required>
                </div>
            `;
            picContainer.appendChild(newRow);
            picIndex++;

            // Bind remove event
            newRow.querySelector('.remove-pic-btn').addEventListener('click', function() {
                newRow.remove();
            });
        });
    });
</script>
