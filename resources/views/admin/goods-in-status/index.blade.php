<x-app-layout>
    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h2 class="mr-3 font-semibold text-white">Daftar barang</h2>
            </div>
            <div class="mr-5 flex max-w-full shrink-0 flex-col items-stretch justify-end space-y-2 py-5 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0 md:py-0">
                {{-- Search --}}
                <form action="{{ route('goods-in-status.index') }}" method="GET" class="block pl-2">
                    <label for="topbar-search" class="sr-only">Search</label>
                    <div class="relative md:w-64 md:w-96">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
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
        <div class="overflow-x-auto">
            <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">Status Listing</th>
                        <th scope="col" class="px-4 py-3">Kode Barang</th>
                        <th scope="col" class="px-4 py-3">Nama Barang</th>
                        <th scope="col" class="px-4 py-3">Kategori</th>
                        <th scope="col" class="px-4 py-3">Stok</th>
                        <th scope="col" class="px-4 py-3">Satuan</th>
                        <th scope="col" class="px-4 py-3">Lokasi</th>
                        <th scope="col" class="px-4 py-3">Harga</th>
                        <th scope="col" class="px-4 py-3">Status Barang</th>
                        <th scope="col" class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="h-min-[300px]">
                    @forelse ($barangs as $barang)
                        <tr class="border-b dark:border-gray-700">
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
                            <td class="px-4 py-3">{{ $barang->status_barang }}</td>
                            <td class="flex items-center justify-end px-4 py-3">
                                @if ($barang->status_barang == 'ditinjau')
                                    {{-- Edit barang modal --}}
                                    <button
                                        class="edit-barang-btn mb-2 me-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                        data-id="{{ $barang->id }}" data-status="{{ $barang->status_listing }}" data-kode="{{ $barang->kode_barang }}" data-nama="{{ $barang->nama_barang }}"
                                        data-kategori="{{ $barang->kategori }}" data-stok="{{ is_numeric($barang->stok) ? $barang->stok : '' }}" data-satuan="{{ $barang->satuan }}"
                                        data-lokasi="{{ $barang->lokasi }}" data-harga="{{ $barang->harga }}" data-deskripsi="{{ $barang->deskripsi }}"
                                        data-tipe_request="{{ $barang->tipe_request }}" data-gambar="{{ $barang->gambar }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('goods-in-status.destroy', $barang->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="mb-2 me-2 rounded-lg bg-red-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                            onclick="confirmDelete(() => this.closest('form').submit())">
                                            Hapus
                                        </button>
                                    </form>
                                @elseif($barang->status_barang == 'ditolak')
                                    {{-- Note modal --}}
                                    <button
                                        class="note-btn mb-2 me-2 rounded-lg bg-yellow-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-4 focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800"
                                        data-catatan="{{ $barang->catatan ?? '' }}">
                                        Note
                                    </button>
                                    {{-- Revise barang modal --}}
                                    <button
                                        class="edit-barang-btn mb-2 me-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                        data-id="{{ $barang->id }}" data-status="{{ $barang->status_listing }}" data-kode="{{ $barang->kode_barang }}" data-nama="{{ $barang->nama_barang }}"
                                        data-kategori="{{ $barang->kategori }}" data-stok="{{ is_numeric($barang->stok) ? $barang->stok : '' }}" data-satuan="{{ $barang->satuan }}"
                                        data-lokasi="{{ $barang->lokasi }}" data-harga="{{ $barang->harga }}" data-deskripsi="{{ $barang->deskripsi }}" data-gambar="{{ $barang->gambar }}"
                                        data-tipe_request="{{ $barang->tipe_request }}">
                                        Revise
                                    </button>
                                    <form action="{{ route('goods-in-status.destroy', $barang->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="mb-2 me-2 rounded-lg bg-red-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                            onclick="confirmDelete(() => this.closest('form').submit())">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    {{-- Note modal --}}
                                    <button
                                        class="note-btn mb-2 me-2 rounded-lg bg-yellow-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-4 focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                                        Note
                                    </button>
                                    {{-- Edit barang modal --}}
                                    <button
                                        class="edit-barang-btn mb-2 me-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                        data-id="{{ $barang->id }}" data-status="{{ $barang->status_listing }}" data-kode="{{ $barang->kode_barang }}" data-nama="{{ $barang->nama_barang }}"
                                        data-kategori="{{ $barang->kategori }}" data-stok="{{ is_numeric($barang->stok) ? $barang->stok : '' }}" data-satuan="{{ $barang->satuan }}"
                                        data-lokasi="{{ $barang->lokasi }}" data-harga="{{ $barang->harga }}" data-deskripsi="{{ $barang->deskripsi }}"
                                        data-tipe_request="{{ $barang->tipe_request }}" data-gambar="{{ $barang->gambar }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('goods-in-status.destroy', $barang->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="mb-2 me-2 rounded-lg bg-red-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                            onclick="return confirm('Yakin hapus?')">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada barang</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav class="flex flex-col items-start justify-between space-y-3 p-4 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
            <div class="flex items-center space-x-2">

                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Showing
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $barangs->firstItem() ?? 0 }}-{{ $barangs->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $barangs->total() ?? $barangs->count() }}</span>
                </span>
                <form method="GET" action="{{ route('goods-in-status.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="perPage" onchange="this.form.submit()" class="ml-2 rounded border-gray-300 p-1 pl-2 pr-5 text-sm">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </form>
                <span class="text-sm text-gray-500 dark:text-gray-400">per page</span>
            </div>

            <div>
                {{ $barangs->links() }}
            </div>
        </nav>


    </div>

    <!-- Modals -->
    @include('components.goods-in-status-modal-edit-primary')
    @include('components.goods-in-status-modal-edit-new-stock')
    @include('components.goods-in-status-modal-show-note')
    @vite(['resources/js/goods-in-status.js'])

</x-app-layout>
