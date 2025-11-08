<x-app-layout>
    <div class="relative overflow-hidden rounded-lg bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0B1D31] p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h2 class="mr-3 font-semibold text-white">Delivery Orders</h2>
            </div>
            <div class="flex w-full flex-col py-5 md:w-auto md:flex-row md:py-0">
                <div class="mr-5 flex max-w-full shrink-0 flex-col items-stretch justify-end space-y-2 py-5 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0 md:py-0">
                    {{-- Search --}}
                    <form action="{{ route('delivery-orders.index') }}" method="GET" class="block pl-2">
                        <label for="topbar-search" class="sr-only">Search</label>
                        <div class="relative md:w-64 md:w-96">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                                    </path>
                                </svg>
                            </div>
                            <input type="search" name="search" id="topbar-search dt-search-0" aria-controls="warehouseTable" value="{{ request('search') }}"
                                class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                placeholder="Search" />
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if (isset($orders) && $orders->isEmpty())
            <div class="mt-4 text-sm text-gray-500">Belum ada delivery order dengan status <strong>sent_to_warehouse</strong>.</div>
        @else
            <div class="overflow-x-auto">
                <table id="DataTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">No. Order</th>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Nama Supervisor</th>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Dibuat</th>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach ($orders as $order)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2">{{ $order->order_number }}</td>
                                <td class="whitespace-nowrap px-4 py-2">{{ optional($order->supervisor)->name ?? '-' }}</td>
                                <td class="whitespace-nowrap px-4 py-2">{{ $order->status }}</td>
                                <td class="whitespace-nowrap px-4 py-2">{{ optional($order->created_at)->format('Y-m-d H:i') }}</td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    <button type="button" class="js-show-order mb-2 me-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" data-order-id="{{ $order->id }}"
                                        data-order-number="{{ $order->order_number }}" data-items='@json($order->items)'>Show</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <nav class="flex flex-col items-start justify-between space-y-3 p-4 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        Showing
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }}</span>
                        of
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $orders->total() ?? $orders->count() }}</span>
                    </span>
                    <form method="GET" action="{{ route('delivery-orders.index') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="perPage" onchange="this.form.submit()" class="ml-2 rounded  pl-2 pr-5 border-gray-300 p-1 text-sm">
                            @foreach ([10, 25, 50, 100] as $size)
                                <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                    </form>
                    <span class="text-sm text-gray-500 dark:text-gray-400">per halaman</span>
                </div>
                <div>
                    {{ $orders->links() }}
                </div>
            </nav>
        @endif
    </div>
    </div>

    <!-- Modals -->
    @include('components.delivery-orders-modal-show')
    @vite(['resources/js/delivery-orders.js', 'resources/js/dataTable.js'])

</x-app-layout>
