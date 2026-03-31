<x-app-layout>
        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-end overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
            <div class="p-4">
                {{-- Search --}}
                <form action="{{ route('sales.delivery-orders.index') }}" method="GET" class="block pl-2">
                    <label for="topbar-search" class="sr-only">Search</label>
                    <div class="relative md:w-64 md:w-96">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1111.472 3.472l4.243 4.243a1 1 0 01-1.415 1.415l-4.243-4.243A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="search" name="search" id="topbar-search dt-search-0" aria-controls="warehouseTable"
                            value="{{ request('search') }}"
                            class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            placeholder="Search" />
                    </div>
                </form>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
            <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
            @if (Auth::user() && in_array(Auth::user()->role, ['Sales']))
                @php
                    $currentStatus = session('warehouse_filter_status', 'masuk');
                    $deliveryOrderCount = \App\Models\Order::where('status', 'sent_to_warehouse')->count();
                @endphp
                <div class="flex items-center space-x-2">
                    <a href="{{ route('warehouse.index', ['status' => 'masuk', 'search' => request('search')]) }}"
                        class="text-white hover:bg-white/10 rounded-lg px-4 py-2 text-sm font-medium transition-all">
                        Semua Barang
                    </a>
                    <a href="{{ route('sales.delivery-orders.index') }}"
                        class="bg-white text-[#225A97] hover:bg-white/10 flex items-center rounded-lg px-4 py-2 text-sm font-medium transition-all">
                        Delivery Orders
                        @if ($deliveryOrderCount > 0)
                            <span
                                class="ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">{{ $deliveryOrderCount }}</span>
                        @endif
                    </a>
                </div>
            @endif
        </div>

            <div class="overflow-x-auto">
                <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-nowrap text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-2">Customer</th>
                            <th class="px-4 py-2">No. DO</th>
                            <th class="px-4 py-2">No. SO</th>
                            <th class="px-4 py-2">No. PO</th>
                            <th class="px-4 py-2">Nama Sales</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Dibuat</th>
                            <th class="px-4 py-2">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="h-min-[300px]">
                        @foreach ($orders as $order)
                            <tr class="dark:border-gray-700">
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900 dark:text-white">
                                    {{ $order->customer?->nama_customer ?? $order->customer_name }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900 dark:text-white">
                                    {{ $order->do_number ?? $order->order_number }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900 dark:text-white">
                                    {{ $order->requestOrder?->sales_order_number ?? $order->no_so }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900 dark:text-white">
                                    {{ $order->requestOrder?->no_po ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900 dark:text-white">
                                    {{ $order->requestOrder?->sales?->name ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900 dark:text-white">
                                    @php
                                        $statusClass = [
                                            'sent_to_warehouse' => 'bg-yellow-50 text-yellow-800 inset-ring inset-ring-yellow-600',
                                            'completed' => 'bg-green-50 text-green-700 inset-ring inset-ring-green-600',
                                            'not_completed' => 'bg-orange-50 text-orange-700 inset-ring inset-ring-orange-600',
                                            'rejected_warehouse' => 'bg-red-50 text-red-700 inset-ring inset-ring-red-700',
                                        ][$order->status] ?? 'bg-gray-100 text-gray-800 inset-ring inset-ring-gray-600';
                                        $statusLabel = [
                                            'sent_to_warehouse' => 'New Request',
                                            'completed' => 'Completed',
                                            'not_completed' => 'Partial Delivery',
                                            'rejected_warehouse' => 'Rejected',
                                        ][$order->status] ?? $order->status;
                                    @endphp
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="{{ $statusClass }} badge">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900 dark:text-white">
                                    {{ optional($order->created_at)->format('Y-m-d H:i') }}
                                </td>
                                <td class="w-fit px-4 py-3 text-right">
                                    @php
                                        $itemsData = $order->items->map(function ($item) {
                                            return [
                                                'id'                 => $item->id,
                                                'kode_barang'        => $item->barang->kode_barang ?? $item->barang->kode ?? $item->kode_barang ?? $item->barang_id ?? '-',
                                                'nama'               => $item->barang->nama ?? $item->barang->nama_barang ?? $item->nama_barang ?? '-',
                                                'quantity'           => $item->quantity,
                                                'delivered_quantity' => $item->delivered_quantity ?? $item->quantity,
                                                'status_item'        => $item->status_item ?? '-',
                                            ];
                                        })->values();
                                    @endphp
                                    <div class="relative flex min-h-[40px] w-fit items-center justify-end">
                                        <div class="pointer-events-none invisible h-9 w-9 opacity-0">Placeholder</div>
                                        <div class="absolute left-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                            {{-- Show (read-only) --}}
                                            <button type="button"
                                                class="js-show-order group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300"
                                                data-order-id="{{ $order->id }}"
                                                data-order-number="{{ $order->do_number ?? $order->order_number }}"
                                                data-reason="{{ $order->reason }}"
                                                data-items='@json($itemsData)'>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="lucide lucide-eye h-4 w-4">
                                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Show</span>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <nav class="flex flex-col items-start justify-between space-y-3 p-4 md:flex-row md:items-center md:space-y-0"
                aria-label="Table navigation">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        Showing
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }}</span>
                        of
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $orders->total() }}</span>
                    </span>
                    <form method="GET" action="{{ route('sales.delivery-orders.index') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="perPage" onchange="this.form.submit()"
                            class="ml-2 block rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500">
                            <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>
                    <span class="text-sm text-gray-500 dark:text-gray-400">per halaman</span>
                </div>
                <div>
                    {{ $orders->links() }}
                </div>
            </nav>
        </div>

        <!-- Modals -->
        @include('admin.delivery-orders.partials.delivery-orders-modal-show')
        @include('admin.delivery-orders.partials.delivery-orders-detail-modal-show')
        @include('admin.delivery-orders.partials.delivery-orders-history-modal')

        @vite(['resources/js/delivery-orders.js'])

    </x-app-layout>