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
                        <th scope="col" class="text-nowrap px-4 py-3">Nama Barang</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Kategori</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Stok</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Satuan</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Lokasi</th>
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
                            <td class="px-4 py-3">{{ $barang->goods_name }}</td>
                            <td class="px-4 py-3">{{ $barang->category }}</td>
                            <td class="px-4 py-3">{{ $barang->stock }}</td>
                            <td class="px-4 py-3">{{ $barang->unit }}</td>
                            <td class="px-4 py-3">{{ $barang->location }}</td>
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
                                                    class="edit-selling-price-btn group flex h-full cursor-pointer items-center justify-center bg-green-600 p-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800"
                                                    data-id="{{ $barang->id }}" data-nama="{{ $barang->goods_name }}"
                                                    data-kode="{{ $barang->goods_code }}" data-harga="{{ $barang->selling_price }}">
                                                    <svg fill="currentColor" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="14" height="14" viewBox="0 0 47.05 47.049" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M43.656,16.982c-0.729-2.255,0.268-5.41-1.098-7.287C41.179,7.8,37.859,7.78,35.964,6.4 c-1.876-1.365-2.914-4.523-5.168-5.255c-2.174-0.706-4.86,1.216-7.271,1.216c-2.41,0-5.097-1.922-7.271-1.216 C14,1.876,12.962,5.035,11.085,6.4C9.19,7.78,5.872,7.8,4.493,9.695c-1.365,1.877-0.369,5.032-1.1,7.287 C2.686,19.156,0,21.113,0,23.525c0,2.41,2.686,4.367,3.393,6.542c0.73,2.254-0.267,5.41,1.1,7.287 c1.379,1.895,4.698,1.915,6.593,3.294c1.876,1.365,2.914,4.523,5.168,5.257c2.175,0.705,4.86-1.217,7.271-1.217 c2.411,0,5.097,1.922,7.271,1.217c2.254-0.732,3.292-3.892,5.168-5.256c1.896-1.379,5.214-1.4,6.595-3.295 c1.364-1.876,0.367-5.032,1.098-7.286c0.707-2.175,3.394-4.133,3.394-6.543C47.049,21.113,44.363,19.157,43.656,16.982z M23.525,40.102c-9.155,0-16.577-7.423-16.577-16.577c0-9.156,7.422-16.578,16.577-16.578c9.156,0,16.577,7.422,16.577,16.578 C40.102,32.679,32.681,40.102,23.525,40.102z"></path> <path d="M23.525,9.062c-7.975,0-14.463,6.488-14.463,14.463s6.488,14.463,14.463,14.463S37.988,31.5,37.988,23.525 S31.5,9.062,23.525,9.062z M23.525,35.626c-6.673,0-12.102-5.429-12.102-12.102c0-6.673,5.429-12.102,12.102-12.102 c6.672,0,12.102,5.428,12.102,12.102C35.627,30.198,30.197,35.626,23.525,35.626z"></path> <path d="M24.859,21.932c-1.836-0.692-2.593-1.146-2.593-1.858c0-0.605,0.454-1.211,1.858-1.211c0.853,0,1.536,0.149,2.073,0.323 c0.269,0.087,0.562,0.057,0.808-0.084c0.246-0.141,0.42-0.38,0.479-0.656l0.107-0.5c0.105-0.492-0.17-0.983-0.645-1.151 c-0.502-0.179-1.176-0.312-2.422-0.352v-0.917c0-0.552-0.449-1-1-1c-0.552,0-1,0.448-1,1v1.067c-2,0.455-3.609,1.945-3.609,3.848 c0,2.096,1.601,3.177,3.913,3.956c1.6,0.54,2.302,1.059,2.302,1.88c0,0.864-0.837,1.34-2.068,1.34c-0.935,0-1.81-0.201-2.558-0.48 c-0.266-0.1-0.563-0.081-0.814,0.052c-0.252,0.132-0.437,0.364-0.507,0.641l-0.135,0.538c-0.128,0.509,0.158,1.029,0.657,1.193 c0.909,0.299,2.093,0.518,2.821,0.564v1.402c0,0.552,0.448,1,1,1c0.552,0,1-0.448,1-1v-1.555c3-0.434,3.896-2.073,3.896-3.997 C28.421,24.029,27.432,22.838,24.859,21.932z"></path> </g> </g> </g></svg>
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
        @include('admin.warehouse.partials.warehouse-modal-edit', [
            'kategoriList' => $kategoriList,
            'barang' => $barang,
        ])
        @if (Auth::user() && Auth::user()->role === 'General Affair')
            @include('admin.warehouse.partials.warehouse-modal-history')
            @include('admin.warehouse.partials.warehouse-modal-edit-price')
        @endif
        @vite(['resources/js/warehouse.js', 'resources/js/table-sort.js'])
    </div>
</x-app-layout>
