<!-- Create Sales Account Modal -->
<dialog id="createUserModal" class="modal">
    <div class="modal-box relative flex max-w-xl flex-col overflow-hidden rounded-lg bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
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
        <form action="{{ route('akun-sales.store') }}" method="POST" class="flex h-full flex-col space-y-4 overflow-auto p-4">
            <div class="h-full overflow-auto">
                <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-1">

                    @csrf
                    <div class="mb-4">
                        <label for="createName" class="block text-sm font-medium">Nama</label>
                        <input type="text" id="createName" name="name" placeholder="Nama" class="w-full rounded border px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label for="createEmail" class="block text-sm font-medium">Email</label>
                        <input type="email" id="createEmail" name="email" placeholder="example@example.com" autocomplete="new-email" class="w-full rounded border px-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="createPassword" class="block text-sm font-medium">Password</label>
                        <input type="password" id="createPassword" name="password" placeholder="Password" autocomplete="new-password" class="w-full rounded border px-3 py-2" required>
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
<dialog id="editUserModal" class="modal">
    <div class="modal-box relative flex max-w-xl flex-col overflow-hidden rounded-lg bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
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
                <input type="text" id="editName" name="name" class="w-full rounded border px-3 py-2">
            </div>
            <div class="mb-4">
                <label for="editEmail" class="block text-sm font-medium">Email</label>
                <input type="email" id="editEmail" name="email" class="w-full rounded border px-3 py-2">
            </div>
            <div class="mb-4">
                <label for="editPassword" class="block text-sm font-medium">Password</label>
                <input type="password" id="editPassword" name="password" placeholder="Isi untuk mengubah password" autocomplete="new-password" class="w-full rounded border px-3 py-2">
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
    window.CHECK_EMAIL_URL = "{{ route('check.email') }}";
    window.CSRF_TOKEN = "{{ csrf_token() }}";
</script>

@vite(['resources/js/checker.js'])
