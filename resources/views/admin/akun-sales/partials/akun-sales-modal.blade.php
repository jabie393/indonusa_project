<!-- Create Sales Account Modal -->
<dialog id="createUserModal" class="modal">
    <div class="modal-box inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative flex max-w-3xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        <div class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
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
        <form id="createUserForm" action="{{ route('akun-sales.store') }}" method="POST" class="flex h-full flex-col space-y-4 overflow-auto p-4">
            <div class="h-full overflow-auto">
                <div class="grid grid-cols-1 gap-2 md:grid-cols-2">

                    @csrf
                    <div class="col-span-2 mb-4">
                        <label for="createName" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                        <input type="text" id="createName" name="name" placeholder="Nama" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" required>
                    </div>
                    <div class="col-span-2 mb-4">
                        <label for="createEmail" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" id="createEmail" name="email" placeholder="example@example.com" autocomplete="new-email" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" required>
                    </div>
                    <div class="col-span-2 mb-4">
                        <label for="createPassword" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" id="createPassword" name="password" placeholder="Password" autocomplete="new-password" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400" required>
                    </div>
                </div>
            </div>


        </form>

        <footer class="flex items-center justify-between gap-3 border-t border-slate-100 bg-slate-50 px-7 py-5 dark:bg-gray-800 dark:border-gray-700">
            <p class="hidden text-xs text-slate-500 sm:block dark:text-gray-400">Pastikan data sudah akurat sebelum menyimpan.</p>
            <div class="flex flex-1 justify-end gap-3 sm:flex-none">
                <form method="dialog">
                    <button class="px-6 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-200 active:scale-95 rounded-xl dark:text-gray-300 dark:hover:bg-gray-700" type="submit">
                        Batal
                    </button>
                </form>
                <button class="px-8 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:opacity-90 active:scale-95 rounded-xl bg-gradient-to-r from-[#225A97] to-[#0D223A]" form="createUserForm" style="background-image: var(--gradient-brand)" type="submit">
                    Tambahkan Sales
                </button>
            </div>
        </footer>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Edit Sales Account Modal -->
<dialog id="editUserModal" class="modal">
    <div class="modal-box inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative flex max-w-3xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        <div class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
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
                <label for="editName" class="block text-sm font-medium">Nama</label>
                <input type="text" id="editName" name="name" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
            </div>
            <div class="mb-4">
                <label for="editEmail" class="block text-sm font-medium">Email</label>
                <input type="email" id="editEmail" name="email" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
            </div>
            <div class="mb-4">
                <label for="editPassword" class="block text-sm font-medium">Password</label>
                <input type="password" id="editPassword" name="password" placeholder="Isi untuk mengubah password" autocomplete="new-password" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
            </div>
        </form>

        <footer class="flex items-center justify-between gap-3 border-t border-slate-100 bg-slate-50 px-7 py-5 dark:bg-gray-800 dark:border-gray-700">
            <p class="hidden text-xs text-slate-500 sm:block dark:text-gray-400">Pastikan data sudah akurat sebelum menyimpan.</p>
            <div class="flex flex-1 justify-end gap-3 sm:flex-none">
                <form method="dialog">
                    <button class="px-6 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-200 active:scale-95 rounded-xl dark:text-gray-300 dark:hover:bg-gray-700" type="submit">
                        Batal
                    </button>
                </form>
                <button class="px-8 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:opacity-90 active:scale-95 rounded-xl bg-gradient-to-r from-[#225A97] to-[#0D223A]" form="editUserForm" style="background-image: var(--gradient-brand)" type="submit">
                    Simpan Perubahan
                </button>
            </div>
        </footer>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    window.CHECK_EMAIL_URL = "{{ route('check.email') }}";
    window.CSRF_TOKEN = "{{ csrf_token() }}";
</script>

@vite(['resources/js/checker.js'])
