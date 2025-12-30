<!-- Edit Customer Modal -->
<dialog id="editCustomerModal" class="modal">
    <div
        class="modal-box relative flex max-w-xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        <div
            class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">
                Edit Customer </h3>
            <div class="modal-action m-0">
                <form method="dialog">
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
        <form id="editCustomerForm" action="{{ route('customer.update', ['customer' => ':id']) }}" method="POST"
            class="flex h-full flex-col space-y-4 overflow-auto p-4">
            @csrf
            @method('PUT')
            <input type="hidden" id="editCustomerId" name="id">
            <div class="h-full overflow-auto">
                <div class="mb-6 grid grid-cols-1 gap-2 md:grid-cols-2">
                    <div class="col-span-2 mb-4">
                        <label for="editName"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                        <input type="text" id="editName" name="name" placeholder="Nama"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            required>
                    </div>
                    <div class="col-span-2 mb-4">
                        <label for="editNpwp" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">No.
                            NPWP</label>
                        <input type="number" id="editNpwp" name="npwp" placeholder="No. NPWP"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                    </div>
                    <div class="col-span-1 mb-4">
                        <label for="editTipeCustomer"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                        <select name="tipe_customer" id="editTipeCustomer"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            required>
                            <option value="pribadi">Pribadi</option>
                            <option value="gov">GOV</option>
                            <option value="bumn">BUMN</option>
                            <option value="swasta">Swasta</option>
                        </select>
                    </div>
                    <div class="col-span-1 mb-4">
                        <label for="editTermOfPayments"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Term of
                            Payments</label>
                        <div class="flex items-center gap-2">
                            <input type="number" id="editTermOfPayments" name="term_of_payments" placeholder="30"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                                min="0">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Hari</span>
                        </div>
                    </div>
                    <div class="col-span-2 mb-4">
                        <label for="editKreditLimit"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kredit Limit</label>
                        <input type="text" id="editKreditLimit" name="kredit_limit" placeholder="Kredit Limit"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                    </div>
                    <div class="col-span-2 mb-4">
                        <label for="editAlamat"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Alamat</label>
                        <input type="text" id="editAlamat" name="alamat" placeholder="Alamat"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                    </div>
                    <div class="col-span-1 mb-4">
                        <label for="editKota"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kota</label>
                        <input type="text" id="editKota" name="kota" placeholder="Kota"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                    </div>
                    <div class="col-span-1 mb-4">
                        <label for="editProvinsi"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Provinsi</label>
                        <input type="text" id="editProvinsi" name="provinsi" placeholder="Provinsi"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                    </div>
                    <div class="col-span-1 mb-4">
                        <label for="editKodePos"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kode
                            Pos</label>
                        <input type="text" id="editKodePos" name="kode_pos" placeholder="Kode Pos"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                    </div>
                    <div class="col-span-2 mb-4">
                        <label for="editTelepon"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">No.
                            HP</label>
                        <input type="tel" id="editTelepon" name="telepon" placeholder="08xxxxxxxxx"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            required>
                    </div>
                    <div class="col-span-2 mb-4">
                        <label for="editEmail"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" id="editEmail" name="email" placeholder="example@example.com"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                    </div>
                    <!-- Dynamic PIC Fields -->
                    <div class="col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">PIC (Minimal
                            1)</label>
                        <div id="edit-pic-container" class="space-y-4">
                            <!-- Rows will be populated by JS -->
                        </div>
                        <button type="button" id="edit-add-pic-btn"
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
    let editPicIndex = 0;

    // Helper to add PIC row
    function addEditPicRow(pic = null) {
        const container = document.getElementById('edit-pic-container');
        const index = editPicIndex++;

        const row = document.createElement('div');
        row.className =
            'pic-row grid grid-cols-1 gap-2 rounded-lg border border-gray-200 p-3 relative dark:border-gray-600 md:grid-cols-2 bg-gray-50 dark:bg-gray-800';

        // Values
        const id = pic ? pic.id : '';
        const name = pic ? pic.name : '';
        const phone = pic ? pic.phone : '';
        const email = pic ? pic.email : '';
        const position = pic ? pic.position : '';

        // Hidden ID input if existing
        const idInput = id ? `<input type="hidden" name="pics[${index}][id]" value="${id}">` : '';

        row.innerHTML = `
            ${idInput}
            <button type="button" class="remove-pic-btn absolute -right-2 -top-2 rounded-full bg-red-100 p-1 text-red-600 hover:bg-red-200 shadow-sm z-10">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="col-span-1">
                <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Nama PIC <span class="text-red-500">*</span></label>
                <input type="text" name="pics[${index}][name]" value="${name}" placeholder="Nama Lengkap" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white" required>
            </div>
            <div class="col-span-1">
                <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">No. HP <span class="text-red-500">*</span></label>
                <input type="tel" name="pics[${index}][phone]" value="${(phone || '')}" placeholder="08xxxxxxxx" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white" required>
            </div>
            <div class="col-span-1">
                <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Email <span class="text-red-500">*</span></label>
                <input type="email" name="pics[${index}][email]" value="${(email || '')}" placeholder="email@example.com" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white" required>
            </div>
            <div class="col-span-1">
                <label class="mb-1 block text-xs font-medium text-gray-700 dark:text-gray-300">Posisi/Jabatan <span class="text-red-500">*</span></label>
                <input type="text" name="pics[${index}][position]" value="${(position || '')}" placeholder="Manager, Staff, dll" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white" required>
            </div>
        `;

        container.appendChild(row);

        // Bind remove event
        row.querySelector('.remove-pic-btn').addEventListener('click', function() {
            row.remove();
        });
    }

    // Tunggu dokumen siap
    $(document).ready(function() {
        // Add listener for adding new PIC row in edit modal
        $('#edit-add-pic-btn').on('click', function() {
            addEditPicRow();
        });
    });

    // Fungsi utama openEditModal
    function openEditModal(customer) {
        // Set form action
        const form = document.getElementById('editCustomerForm');
        form.action = "{{ route('customer.update', ['customer' => ':id']) }}".replace(':id', customer
            .id); // Reset base route then replace

        // Isi data form
        document.getElementById('editCustomerId').value = customer.id;
        document.getElementById('editName').value = customer.nama_customer || '';
        document.getElementById('editNpwp').value = customer.npwp || '';
        document.getElementById('editTipeCustomer').value = customer.tipe_customer ? customer.tipe_customer
            .toLowerCase() : 'pribadi';
        document.getElementById('editTermOfPayments').value = customer.term_of_payments || '';
        document.getElementById('editKreditLimit').value = customer.kredit_limit || '';
        document.getElementById('editAlamat').value = customer.alamat || '';
        document.getElementById('editKota').value = customer.kota || '';
        document.getElementById('editProvinsi').value = customer.provinsi || '';
        document.getElementById('editKodePos').value = customer.kode_pos || '';
        document.getElementById('editTelepon').value = customer.telepon || '';
        document.getElementById('editEmail').value = customer.email || '';

        // Reset PIC Container
        const container = document.getElementById('edit-pic-container');
        container.innerHTML = '';
        editPicIndex = 0;

        // Ambil PICs langsung dari tabel via AJAX
        const picsUrl = "{{ url('admin/customer') }}" + "/" + customer.id + "/pics";

        fetch(picsUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(customerPics => {
                // Populate rows
                if (customerPics && customerPics.length > 0) {
                    customerPics.forEach(pic => {
                        addEditPicRow(pic);
                    });
                } else {
                    // Jika tidak ada PIC, tambahkan 1 baris kosong (optional, tapi good UX)
                    addEditPicRow();
                }

                // Buka modal setelah data siap
                setTimeout(() => {
                    const modal = document.getElementById('editCustomerModal');
                    if (modal) modal.showModal();
                }, 100);
            })
            .catch(err => {
                console.error(err);
                // fallback: buka modal tanpa data PIC (add 1 empty row)
                addEditPicRow();
                const modal = document.getElementById('editCustomerModal');
                if (modal) modal.showModal();
            });
    }
</script>
