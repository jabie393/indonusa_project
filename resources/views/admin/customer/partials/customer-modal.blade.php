<!-- Create Sales Account Modal -->
<dialog id="createCustomerModal" class="modal">
    <div
        class="modal-box relative flex max-w-xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        <div
            class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">
                Tambah Akun Sales </h3>
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
            class="flex h-full flex-col space-y-4 overflow-auto p-4">
            <div class="h-full overflow-auto">
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
                        <input type="text" id="npwp" name="npwp" placeholder="No. NPWP"
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
                        <input type="text" id="term_of_payments" name="term_of_payments" placeholder="Term of Payments"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                    </div>
                    <div class="col-span-2 mb-4">
                        <label for="kredit_limit"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Kredit Limit</label>
                        <input type="text" id="kredit_limit" name="kredit_limit" placeholder="Kredit Limit"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                    </div>
                    <div class="col-span-2 mb-4">
                        <label for="alamat"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Alamat</label>
                        <input type="text" id="alamat" name="alamat" placeholder="Alamat"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
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
                        <input type="text" id="telepon" name="telepon" placeholder="08xxxxxxxxx"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            required>
                    </div>
                    <div class="col-span-2 mb-4">
                        <label for="email"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" id="email" name="email" placeholder="example@example.com"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                    </div>
                    <div class="col-span-2 mb-4">
                        <label for="pic"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">PIC</label>
                        <select id="pic" name="pic"
                            class="form-control block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                            multiple="multiple" required>
                            <optgroup>
                                @foreach($salesUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} (Sales)</option>
                                @endforeach
                                @foreach($pics as $pic)
                                    <option value="{{ $pic->id }}">{{ $pic->name }} ({{ $pic->position }})</option>
                                @endforeach
                            </optgroup>
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
{{-- <dialog id="editUserModal" class="modal">
    <div
        class="modal-box relative flex max-w-xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        <div
            class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">Edit Akun Sales</h3>
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
        <form id="editUserForm" action="" method="POST" class="flex h-full flex-col space-y-4 overflow-auto p-4">
            @csrf
            @method('PUT')
            <input type="hidden" id="editUserId" name="id">
            <div class="mb-4">
                <label for="editName" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                <input type="text" id="editName" name="name"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
            </div>
            <div class="mb-4">
                <label for="editEmail"
                    class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <input type="email" id="editEmail" name="email"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
            </div>
            <div class="mb-4">
                <label for="editPassword"
                    class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Password</label>
                <input type="password" id="editPassword" name="password" placeholder="Isi untuk mengubah password"
                    autocomplete="new-password"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
            </div>
            <div class="flex justify-end">
                <button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog> --}}

<script>
    window.CHECK_EMAIL_URL = "{{ route('check.email') }}";
    window.CSRF_TOKEN = "{{ csrf_token() }}";
</script>
<script>
        $("#pic").select2({
            tags: true,
            placeholder: "Ketik untuk mencari PIC atau isi manual...",
            dropdownParent: $("#createCustomerModal"), // Pastikan ID modal sesuai
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
            }
        });
</script>

@vite(['resources/js/checker.js'])