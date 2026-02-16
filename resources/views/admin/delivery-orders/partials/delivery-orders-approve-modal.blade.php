<dialog id="delivery-orders-approve-modal" class="modal">
    <div
        class="modal-box relative flex w-full max-w-lg flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-800">
        <div
            class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">
                Pilih Tipe Pengiriman
            </h3>
            <div class="modal-action m-0">
                <form method="dialog">
                    <button type="button"
                        class="js-approve-modal-close ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <div id="selection-view" class="p-6">
            <p class="mb-6 text-center text-gray-600 dark:text-gray-400">
                Silakan pilih metode pengiriman untuk order <span id="approve-order-number"
                    class="font-bold text-gray-900 dark:text-white"></span>
            </p>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                {{-- Full Delivery --}}
                <form id="full-delivery-form" action="" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex h-full w-full flex-col items-center justify-center rounded-xl border-2 border-gray-100 bg-gray-50 p-6 transition-all hover:border-blue-500 hover:bg-blue-50 dark:border-gray-700 dark:bg-gray-700 dark:hover:border-blue-400 dark:hover:bg-gray-600">
                        <div
                            class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">Full Delivery</span>
                        <span class="mt-1 text-xs text-gray-500">Kirim semua item sekaligus</span>
                    </button>
                </form>

                {{-- Partial Delivery --}}
                <button type="button" id="btn-partial-delivery"
                    class="flex h-full w-full flex-col items-center justify-center rounded-xl border-2 border-gray-100 bg-gray-50 p-6 transition-all hover:border-orange-500 hover:bg-orange-50 dark:border-gray-700 dark:bg-gray-700 dark:hover:border-orange-400 dark:hover:bg-gray-600">
                    <div
                        class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-orange-100 text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">Partial Delivery</span>
                    <span class="mt-1 text-xs text-gray-500 text-center">Kirim item secara bertahap</span>
                </button>
            </div>
        </div>

        {{-- Partial View --}}
        <div id="partial-view" class="hidden flex flex-col h-full overflow-hidden">
            <div class="flex-1 overflow-auto p-4">
                <div class="mb-4 flex items-center justify-between">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-white">Sesuaikan Item Pengiriman</h4>
                    <span id="partial-order-number"
                        class="text-xs font-semibold text-blue-600 dark:text-blue-400"></span>
                </div>

                <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Barang</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Pesanan</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Stok</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400 w-32">
                                    Kirim</th>
                            </tr>
                        </thead>
                        <tbody id="partial-items-body"
                            class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            {{-- JS will populate rows --}}
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="border-t bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900 flex justify-between gap-3">
                <button type="button" id="btn-back-to-selection"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                    Kembali
                </button>
                <form id="partial-delivery-form" action="" method="POST">
                    @csrf
                    <div id="partial-inputs-container"></div>
                    <button type="submit" id="btn-submit-partial"
                        class="rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Proses Partial Delivery
                    </button>
                </form>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
