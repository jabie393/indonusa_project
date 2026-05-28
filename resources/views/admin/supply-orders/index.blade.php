<x-app-layout>

    <div
        class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex items-center h-16 justify-end overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-4">
            {{-- Bulk Actions --}}
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

        <div class="p-4">
            {{-- Search --}}
            <form action="{{ route('supply-orders.index') }}" method="GET" class="block pl-2">
                <label for="topbar-search" class="sr-only">Search</label>
                <div class="relative md:w-96">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                            </path>
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

    <div
        class="relative flex max-h-[calc(100vh-210px)] flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div
            class="shrink-0 flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
            @php
                $supplyOrderCount = \App\Models\Barang::where('goods_status', 'ditinjau')->count();
                $deliveryOrderCount = \App\Models\Order::where('status', 'sent_to_warehouse')->count();
            @endphp
            <div class="flex items-center space-x-2">
                <a href="{{ route('warehouse.index', ['status' => 'masuk']) }}"
                    class="rounded-lg px-4 py-2 text-sm font-medium text-white transition-all hover:bg-white/10">
                    Semua Barang
                </a>
                <a href="{{ route('supply-orders.index') }}"
                    class="flex items-center rounded-lg bg-white px-4 py-2 text-sm font-medium text-[#225A97] transition-all">
                    Supply Orders
                    @if ($supplyOrderCount > 0)
                        <span
                            class="ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">{{ $supplyOrderCount }}</span>
                    @endif
                </a>
                <a href="{{ route('delivery-orders.index') }}"
                    class="flex items-center rounded-lg px-4 py-2 text-sm font-medium text-white transition-all hover:bg-white/10">
                    Delivery Orders
                    @if ($deliveryOrderCount > 0)
                        <span
                            class="ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">{{ $deliveryOrderCount }}</span>
                    @endif
                </a>
            </div>
        </div>
        <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
            <table id="" class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead
                    class="sticky top-0 z-30 text-nowrap bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">Status Listing</th>
                        <th scope="col" class="px-4 py-3">Barang</th>
                        <th scope="col" class="px-4 py-3">Deskripsi</th>
                        <th scope="col" class="px-4 py-3">Stok</th>
                        <th scope="col" class="px-4 py-3">Status Barang</th>
                        <th scope="col" class="px-4 py-3">Tipe Request</th>
                        <th scope="col" class="flex justify-center text-nowrap px-4 py-3 text-right no-sort">Action</th>
                    </tr>
                </thead>
                <tbody class="text-nowrap">
                    @forelse ($goods as $barang)
                        <tr
                            class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                            <td class="px-4 py-3">{{ $barang->status_listing }}</td>
                            <td class="px-4 py-3 flex-shrink-0">
                                <div
                                    class="mb-0.5 text-[10px] font-semibold uppercase tracking-wider text-black dark:text-white">
                                    {{ $barang->category }}
                                </div>
                                <div class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                    {{ $barang->goods_code }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $barang->goods_name }}
                                </div>
                            </td>
                            <td class="px-4 max-w-xs align-middle">
                                <div class="max-w-[250px] break-words line-clamp-3 whitespace-normal">
                                    {{ $barang->description }}
                                </div>
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">{{ $barang->stock }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusClass =
                                        [
                                            'ditinjau' => 'bg-yellow-50 text-yellow-800 inset-ring inset-ring-yellow-600',
                                        ][$barang->goods_status] ?? 'bg-gray-100 text-gray-800 inset-ring inset-ring-gray-600';
                                    $statusLabel =
                                        [
                                            'ditinjau' => 'Ditinjau',
                                        ][$barang->goods_status] ?? $barang->goods_status;
                                @endphp
                                <div class="flex items-center justify-center gap-2">
                                    <span class="{{ $statusClass }} badge">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if ($barang->request_type == 'primary')
                                    Barang baru
                                @elseif($barang->request_type == 'new_stock')
                                    Stok baru
                                @elseif($barang->request_type)
                                    {{ $barang->request_type }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right align-middle">
                                <div class="flex justify-center">
                                    <div
                                        class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700 transition-all duration-300 ease-in-out">
                                        {{-- Approve barang --}}
                                        <form action="{{ route('supply-orders.approve', $barang->id) }}" method="POST"
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
                                        {{-- Reject barang --}}
                                        <button type="button"
                                            class="reject-btn group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 transition-all duration-300 ease-in-out"
                                            onclick="openTolakModal('supply_order', '{{ $barang->id }}', '{{ $barang->goods_name }}', '{{ $barang->goods_code }}')">
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
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
        <nav class="sticky bottom-0 z-20 flex flex-col items-start justify-between space-y-3 bg-white p-4 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
            aria-label="Table navigation">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Showing
                    <span
                        class="font-semibold text-gray-900 dark:text-white">{{ $goods->firstItem() ?? 0 }}-{{ $goods->lastItem() ?? 0 }}</span>
                    of
                    <span
                        class="font-semibold text-gray-900 dark:text-white">{{ $goods->total() ?? $goods->count() }}</span>
                </span>
                <form method="GET" action="{{ route('supply-orders.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="perPage" onchange="this.form.submit()"
                        class="mx-2 rounded-xl border border-gray-300 bg-gray-50 p-1 pl-2 pr-8 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <span class="text-sm text-gray-500 dark:text-gray-400">per halaman</span>
            </div>
            <div>
                {{ $goods->links() }}
            </div>
        </nav>
    </div>

    <!-- Modals -->
    @include('admin.supply-orders.partials.supply-orders-modal-reject')
    @vite(['resources/js/supply-orders.js', 'resources/js/table-sort.js'])

</x-app-layout>