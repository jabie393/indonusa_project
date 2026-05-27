<x-app-layout>

    <div
        class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-between overflow-hidden rounded-2xl bg-white p-4 shadow-md dark:bg-gray-800">
        <a href="{{ route('dashboard.supervisor.export.semua-barang') }}"
            class="flex flex-row items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 font-semibold text-white hover:bg-[#19426d]">
            <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Report Semua Barang
        </a>
        <div>
            {{-- Search --}}
            <form action="{{ route('warehouse.index') }}" method="GET" class="block pl-2">
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
        <div class="shrink-0 flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
            @if (Auth::user() && in_array(Auth::user()->role, ['Warehouse', 'Sales']))
                @php
                    $currentStatus = session('warehouse_filter_status', 'masuk');
                    $supplyOrderCount = \App\Models\Barang::where('goods_status', 'ditinjau')->count();
                    $deliveryOrderCount = \App\Models\Order::where('status', 'sent_to_warehouse')->count();
                @endphp
                <div class="flex items-center space-x-2">
                    <a href="{{ route('warehouse.index', ['status' => 'masuk', 'search' => request('search')]) }}"
                        class="{{ $currentStatus === 'masuk' ? 'bg-white text-[#225A97]' : 'text-white hover:bg-white/10' }} rounded-lg px-4 py-2 text-sm font-medium transition-all">
                        Semua Barang
                    </a>
                    @if (Auth::user() && Auth::user()->role === 'Warehouse')
                        <a href="{{ route('supply-orders.index') }}"
                            class="flex items-center rounded-lg px-4 py-2 text-sm font-medium text-white transition-all hover:bg-white/10">
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
                    @endif
                    @if (Auth::user() && Auth::user()->role === 'Sales')
                        <a href="{{ route('sales.delivery-orders.index') }}"
                            class="flex items-center rounded-lg px-4 py-2 text-sm font-medium text-white transition-all hover:bg-white/10">
                            Delivery Orders
                            @if ($deliveryOrderCount > 0)
                                <span
                                    class="ml-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">{{ $deliveryOrderCount }}</span>
                            @endif
                        </a>
                    @endif
                </div>
            @endif
        </div>
        <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
            <table class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400" id="">
                <thead
                    class="sticky top-0 z-30 text-nowrap bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="text-nowrap px-4 py-3">Status Listing</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Kode Barang</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Kategori</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Nama Barang</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Deskripsi</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Stok</th>
                        @if (Auth::user() && Auth::user()->role === 'General Affair')
                            <th scope="col" class="text-nowrap px-4 py-3">Harga Jual</th>
                        @endif

                        @if (Auth::user() && in_array(Auth::user()->role, ['Warehouse', 'General Affair']))
                            <th scope="col" class="text-nowrap w-fit px-4 py-3 text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="text-nowrap">
                    @forelse ($goods as $barang)
                        <tr class="dark:border-gray-700">
                            <td class="px-4 py-3">{{ $barang->status_listing }}</td>
                            <td scope="row" class="whitespace-nowrap px-4 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $barang->goods_code }}
                            </td>
                            <td class="px-4 py-3">{{ $barang->category }}</td>
                            <td class="px-4 py-3">{{ $barang->goods_name }}</td>
                            <td class="px-4 max-w-xs truncate">{{ $barang->description }}</td>
                            <td class="px-4 py-3">{{ $barang->stock }}</td>
                            @if (Auth::user() && Auth::user()->role === 'General Affair')
                                <td class="text-nowrap px-4 py-3 font-medium text-slate-700">
                                    <div class="flex w-full items-center justify-between">
                                        <span>Rp</span>
                                        <span>{{ number_format($barang->selling_price, 0, '.', ',') }}</span>
                                    </div>
                                </td>
                            @endif

                            @if (Auth::user() && in_array(Auth::user()->role, ['Warehouse', 'General Affair']))
                                <td class="w-fit px-4 py-3 text-right">
                                    <div class="relative flex min-h-[40px] w-fit items-center justify-center">
                                        {{-- This invisible div helps reserve space for the absolute-positioned buttons --}}
                                        <div class="pointer-events-none invisible h-9 w-24 opacity-0">Placeholder</div>
                                        <div
                                            class="absolute left-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">

                                            @if (Auth::user()->role === 'Warehouse')
                                                <button type="button"
                                                    class="edit-barang-btn group flex h-full cursor-pointer items-center justify-center border-r border-blue-800 bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:border-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900"
                                                    data-id="{{ $barang->id }}" data-status="{{ $barang->status_listing }}"
                                                    data-kode="{{ $barang->goods_code }}" data-nama="{{ $barang->goods_name }}"
                                                    data-kategori="{{ $barang->category }}" data-stok="{{ $barang->stock }}"
                                                    data-satuan="{{ $barang->unit }}" data-lokasi="{{ $barang->location }}"
                                                    data-harga="{{ $barang->selling_price }}"
                                                    data-deskripsi="{{ $barang->description ?? '' }}"
                                                    data-gambar="{{ $barang->image ?? '' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-edit-2 h-4 w-4" aria-hidden="true">
                                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z">
                                                        </path>
                                                    </svg>
                                                    <span
                                                        class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Edit</span>
                                                </button>

                                                @if ($barang->stock < 1)
                                                    <form action="{{ route('warehouse.destroy', $barang->id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                                            onclick="confirmDelete(() => this.closest('form').submit())">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2 lucide-trash-2 h-4 w-4" aria-hidden="true">
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
                                                    class="view-detail-btn group flex h-full cursor-pointer items-center justify-center bg-yellow-600 p-2 text-sm font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-4 focus:ring-yellow-300 dark:bg-yellow-600 dark:text-white dark:hover:bg-yellow-700 dark:focus:ring-yellow-800"
                                                    data-id="{{ $barang->id }}" data-nama="{{ $barang->goods_name }}"
                                                    data-kode="{{ $barang->goods_code }}" data-kategori="{{ $barang->category }}" data-status="{{ $barang->status_listing }}"
                                                    data-stok="{{ $barang->stock }}" data-satuan="{{ $barang->unit }}"
                                                    data-lokasi="{{ $barang->location }}" data-harga="{{ $barang->selling_price }}"
                                                    data-deskripsi="{{ $barang->description ?? '' }}" data-gambar="{{ $barang->image ?? '' }}">
                                                    <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Detail</span>
                                                </button>
                                                <button
                                                    class="edit-selling-price-btn group flex h-full cursor-pointer items-center justify-center bg-green-600 p-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800"
                                                    data-id="{{ $barang->id }}" data-nama="{{ $barang->goods_name }}"
                                                    data-kode="{{ $barang->goods_code }}" data-harga="{{ $barang->selling_price }}">
                                                    <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg">
                                                        <rect width="20" height="12" x="2" y="6" rx="2"></rect>
                                                        <circle cx="12" cy="12" r="2"></circle>
                                                        <path d="M6 12h.01M18 12h.01"></path>
                                                    </svg>
                                                    <span
                                                        class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Edit Selling Price</span>
                                                </button>

                                                <button
                                                    class="view-history-btn group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                                    data-id="{{ $barang->id }}" data-nama="{{ $barang->goods_name }}"
                                                    data-kode="{{ $barang->goods_code }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-history h-4 w-4">
                                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8">
                                                        </path>
                                                        <path d="M3 3v5h5"></path>
                                                        <path d="M12 7v5l4 2"></path>
                                                    </svg>
                                                    <span
                                                        class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Price History</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            @endif
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
                    Showing
                    <span
                        class="font-semibold text-gray-900 dark:text-white">{{ $goods->firstItem() ?? 0 }}-{{ $goods->lastItem() ?? 0 }}</span>
                    of
                    <span
                        class="font-semibold text-gray-900 dark:text-white">{{ $goods->total() ?? $goods->count() }}</span>
                </span>
                <form method="GET" action="{{ route('warehouse.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="perPage" onchange="this.form.submit()"
                        class="ml-2 rounded border-gray-300 p-1 pl-2 pr-5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
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
        @include('admin.warehouse.partials.warehouse-modal-tambah', ['kategoriList' => $kategoriList])
        @include('admin.warehouse.partials.warehouse-modal-edit-image', [
            'kategoriList' => $kategoriList,
            'barang' => $barang,
        ])
        @include('admin.warehouse.partials.warehouse-modal-detail')
        @if (Auth::user() && Auth::user()->role === 'General Affair')
            @include('admin.warehouse.partials.warehouse-modal-history')
            @include('admin.warehouse.partials.warehouse-modal-edit-price')
        @endif
        @vite(['resources/js/warehouse.js', 'resources/js/table-sort.js'])
    </div>
</x-app-layout>
