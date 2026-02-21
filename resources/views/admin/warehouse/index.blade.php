<x-app-layout>

    <div
        class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-end overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-4">
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

    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
            @if (Auth::user() && Auth::user()->role === 'Warehouse')
                @php
                    $currentStatus = session('warehouse_filter_status', 'masuk');
                @endphp
                <div class="flex space-x-2">
                    <a href="{{ route('warehouse.index', ['status' => 'masuk', 'search' => request('search')]) }}"
                        class="rounded-lg px-4 py-2 text-sm font-medium transition-all {{ $currentStatus === 'masuk' ? 'bg-white text-[#225A97]' : 'text-white hover:bg-white/10' }}">
                        Semua Barang
                    </a>
                    <a href="{{ route('warehouse.index', ['status' => 'defect', 'search' => request('search')]) }}"
                        class="rounded-lg px-4 py-2 text-sm font-medium transition-all {{ $currentStatus === 'defect' ? 'bg-white text-[#225A97]' : 'text-white hover:bg-white/10' }}">
                        Pengajuan Defect
                    </a>
                </div>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table id="warehouseTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="selectCol px-4 py-3"></th>
                        <th scope="col" class="px-4 py-3">Status Listing</th>
                        <th scope="col" class="px-4 py-3">Kode Barang</th>
                        <th scope="col" class="px-4 py-3">Nama Barang</th>
                        <th scope="col" class="px-4 py-3">Kategori</th>
                        <th scope="col" class="px-4 py-3">Stok</th>
                        <th scope="col" class="px-4 py-3">Satuan</th>
                        <th scope="col" class="px-4 py-3">Lokasi</th>
                        <th scope="col" class="px-4 py-3">Harga</th>
                        @if (session('warehouse_filter_status') === 'defect')
                            <th scope="col" class="px-4 py-3">Status</th>
                        @endif
                        @if (Auth::user() && Auth::user()->role === 'Warehouse')
                            <th scope="col" class="w-fit px-4 py-3">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="h-min-[300px]">
                    @forelse ($goods as $barang)
                        <tr class="dark:border-gray-700">
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3">{{ $barang->status_listing }}</td>
                            <td scope="row"
                                class="whitespace-nowrap px-4 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $barang->kode_barang }}
                            </td>
                            <td class="px-4 py-3">{{ $barang->nama_barang }}</td>
                            <td class="px-4 py-3">{{ $barang->kategori }}</td>
                            <td class="px-4 py-3">{{ $barang->stok }}</td>
                            <td class="px-4 py-3">{{ $barang->satuan }}</td>
                            <td class="px-4 py-3">{{ $barang->lokasi }}</td>
                            <td class="px-4 py-3">Rp{{ number_format($barang->harga, 0, ',', '.') }}</td>
                            @if (session('warehouse_filter_status') === 'defect')
                                <td class="px-4 py-3">
                                    <span
                                        class="rounded bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                        {{ $barang->status_barang === 'ditinjau_supervisor' ? 'Ditinjau' : str_replace('_', ' ', ucfirst($barang->status_barang)) }}
                                    </span>
                                </td>
                            @endif
                            @if (Auth::user() && Auth::user()->role === 'Warehouse')
                                <td class="w-fit px-4 py-3">
                                    <div class="relative flex min-h-[40px] w-fit items-center justify-start">
                                        <div class="w-15 pointer-events-none invisible h-9 opacity-0">Placeholder</div>
                                        <div
                                            class="absolute right-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                            @if (session('warehouse_filter_status', 'masuk') === 'masuk')
                                                <button
                                                    class="edit-barang-btn group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
                                                    title="Ajukan Barang Rusak" data-id="{{ $barang->id }}"
                                                    data-status="{{ $barang->status_listing }}"
                                                    data-kode="{{ $barang->kode_barang }}"
                                                    data-nama="{{ $barang->nama_barang }}"
                                                    data-kategori="{{ $barang->kategori }}"
                                                    data-stok="{{ $barang->stok }}"
                                                    data-satuan="{{ $barang->satuan }}"
                                                    data-lokasi="{{ $barang->lokasi }}"
                                                    data-harga="{{ $barang->harga }}"
                                                    data-deskripsi="{{ $barang->deskripsi }}"
                                                    data-gambar="{{ $barang->gambar }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="lucide lucide-shield-alert h-4 w-4">
                                                        <path
                                                            d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                                                        <path d="M12 8v4" />
                                                        <path d="M12 16h.01" />
                                                    </svg>
                                                    <span
                                                        class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Ajukan
                                                        Barang Rusak</span>
                                                </button>
                                            @endif
                                            <form action="{{ route('warehouse.destroy', $barang->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                                    onclick="confirmDelete(() => this.closest('form').submit())">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="lucide lucide-trash2 lucide-trash-2 h-4 w-4"
                                                        aria-hidden="true">
                                                        <path d="M10 11v6"></path>
                                                        <path d="M14 11v6"></path>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                                        <path d="M3 6h18"></path>
                                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                    <span
                                                        class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Delete</span>
                                                </button>
                                            </form>
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
        <nav class="flex flex-col items-start justify-between space-y-3 p-4 md:flex-row md:items-center md:space-y-0"
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
                        class="ml-2 rounded border-gray-300 p-1 pl-2 pr-5 text-sm">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}"
                                {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
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
        @vite(['resources/js/warehouse.js'])
    </div>
</x-app-layout>
