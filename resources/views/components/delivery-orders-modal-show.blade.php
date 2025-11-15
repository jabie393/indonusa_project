<dialog id="delivery-order-modal" class="modal">
    <div class="modal-box relative flex h-full w-full max-w-5xl flex-col overflow-hidden rounded-lg bg-white p-0 shadow dark:bg-gray-800 sm:max-h-[90vh]">
        <div class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">
                Detail Delivery Order - <span id="delivery-order-number" class="font-normal">#-</span>
            </h3>
            <div class="modal-action m-0">
                <form method="dialog">
                    <!-- if there is a button in form, it will close the modal -->
                    <button id="delivery-order-close-top" type="button"
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

        <div class="overflow-auto p-4">
            <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-700 dark:text-white">Items</h4>
            </div>

            <div class="w-full overflow-x-auto">
                <table id="DataTable" class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400"">
                        <tr>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Kode Barang</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Nama Barang</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Qty</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Delivered</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody id="delivery-order-items-body" class="">
                        <!-- JS will populate rows here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>