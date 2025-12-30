<!-- Create PICS Modal -->
<dialog id="createPicsModal" class="modal">
    <div
        class="modal-box relative flex max-w-xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        <div
            class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">
                Tambah Pic </h3>
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
        <form action="{{ route('pics.store') }}" method="POST"
            class="flex h-full flex-col space-y-4 overflow-auto p-4">
            <div class="mb-6 grid grid-cols-1 gap-2 md:grid-cols-2">
                @csrf
                <div class="col-span-2 mb-4">
                    <label for="createName"
                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                    <input type="text" id="createName" name="name" placeholder="Jhon Doe"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                        required>
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
                <div class="col-span-2 mb-4">
                    <label for="position"
                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Posisi/Jabatan</label>
                    <input type="text" id="position" name="position" placeholder="Posisi/Jabatan"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
                </div>
                <!-- PIC of Customer -->
                <div class="col-span-2 mb-4">
                    <label for="createCustomerId"
                        class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">PIC of Customer</label>
                    <select id="createCustomerId" name="customer_id" class="select2 block w-full" required>
                        <option value="">-- Pilih Customer --</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->nama_customer }}</option>
                        @endforeach
                    </select>
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
<dialog id="editPicsModal" class="modal">
    <div
        class="modal-box relative flex max-w-xl flex-col rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        <div
            class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">
                Edit Pic </h3>
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
        <form id="editPicsForm" method="POST" class="flex h-full flex-col space-y-4 overflow-auto p-4">
            @csrf
            @method('PUT')
            <input type="hidden" id="editPicsId" name="id">
            <div class="mb-4">
                <label for="editName" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                <input type="text" id="editName" name="name" placeholder="Jhon Doe"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
            </div>
            <div class="col-span-2 mb-4">
                <label for="editTelepon" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">No.
                    HP</label>
                <input type="text" id="editTelepon" name="telepon" placeholder="08xxxxxxxxx"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                    required>
            </div>
            <div class="mb-4">
                <label for="editEmail"
                    class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <input type="email" id="editEmail" name="email" placeholder="example@example.com"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
            </div>
            <div class="col-span-2 mb-4">
                <label for="editPosition"
                    class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Posisi/Jabatan</label>
                <input type="text" id="editPosition" name="position" placeholder="Posisi/Jabatan"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400">
            </div>
            <!-- PIC of Customer -->
            <div class="col-span-2 mb-4">
                <label for="editCustomerId" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">PIC
                    of Customer</label>
                <select id="editCustomerId" name="customer_id"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-500 dark:bg-gray-600 dark:text-white dark:placeholder-gray-400"
                    required>
                    <option value="">-- Pilih Customer --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->nama_customer }}</option>
                    @endforeach
                </select>
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

<style>
    /* Ensure Select2 Dropdown is visible on top of modal */
    .select2-container--open {
        z-index: 9999999 !important;
    }

    .select2-dropdown {
        z-index: 9999999 !important;
    }
</style>

<script>
    $(document).ready(function() {
        // Init Select2 for Create Modal
        $('#createCustomerId').select2({
            placeholder: "Cari Customer...",
            allowClear: true,
            dropdownParent: $('#createPicsModal'),
            width: '100%',
            minimumResultsForSearch: 0
        });

        // Init Select2 for Edit Modal
        $('#editCustomerId').select2({
            placeholder: "Cari Customer...",
            allowClear: true,
            dropdownParent: $('#editPicsModal'),
            width: '100%',
            minimumResultsForSearch: 0
        });

        // Event listener to auto-focus search field when Select2 opens
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
    });
</script>
