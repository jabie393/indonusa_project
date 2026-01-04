<?php use App\Models\Barang; ?>
<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-end overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-4">
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
                    <input type="text" name="search" id="topbar-search" value="{{ request('search') }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" placeholder="Search" />
                </div>
            </form>
        </div>
    </div>
    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="overflow-x-auto">
            <div class=" bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
            </div>
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
                        <th scope="col" class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="h-min-[300px]">
                    @forelse ($barangs as $barang)
                        <tr class="border-b dark:border-gray-700">
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
                            <td class="px-4 py-3">{{ $barang->harga }}</td>
                            <td class="px-4 py-3">{{ $barang->status_barang }}</td>
                            <td class="w-fit px-4 py-3 text-right">
                                <div class="relative flex min-h-[40px] w-fit items-center justify-end">
                                    <div class="pointer-events-none invisible h-9 w-32 opacity-0">Placeholder</div>
                                    <div class="absolute left-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                        @if ($barang->status_barang == 'ditinjau')
                                            {{-- Edit barang modal --}}
                                            <button class="edit-barang-btn group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" data-id="{{ $barang->id }}" data-status="{{ $barang->status_listing }}" data-kode="{{ $barang->kode_barang }}" data-nama="{{ $barang->nama_barang }}" data-kategori="{{ $barang->kategori }}" data-stok="{{ is_numeric($barang->stok) ? $barang->stok : '' }}" data-satuan="{{ $barang->satuan }}" data-lokasi="{{ $barang->lokasi }}" data-harga="{{ $barang->harga }}" data-deskripsi="{{ $barang->deskripsi }}" data-tipe_request="{{ $barang->tipe_request }}" data-gambar="{{ $barang->gambar }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil h-4 w-4">
                                                    <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path>
                                                    <path d="m15 5 4 4"></path>
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Edit</span>
                                            </button>
                                            <form action="{{ route('goods-in-status.destroy', $barang->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" onclick="confirmDelete(() => this.closest('form').submit())">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 lucide-trash-2 h-4 w-4">
                                                        <path d="M10 11v6"></path>
                                                        <path d="M14 11v6"></path>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                                        <path d="M3 6h18"></path>
                                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Hapus</span>
                                                </button>
                                            </form>
                                        @elseif($barang->status_barang == 'ditolak')
                                            {{-- Note modal --}}
                                            <button class="note-btn group flex h-full cursor-pointer items-center justify-center bg-yellow-600 p-2 text-sm font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-4 focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800" data-catatan="{{ $barang->catatan ?? '' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sticky-note h-4 w-4">
                                                    <path d="M15.5 3H5a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V8.5L15.5 3Z"></path>
                                                    <path d="M15 3v6h6"></path>
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Note</span>
                                            </button>
                                            {{-- Revise barang modal --}}
                                            <button class="edit-barang-btn group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" data-id="{{ $barang->id }}" data-status="{{ $barang->status_listing }}" data-kode="{{ $barang->kode_barang }}" data-nama="{{ $barang->nama_barang }}" data-kategori="{{ $barang->kategori }}" data-stok="{{ is_numeric($barang->stok) ? $barang->stok : '' }}" data-satuan="{{ $barang->satuan }}" data-lokasi="{{ $barang->lokasi }}" data-harga="{{ $barang->harga }}" data-deskripsi="{{ $barang->deskripsi }}" data-gambar="{{ $barang->gambar }}" data-tipe_request="{{ $barang->tipe_request }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil h-4 w-4">
                                                    <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path>
                                                    <path d="m15 5 4 4"></path>
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Revise</span>
                                            </button>
                                            <form action="{{ route('goods-in-status.destroy', $barang->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" onclick="confirmDelete(() => this.closest('form').submit())">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 lucide-trash-2 h-4 w-4">
                                                        <path d="M10 11v6"></path>
                                                        <path d="M14 11v6"></path>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                                        <path d="M3 6h18"></path>
                                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Hapus</span>
                                                </button>
                                            </form>
                                        @else
                                            {{-- Note modal --}}
                                            <button class="note-btn group flex h-full cursor-pointer items-center justify-center bg-yellow-600 p-2 text-sm font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-4 focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sticky-note h-4 w-4">
                                                    <path d="M15.5 3H5a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V8.5L15.5 3Z"></path>
                                                    <path d="M15 3v6h6"></path>
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Note</span>
                                            </button>
                                            {{-- Edit barang modal --}}
                                            <button class="edit-barang-btn group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" data-id="{{ $barang->id }}" data-status="{{ $barang->status_listing }}" data-kode="{{ $barang->kode_barang }}" data-nama="{{ $barang->nama_barang }}" data-kategori="{{ $barang->kategori }}" data-stok="{{ is_numeric($barang->stok) ? $barang->stok : '' }}" data-satuan="{{ $barang->satuan }}" data-lokasi="{{ $barang->lokasi }}" data-harga="{{ $barang->harga }}" data-deskripsi="{{ $barang->deskripsi }}" data-tipe_request="{{ $barang->tipe_request }}" data-gambar="{{ $barang->gambar }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil h-4 w-4">
                                                    <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path>
                                                    <path d="m15 5 4 4"></path>
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Edit</span>
                                            </button>
                                            <form action="{{ route('goods-in-status.destroy', $barang->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" onclick="return confirm('Yakin hapus?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 lucide-trash-2 h-4 w-4">
                                                        <path d="M10 11v6"></path>
                                                        <path d="M14 11v6"></path>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                                        <path d="M3 6h18"></path>
                                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Delete</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
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
    @include('components.goods-in-status-modal-edit-primary', ['kategoriList' => Barang::KATEGORI])
    @include('components.goods-in-status-modal-edit-new-stock')
    @include('components.goods-in-status-modal-show-note')
    @vite(['resources/js/goods-in-status.js'])

</x-app-layout>
