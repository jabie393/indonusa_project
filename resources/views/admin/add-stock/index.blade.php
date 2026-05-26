<x-app-layout>
    <div
        class="relative mb-5 flex justify-end overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800 inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">
        <div class="p-4">
            {{-- Search --}}
            <form action="{{ route('add-stock.index') }}" method="GET" class="block pl-2">
                <label for="topbar-search" class="sr-only">Search</label>
                <div class="relative md:w-64 md:w-96">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                            </path>
                        </svg>
                    </div>
                    <input type="text" name="search" id="topbar-search" value="{{ request('search') }}"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                        placeholder="Search" />
                </div>
            </form>
        </div>
    </div>
    <div
        class="relative flex max-h-[calc(100vh-210px)] flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800 inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">
        <div
            class="shrink-0 flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 shadow-2xl md:flex-row md:space-x-4 md:space-y-0">
        </div>
        <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
            <table class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="sticky top-0 z-30 bg-gray-50 text-nowrap text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-nowrap">Status Listing</th>
                        <th scope="col" class="px-4 py-3 text-nowrap">Kode Barang</th>
                        <th scope="col" class="px-4 py-3 text-nowrap">Kategori</th>
                        <th scope="col" class="px-4 py-3 text-nowrap">Nama Barang</th>
                        <th scope="col" class="px-4 py-3 text-nowrap">Description</th>
                        <th scope="col" class="px-4 py-3 text-nowrap">Stok</th>
                        <th scope="col" class="px-4 py-3 text-nowrap">Harga Jual</th>
                        <th scope="col" class="px-4 py-3 text-center no-sort text-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody class="text-nowrap">
                    @forelse ($goods as $barang)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4">{{ $barang->status_listing }}</td>
                            <td scope="row" class="whitespace-nowrap px-4 font-medium text-gray-900 dark:text-white">
                                {{ $barang->goods_code }}
                            </td>
                            <td class="px-4">{{ $barang->category }}</td>
                            <td class="px-4">{{ $barang->goods_name }}</td>
                            <td class="px-4 max-w-xs truncate">{{ $barang->description }}</td>
                            <td class="px-4">{{ $barang->stock }}</td>
                            <td class="px-4 text-nowrap font-medium text-slate-700">
                                <div class="flex justify-between items-center w-full">
                                    <span>Rp</span>
                                    <span>{{ number_format($barang->selling_price, 0, '.', ',') }}</span>
                                </div>
                            </td>
                            <td class="w-fit px-4 py-3 text-right">
                                <div class="relative flex min-h-[40px] w-fit items-center justify-end">
                                    <div class="pointer-events-none invisible h-9 w-32 opacity-0">Placeholder</div>
                                    <div
                                        class="absolute left-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                        {{-- Add Stock --}}
                                        <button
                                            class="edit-barang-btn group flex cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                            data-id="{{ $barang->id }}" data-status="{{ $barang->status_listing }}"
                                            data-kode="{{ $barang->goods_code }}"
                                            data-nama="{{ $barang->goods_name }}"
                                            data-kategori="{{ $barang->category }}" data-stok="{{ $barang->stock }}"
                                            data-satuan="{{ $barang->unit }}" data-lokasi="{{ $barang->location }}"
                                            data-harga="{{ $barang->selling_price }}"
                                            data-deskripsi="{{ $barang->description }}"
                                            data-gambar="{{ $barang->image }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-plus h-4 w-4">
                                                <path d="M5 12h14"></path>
                                                <path d="M12 5v14"></path>
                                            </svg>
                                            <span
                                                class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Add
                                                Stock</span>
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
                <form method="GET" action="{{ route('add-stock.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="perPage" onchange="this.form.submit()"
                        class="mx-2 rounded-xl border border-gray-300 bg-gray-50 p-1 pl-2 pr-8 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}"
                                {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </form>
                <span class="text-sm text-gray-500 dark:text-gray-400">per page</span>
            </div>
            <div>
                {{ $goods->links() }}
            </div>
        </nav>
    </div>

    <!-- Modals -->
    @include('admin.add-stock.partials.add-stock-modal')

    <!-- Js -->
    @vite(['resources/js/add-stock.js', 'resources/js/table-sort.js'])
</x-app-layout>
