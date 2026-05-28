<dialog id="delivery-order-modal" class="modal">
    <div class="modal-box relative flex h-full w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-800 sm:max-h-[90vh]">
        <header class="relative flex items-center justify-between px-7 py-5 text-white"
            style="background-image: var(--gradient-header)">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold leading-tight">Detail Delivery Order</h3>
                    <p class="text-xs text-white/80">No. DO: <span id="delivery-order-number">#-</span></p>
                </div>
            </div>
            <form method="dialog">
                <button id="delivery-order-close-top" type="button" aria-label="Tutup"
                    class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </form>
        </header>

        <div class="overflow-auto p-4">
            <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-700 dark:text-white">Items</h4>
            </div>

            <div class="w-full overflow-x-auto">
                <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
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
