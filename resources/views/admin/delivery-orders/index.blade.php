<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-end overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-4">
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
                    <input type="search" name="search" id="topbar-search dt-search-0" aria-controls="warehouseTable" value="{{ request('search') }}" class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" placeholder="Search" />
                </div>
            </form>
        </div>
    </div>

    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
        </div>

        <div class="overflow-x-auto">
            <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="selectCol px-4 py-2"></th>
                        <th class="px-4 py-2">No. Order</th>
                        <th class="px-4 py-2">Nama Supervisor</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Dibuat</th>
                        <th class="px-4 py-2">Detail</th>
                    </tr>
                </thead>
                <tbody class="h-min-[300px]">
                    @foreach ($orders as $order)
                        <tr class="dark:border-gray-700">
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 dark:text-white"></td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 dark:text-white">{{ $order->order_number }}</td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 dark:text-white">{{ optional($order->supervisor)->name ?? '-' }}</td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 dark:text-white">
                                @php
                                    $statusClass =
                                        [
                                            'sent_to_warehouse' => 'bg-green-100 text-green-800',
                                            'approved_warehouse' => 'bg-green-100 text-green-800',
                                            'rejected_warehouse' => 'bg-red-100 text-red-800',
                                        ][$order->status] ?? 'bg-gray-100 text-gray-800';
                                    $statusLabel =
                                        [
                                            'sent_to_warehouse' => 'Terkirim ke Warehouse',
                                            'approved_warehouse' => 'Disetujui Warehouse',
                                            'rejected_warehouse' => 'Ditolak Warehouse',
                                        ][$order->status] ?? $order->status;
                                @endphp
                                <span class="{{ $statusClass }} mt-1 inline-block rounded-full px-3 py-1 text-sm font-semibold">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-900 dark:text-white">{{ optional($order->created_at)->format('Y-m-d H:i') }}</td>
                            <td class="w-fit px-4 py-3 text-right">
                                <div class="relative flex min-h-[40px] w-fit items-center justify-end">
                                    <div class="pointer-events-none invisible h-9 w-9 opacity-0">Placeholder</div>
                                    <div class="absolute left-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                        <button type="button" class="js-show-order group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" data-order-id="{{ $order->id }}" data-order-number="{{ $order->order_number }}" data-items='@json($order->items)'>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye h-4 w-4">
                                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Show</span>
                                        </button>
                                        @if ($order->status === 'sent_to_warehouse')
                                            {{-- Approve --}}
                                            <form action="{{ route('delivery-orders.approve', $order->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="group flex h-full cursor-pointer items-center justify-center bg-green-700 p-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" data-id="{{ $order->id }}" data-order-number="{{ $order->order_number }}" data-items='@json($order->items)'>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Approve</span>
                                                </button>
                                            </form>
                                            {{-- Reject barang --}}
                                            <form action="{{ route('delivery-orders.reject', $order->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="reject-btn group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" data-id="{{ $order->id }}" data-order-number="{{ $order->order_number }}" data-items='@json($order->items)'>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Reject</span>
                                                </button>
                                            </form>
                                        @endif
                                        @if ($order->status === 'approved_warehouse')
                                            <a href="{{ route('delivery-orders.pdf', $order->id) }}" class="group flex h-full cursor-pointer items-center justify-center bg-green-700 p-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" data-id="{{ $order->id }}" data-order-number="{{ $order->order_number }}" data-items='@json($order->items)'>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text mr-2 h-4 w-4">
                                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                                <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                                <path d="M10 9H8"></path>
                                                <path d="M16 13H8"></path>
                                                <path d="M16 17H8"></path>
                                            </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">DO</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
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
                    <select name="perPage" onchange="this.form.submit()" class="ml-2 rounded border-gray-300 p-1 pl-2 pr-5 text-sm">
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
    </div>

    <!-- Modals -->
    @include('components.delivery-orders-modal-show')
    @vite(['resources/js/delivery-orders.js'])

</x-app-layout>
