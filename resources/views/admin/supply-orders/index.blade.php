<x-app-layout>

    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-between overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-4">
            {{-- Bulk Actions --}}
            <div id="bulk-actions" class="hidden flex-row items-center space-x-2" data-approve-url="{{ route('supply-orders.bulk-approve') }}" data-reject-url="{{ route('supply-orders.bulk-reject') }}">
                <button id="bulk-approve" class="flex items-center justify-center rounded-lg bg-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300">
                    Approve Selected (<span id="selected-count">0</span>)
                </button>
                <button id="bulk-reject" class="flex items-center justify-center rounded-lg bg-red-700 px-4 py-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300">
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
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                            </path>
                        </svg>
                    </div>
                    <input type="search" name="search" id="topbar-search dt-search-0" aria-controls="warehouseTable" value="{{ request('search') }}" class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" placeholder="Search" />
                </div>
            </form>

        </div>
    </div>

    <div class="relative overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-2xl">
        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
        </div>
        <div class="overflow-x-auto">
            <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
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
                        <th scope="col" class="px-4 py-3">Status Barang</th>
                        <th scope="col" class="px-4 py-3">Tipe Request</th>
                        <th scope="col" class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="h-min-[300px]">
                    @forelse ($goods as $barang)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3">{{ $barang->id }}</td>
                            <td class="px-4 py-3">{{ $barang->status_listing }}</td>
                            <td scope="row" class="whitespace-nowrap px-4 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $barang->kode_barang }}
                            </td>
                            <td class="px-4 py-3">{{ $barang->nama_barang }}</td>
                            <td class="px-4 py-3">{{ $barang->kategori }}</td>
                            <td class="px-4 py-3">{{ $barang->stok }}</td>
                            <td class="px-4 py-3">{{ $barang->satuan }}</td>
                            <td class="px-4 py-3">{{ $barang->lokasi }}</td>
                            <td class="px-4 py-3">{{ $barang->harga }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusClass =
                                        [
                                            'ditinjau' => 'bg-yellow-50 text-yellow-800 inset-ring inset-ring-yellow-600',
                                        ][$barang->status_barang] ?? 'bg-gray-100 text-gray-800 inset-ring inset-ring-gray-600';
                                    $statusLabel =
                                        [
                                            'ditinjau' => 'Ditinjau',
                                        ][$barang->status_barang] ?? $barang->status_barang;
                                @endphp
                                <div class="flex items-center justify-center gap-2">
                                    <span class="{{ $statusClass }} badge">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if ($barang->tipe_request == 'primary')
                                    Barang baru
                                @elseif($barang->tipe_request == 'new_stock')
                                    Stok baru
                                @else
                                    {{ $barang->tipe_request }}
                                @endif
                            </td>
                            <td class="w-fit px-4 py-3">
                                <div class="relative flex min-h-[40px] w-fit items-center justify-end">
                                    <div class="pointer-events-none invisible h-9 w-20 opacity-0">Placeholder</div>
                                    <div class="absolute right-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                        {{-- Approve barang --}}
                                        <form action="{{ route('supply-orders.approve', $barang->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="group flex h-full cursor-pointer items-center justify-center bg-green-700 p-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Approve</span>
                                            </button>
                                        </form>
                                        {{-- Reject barang --}}
                                        <button type="button" class="reject-btn group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" data-id="{{ $barang->id }}" data-nama="{{ $barang->nama_barang }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Reject</span>
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
        <nav class="flex flex-col items-start justify-between space-y-3 p-4 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Showing
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $goods->firstItem() ?? 0 }}-{{ $goods->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $goods->total() ?? $goods->count() }}</span>
                </span>
                <form method="GET" action="{{ route('supply-orders.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="perPage" onchange="this.form.submit()" class="ml-2 rounded border-gray-300 p-1 pl-2 pr-5 text-sm">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </form>
                <span class="text-sm text-gray-500 dark:text-gray-400">per halaman</span>
                <div>
                    {{ $goods->links() }}
                </div>
            </div>
        </nav>
    </div>

    <!-- Modals -->
    @include('components.supply-orders-modal-reject')
    @vite(['resources/js/supply-orders.js'])

</x-app-layout>
