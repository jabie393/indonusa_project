<x-app-layout>

    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-end overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-4">
            {{-- Search --}}
            <form action="{{ route('warehouse.index') }}" method="GET" class="block pl-2">
                <label for="topbar-search" class="sr-only">Search</label>
                <div class="relative md:w-96">
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
        <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
            @if (Auth::user() && Auth::user()->role === 'Warehouse')
                @php
                    $currentStatus = session('warehouse_filter_status', 'masuk');
                @endphp
                <div class="flex space-x-2">
                    <a href="{{ route('warehouse.index', ['status' => 'masuk', 'search' => request('search')]) }}" class="{{ $currentStatus === 'masuk' ? 'bg-white text-[#225A97]' : 'text-white hover:bg-white/10' }} rounded-lg px-4 py-2 text-sm font-medium transition-all">
                        Semua Barang
                    </a>
                    <a href="{{ route('warehouse.index', ['status' => 'defect', 'search' => request('search')]) }}" class="{{ $currentStatus === 'defect' ? 'bg-white text-[#225A97]' : 'text-white hover:bg-white/10' }} rounded-lg px-4 py-2 text-sm font-medium transition-all">
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
                            <th scope="col" class="w-fit px-4 py-3 text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="h-min-[300px]">
                    @forelse ($goods as $barang)
                        <tr class="dark:border-gray-700">
                            <td class="px-4 py-3"></td>
                            <td class="px-4 py-3">{{ $barang->status_listing }}</td>
                            <td scope="row" class="whitespace-nowrap px-4 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $barang->kode_barang }}
                            </td>
                            <td class="px-4 py-3">{{ $barang->nama_barang }}</td>
                            <td class="px-4 py-3">{{ $barang->kategori }}</td>
                            <td class="px-4 py-3">{{ $barang->stok }}</td>
                            <td class="px-4 py-3">{{ $barang->satuan }}</td>
                            <td class="px-4 py-3">{{ $barang->lokasi }}</td>
                            <td class="text-nowrap px-4 py-3">Rp{{ number_format($barang->harga, 0, ',', '.') }}</td>
                            @if (session('warehouse_filter_status') === 'defect')
                                <td class="px-4 py-3">
                                    @php
                                        $statusClass =
                                            [
                                                'ditinjau_supervisor' => 'bg-yellow-50 text-yellow-800 inset-ring inset-ring-yellow-600',
                                                'completed' => 'bg-green-50 text-green-700 inset-ring inset-ring-green-600',
                                            ][$barang->status_barang] ?? 'bg-gray-100 text-gray-800 inset-ring inset-ring-gray-600';
                                        $statusLabel =
                                            [
                                                'ditinjau_supervisor' => 'Ditinjau',
                                                'completed' => 'Selesai',
                                            ][$barang->status_barang] ?? $barang->status_barang;
                                    @endphp
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="{{ $statusClass }} badge">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                </td>
                            @endif
                            @if (Auth::user() && Auth::user()->role === 'Warehouse')
                                <td class="w-fit px-4 py-3 text-right">
                                    <div class="relative flex min-h-[40px] w-fit items-center justify-center">
                                        {{-- This invisible div helps reserve space for the absolute-positioned buttons --}}
                                        <div class="pointer-events-none invisible h-9 w-20 opacity-0">Placeholder</div>
                                        <div class="absolute left-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                            @if (session('warehouse_filter_status', 'masuk') === 'masuk')
                                                <button class="edit-barang-btn group flex h-full cursor-pointer items-center justify-center bg-amber-600 p-2 text-sm font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-4 focus:ring-amber-300 dark:bg-amber-500 dark:hover:bg-amber-600 dark:focus:ring-amber-800" title="Ajukan Barang Rusak" data-id="{{ $barang->id }}" data-status="{{ $barang->status_listing }}" data-kode="{{ $barang->kode_barang }}" data-nama="{{ $barang->nama_barang }}" data-kategori="{{ $barang->kategori }}" data-stok="{{ $barang->stok }}" data-satuan="{{ $barang->satuan }}" data-lokasi="{{ $barang->lokasi }}" data-harga="{{ $barang->harga }}" data-deskripsi="{{ $barang->deskripsi }}" data-gambar="{{ $barang->gambar }}">
                                                    <svg class="h-4 w-4" fill="currentColor" height="64px" width="64px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 271.264 271.264" xml:space="preserve">
                                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                                        <g id="SVGRepo_iconCarrier">
                                                            <path d="M157.655,243.023v-71.985c27.26-7.387,52.179-31.393,49.308-65.275l-10-101.255C196.711,1.95,194.559,0,191.988,0h-47.955 l-12.52,26.719l21.958,9.223c1.967,0.825,3.538,2.44,4.311,4.43c0.772,1.989,0.703,4.241-0.19,6.178l-17.733,38.42 c-1.29,2.793-4.11,4.597-7.186,4.597h-0.003c-1.147,0-2.26-0.245-3.308-0.729c-3.96-1.827-5.696-6.537-3.868-10.498l14.3-30.983 l-22.001-9.241c-1.976-0.829-3.55-2.452-4.32-4.452c-0.77-1.999-0.689-4.259,0.22-6.199L126.561,0H79.323 c-2.571,0-4.723,1.95-4.976,4.509L64.342,105.821c-1.515,17.403,3.411,32.937,14.247,44.921 c8.595,9.507,20.936,16.634,35.066,20.317v71.964c-25.016,3.741-42.726,18.776-43.51,19.452c-1.584,1.364-2.152,3.569-1.425,5.529 c0.728,1.959,2.597,3.26,4.688,3.26h124.494c2.09,0,3.96-1.3,4.688-3.26c0.727-1.96,0.159-4.165-1.425-5.529 C200.381,261.799,182.671,246.765,157.655,243.023z"></path>
                                                        </g>
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Ajukan Barang Rusak</span>
                                                </button>
                                            @endif
                                            <form action="{{ route('warehouse.destroy', $barang->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" onclick="confirmDelete(() => this.closest('form').submit())">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 lucide-trash-2 h-4 w-4" aria-hidden="true">
                                                        <path d="M10 11v6"></path>
                                                        <path d="M14 11v6"></path>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                                        <path d="M3 6h18"></path>
                                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Delete</span>
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
        <nav class="flex flex-col items-start justify-between space-y-3 p-4 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Showing
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $goods->firstItem() ?? 0 }}-{{ $goods->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $goods->total() ?? $goods->count() }}</span>
                </span>
                <form method="GET" action="{{ route('warehouse.index') }}">
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
