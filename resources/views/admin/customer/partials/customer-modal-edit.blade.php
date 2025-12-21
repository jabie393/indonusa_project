<!-- Edit Sales Account Modal -->
<dialog id="editCustomerModal" class="modal">
    <div
        class="modal-box relative flex max-w-xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        <div
            class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">
                Edit Akun Sales </h3>
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
                        <input type="text" id="editTermOfPayments" name="term_of_payments"
                            placeholder="Term of Payments"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
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
                    <div class="col-span-2 mb-4">
                        <label for="editPic"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">PIC</label>
                        <select id="editPic" name="pics[]"
                            class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            multiple="multiple" required>
                            <!-- Options akan diisi via JavaScript -->
                        </select>
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

<!-- Edit Sales Account Modal -->
<dialog id="editCustomerModal" class="modal">
    <!-- ... (modal HTML tetap sama seperti sebelumnya) ... -->
</dialog>

<script>
    // Data global
    let salesUsersData = @json($salesUsers);
    let picsData = @json($pics);

    // Fungsi inisialisasi Select2
    function initSelect2() {
        if ($.fn.select2) {
            $('#editPic').select2({
                tags: true,
                placeholder: "Ketik untuk mencari PIC atau isi manual...",
                dropdownParent: $("#editCustomerModal"),
                createTag: function (params) {
                    let term = $.trim(params.term);
                    if (term === '') return null;
                    return {
                        id: term,
                        text: term + " (baru)",
                        newTag: true
                    };
                },
                templateResult: function (data) {
                    if (data.newTag) {
                        return $('<span>' + data.text + '</span>');
                    }
                    return data.text;
                },
                templateSelection: function (data) {
                    if (data.newTag) {
                        return data.id;
                    }
                    return data.text;
                }
            }).on('select2:open', function () {
                // Pastikan dropdown berada di dalam modal
                $('.select2-dropdown').css('z-index', '9999');
            });

            // Sembunyikan dropdown awal
            $('#editPic').select2('close');
        }
    }

    // Tunggu dokumen siap
    $(document).ready(function () {
        // Inisialisasi Select2 jika sudah tersedia
        if ($.fn.select2) {
            initSelect2();
        }

        // Event untuk menutup modal - reset select2
        $('#editCustomerModal').on('close', function () {
            if ($.fn.select2 && $('#editPic').hasClass('select2-hidden-accessible')) {
                $('#editPic').val(null).trigger('change');
            }
        });

        // Pastikan saat submit, nilai yang dikirim ke server tidak mengandung " (baru)"
        $('#editCustomerForm').on('submit', function () {
            if ($.fn.select2) {
                const $el = $('#editPic');
                let vals = $el.val() || [];
                console.log('Submitting PIC values:', vals);
                vals = vals.map(function (v) {
                    try {
                        return v.replace(/\s*\(baru\)$/, '');
                    } catch (e) {
                        return v;
                    }
                });
                $el.val(vals);
                console.log('Cleaned PIC values:', vals);
            }
        });
    });

    // Fungsi utama openEditModal
    function openEditModal(customer) {
        // Set form action
        const form = document.getElementById('editCustomerForm');
        form.action = form.action.replace(':id', customer.id);

        // Isi data form
        document.getElementById('editCustomerId').value = customer.id;
        document.getElementById('editName').value = customer.nama_customer || '';
        document.getElementById('editNpwp').value = customer.npwp || '';
        document.getElementById('editTipeCustomer').value = customer.tipe_customer ? customer.tipe_customer.toLowerCase() : 'pribadi';
        document.getElementById('editTermOfPayments').value = customer.term_of_payments || '';
        document.getElementById('editKreditLimit').value = customer.kredit_limit || '';
        document.getElementById('editAlamat').value = customer.alamat || '';
        document.getElementById('editKota').value = customer.kota || '';
        document.getElementById('editProvinsi').value = customer.provinsi || '';
        document.getElementById('editKodePos').value = customer.kode_pos || '';
        document.getElementById('editTelepon').value = customer.telepon || '';
        document.getElementById('editEmail').value = customer.email || '';

        const editPicDropdown = $('#editPic');
        editPicDropdown.empty();

        // Ambil PICs langsung dari tabel via AJAX
        const picsUrl = "{{ url('admin/customer') }}" + "/" + customer.id + "/pics";

        fetch(picsUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(customerPics => {
                console.log('Customer PICs loaded:', customerPics);
                // Render daftar customer_pics ke div #customerPicsList
                (function renderCustomerPicsList(picsList) {
                    const container = document.getElementById('customerPicsList');
                    if (!container) return;
                    container.innerHTML = '';
                    if (!picsList || picsList.length === 0) {
                        container.innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-300">Tidak ada PIC untuk customer ini.</p>';
                        return;
                    }
                    const ul = document.createElement('ul');
                    ul.className = 'space-y-2';
                    picsList.forEach((p) => {
                        const type = p.pivot ? (p.pivot.pic_type || 'Unknown') : 'Unknown';
                        const name = p.name || '—';
                        const position = p.position || '';
                        const addedAt = (p.pivot && p.pivot.created_at) ? p.pivot.created_at : '';
                        const li = document.createElement('li');
                        li.className = 'flex items-start justify-between gap-3';
                        li.innerHTML = `
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">${escapeHtml(name)}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-300">
                                ${escapeHtml(type)}${position ? ' · ' + escapeHtml(position) : ''}
                                ${addedAt ? ' · ditambahkan: ' + escapeHtml(addedAt) : ''}
                            </div>
                        </div>
                    `;
                        ul.appendChild(li);
                    });
                    container.appendChild(ul);
                })(customerPics);

                // Sekarang build options Select2 dari salesUsersData dan picsData, dan tandai selected berdasarkan customerPics
                const selectedValues = [];

                // Sales Users
                salesUsersData.forEach(user => {
                    const isSelected = customerPics.some(cp => Number(cp.id) === Number(user.id) && cp.pivot && cp.pivot.pic_type === 'User');
                    const optionValue = JSON.stringify({ id: user.id, type: 'User' });
                    const optionText = `${user.name} (Sales)`;
                    if (isSelected) selectedValues.push(optionValue);
                    editPicDropdown.append(new Option(optionText, optionValue, isSelected, isSelected));
                });

                // PICs (master list from picsData)
                picsData.forEach(pic => {
                    const isSelected = customerPics.some(cp => Number(cp.id) === Number(pic.id) && cp.pivot && cp.pivot.pic_type === 'Pic');
                    const position = pic.position || 'Tanpa jabatan';
                    const optionValue = JSON.stringify({ id: pic.id, type: 'Pic' });
                    const optionText = `${pic.name} (${position})`;
                    if (isSelected) selectedValues.push(optionValue);
                    editPicDropdown.append(new Option(optionText, optionValue, isSelected, isSelected));
                });

                // Init / re-init Select2 dan set selected
                setTimeout(() => {
                    if ($.fn.select2) {
                        if (!editPicDropdown.hasClass('select2-hidden-accessible')) {
                            initSelect2();
                        } else {
                            editPicDropdown.select2('destroy');
                            initSelect2();
                        }

                        console.log('Setting selected values:', selectedValues);
                        if (selectedValues.length > 0) {
                            editPicDropdown.val(selectedValues).trigger('change');
                        } else {
                            editPicDropdown.val(null).trigger('change');
                        }
                    }
                }, 50);

                // Buka modal setelah data siap
                setTimeout(() => {
                    const modal = document.getElementById('editCustomerModal');
                    if (modal) modal.showModal();
                }, 100);
            })
            .catch(err => {
                // fallback: buka modal tanpa data PIC
                const modal = document.getElementById('editCustomerModal');
                if (modal) modal.showModal();
            });
    }

    // Override fungsi openEditModal untuk tambahan debug + fallback fetch jika pics kosong
    const originalOpenEditModal = window.openEditModal || function (c) { };

    window.openEditModal = function (customer) {
        // jika relationship pics tidak ada/ kosong, fetch detail dari server
        if (!customer.pics || !customer.pics.length) {
            fetch(`/admin/customer/${customer.id}/json`)
                .then(res => {
                    if (!res.ok) throw new Error('Failed to fetch customer');
                    return res.json();
                })
                .then(fullCustomer => {
                    originalOpenEditModal(fullCustomer);
                })
                .catch(err => {
                    // fallback: buka modal dengan data yg ada (tanpa pic selected)
                    originalOpenEditModal(customer);
                });
        } else {
            originalOpenEditModal(customer);
        }
    };

    // Fungsi escape HTML
    $("#editPic").select2({
        tags: true,
        placeholder: "Ketik untuk mencari PIC atau isi manual...",
        dropdownParent: $("#editCustomerModal"),
        createTag: function (params) {
            let term = $.trim(params.term);

            if (term === '') {
                return null;
            }

            return {
                id: term,
                text: term + " (baru)",
                newTag: true
            };
        },
        templateResult: function (data) {
            if (data.newTag) {
                return $('<span>' + data.text + '</span>');
            }
            return data.text;
        },
        templateSelection: function (data) {
            return data.text || data.id;
        }
    });
</script>
