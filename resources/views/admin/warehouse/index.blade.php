<x-app-layout>
    <div class="flex flex-col lg:h-[calc(100vh-112px)] overflow-hidden">
        <div
            class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex h-16 items-center justify-between overflow-hidden rounded-2xl bg-white px-4 shadow-md dark:bg-gray-800 shrink-0">
            <div class="flex items-center gap-2 md:gap-3">
                <button type="button" id="btn-filter"
                    class="flex cursor-pointer flex-row items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 text-sm font-semibold text-white shadow transition-all duration-200 hover:bg-[#19426d] focus:outline-none focus:ring-2 focus:ring-[#225A97]/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Filter & Export
                </button>
            </div>
        <div>
            {{-- Search --}}
            <form id="warehouseSearchForm" action="{{ route('warehouse.index') }}" method="GET" class="block pl-2" data-realtime-table-search data-search-input="#topbar-search"
                data-search-target="#tableContainer" data-pagination-target="#pagination-nav" data-extra-fields="#pagination-nav select[name='perPage']">
                <label for="topbar-search" class="sr-only">Search</label>
                <div class="relative md:w-96">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                            </path>
                        </svg>
                    </div>
                    <input type="search" name="search" id="topbar-search" aria-controls="warehouseTable" value="{{ request('search') }}"
                        class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                        placeholder="Search by name, code, category, or description" />
                </div>
            </form>
        </div>
    </div>

        <!-- Filter Panel -->
        <div id="filter-panel"
            class="collapse mb-0 shrink-0 rounded-2xl border-0 bg-white shadow-none transition-all duration-300 dark:bg-gray-800 [&.collapse-open]:mb-5 [&.collapse-open]:border [&.collapse-open]:border-gray-100 [&.collapse-open]:shadow-md [&.collapse-open]:dark:border-gray-700/50">
            <div class="collapse-content !p-0">
                <div class="p-5">
                    <form id="filter-form" action="{{ route('warehouse.index') }}" method="GET">
                        <!-- Forward current search parameter -->
                        <input type="hidden" name="search" value="{{ request('search') }}" />

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                            <!-- Category Filter -->
                            <div class="flex flex-col gap-1.5 col-span-1">
                                <label for="category" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Pilih Kategori</label>
                                <select name="category" id="category"
                                    class="rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    <option value="all" {{ request('category') === 'all' ? 'selected' : '' }}>Semua Kategori</option>
                                    @foreach ($kategoriList as $kat)
                                        <option value="{{ $kat }}" {{ request('category') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Stock Status Filter -->
                            <div class="flex flex-col gap-1.5 col-span-1">
                                <label for="stock_status" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Status Stok</label>
                                <select name="stock_status" id="stock_status"
                                    class="rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    <option value="all" {{ request('stock_status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="ready" {{ request('stock_status') === 'ready' ? 'selected' : '' }}>Ready Stock (>20)</option>
                                    <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Stok Rendah (1-20)</option>
                                    <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>Stok Habis (0)</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-span-1 md:col-span-2 flex items-end justify-between gap-2">
                                <div class="flex gap-3">
                                    <a href="{{ route('warehouse.index') }}"
                                        class="flex w-fit flex-row items-center rounded-xl bg-gray-100 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition duration-150 hover:bg-gray-200 whitespace-nowrap">
                                        <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                            <path d="M3 3v5h5"></path>
                                        </svg>
                                        Reset</a>
                                    <button type="submit"
                                        class="w-fit flex flex-row items-center rounded-xl bg-[#225A97] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition duration-150 hover:bg-[#19426d] whitespace-nowrap">
                                        <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                        </svg>
                                        Filter
                                    </button>
                                </div>
                                <div class="flex gap-3">
                                    <button type="button" id="btn-export-excel"
                                        class="flex w-fit cursor-pointer flex-row items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow transition duration-200 hover:bg-emerald-700 whitespace-nowrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Export Excel
                                    </button>
                                    <button type="button" id="btn-export-pdf"
                                        class="flex w-fit cursor-pointer flex-row items-center justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow transition duration-200 hover:bg-red-700 whitespace-nowrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Export PDF
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="relative flex flex-1 min-h-0 flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex shrink-0 items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
            @if (Auth::user() && in_array(Auth::user()->role, ['Warehouse', 'Sales']))
                @php
                    $currentStatus = session('warehouse_filter_status', 'approved');
                    $supplyOrderCount = \App\Models\Goods::where('goods_status', 'pending')
                        ->where('status_listing', '!=', 'non_listing')
                        ->whereDoesntHave('procurementOfGoodsItems')
                        ->count();
                    $procOrderCount = \App\Models\ProcurementArrivalRequest::where('status', 'pending')->count();
                    $deliveryOrderCount = \App\Models\Order::where('status', 'sent_to_warehouse')->count();
                @endphp
                <div class="flex items-center space-x-2">
                    <a href="{{ route('warehouse.index', ['status' => 'approved', 'search' => request('search')]) }}"
                        class="{{ $currentStatus === 'approved' ? 'bg-white text-[#225A97]' : 'text-white hover:bg-white/10' }} rounded-lg px-4 py-2 text-sm font-medium transition-all">
                        Inventory
                    </a>
                    @if (Auth::user() && Auth::user()->role === 'Warehouse')
                        <a href="{{ route('supply-orders.index') }}" class="flex items-center rounded-lg px-4 py-2 text-sm font-medium text-white transition-all hover:bg-white/10">
                            Supply Orders
                            <span id="supply-orders-nav-badge"
                                class="{{ ($supplyOrderCount + $procOrderCount) > 0 ? '' : 'hidden' }} ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">
                                {{ $supplyOrderCount + $procOrderCount }}
                            </span>
                        </a>
                    @endif
                    @if (Auth::user() && in_array(Auth::user()->role, ['Warehouse', 'Sales']))
                        <a href="{{ route('delivery-orders.index') }}" class="flex items-center rounded-lg px-4 py-2 text-sm font-medium text-white transition-all hover:bg-white/10">
                            Delivery Orders
                            <span id="delivery-orders-nav-badge"
                                class="{{ $deliveryOrderCount > 0 ? '' : 'hidden' }} ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">{{ $deliveryOrderCount }}</span>
                        </a>
                    @endif
                </div>
            @endif
        </div>
        <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
            <table class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400" id="">
                <thead class="sticky top-0 z-30 text-nowrap bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        @if (Auth::user() && Auth::user()->role !== 'Sales')
                            <th scope="col" class="text-nowrap px-4 py-3">Status Listing</th>
                        @endif
                        <th scope="col" class="text-nowrap px-4 py-3">Barang</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Deskripsi</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Stok</th>
                        @if (Auth::user() && Auth::user()->role === 'Warehouse')
                            <th scope="col" class="text-nowrap px-4 py-3">Satuan</th>
                            <th scope="col" class="text-nowrap px-4 py-3">Lokasi</th>
                        @endif
                        @if (Auth::user() && in_array(Auth::user()->role, ['General Affair', 'Supervisor']))
                            <th scope="col" class="text-nowrap px-4 py-3">Harga Jual</th>
                        @endif
                        <th scope="col" class="flex justify-end text-nowrap px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($goods as $barang)
                        <tr class="border-b transition-colors duration-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50">
                            @if (Auth::user() && Auth::user()->role !== 'Sales')
                                <td class="px-4 py-3">{{ $barang->status_listing }}</td>
                            @endif
                            <td class="flex-shrink-0 px-4 py-3">
                                <div class="mb-0.5 text-[10px] font-semibold uppercase tracking-wider text-black dark:text-white">
                                    {{ $barang->category }}
                                </div>
                                <div class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                    {{ $barang->goods_code }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $barang->goods_name }}
                                </div>
                            </td>
                            <td class="max-w-xs px-4 align-middle">
                                <div class="line-clamp-3 max-w-[250px] break-words">
                                    {{ $barang->description }}
                                </div>
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $barang->stock }}</td>
                            @if (Auth::user() && Auth::user()->role === 'Warehouse')
                                <td class="px-4 py-3">{{ $barang->unit }}</td>
                                <td class="px-4 py-3">{{ $barang->location }}</td>
                            @endif
                            @if (Auth::user() && in_array(Auth::user()->role, ['General Affair', 'Supervisor']))
                                <td class="text-nowrap px-4 py-3 font-medium text-slate-700">
                                    <div class="flex w-full items-center justify-between">
                                        <span>Rp</span>
                                        <span>{{ number_format($barang->selling_price, 0, '.', ',') }}</span>
                                    </div>
                                </td>
                            @endif
                                <td class="px-4 py-3 text-right align-middle">
                                    <div class="flex justify-end">
                                        <div
                                            class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-300 ease-in-out dark:border-gray-600 dark:bg-gray-700">

                                            @if (Auth::user()->role === 'Warehouse')
                                                <button type="button"
                                                    class="edit-barang-btn group flex h-full cursor-pointer items-center justify-center border-r border-blue-800 bg-blue-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:border-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900"
                                                    data-id="{{ $barang->id }}" data-status="{{ $barang->status_listing }}" data-kode="{{ $barang->goods_code }}"
                                                    data-nama="{{ $barang->goods_name }}" data-kategori="{{ $barang->category }}" data-stok="{{ $barang->stock }}" data-satuan="{{ $barang->unit }}"
                                                    data-lokasi="{{ $barang->location }}" data-harga="{{ $barang->selling_price }}" data-deskripsi="{{ $barang->description ?? '' }}"
                                                    data-gambar="{{ $barang->image ?? '' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit-2 h-4 w-4" aria-hidden="true">
                                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z">
                                                        </path>
                                                    </svg>
                                                    <span
                                                        class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Edit</span>
                                                </button>

                                                @if ($barang->stock < 1)
                                                    <form action="{{ route('warehouse.destroy', $barang->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                                            onclick="confirmDelete(() => this.closest('form').submit())">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 lucide-trash-2 h-4 w-4"
                                                                aria-hidden="true">
                                                                <path d="M10 11v6"></path>
                                                                <path d="M14 11v6"></path>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6">
                                                                </path>
                                                                <path d="M3 6h18"></path>
                                                                <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            </svg>
                                                            <span
                                                                class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Hapus</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            @elseif (Auth::user()->role === 'General Affair')
                                                <button
                                                    class="view-detail-btn group flex h-full cursor-pointer items-center justify-center border-r border-yellow-700 bg-yellow-600 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300 dark:border-yellow-500 dark:bg-yellow-600 dark:text-white dark:hover:bg-yellow-700 dark:focus:ring-yellow-800"
                                                    data-id="{{ $barang->id }}" data-nama="{{ $barang->goods_name }}" data-kode="{{ $barang->goods_code }}"
                                                    data-kategori="{{ $barang->category }}" data-status="{{ $barang->status_listing }}" data-stok="{{ $barang->stock }}"
                                                    data-satuan="{{ $barang->unit }}" data-lokasi="{{ $barang->location }}" data-harga="{{ $barang->selling_price }}"
                                                    data-deskripsi="{{ $barang->description ?? '' }}" data-gambar="{{ $barang->image ?? '' }}">
                                                    <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                                        width="14" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    <span
                                                        class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Detail</span>
                                                </button>
                                                <button
                                                    class="edit-selling-price-btn group flex h-full cursor-pointer items-center justify-center border-r border-green-700 bg-green-600 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 dark:border-green-500 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800"
                                                    data-id="{{ $barang->id }}" data-nama="{{ $barang->goods_name }}" data-kode="{{ $barang->goods_code }}"
                                                    data-harga="{{ $barang->selling_price }}">
                                                    <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                                        width="14" xmlns="http://www.w3.org/2000/svg">
                                                        <rect width="20" height="12" x="2" y="6" rx="2"></rect>
                                                        <circle cx="12" cy="12" r="2"></circle>
                                                        <path d="M6 12h.01M18 12h.01"></path>
                                                    </svg>
                                                    <span
                                                        class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Edit
                                                        Selling Price</span>
                                                </button>

                                                <button
                                                    class="view-history-btn group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                                    data-id="{{ $barang->id }}" data-nama="{{ $barang->goods_name }}" data-kode="{{ $barang->goods_code }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-history h-4 w-4">
                                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8">
                                                        </path>
                                                        <path d="M3 3v5h5"></path>
                                                        <path d="M12 7v5l4 2"></path>
                                                    </svg>
                                                    <span
                                                        class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Price
                                                        History</span>
                                                </button>
                                            @elseif(Auth::user()->role === 'Supervisor')
                                                <button
                                                    class="view-detail-btn group flex h-full cursor-pointer items-center justify-center border-r border-yellow-700 bg-yellow-600 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300 dark:border-yellow-500 dark:bg-yellow-600 dark:text-white dark:hover:bg-yellow-700 dark:focus:ring-yellow-800"
                                                    data-id="{{ $barang->id }}" data-nama="{{ $barang->goods_name }}" data-kode="{{ $barang->goods_code }}"
                                                    data-kategori="{{ $barang->category }}" data-status="{{ $barang->status_listing }}" data-stok="{{ $barang->stock }}"
                                                    data-satuan="{{ $barang->unit }}" data-lokasi="{{ $barang->location }}" data-harga="{{ $barang->selling_price }}"
                                                    data-deskripsi="{{ $barang->description ?? '' }}" data-gambar="{{ $barang->image ?? '' }}">
                                                    <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                                        width="14" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    <span
                                                        class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Detail</span>
                                                </button>
                                            @elseif(Auth::user() && Auth::user()->role == 'Sales')
                                                <button
                                                    class="view-detail-btn group flex h-full cursor-pointer items-center justify-center border-r border-yellow-700 bg-yellow-600 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300 dark:border-yellow-500 dark:bg-yellow-600 dark:text-white dark:hover:bg-yellow-700 dark:focus:ring-yellow-800"
                                                    data-id="{{ $barang->id }}" data-nama="{{ $barang->goods_name }}" data-kode="{{ $barang->goods_code }}"
                                                    data-kategori="{{ $barang->category }}" data-status="{{ $barang->status_listing }}" data-stok="{{ $barang->stock }}"
                                                    data-satuan="{{ $barang->unit }}" data-lokasi="{{ $barang->location }}" data-harga="{{ $barang->selling_price }}"
                                                    data-deskripsi="{{ $barang->description ?? '' }}" data-gambar="{{ $barang->image ?? '' }}">
                                                    <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                                        width="14" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    <span
                                                        class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Detail</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
        <nav id="pagination-nav"
            class="sticky bottom-0 z-20 flex shrink-0 flex-col items-start justify-between space-y-3 border-t border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
            aria-label="Table navigation">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Menampilkan
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $goods->firstItem() ?? 0 }}-{{ $goods->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $goods->total() ?? $goods->count() }}</span>
                </span>
                <form method="GET" action="{{ route('warehouse.index') }}">
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

        {{-- Modal --}}
        @include('admin.warehouse.partials.warehouse-modal-edit-image', [
            'kategoriList' => $kategoriList,
            'barang' => $barang,
        ])
        @include('admin.warehouse.partials.warehouse-modal-detail')
        @if (Auth::user() && Auth::user()->role === 'General Affair')
            @include('admin.warehouse.partials.warehouse-modal-history')
            @include('admin.warehouse.partials.warehouse-modal-edit-price')
        @endif
        @vite(['resources/js/warehouse.js', 'resources/js/realtime-table-search.js', 'resources/js/table-sort.js'])
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnFilter = document.getElementById('btn-filter');
            const filterPanel = document.getElementById('filter-panel');
            if (btnFilter && filterPanel) {
                btnFilter.addEventListener('click', function(e) {
                    e.preventDefault();
                    filterPanel.classList.toggle('collapse-open');
                });
            }

            function syncValueInputs() {
                const searchInput = document.getElementById('topbar-search');
                if (searchInput) {
                    const forms = document.querySelectorAll('#filter-form');
                    forms.forEach(form => {
                        let searchHidden = form.querySelector('input[name="search"]');
                        if (!searchHidden) {
                            searchHidden = document.createElement('input');
                            searchHidden.type = 'hidden';
                            searchHidden.name = 'search';
                            form.appendChild(searchHidden);
                        }
                        searchHidden.value = searchInput.value;
                    });
                }
            }

            const btnExportExcel = document.getElementById('btn-export-excel');
            if (btnExportExcel) {
                btnExportExcel.addEventListener('click', function(e) {
                    e.preventDefault();
                    syncValueInputs();
                    const form = document.getElementById('filter-form');
                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData).toString();
                    window.location.href = "{{ route('warehouse.export') }}?" + params;
                });
            }

            const btnExportPdf = document.getElementById('btn-export-pdf');
            if (btnExportPdf) {
                btnExportPdf.addEventListener('click', function(e) {
                    e.preventDefault();
                    syncValueInputs();
                    const form = document.getElementById('filter-form');
                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData).toString();
                    window.open("{{ route('warehouse.pdf') }}?" + params, '_blank');
                });
            }
        });
    </script>
</x-app-layout>
