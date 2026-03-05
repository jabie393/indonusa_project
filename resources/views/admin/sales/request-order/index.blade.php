<x-app-layout>

    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-between overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-4">
            <div class="flex w-full shrink-0 flex-col items-stretch justify-end space-y-2 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0">
                {{-- Tambah barang modal --}}
                <a href="{{ route('sales.request-order.create') }}" class="flex flex-row items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 font-semibold text-white hover:bg-[#19426d]">
                    <svg class="mr-2 h-3.5 w-3.5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    Request Order Baru
                </a>
                <div id="bulk-actions" class="hidden flex-row items-center space-x-2" data-delete-url="{{ route('sales.request-order.bulk-delete') }}" data-sent-url="{{ route('sales.request-order.bulk-send-to-warehouse') }}">
                    <button id="bulk-delete" class="flex cursor-pointer items-center justify-center rounded-lg bg-red-700 px-4 py-2 font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300">
                        Delete Selected (<span id="selected-count">0</span>)
                    </button>
                    <button id="bulk-send" class="flex cursor-pointer items-center justify-center rounded-lg bg-blue-700 px-4 py-2 font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">
                        Send to Warehouse
                    </button>
                </div>
            </div>
            {{-- Bulk Actions --}}

        </div>

        <div class="p-4">

            {{-- Search --}}
            <form action="{{ route('sales.request-order.index') }}" method="GET" class="block pl-2">
                <label for="topbar-search" class="sr-only">Search</label>
                <div class="relative md:w-64 md:w-96">
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
        <div class="bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
        </div>
        <div class="overflow-x-auto">
            <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="selectCol px-4 py-3"></th>
                        <th scope="col" class="text-nowrap px-4 py-3">No. Request</th>
                        <th scope="col" class="text-nowrap px-4 py-3">No. Penawaran</th>
                        <th scope="col" class="text-nowrap px-4 py-3">No. PO</th>
                        <th scope="col" class="text-nowrap px-4 py-3">No. Sales Order</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Tanggal</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Nama Customer</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Jumlah Item</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Total</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Stok</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Diskon</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Status</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Berlaku Sampai</th>
                        <th scope="col" class="text-nowrap px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requestOrders as $ro)
                        <tr class="max-h-16 dark:border-gray-700">
                            <td class="px-4 py-3">{{ $ro->id }}</td>
                            <td class="px-4 py-3 text-nowrap">{{ $ro->request_number }}</td>
                            <td class="px-4 py-3 text-nowrap">{{ $ro->nomor_penawaran ?? '-' }}</td>
                            <td class="px-4 py-3 text-nowrap">{{ $ro->no_po ?? '-' }}</td>
                            <td class="px-4 py-3 text-nowrap">{{ $ro->sales_order_number ?? '-' }}</td>
                            <td class="px-4 py-3 text-nowrap">{{ $ro->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-nowrap">{{ $ro->customer_name }}</td>
                            <td class="px-4 py-3 text-nowrap">{{ $ro->items->count() }} item(s)</td>
                            <td class="text-nowrap px-4 py-3">Rp {{ number_format($ro->grand_total, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $stokKurangItems = [];
                                    $stokCukupCount  = 0;
                                    $totalItemsCount = $ro->items->count();

                                    foreach ($ro->items as $roItem) {
                                        $barang = $roItem->barang;
                                        if (!$barang) continue;

                                        $stokGudang    = (int) $barang->stok;
                                        $qtyDibutuhkan = (int) $roItem->quantity;
                                        $satuan        = $barang->satuan ?? '';

                                        if ($qtyDibutuhkan > $stokGudang) {
                                            $stokKurangItems[] = [
                                                'nama'   => $barang->nama_barang,
                                                'stok'   => $stokGudang,
                                                'qty'    => $qtyDibutuhkan,
                                                'satuan' => $satuan,
                                                'kurang' => $qtyDibutuhkan - $stokGudang,
                                            ];
                                        } else {
                                            $stokCukupCount++;
                                        }
                                    }

                                    $adaStokKurang = count($stokKurangItems) > 0;
                                @endphp

                                @if ($totalItemsCount === 0)
                                    <span class="text-xs text-gray-300 dark:text-gray-600">-</span>
                                @elseif ($adaStokKurang)
                                    <div class="group relative inline-block">
                                        <span class="inline-flex cursor-pointer items-center gap-1 rounded-md border border-red-300 bg-red-50 px-2 py-1 text-xs font-semibold text-red-600 dark:border-red-700 dark:bg-red-900/30 dark:text-red-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                            </svg>
                                            Stok Kurang ({{ count($stokKurangItems) }})
                                        </span>
                                        <div class="pointer-events-none absolute bottom-full left-1/2 z-50 mb-2 w-72 -translate-x-1/2 rounded-lg border border-red-200 bg-white p-3 text-left text-xs opacity-0 shadow-lg transition-opacity duration-200 group-hover:pointer-events-auto group-hover:opacity-100 dark:border-gray-600 dark:bg-gray-800">
                                            <p class="mb-2 font-semibold text-red-600 dark:text-red-400">Item dengan stok kurang:</p>
                                            @foreach ($stokKurangItems as $sk)
                                                <div class="mb-1 border-b border-gray-100 pb-1 last:border-0 dark:border-gray-700">
                                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ Str::limit($sk['nama'], 35) }}</p>
                                                    <p class="text-gray-500 dark:text-gray-400">
                                                        Dibutuhkan: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $sk['qty'] }} {{ $sk['satuan'] }}</span>
                                                    </p>
                                                    <p class="text-gray-500 dark:text-gray-400">
                                                        Tersedia: <span class="font-semibold text-red-600">{{ $sk['stok'] }} {{ $sk['satuan'] }}</span>
                                                        &nbsp;|&nbsp; Kurang: <span class="font-bold text-red-600">{{ $sk['kurang'] }} {{ $sk['satuan'] }}</span>
                                                    </p>
                                                </div>
                                            @endforeach
                                            @if ($stokCukupCount > 0)
                                                <p class="mt-1 text-green-600">{{ $stokCukupCount }} item lainnya stok cukup</p>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-md border border-green-300 bg-green-50 px-2 py-1 text-xs font-semibold text-green-600 dark:border-green-700 dark:bg-green-900/30 dark:text-green-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Stok Cukup
                                    </span>
                                @endif
                            </td>
                            @php
                                // Collect discounts per item, group by percentage and count occurrences
                                $discountCounts = $ro->items
                                    ->pluck('diskon_percent')
                                    ->map(function ($d) {
                                        return $d === null ? 0 : $d;
                                    })
                                    ->groupBy(function ($d) {
                                        return $d;
                                    })
                                    ->map(function ($g, $k) {
                                        return ['percent' => $k, 'count' => $g->count()];
                                    })
                                    ->values();
                            @endphp
                            <td class="px-4 py-3 text-center">
                                @if ($discountCounts->isEmpty())
                                    <span class="text-gray-300 dark:text-gray-600">-</span>
                                @else
                                    <div class="flex flex-col items-center gap-1">
                                        @php $displayed = false; @endphp
                                        @foreach ($discountCounts as $dc)
                                            @php $dk = (float) $dc['percent']; @endphp
                                            @if ($dk > 0)
                                                <div class="flex flex-col items-center">
                                                    <span class="{{ $dk > 20 ? 'badge bg-red-50 text-red-700 inset-ring inset-ring-red-600' : 'badge bg-green-50 text-green-700 inset-ring inset-ring-green-600' }} inline-flex items-center  px-2 py-0.5 text-xs font-bold">
                                                        {{ $dk }}%{{ $dc['count'] > 1 ? ' x' . $dc['count'] : '' }}
                                                    </span>
                                                </div>
                                                @php $displayed = true; @endphp
                                            @endif
                                        @endforeach
                                        @if (!$displayed)
                                            <span class="text-gray-300 dark:text-gray-600">-</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusClass =
                                        [
                                            'Pending' => 'bg-yellow-50 text-yellow-800 inset-ring inset-ring-yellow-600',
                                            'Open' => 'bg-green-50 text-green-700 inset-ring inset-ring-green-600',
                                            'Dikirim ke Supervisor' => 'bg-blue-50 text-blue-700 inset-ring inset-ring-blue-600',
                                            'Disetujui Supervisor' => 'bg-green-50 text-green-700 inset-ring inset-ring-green-600',
                                            'Ditolak Supervisor' => 'bg-red-50 text-red-700 inset-ring inset-ring-red-600',
                                            'Dikirim ke Gudang' => 'bg-blue-50 text-blue-700 inset-ring inset-ring-blue-600',
                                            'Disetujui Gudang' => 'bg-green-50 text-green-700 inset-ring inset-ring-green-600',
                                            'Ditolak Gudang' => 'bg-red-50 text-red-700 inset-ring inset-ring-red-600',
                                            'Selesai' => 'bg-green-50 text-green-700 inset-ring inset-ring-green-600',
                                            'Tidak Selesai' => 'bg-red-50 text-red-700 inset-ring inset-ring-red-600',
                                        ][$ro->status] ?? 'bg-gray-100 text-gray-800 inset-ring inset-ring-gray-600';
                                @endphp
                                <div class="flex items-center justify-center gap-2">
                                    <span class="{{ $statusClass }} badge">
                                        {{ $ro->status }}
                                    </span>
                                </div>
                                @if ($ro->status === 'Disetujui Supervisor')
                                    @php
                                        $maxDiskon = $ro->items->max('diskon_percent');
                                    @endphp
                                    @if ($maxDiskon > 20)
                                        <div class="mt-1 text-xs text-orange-600">
                                            <i class="fa fa-exclamation-triangle"></i>
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td class="text-nowrap px-4 py-3">
                                @if ($ro->tanggal_berlaku)
                                    {{ \Carbon\Carbon::parse($ro->tanggal_berlaku)->translatedFormat('d F Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="w-fit px-4 py-3 text-right">
                                <div class="relative flex min-h-[40px] w-fit items-center justify-end">
                                    <div class="pointer-events-none invisible h-9 w-32 opacity-0">Placeholder</div>
                                    <div class="absolute left-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                        
                                        
                                        {{-- Detail --}}
                                        <a href="{{ route('sales.request-order.show', $ro->id) }}" class="group flex h-full items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye h-4 w-4">
                                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0">
                                                </path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Detail</span>
                                        </a>

                                        {{-- If supervisor rejects --}}
                                        @if ($ro->status == 'Ditolak Supervisor')
                                            {{-- Note modal --}}
                                            <button type="button" class="note-btn group flex h-full cursor-pointer items-center justify-center border-l border-white/20 bg-yellow-600 p-2 text-sm font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-4 focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800" data-catatan="{{ $ro->reason ?? '' }}" title="Lihat Alasan Penolakan">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sticky-note h-4 w-4">
                                                    <path d="M15.5 3H5a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V8.5L15.5 3Z">
                                                    </path>
                                                    <path d="M15 3v6h6"></path>
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Note</span>
                                            </button>
                                        @endif

                                        {{-- Action Dropdown --}}
                                        <button class="group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" popovertarget="popover-{{ $ro->id }}" style="anchor-name:--anchor-{{ $ro->id }}">
                                            <svg width="24px" height="24px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-three-dots-vertical h-4 w-4">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                                                    </path>
                                                </g>
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Aksi</span>
                                        </button>
                                        <ul class="dropdown dropdown-end menu rounded-box bg-base-100 w-52 shadow-sm" popover id="popover-{{ $ro->id }}" style="position-anchor:--anchor-{{ $ro->id }}">
                                            {{-- Edit --}}
                                            <li>
                                                <a href="{{ route('sales.request-order.edit', $ro->id) }}" class="flex items-center gap-2 text-yellow-600 hover:bg-yellow-50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil">
                                                        <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                        </path>
                                                        <path d="m15 5 4 4"></path>
                                                    </svg>
                                                    Edit
                                                </a>
                                            </li>

                                            <li>
                                                {{-- PDF --}}
                                                @if ($ro->canDownloadPdf())
                                                    <a href="{{ route('sales.request-order.pdf', $ro->id) }}" class="flex items-center gap-2 text-green-600 hover:bg-green-50" target="_blank">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text">
                                                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                                            <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                                            <path d="M10 9H8"></path>
                                                            <path d="M16 13H8"></path>
                                                            <path d="M16 17H8"></path>
                                                        </svg>
                                                        PDF
                                                    </a>
                                                @else
                                                    <button type="button" disabled class="flex w-full cursor-not-allowed items-center gap-2 text-gray-400" title="Menunggu Persetujuan Supervisor">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock">
                                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                                        </svg>
                                                        PDF
                                                    </button>
                                                @endif
                                            </li>

                                            {{-- Delete --}}
                                            <form action="{{ route('sales.request-order.destroy', $ro->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <li>
                                                    <button type="button" onclick="confirmDelete(() => this.closest('form').submit())" class="flex w-full items-center gap-2 text-red-600 hover:bg-red-50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2">
                                                            <path d="M10 11v6"></path>
                                                            <path d="M14 11v6"></path>
                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                                            <path d="M3 6h18"></path>
                                                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </li>
                                            </form>

                                            {{-- Sent to Warehouse --}}
                                            @if (!$ro->order)
                                                <form action="{{ route('sales.request-order.sent-to-warehouse', $ro->id) }}" method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <li>
                                                        <button type="submit" class="flex w-full items-center gap-2 text-blue-600 hover:bg-blue-50" title="Kirim ke Warehouse">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck">
                                                                <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2">
                                                                </path>
                                                                <path d="M15 18H9"></path>
                                                                <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14">
                                                                </path>
                                                                <circle cx="17" cy="18" r="2"></circle>
                                                                <circle cx="7" cy="18" r="2"></circle>
                                                            </svg>
                                                            Kirim ke Warehouse
                                                        </button>
                                                    </li>
                                                </form>
                                            @endif
                                        </ul>
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
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $requestOrders->firstItem() ?? 0 }}-{{ $requestOrders->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $requestOrders->total() ?? $requestOrders->count() }}</span>
                </span>
                <form method="GET" action="{{ route('sales.request-order.index') }}">
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
                {{ $requestOrders->links() }}
            </div>
        </nav>
    </div>
    @vite(['resources/js/request-order.js'])
    @include('admin.sales.request-order.partials.modal-show-note')
</x-app-layout>
