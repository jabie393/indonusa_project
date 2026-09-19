<!-- Edit Vendor Modal -->
<dialog id="editVendorModal" class="modal">
    <div class="modal-box relative flex h-full w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow-2xl dark:bg-gray-800 sm:max-h-[90vh]">
        <!-- Header -->
        <header class="flex items-center justify-between border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] px-6 py-4 text-white dark:border-gray-700 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/20">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold">Edit Data Vendor</h3>
                    <p class="text-xs text-white/80" id="edit_vendor_subtitle">-</p>
                </div>
            </div>
            <form method="dialog">
                <button type="submit" class="rounded-lg p-1 text-white/80 hover:bg-white/10 hover:text-white transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </form>
        </header>

        <!-- Form -->
        <form action="" method="POST" id="editVendorForm" class="flex flex-col flex-1 min-h-0">
            @csrf
            @method('PUT')

            <div class="overflow-y-auto p-6 space-y-6 flex-1">
                <!-- 1. Identitas Vendor -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#225A97] dark:text-blue-400 mb-3 flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Identitas Vendor
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="edit_vendor_name" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Nama Vendor / Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="edit_vendor_name" name="vendor_name" required
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        </div>

                        <div>
                            <label for="edit_company_type" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Bentuk Entitas
                            </label>
                            <select id="edit_company_type" name="company_type"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="PT">PT (Perseroan Terbatas)</option>
                                <option value="CV">CV (Commanditaire Vennootschap)</option>
                                <option value="UD">UD (Usaha Dagang)</option>
                                <option value="Toko">Toko / Agen</option>
                                <option value="Perorangan">Perorangan / Pribadi</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label for="edit_vendor_code" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Kode Vendor
                            </label>
                            <input type="text" id="edit_vendor_code" name="vendor_code"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm font-mono text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        </div>

                        <div class="md:col-span-2">
                            <label for="edit_npwp" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                No. NPWP
                            </label>
                            <input type="text" id="edit_npwp" name="npwp"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        </div>
                    </div>
                </div>

                <!-- 2. Kontak & Alamat -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#225A97] dark:text-blue-400 mb-3 flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Kontak &amp; Alamat
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_phone" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                No. Telepon / WhatsApp
                            </label>
                            <input type="tel" id="edit_phone" name="phone"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        </div>

                        <div>
                            <label for="edit_email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Email Resmi
                            </label>
                            <input type="email" id="edit_email" name="email"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        </div>

                        <div class="md:col-span-2">
                            <label for="edit_address" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Alamat Kantor / Gudang
                            </label>
                            <textarea id="edit_address" name="address" rows="2"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                        </div>

                        <div>
                            <label for="edit_city" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Kota / Kabupaten
                            </label>
                            <input type="text" id="edit_city" name="city"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        </div>

                        <div>
                            <label for="edit_province" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Provinsi
                            </label>
                            <input type="text" id="edit_province" name="province"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        </div>
                    </div>
                </div>

                <!-- 3. Person in Charge (PIC) -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#225A97] dark:text-blue-400 mb-3 flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Kontak Person (PIC Vendor)
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="edit_pic_name" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Nama PIC
                            </label>
                            <input type="text" id="edit_pic_name" name="pic_name"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        </div>

                        <div>
                            <label for="edit_pic_phone" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                No. HP PIC
                            </label>
                            <input type="tel" id="edit_pic_phone" name="pic_phone"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        </div>

                        <div>
                            <label for="edit_pic_email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Email PIC
                            </label>
                            <input type="email" id="edit_pic_email" name="pic_email"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        </div>
                    </div>
                </div>

                <!-- 4. Rekening Bank & Term of Payment -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-[#225A97] dark:text-blue-400 mb-3 flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Informasi Pembayaran &amp; Ketentuan
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="edit_bank_name" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Nama Bank
                            </label>
                            <input type="text" id="edit_bank_name" name="bank_name"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        </div>

                        <div>
                            <label for="edit_bank_account_number" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Nomor Rekening
                            </label>
                            <input type="text" id="edit_bank_account_number" name="bank_account_number"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm font-mono text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        </div>

                        <div>
                            <label for="edit_bank_account_holder" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Atas Nama Rekening
                            </label>
                            <input type="text" id="edit_bank_account_holder" name="bank_account_holder"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        </div>

                        <div>
                            <label for="edit_term_of_payment" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Term of Payment (TOP)
                            </label>
                            <div class="relative flex items-center">
                                <input type="number" id="edit_term_of_payment" name="term_of_payment" min="0"
                                    class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 pr-14 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                                <span class="absolute right-3 text-xs font-bold text-gray-400">Hari</span>
                            </div>
                        </div>

                        <div>
                            <label for="edit_status" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Status Vendor
                            </label>
                            <select id="edit_status" name="status"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="active">Aktif (Dapat Digunakan)</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>

                        <div class="md:col-span-3">
                            <label for="edit_notes" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Catatan Tambahan (Notes)
                            </label>
                            <textarea id="edit_notes" name="notes" rows="2"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="flex items-center justify-end gap-2 border-t border-gray-200 bg-gray-50 px-6 py-3.5 dark:border-gray-700 dark:bg-gray-900/60 shrink-0">
                <button type="button" onclick="editVendorModal.close()"
                    class="rounded-xl px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-700 transition">
                    Batal
                </button>
                <button type="submit"
                    class="rounded-xl bg-[#225A97] px-6 py-2 text-sm font-bold text-white shadow hover:bg-[#19426d] transition">
                    Simpan Perubahan
                </button>
            </footer>
        </form>
    </div>
</dialog>
