<x-app-layout>
    <div class="flex flex-col lg:h-[calc(100vh-112px)] overflow-hidden">
        <div
            class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex items-center h-16 justify-between overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800 shrink-0 px-4">
            <div>
                <!-- Bulk Actions (for Reguler Goods) -->
                <div id="bulk-actions" class="hidden flex-row items-center space-x-2"
                    data-approve-url="{{ route('supply-orders.bulk-approve') }}"
                    data-reject-url="{{ route('supply-orders.bulk-reject') }}">
                    <button id="bulk-approve"
                        class="flex items-center justify-center rounded-lg bg-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300">
                        Approve Selected (<span id="selected-count">0</span>)
                    </button>
                    <button id="bulk-reject"
                        class="flex items-center justify-center rounded-lg bg-red-700 px-4 py-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300">
                        Reject Selected
                    </button>
                </div>
            </div>

            <div>
                {{-- Search --}}
                <form action="{{ route('supply-orders.index') }}" method="GET" class="block pl-2"
                    data-realtime-table-search data-search-input="#topbar-search" data-search-target="#tableContainer"
                    data-pagination-target="#pagination-nav" data-extra-fields="#pagination-nav select[name='perPage']">
                    <label for="topbar-search" class="sr-only">Search</label>
                    <div class="relative md:w-96">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                                </path>
                            </svg>
                        </div>
                        <input type="search" name="search" id="topbar-search" value="{{ request('search') }}"
                            class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            placeholder="Search" />
                    </div>
                </form>
            </div>
        </div>

        <div
            class="relative flex flex-1 min-h-0 flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
            <!-- Header Group Tab -->
            <div
                class="shrink-0 flex flex-col items-stretch justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:items-center gap-4">
                @php
                    $supplyOrderCount = \App\Models\Goods::where('goods_status', 'pending')
                        ->where('status_listing', '!=', 'non_listing')
                        ->whereDoesntHave('procurementOfGoodsItems')
                        ->count();
                    $procOrderCount = \App\Models\ProcurementArrivalRequest::where('status', 'pending')->count();
                    $deliveryOrderCount = \App\Models\Order::where('status', 'sent_to_warehouse')->count();
                @endphp
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('warehouse.index', ['status' => 'approved']) }}"
                        class="rounded-lg px-4 py-2 text-sm font-medium text-white transition-all hover:bg-white/10">
                        Inventory
                    </a>
                    <a href="{{ route('supply-orders.index') }}"
                        class="flex items-center rounded-lg bg-white px-4 py-2 text-sm font-medium text-[#225A97] transition-all">
                        Supply Orders
                        <span id="supply-orders-nav-badge"
                            class="{{ ($supplyOrderCount + $procOrderCount) > 0 ? '' : 'hidden' }} ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">
                            {{ $supplyOrderCount + $procOrderCount }}
                        </span>
                    </a>
                    <a href="{{ route('delivery-orders.index') }}"
                        class="flex items-center rounded-lg px-4 py-2 text-sm font-medium text-white transition-all hover:bg-white/10">
                        Delivery Orders
                        <span id="delivery-orders-nav-badge"
                            class="{{ $deliveryOrderCount > 0 ? '' : 'hidden' }} ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">{{ $deliveryOrderCount }}</span>
                    </a>
                </div>
            </div>

            <!-- Combined Goods In (Reguler & Procurement) Panel -->
            <div class="flex flex-col flex-1 min-h-0">
                <div class="grow overflow-x-auto overflow-y-auto">
                    <table class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead
                            class="sticky top-0 z-30 text-nowrap bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-3">Status Listing</th>
                                <th scope="col" class="px-4 py-3">Barang</th>
                                <th scope="col" class="px-4 py-3">Deskripsi</th>
                                <th scope="col" class="px-4 py-3 text-center">Stok Datang</th>
                                <th scope="col" class="px-4 py-3 text-center">Status</th>
                                <th scope="col" class="px-4 py-3 text-center">Tipe Request</th>
                                <th scope="col" class="flex justify-end text-nowrap px-6 py-3 text-right no-sort">Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-nowrap">
                            @forelse ($goods as $item)
                                @if ($item instanceof \App\Models\Goods)
                                    <!-- Reguler Goods In Row -->
                                    <tr
                                        class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                        <td class="px-4 py-3">{{ str_replace('_', ' ', $item->status_listing) }}</td>
                                        <td class="px-4 py-3">
                                            <div
                                                class="mb-0.5 text-[10px] font-semibold uppercase tracking-wider text-black dark:text-white">
                                                {{ $item->category }}
                                            </div>
                                            <div class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                                {{ $item->goods_code }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $item->goods_name }}
                                            </div>
                                        </td>
                                        <td class="px-4 max-w-xs align-middle">
                                            <div class="max-w-[250px] break-words line-clamp-3 whitespace-normal">
                                                {{ $item->description }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 font-semibold text-center text-gray-900 dark:text-white">
                                            {{ $item->stock }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                class="inline-flex items-center justify-center rounded-full px-2.5 py-1 text-xs font-semibold bg-amber-50 dark:bg-amber-950/30 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800/50">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-clock mr-1.5 shrink-0">
                                                    <circle cx="12" cy="12" r="10" />
                                                    <polyline points="12 6 12 12 16 14" />
                                                </svg>
                                                Pending Review
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if ($item->request_type == 'primary')
                                                New item
                                            @elseif($item->request_type == 'new_stock')
                                                New stock
                                            @else
                                                {{ $item->request_type ?: '-' }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right align-middle">
                                            <div class="flex justify-end">
                                                <div
                                                    class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700 transition-all duration-300 ease-in-out">
                                                    <form action="{{ route('supply-orders.approve', $item->id) }}" method="POST"
                                                        class="approve-form inline-flex">
                                                        @csrf
                                                        <button type="submit"
                                                            class="group flex h-full cursor-pointer items-center justify-center border-r border-green-800 bg-green-700 p-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-300 dark:border-green-500 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 transition-all duration-300 ease-in-out">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            <span
                                                                class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Approve</span>
                                                        </button>
                                                    </form>
                                                    <button type="button"
                                                        class="reject-btn group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 transition-all duration-300 ease-in-out"
                                                        onclick="openTolakModal('supply_order', '{{ $item->id }}', '{{ $item->goods_name }}', '{{ $item->goods_code }}')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        <span
                                                            class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Reject</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <!-- Custom Procurement Receipt Row -->
                                    <tr
                                        class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                        <td class="px-4 py-3">{{ $item->good->status_listing ? str_replace('_', ' ', $item->good->status_listing) : '-' }}</td>
                                        <td class="px-4 py-3">
                                            <div
                                                class="mb-0.5 text-[10px] font-semibold uppercase tracking-wider text-black dark:text-white">
                                                {{ $item->good->category ?? '-' }}
                                            </div>
                                            <div class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                                {{ $item->good->goods_code ?? '-' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $item->good->goods_name ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 max-w-xs align-middle">
                                            <div class="max-w-[250px] break-words line-clamp-3 whitespace-normal">
                                                {{ $item->good->description ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 font-semibold text-center text-gray-900 dark:text-white">
                                            {{ $item->quantity }} {{ $item->good->unit ?? 'Unit' }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                class="inline-flex items-center justify-center rounded-full px-2.5 py-1 text-xs font-semibold bg-amber-50 dark:bg-amber-950/30 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800/50">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-clock mr-1.5 shrink-0">
                                                    <circle cx="12" cy="12" r="10" />
                                                    <polyline points="12 6 12 12 16 14" />
                                                </svg>
                                                Pending Review
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            Procurement
                                        </td>
                                        <td class="px-4 py-3 text-right align-middle">
                                            <div class="flex justify-end">
                                                <div
                                                    class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700 transition-all duration-300 ease-in-out">
                                                    <form action="{{ route('supply-orders.approve-procurement', $item->id) }}"
                                                        method="POST" class="approve-form inline-flex">
                                                        @csrf
                                                        <button type="submit"
                                                            class="group flex h-full cursor-pointer items-center justify-center border-r border-green-800 bg-green-700 p-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-300 dark:border-green-500 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 transition-all duration-300 ease-in-out">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            <span
                                                                class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Approve</span>
                                                        </button>
                                                    </form>
                                                    <button type="button"
                                                        class="reject-btn group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 transition-all duration-300 ease-in-out"
                                                        onclick="openTolakModal('procurement_order', '{{ $item->id }}', '{{ $item->good->goods_name }}', '{{ $item->good->goods_code }}')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        <span
                                                            class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Reject</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Tidak ada
                                        pengajuan barang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav class="sticky bottom-0 z-20 flex flex-col items-start justify-between space-y-3 border-t border-gray-100 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
                    aria-label="Table navigation">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            Menampilkan
                            <span
                                class="font-semibold text-gray-900 dark:text-white">{{ $goods->firstItem() ?? 0 }}-{{ $goods->lastItem() ?? 0 }}</span>
                            dari
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $goods->total() }}</span>
                        </span>
                    </div>
                    <div>
                        {{ $goods->links() }}
                    </div>
                </nav>
            </div>

            <!-- Modals -->
            @include('admin.supply-orders.partials.supply-orders-modal-reject')

            @vite(['resources/js/supply-orders.js', 'resources/js/realtime-table-search.js', 'resources/js/table-sort.js'])
</x-app-layout>