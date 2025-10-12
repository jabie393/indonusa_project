<div id="orderDetailModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="fixed inset-0 bg-black opacity-50"></div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-2xl w-full z-10 p-6 mx-4">
        <div class="flex justify-between items-start">
            <h2 id="modal_order_number" class="text-xl font-semibold text-gray-900 dark:text-gray-100"></h2>
            <button id="close_order_modal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">&times;</button>
        </div>

        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300"><strong>Status:</strong> <span id="modal_status"></span></p>
        <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Reason:</strong> <span id="modal_reason">-</span></p>

        <h3 class="mt-4 font-semibold text-gray-900 dark:text-gray-100">Items</h3>
        <div class="overflow-x-auto mt-2">
            <table id="modal_items" class="min-w-full text-sm text-gray-700 dark:text-gray-300">
                <thead>
                    <tr>
                        <th class="text-left">Kode</th>
                        <th class="text-left">Item</th>
                        <th class="text-left">Qty</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <button id="modal_close_btn" class="px-3 py-1 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded">Close</button>
        </div>
    </div>
</div>
