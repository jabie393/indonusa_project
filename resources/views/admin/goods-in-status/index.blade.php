<?php

use App\Models\Barang; ?>
<x-app-layout>
    <div
        class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex items-center h-16 justify-end overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="px-4">
            {{-- Search --}}
            <form action="{{ route('goods-in-status.index') }}" method="GET" class="block pl-2">
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
        class="relative flex max-h-[calc(100vh-210px)] flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="shrink-0 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
        </div>
        <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
            <table id="" class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead
                    class="sticky top-0 z-30 bg-gray-50 text-nowrap text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">Status Listing</th>
                        <th scope="col" class="px-4 py-3">Barang</th>
                        <th scope="col" class="px-4 py-3">Deskripsi</th>
                        <th scope="col" class="px-4 py-3">Stok</th>
                        <th scope="col" class="px-4 py-3">Harga Beli</th>
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
                            <td class="text-nowrap px-4 py-3 font-medium text-slate-700 dark:text-gray-300">
                                <div class="flex w-full items-center justify-between">
                                    <span>Rp</span>
                                    <span>{{ number_format($barang->buy_price, 0, '.', ',') }}</span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-center align-middle">
                                @php
                                    $statusLabel =
                                        [
                                            'ditinjau' => 'Pending',
                                            'disetujui' => 'Approved',
                                            'ditolak' => 'Rejected',
                                        ][$barang->goods_status] ?? $barang->goods_status;

                                    $badgeBg = 'bg-gray-50 dark:bg-gray-900/30';
                                    $badgeText = 'text-gray-700 dark:text-gray-300';
                                    $badgeBorder = 'border border-gray-200 dark:border-gray-700/50';
                                    $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/></svg>';

                                    if ($barang->goods_status === 'disetujui') {
                                        $badgeBg = 'bg-green-50 dark:bg-green-950/30';
                                        $badgeText = 'text-green-700 dark:text-green-300';
                                        $badgeBorder = 'border border-green-200 dark:border-green-800/50';
                                        $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>';
                                    } elseif ($barang->goods_status === 'ditinjau') {
                                        $badgeBg = 'bg-blue-50 dark:bg-blue-950/30';
                                        $badgeText = 'text-blue-700 dark:text-blue-300';
                                        $badgeBorder = 'border border-blue-200 dark:border-blue-800/50';
                                        $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
                                    } elseif ($barang->goods_status === 'ditolak') {
                                        $badgeBg = 'bg-red-50 dark:bg-red-950/30';
                                        $badgeText = 'text-red-700 dark:text-red-300';
                                        $badgeBorder = 'border border-red-200 dark:border-red-800/50';
                                        $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>';
                                    }
                                @endphp
                                <span
                                    class="flex items-center justify-center rounded-full px-2 py-1 text-xs font-semibold {{ $badgeBg }} {{ $badgeText }} {{ $badgeBorder }}">
                                    {!! $iconSvg !!}{{ $statusLabel }}
                                </span>
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
                                        @if ($barang->goods_status == 'ditinjau')
                                            {{-- Edit barang modal --}}
                                            <button
                                                class="edit-barang-btn group flex h-full cursor-pointer items-center justify-center border-r border-blue-800 bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:border-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900 transition-all duration-300 ease-in-out"
                                                data-id="{{ $barang->id }}" data-status="{{ $barang->status_listing }}"
                                                data-kode="{{ $barang->goods_code }}" data-nama="{{ $barang->goods_name }}"
                                                data-kategori="{{ $barang->category }}"
                                                data-stok="{{ is_numeric($barang->stock) ? $barang->stock : '' }}"
                                                data-satuan="{{ $barang->unit }}" data-lokasi="{{ $barang->location }}"
                                                data-harga="{{ $barang->buy_price }}"
                                                data-harga_jual="{{ $barang->selling_price }}"
                                                data-deskripsi="{{ $barang->description }}"
                                                data-tipe_request="{{ $barang->request_type }}"
                                                data-gambar="{{ $barang->image }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-pencil h-4 w-4">
                                                    <path
                                                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                    </path>
                                                    <path d="m15 5 4 4"></path>
                                                </svg>
                                                <span
                                                    class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Edit</span>
                                            </button>
                                            <form action="{{ route('goods-in-status.destroy', $barang->id) }}" method="POST"
                                                class="inline-flex">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 transition-all duration-300 ease-in-out"
                                                    onclick="confirmDelete(() => this.closest('form').submit())">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-trash2 lucide-trash-2 h-4 w-4">
                                                        <path d="M10 11v6"></path>
                                                        <path d="M14 11v6"></path>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                                        <path d="M3 6h18"></path>
                                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                    <span
                                                        class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Delete</span>
                                                </button>
                                            </form>
                                        @elseif($barang->goods_status == 'ditolak')
                                            {{-- Note modal --}}
                                            <button
                                                class="note-btn group flex h-full cursor-pointer items-center justify-center border-r border-yellow-700 dark:border-yellow-500 bg-yellow-600 p-2 text-sm font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800 transition-all duration-300 ease-in-out"
                                                data-catatan="{{ $barang->note ?? '' }}" data-nama="{{ $barang->goods_name }}"
                                                data-kode="{{ $barang->goods_code }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-sticky-note h-4 w-4">
                                                    <path
                                                        d="M15.5 3H5a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V8.5L15.5 3Z">
                                                    </path>
                                                    <path d="M15 3v6h6"></path>
                                                </svg>
                                                <span
                                                    class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Note</span>
                                            </button>
                                            {{-- Revise barang modal --}}
                                            <button
                                                class="edit-barang-btn group flex h-full cursor-pointer items-center justify-center border-r border-blue-800 bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:border-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-all duration-300 ease-in-out"
                                                data-id="{{ $barang->id }}" data-status="{{ $barang->status_listing }}"
                                                data-kode="{{ $barang->goods_code }}" data-nama="{{ $barang->goods_name }}"
                                                data-kategori="{{ $barang->category }}"
                                                data-stok="{{ is_numeric($barang->stock) ? $barang->stock : '' }}"
                                                data-satuan="{{ $barang->unit }}" data-lokasi="{{ $barang->location }}"
                                                data-harga="{{ $barang->buy_price }}"
                                                data-harga_jual="{{ $barang->selling_price }}"
                                                data-deskripsi="{{ $barang->description }}" data-gambar="{{ $barang->image }}"
                                                data-tipe_request="{{ $barang->request_type }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-pencil h-4 w-4">
                                                    <path
                                                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                    </path>
                                                    <path d="m15 5 4 4"></path>
                                                </svg>
                                                <span
                                                    class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Revise</span>
                                            </button>
                                            <form action="{{ route('goods-in-status.destroy', $barang->id) }}" method="POST"
                                                class="inline-flex">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 transition-all duration-300 ease-in-out"
                                                    onclick="confirmDelete(() => this.closest('form').submit())">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-trash2 lucide-trash-2 h-4 w-4">
                                                        <path d="M10 11v6"></path>
                                                        <path d="M14 11v6"></path>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                                        <path d="M3 6h18"></path>
                                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                    <span
                                                        class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Delete</span>
                                                </button>
                                            </form>
                                        @else
                                            {{-- Note modal --}}
                                            <button
                                                class="note-btn group flex h-full cursor-pointer items-center justify-center border-r border-yellow-700 dark:border-yellow-500 bg-yellow-600 p-2 text-sm font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800 transition-all duration-300 ease-in-out"
                                                data-catatan="{{ $barang->note ?? '' }}" data-nama="{{ $barang->goods_name }}"
                                                data-kode="{{ $barang->goods_code }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-sticky-note h-4 w-4">
                                                    <path
                                                        d="M15.5 3H5a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V8.5L15.5 3Z">
                                                    </path>
                                                    <path d="M15 3v6h6"></path>
                                                </svg>
                                                <span
                                                    class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Note</span>
                                            </button>
                                            {{-- Edit barang modal --}}
                                            <button
                                                class="edit-barang-btn group flex h-full cursor-pointer items-center justify-center border-r border-blue-800 bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:border-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900 transition-all duration-300 ease-in-out"
                                                data-id="{{ $barang->id }}" data-status="{{ $barang->status_listing }}"
                                                data-kode="{{ $barang->goods_code }}" data-nama="{{ $barang->goods_name }}"
                                                data-kategori="{{ $barang->category }}"
                                                data-stok="{{ is_numeric($barang->stock) ? $barang->stock : '' }}"
                                                data-satuan="{{ $barang->unit }}" data-lokasi="{{ $barang->location }}"
                                                data-harga="{{ $barang->buy_price }}"
                                                data-harga_jual="{{ $barang->selling_price }}"
                                                data-deskripsi="{{ $barang->description }}"
                                                data-tipe_request="{{ $barang->request_type }}"
                                                data-gambar="{{ $barang->image }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-pencil h-4 w-4">
                                                    <path
                                                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                    </path>
                                                    <path d="m15 5 4 4"></path>
                                                </svg>
                                                <span
                                                    class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Edit</span>
                                            </button>
                                            <form action="{{ route('goods-in-status.destroy', $barang->id) }}" method="POST"
                                                class="inline-flex">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 transition-all duration-300 ease-in-out"
                                                    onclick="return confirm('Yakin hapus?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-trash2 lucide-trash-2 h-4 w-4">
                                                        <path d="M10 11v6"></path>
                                                        <path d="M14 11v6"></path>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                                        <path d="M3 6h18"></path>
                                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                    <span
                                                        class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Delete</span>
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
                <form method="GET" action="{{ route('goods-in-status.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="perPage" onchange="this.form.submit()"
                        class="mx-2 rounded-xl border border-gray-300 bg-gray-50 p-1 pl-2 pr-8 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}
                            </option>
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
    @include('admin.goods-in-status.partials.goods-in-status-modal-edit-primary', [
        'kategoriList' => Barang::KATEGORI,
    ])
    @include('admin.goods-in-status.partials.goods-in-status-modal-edit-new-stock')
    @include('admin.goods-in-status.partials.goods-in-status-modal-show-note')
    @vite(['resources/js/goods-in-status.js', 'resources/js/table-sort.js'])

</x-app-layout>
