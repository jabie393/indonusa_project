<x-app-layout>
    <div class="flex flex-col lg:h-[calc(100vh-112px)] overflow-hidden">
        <!-- Top Search Bar -->
        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex h-16 items-center justify-end overflow-hidden rounded-2xl bg-white px-4 shadow-md dark:bg-gray-800 shrink-0">
            <form id="procurementSearchForm" action="{{ route('general-affair.procurement.index') }}" method="GET" class="block pl-2" data-realtime-table-search
                data-search-input="#topbar-search" data-search-target="#procurementTableContent" data-extra-fields="#procurementTableContent select[name='perPage']">
                <label for="topbar-search" class="sr-only">Search</label>
                <div class="relative md:w-96">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                            </path>
                        </svg>
                    </div>
                    <input type="search" name="search" id="topbar-search" value="{{ request('search') }}"
                        class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                        placeholder="Search procurement" />
                </div>
            </form>
        </div>

        <!-- Main Card Container -->
        <div class="relative flex flex-1 min-h-0 flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
            <div class="flex shrink-0 items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] px-4 py-2">
                <div class="flex space-x-2">
                    <button type="button" id="tab-listing-btn" class="px-4 py-2 text-sm font-extrabold text-white border-b-2 border-white focus:outline-none transition-all flex items-center gap-2">
                        <span>Pengadaan Listing</span>
                        <span class="inline-flex items-center justify-center rounded-full bg-white/20 px-2 py-0.5 text-xs text-white">{{ $listingItems->total() }}</span>
                    </button>
                    <button type="button" id="tab-non-listing-btn" class="px-4 py-2 text-sm font-semibold text-slate-300 hover:text-white focus:outline-none transition-all flex items-center gap-2">
                        <span>Pengadaan Non-Listing</span>
                        <span class="inline-flex items-center justify-center rounded-full bg-white/10 px-2 py-0.5 text-xs text-slate-300">{{ $nonListingItems->total() }}</span>
                    </button>
                    <button type="button" id="tab-combined-btn" class="px-4 py-2 text-sm font-semibold text-slate-300 hover:text-white focus:outline-none transition-all flex items-center gap-2">
                        <span>Ringkasan Kebutuhan Barang</span>
                        <span class="inline-flex items-center justify-center rounded-full bg-white/10 px-2 py-0.5 text-xs text-slate-300">{{ count($combinedRequirements) }}</span>
                    </button>
                </div>
            </div>

            <!-- Tab 1: Pengadaan Listing (Shortage Stok Katalog) -->
            <div id="tab-listing-content" class="flex flex-1 min-h-0 flex-col">
                <div class="grow overflow-x-auto overflow-y-auto">
                    <table class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="sticky top-0 z-30 text-nowrap bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="text-nowrap px-4 py-3">No. Pengadaan (Batch)</th>
                                <th scope="col" class="text-nowrap px-4 py-3">Keterangan &amp; Item</th>
                                <th scope="col" class="text-nowrap px-4 py-3">Alokasi Sales Order</th>
                                <th scope="col" class="text-nowrap px-4 py-3 text-center">Total Item</th>
                                <th scope="col" class="text-nowrap px-4 py-3 text-center">Status</th>
                                <th scope="col" class="flex justify-end text-nowrap px-6 py-3 text-right no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listingItems as $item)
                                @php
                                    $hasRejected = $item->items->pluck('procurementArrivalRequests')->flatten()->contains('status', 'rejected');
                                    $statusLabel = [
                                        'pending' => 'Pending',
                                        'partial_received' => 'Partial Received',
                                        'completed' => 'Completed',
                                    ][$item->status] ?? $item->status;

                                    $badgeClass = 'bg-amber-50 dark:bg-amber-950/30 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800/50';
                                    if ($item->status === 'completed') {
                                        $badgeClass = 'bg-green-50 dark:bg-green-950/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800/50';
                                    } elseif ($item->status === 'partial_received') {
                                        $badgeClass = 'bg-blue-50 dark:bg-blue-950/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800/50';
                                    }

                                    $procItemIds = $item->items->pluck('id');
                                    $linkedSos = \Illuminate\Support\Facades\DB::table('procurement_order_items')
                                        ->whereIn('procurement_of_goods_item_id', $procItemIds)
                                        ->join('order_items', 'procurement_order_items.order_item_id', '=', 'order_items.id')
                                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                        ->where('orders.status', '!=', 'canceled')
                                        ->select('orders.id as order_id', 'orders.order_number', 'orders.status as order_status', 'order_items.shortage_quantity', 'order_items.allocated_quantity')
                                        ->get();

                                    $totalSos = $linkedSos->pluck('order_id')->unique()->count();
                                    $fulfilledSos = $linkedSos->groupBy('order_id')->filter(function($items) {
                                        return $items->every(fn($oi) => ($oi->shortage_quantity == 0) || ($oi->order_status === 'sent_to_warehouse'));
                                    })->count();
                                    $pendingSos = max(0, $totalSos - $fulfilledSos);
                                @endphp
                                <tr class="border-b transition-colors duration-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50">
                                    <!-- No. Pengadaan -->
                                    <td class="px-4 py-3">
                                        <button type="button" class="js-show-procurement text-sm font-bold text-blue-600 hover:underline dark:text-blue-400 text-left focus:outline-none" data-id="{{ $item->id }}">
                                            {{ $item->procurement_number }}
                                        </button>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            @if($item->order)
                                                SO: {{ $item->order->order_number }}
                                            @else
                                                Pengadaan Terpadu (Multi SO)
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-gray-400 dark:text-gray-500">
                                            {{ $item->created_at->format('Y-m-d H:i') }}
                                        </div>
                                    </td>

                                    <!-- Keterangan & Item -->
                                    <td class="px-4 py-3 align-middle">
                                        <div class="text-sm font-medium text-slate-800 dark:text-white">
                                            {{ $item->notes ?: 'Pengadaan Shortage Stok Listing' }}
                                        </div>
                                        <div class="flex flex-col gap-1.5 mt-2">
                                            @foreach($item->items as $procItem)
                                                <div class="inline-flex flex-col gap-0.5 rounded-lg border border-slate-200 bg-slate-50/80 px-2.5 py-1.5 text-xs dark:border-gray-700 dark:bg-gray-800/80 shadow-2xs max-w-fit">
                                                    <div class="flex items-center gap-1.5">
                                                        @if(!empty($procItem->goods?->goods_code))
                                                            <span class="font-mono text-[11px] font-bold text-blue-600 dark:text-blue-400">[{{ $procItem->goods->goods_code }}]</span>
                                                        @endif
                                                        <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $procItem->goods->goods_name ?? 'Barang' }}</span>
                                                    </div>
                                                    <div class="text-[11px] text-slate-600 dark:text-slate-400">
                                                        <span>Kuantitas: </span>
                                                        <strong class="font-bold text-slate-900 dark:text-white">{{ $procItem->qty_ordered }} {{ $procItem->unit }}</strong>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>

                                    <!-- Alokasi Sales Order -->
                                    <td class="px-4 py-3 align-middle">
                                        @if($totalSos > 0)
                                            <div class="flex flex-col gap-1.5">
                                                <div class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs text-slate-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 max-w-fit shadow-2xs">
                                                    <span class="font-bold text-slate-900 dark:text-white">{{ $totalSos }} SO:</span>
                                                    @if($fulfilledSos > 0)
                                                        <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $fulfilledSos }} Terpenuhi</span>
                                                    @endif
                                                    @if($fulfilledSos > 0 && $pendingSos > 0)
                                                        <span>•</span>
                                                    @endif
                                                    @if($pendingSos > 0)
                                                        <span class="font-semibold text-amber-600 dark:text-amber-400">{{ $pendingSos }} Menunggu</span>
                                                    @endif
                                                </div>
                                                <button type="button" 
                                                    class="js-show-allocations inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:underline dark:text-blue-400 focus:outline-none max-w-fit cursor-pointer"
                                                    data-id="{{ $item->id }}">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                                    <span>Rincian Alokasi SO</span>
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>

                                    <!-- Total Item -->
                                    <td class="px-4 py-3 text-center align-middle">
                                        <span class="inline-flex items-center justify-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700 dark:bg-blue-950/30 dark:text-blue-300">
                                            {{ $item->items->count() }} Item
                                        </span>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-4 py-3 text-center align-middle">
                                        <div class="flex flex-wrap items-center justify-center gap-1">
                                            <span class="inline-flex items-center justify-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                            @if($hasRejected)
                                                <span class="inline-flex items-center justify-center rounded-full px-2.5 py-1 text-xs font-semibold bg-red-50 dark:bg-red-950/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800/50" title="Ada kedatangan barang yang ditolak oleh Warehouse">
                                                    Revision Required
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Action -->
                                    <td class="px-4 py-3 text-right align-middle">
                                        <div class="flex justify-end">
                                            <div class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-300 ease-in-out dark:border-gray-600 dark:bg-gray-700">
                                                <button type="button"
                                                    class="js-show-procurement group flex h-full cursor-pointer items-center justify-center bg-yellow-600 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800"
                                                    data-id="{{ $item->id }}" title="Lihat Detail & Catat Kedatangan">
                                                    <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                                        width="14" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100 font-semibold">Detail</span>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada data pengadaan shortage listing saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Listing Pagination Footer -->
                <nav class="sticky bottom-0 z-20 flex shrink-0 flex-col items-start justify-between space-y-3 border-t border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
                    aria-label="Listing procurement table navigation">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            Menampilkan
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $listingItems->firstItem() ?? 0 }}-{{ $listingItems->lastItem() ?? 0 }}</span>
                            dari
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $listingItems->total() }}</span>
                        </span>
                        <form method="GET" action="{{ route('general-affair.procurement.index') }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <select name="perPage" onchange="this.form.submit()"
                                class="mx-2 rounded-xl border border-gray-300 bg-gray-50 p-1 pl-2 pr-8 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @foreach ([10, 25, 50, 100] as $size)
                                    <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                        </form>
                        <span class="text-sm text-gray-500 dark:text-gray-400">per halaman</span>
                    </div>
                    <div>
                        {{ $listingItems->links() }}
                    </div>
                </nav>
            </div>

            <!-- Tab 2: Pengadaan Non-Listing (Custom Quotation) -->
            <div id="tab-non-listing-content" class="hidden flex flex-1 min-h-0 flex-col">
                <div class="grow overflow-x-auto overflow-y-auto">
                    <table class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="sticky top-0 z-30 text-nowrap bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="text-nowrap px-4 py-3">No. Pengadaan / Quotation</th>
                                <th scope="col" class="text-nowrap px-4 py-3">Customer</th>
                                <th scope="col" class="text-nowrap px-4 py-3">Subject</th>
                                <th scope="col" class="text-nowrap px-4 py-3 text-center">Status</th>
                                <th scope="col" class="flex justify-end text-nowrap px-6 py-3 text-right no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($nonListingItems as $item)
                                @php
                                    $isProcurement = $item instanceof \App\Models\ProcurementOfGoods;
                                @endphp
                                <tr class="border-b transition-colors duration-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50">
                                    <!-- No. Pengadaan / Quotation -->
                                    <td class="px-4 py-3">
                                        @if($isProcurement)
                                            <button type="button" class="js-show-procurement text-sm font-semibold text-blue-600 hover:underline dark:text-blue-400 text-left focus:outline-none" data-id="{{ $item->id }}">
                                                {{ $item->procurement_number }}
                                            </button>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                Quotation: {{ $item->customQuotation->quotation_number ?? '-' }}
                                            </div>
                                            <div class="text-[10px] text-gray-400 dark:text-gray-500">
                                                {{ $item->created_at->format('Y-m-d H:i') }}
                                            </div>
                                        @else
                                            <div class="text-sm font-semibold text-slate-800 dark:text-white">
                                                Belum Diproses (Baru)
                                            </div>
                                            <div class="text-xs text-blue-600 dark:text-blue-400 mt-0.5">
                                                Quotation: {{ $item->quotation_number }}
                                            </div>
                                            <div class="text-[10px] text-gray-400 dark:text-gray-500">
                                                {{ $item->created_at->format('Y-m-d H:i') }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Customer & Items -->
                                    <td class="px-4 py-3 align-middle">
                                        @php
                                            if ($isProcurement) {
                                                $to = $item->customQuotation->to ?? '-';
                                                $up = $item->customQuotation->up ?? null;
                                                $email = $item->customQuotation->email ?? null;
                                            } else {
                                                $to = $item->to;
                                                $up = $item->up;
                                                $email = $item->email;
                                            }
                                            $itemCount = $item->items->count();
                                        @endphp
                                        <div class="flex flex-col gap-2">
                                            <div class="flex flex-col gap-0.5">
                                                <span class="text-sm font-bold text-slate-900 dark:text-white">
                                                    {{ $to }}
                                                </span>
                                                @if($up || $email)
                                                    <span class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400 dark:text-slate-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                                            <circle cx="12" cy="7" r="4"></circle>
                                                        </svg>
                                                        <span class="font-medium">{{ $up ?: '-' }}</span>
                                                        @if($email)
                                                            <span class="text-slate-300 dark:text-slate-600">•</span>
                                                            <span class="text-slate-400 dark:text-slate-500">{{ $email }}</span>
                                                        @endif
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="border-t border-dashed border-gray-200 dark:border-gray-700/80"></div>
                                            <div class="flex flex-wrap items-center">
                                                <span class="inline-flex items-center gap-1 rounded bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600 dark:bg-gray-800 dark:text-gray-400">
                                                    {{ $itemCount }} item
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Subject -->
                                    <td class="px-4 py-3 align-middle">
                                        @php
                                            $subjectTitle = $isProcurement ? ($item->customQuotation->subject ?? '-') : $item->subject;
                                        @endphp
                                        <div class="text-sm font-medium text-gray-900 dark:text-white max-w-[240px] truncate" title="{{ $subjectTitle }}">
                                            {{ $subjectTitle }}
                                        </div>
                                    </td>

                                    <!-- Status Badge -->
                                    <td class="px-4 py-3 text-center">
                                        @if($isProcurement)
                                            @php
                                                $hasRejected = $item->items->pluck('procurementArrivalRequests')->flatten()->contains('status', 'rejected');

                                                $statusLabel = [
                                                    'pending' => 'Pending',
                                                    'partial_received' => 'Partial Received',
                                                    'completed' => 'Completed',
                                                ][$item->status] ?? $item->status;

                                                $badgeClass = 'bg-amber-50 dark:bg-amber-950/30 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800/50';
                                                if ($item->status === 'completed') {
                                                    $badgeClass = 'bg-green-50 dark:bg-green-950/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800/50';
                                                } elseif ($item->status === 'partial_received') {
                                                    $badgeClass = 'bg-blue-50 dark:bg-blue-950/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800/50';
                                                }
                                            @endphp
                                            <div class="flex flex-wrap items-center justify-center gap-1">
                                                <span class="inline-flex items-center justify-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">
                                                    {{ $statusLabel }}
                                                </span>
                                                @if($hasRejected)
                                                    <span class="inline-flex items-center justify-center rounded-full px-2.5 py-1 text-xs font-semibold bg-red-50 dark:bg-red-950/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800/50" title="Ada kedatangan barang yang ditolak oleh Warehouse">
                                                        Revision Required
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="inline-flex items-center justify-center rounded-full px-2.5 py-1 text-xs font-semibold bg-red-50 dark:bg-red-950/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800/50">
                                                Waiting for Procurement
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Action Button -->
                                    <td class="px-4 py-3 text-right align-middle">
                                        <div class="flex justify-end">
                                            <div class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-300 ease-in-out dark:border-gray-600 dark:bg-gray-700">
                                                @if($isProcurement)
                                                    <button type="button"
                                                        class="js-show-procurement group flex h-full cursor-pointer items-center justify-center bg-yellow-600 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800"
                                                        data-id="{{ $item->id }}">
                                                        <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                                            width="14" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        <span class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100 font-semibold">Detail</span>
                                                    </button>
                                                @else
                                                    <button type="button"
                                                        class="js-process-procurement group flex h-full cursor-pointer items-center justify-center bg-green-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                                        data-id="{{ $item->id }}"
                                                        data-number="{{ $item->quotation_number }}"
                                                        data-customer="{{ $item->to }}"
                                                        data-subject="{{ $item->subject }}"
                                                        data-date="{{ \Carbon\Carbon::parse($item->date)->format('Y-m-d') }}"
                                                        data-items="{{ json_encode($item->items) }}">
                                                         <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M11 16L15 12M15 12L11 8M15 12H3M4.51555 17C6.13007 19.412 8.87958 21 12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C8.87958 3 6.13007 4.58803 4.51555 7" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                                         <span class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100 font-semibold">Proses</span>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada data pengadaan non-listing saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Non-Listing Pagination Footer -->
                <nav class="sticky bottom-0 z-20 flex shrink-0 flex-col items-start justify-between space-y-3 border-t border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
                    aria-label="Non-Listing procurement table navigation">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            Menampilkan
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $nonListingItems->firstItem() ?? 0 }}-{{ $nonListingItems->lastItem() ?? 0 }}</span>
                            dari
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $nonListingItems->total() }}</span>
                        </span>
                        <form method="GET" action="{{ route('general-affair.procurement.index') }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <select name="perPage" onchange="this.form.submit()"
                                class="mx-2 rounded-xl border border-gray-300 bg-gray-50 p-1 pl-2 pr-8 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @foreach ([10, 25, 50, 100] as $size)
                                    <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                        </form>
                        <span class="text-sm text-gray-500 dark:text-gray-400">per halaman</span>
                    </div>
                    <div>
                        {{ $nonListingItems->links() }}
                    </div>
                </nav>
            </div>

            <div id="tab-combined-content" class="hidden flex flex-1 min-h-0 flex-col bg-white dark:bg-gray-800">
                <div class="grow overflow-x-auto overflow-y-auto">
                    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="sticky top-0 z-30 text-nowrap bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nama & Kode Barang</th>
                                <th scope="col" class="px-6 py-3 text-center">Total Kebutuhan</th>
                                <th scope="col" class="px-6 py-3 text-center">Total Diterima</th>
                                <th scope="col" class="px-6 py-3 text-center">Sisa Kebutuhan</th>
                                <th scope="col" class="px-6 py-3">Breakdown per SO / Quotation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($combinedRequirements as $goodsId => $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 border-b dark:border-gray-700">
                                    <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">
                                        <div class="font-bold text-slate-800 dark:text-white">
                                            {{ $item['goods_name'] }}
                                        </div>
                                        <span class="block text-xs font-mono text-gray-400 mt-0.5">{{ $item['goods_code'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-semibold text-gray-900 dark:text-white">
                                        {{ $item['total_ordered'] }} pcs
                                    </td>
                                    <td class="px-6 py-4 text-center font-semibold text-green-600 dark:text-green-400">
                                        {{ $item['total_received'] }} pcs
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-amber-600 dark:text-amber-400">
                                        {{ $item['total_remaining'] }} pcs
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1.5">
                                            @foreach($item['breakdown'] as $bd)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs rounded bg-slate-100 dark:bg-gray-700 px-2 py-0.5 text-slate-600 dark:text-gray-300">
                                                        {{ $bd['qty'] }} pcs
                                                    </span>
                                                    <a href="{{ $bd['url'] }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:underline dark:text-blue-400">
                                                        {{ $bd['source'] }}
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada kebutuhan procurement barang saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('admin.procurement.partials.procurement-process-modal')
    @include('admin.procurement.partials.procurement-revision-modal')
    @include('admin.procurement.partials.procurement-allocations-modal')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal elements
            const modal = document.getElementById('procurement-modal');
            const createView = document.getElementById('procurement-create-view');
            const optionsView = document.getElementById('procurement-options-view');
            const detailView = document.getElementById('procurement-detail-view');

            let shouldReloadOnClose = false;

            function closeModal() {
                if (!modal) return;
                try {
                    if (typeof modal.close === 'function') modal.close();
                    else modal.style.display = 'none';
                } catch (e) {
                    modal.style.display = 'none';
                }
            }

            // Close on click close button
            document.addEventListener('click', function(e) {
                if (e.target && e.target.closest('.js-procurement-modal-close')) {
                    closeModal();
                }
            });

            // Close on backdrop click
            modal?.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Close listener to reload page if a partial procurement was created
            modal?.addEventListener('close', function() {
                if (shouldReloadOnClose) {
                    window.location.reload();
                }
            });

            // Helper functions for price formatting
            function formatNumberWithCommas(value) {
                if (!value) return '';
                // Discard decimal places and keep digits only
                let cleanValue = value.toString().split('.')[0].replace(/[^0-9]/g, '');
                return cleanValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            function formatInputPrice(input) {
                let selectionStart = input.selectionStart;
                let oldLength = input.value.length;
                let value = input.value;
                
                // Discard decimal places and keep digits only
                let cleanValue = value.split('.')[0].replace(/[^0-9]/g, '');

                let formattedValue = cleanValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                input.value = formattedValue;

                let newLength = formattedValue.length;
                let newStart = selectionStart + (newLength - oldLength);
                input.setSelectionRange(newStart, newStart);
            }

            // Initialize formatting on dynamically loaded container
            function initializeBuyPriceInputs(container) {
                container.querySelectorAll('.buy-price-input').forEach(input => {
                    if (input.value) {
                        input.value = formatNumberWithCommas(input.value);
                    }
                    input.addEventListener('input', function() {
                        formatInputPrice(this);
                    });
                });
            }

            // --- 1. PROCESS PROCUREMENT FLOW (Creation) ---
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.js-process-procurement');
                if (!btn) return;

                e.preventDefault();

                const id = btn.getAttribute('data-id');
                const number = btn.getAttribute('data-number');
                const customer = btn.getAttribute('data-customer');
                const subject = btn.getAttribute('data-subject');
                const date = btn.getAttribute('data-date');
                const items = JSON.parse(btn.getAttribute('data-items') || '[]');

                // Reset views
                createView.classList.remove('hidden');
                createView.classList.add('flex');
                optionsView.classList.add('hidden');
                optionsView.classList.remove('flex');
                detailView.classList.add('hidden');
                detailView.classList.remove('flex');

                // Reset error alerts
                const errorAlert = document.getElementById('modal-validation-errors');
                if (errorAlert) {
                    errorAlert.classList.add('hidden');
                    errorAlert.querySelector('ul').innerHTML = '';
                }

                // Set inputs
                document.getElementById('process-quotation-id').value = id;
                document.getElementById('process-quotation-number').textContent = number;
                document.getElementById('process-customer-to').textContent = customer;
                document.getElementById('process-subject').textContent = subject;
                document.getElementById('process-delivery-date').textContent = date;
                document.getElementById('process-notes').value = '';

                // Populate items
                const tbody = document.getElementById('process-items-body');
                tbody.innerHTML = '';

                items.forEach((item, index) => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-50/50 dark:hover:bg-gray-700/30';
                    tr.innerHTML = `
                        <input type="hidden" name="items[${index}][goods_id]" value="${item.goods_id}">
                        <input type="hidden" name="items[${index}][qty_requested]" value="${item.qty}">
                        
                        <td class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 font-medium text-gray-900 dark:text-white">
                            ${item.product_name}
                            ${item.description ? `<span class="block text-xs text-gray-400 dark:text-gray-500 font-normal mt-1">${item.description}</span>` : ''}
                        </td>
                        <td class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 text-slate-500 dark:text-gray-400">
                            ${item.category || 'OTHER CATEGORIES'}
                        </td>
                        <td class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 text-center text-slate-500 dark:text-gray-400">
                            ${item.unit}
                        </td>
                        <td class="border-b border-gray-200 dark:border-gray-700 px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">
                            ${item.qty}
                        </td>
                        <td class="border-b border-gray-200 dark:border-gray-700 px-4 py-2" style="width: 150px;">
                            <input type="number" name="items[${index}][qty_ordered]" 
                                value="${item.qty}"
                                min="${item.qty}" required
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-center text-sm font-semibold focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                title="GA bisa memesan stok lebih dari permintaan quotation">
                        </td>
                        <td class="border-b border-gray-200 dark:border-gray-700 px-4 py-2" style="width: 220px;">
                            <div class="relative flex items-center">
                                <span class="absolute left-3 text-sm text-gray-500 dark:text-gray-400 font-semibold">Rp</span>
                                <input type="text" name="items[${index}][buy_price]" 
                                    value=""
                                    required placeholder="0"
                                    class="buy-price-input w-full rounded-lg border border-gray-300 bg-gray-50 p-2 pl-9 text-right text-sm font-semibold focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

                // Initialize formatting on inputs
                initializeBuyPriceInputs(tbody);

                // Open modal
                modal?.showModal();
            });

            // Handle "Simpan & Buat Pengadaan" Form Submit (Transitions to Options Selection)
            const createForm = document.getElementById('modalProcurementCreateForm');
            createForm?.addEventListener('submit', function(e) {
                e.preventDefault();

                // Standard HTML5 validation
                if (!createForm.checkValidity()) {
                    createForm.reportValidity();
                    return;
                }

                // Transition to options view
                createView.classList.add('hidden');
                createView.classList.remove('flex');
                optionsView.classList.remove('hidden');
                optionsView.classList.add('flex');
            });

            // Options Back button
            document.getElementById('btn-back-to-form')?.addEventListener('click', function() {
                optionsView.classList.add('hidden');
                optionsView.classList.remove('flex');
                createView.classList.remove('hidden');
                createView.classList.add('flex');
            });

            // Options submits: Full or Partial
            function submitProcurementModal(type) {
                const formData = new FormData(createForm);
                formData.append('type', type);

                // Clean prices
                const params = new URLSearchParams();
                for (const [key, value] of formData.entries()) {
                    if (key.includes('[buy_price]')) {
                        params.append(key, value.replace(/,/g, ''));
                    } else {
                        params.append(key, value);
                    }
                }

                // Disable buttons & show loading state
                const btnFull = document.getElementById('btn-full-procurement');
                const btnPartial = document.getElementById('btn-partial-procurement');
                if (btnFull) btnFull.disabled = true;
                if (btnPartial) btnPartial.disabled = true;

                fetch('{{ route("general-affair.procurement.store-modal") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: params.toString()
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(({ status, body }) => {
                    if (btnFull) btnFull.disabled = false;
                    if (btnPartial) btnPartial.disabled = false;

                    if (status === 200 && body.success) {
                        if (body.type === 'full') {
                            // Full success -> reload page with Swal notification
                            sessionStorage.setItem('sweetTitle', 'Berhasil');
                            sessionStorage.setItem('sweetText', body.message);
                            window.location.reload();
                        } else {
                            // Partial success -> reload page with open_show parameter to re-open modal with updated background status
                            sessionStorage.setItem('sweetTitle', 'Berhasil');
                            sessionStorage.setItem('sweetText', body.message);
                            
                            const url = new URL(window.location.href);
                            url.searchParams.delete('page');
                            url.searchParams.set('open_show', body.procurement_id);
                            window.location.href = url.toString();
                        }
                    } else {
                        // Validation error or backend error -> transition back to form and show error
                        optionsView.classList.add('hidden');
                        optionsView.classList.remove('flex');
                        createView.classList.remove('hidden');
                        createView.classList.add('flex');

                        const errorsContainer = document.getElementById('modal-validation-errors');
                        const errorsList = errorsContainer.querySelector('ul');
                        errorsList.innerHTML = '';

                        if (body.errors) {
                            Object.values(body.errors).forEach(errArr => {
                                errArr.forEach(err => {
                                    const li = document.createElement('li');
                                    li.textContent = err;
                                    errorsList.appendChild(li);
                                });
                            });
                        } else {
                            const li = document.createElement('li');
                            li.textContent = body.message || 'Terjadi kesalahan.';
                            errorsList.appendChild(li);
                        }
                        errorsContainer.classList.remove('hidden');
                        document.querySelector('#procurement-create-view .overflow-y-auto').scrollTop = 0;
                    }
                })
                .catch(err => {
                    if (btnFull) btnFull.disabled = false;
                    if (btnPartial) btnPartial.disabled = false;
                    console.error(err);

                    optionsView.classList.add('hidden');
                    optionsView.classList.remove('flex');
                    createView.classList.remove('hidden');
                    createView.classList.add('flex');

                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat memproses data.',
                        icon: 'error',
                        customClass: { popup: 'rounded-2xl!' },
                        target: document.getElementById('procurement-modal')
                    });
                });
            }

            document.getElementById('btn-full-procurement')?.addEventListener('click', () => submitProcurementModal('full'));
            document.getElementById('btn-partial-procurement')?.addEventListener('click', () => submitProcurementModal('partial'));


            // --- 2. SHOW PROCUREMENT FLOW (Details) ---
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.js-show-procurement');
                if (!btn) return;

                e.preventDefault();

                const id = btn.getAttribute('data-id');

                // Reset views
                createView.classList.add('hidden');
                createView.classList.remove('flex');
                optionsView.classList.add('hidden');
                optionsView.classList.remove('flex');

                detailView.innerHTML = `
                    <div class="flex-1 flex items-center justify-center p-12 w-full h-full min-h-[350px]">
                        <div class="text-center">
                            <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-gray-500 mt-2 text-sm">Memuat detail procurement...</p>
                        </div>
                    </div>
                `;
                detailView.classList.remove('hidden');
                detailView.classList.add('flex');

                modal?.showModal();

                fetch(`/procurement/${id}/detail-html`)
                    .then(res => {
                        if (!res.ok) throw new Error('Failed to fetch details');
                        return res.text();
                    })
                    .then(html => {
                        detailView.innerHTML = html;
                        initializeBuyPriceInputs(detailView);
                    })
                    .catch(err => {
                        console.error(err);
                        closeModal();
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Gagal memuat detail procurement.',
                            icon: 'error',
                            customClass: { popup: 'rounded-2xl!' },
                            target: document.querySelector('dialog[open]') || 'body'
                        });
                    });
            });

            // --- 3. SHOW ALLOCATIONS MODAL (FIFO BREAKDOWN & SEARCH) ---
            const allocationsModal = document.getElementById('procurement-allocations-modal');
            const allocationsView = document.getElementById('procurement-allocations-view');

            function closeAllocationsModal() {
                if (!allocationsModal) return;
                try {
                    if (typeof allocationsModal.close === 'function') allocationsModal.close();
                    else allocationsModal.style.display = 'none';
                } catch (e) {
                    allocationsModal.style.display = 'none';
                }
            }

            document.addEventListener('click', function(e) {
                if (e.target && e.target.closest('.js-allocations-modal-close')) {
                    closeAllocationsModal();
                }
            });

            allocationsModal?.addEventListener('click', function(e) {
                if (e.target === allocationsModal) {
                    closeAllocationsModal();
                }
            });

            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.js-show-allocations');
                if (!btn) return;

                e.preventDefault();
                const id = btn.getAttribute('data-id');

                allocationsView.innerHTML = `
                    <div class="flex-1 flex items-center justify-center p-12 w-full min-h-[350px]">
                        <div class="text-center">
                            <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-gray-500 mt-2 text-sm">Memuat rincian alokasi SO...</p>
                        </div>
                    </div>
                `;

                allocationsModal?.showModal();

                fetch(`/procurement/${id}/allocations-html`)
                    .then(res => {
                        if (!res.ok) throw new Error('Failed to fetch allocations');
                        return res.text();
                    })
                    .then(html => {
                        allocationsView.innerHTML = html;
                        // Execute script inside dynamically injected modal
                        const scripts = allocationsView.querySelectorAll('script');
                        scripts.forEach(oldScript => {
                            const newScript = document.createElement('script');
                            Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                            newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                            oldScript.parentNode.replaceChild(newScript, oldScript);
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        closeAllocationsModal();
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Gagal memuat rincian alokasi Sales Order.',
                            icon: 'error',
                            customClass: { popup: 'rounded-2xl!' },
                            target: document.querySelector('dialog[open]') || 'body'
                        });
                    });
            });

            // Intercept record arrival form submit to clean commas
            document.addEventListener('submit', function(e) {
                if (e.target && e.target.id === 'recordArrivalForm') {
                    e.target.querySelectorAll('.buy-price-input').forEach(input => {
                        input.value = input.value.replace(/,/g, '');
                    });
                }
            });

            // Check if there is a sessionStorage success notification from reload
            setTimeout(() => {
                const sweetTitle = sessionStorage.getItem('sweetTitle');
                const sweetText = sessionStorage.getItem('sweetText');
                if (sweetTitle || sweetText) {
                    sessionStorage.removeItem('sweetTitle');
                    sessionStorage.removeItem('sweetText');

                    Swal.fire({
                        title: sweetTitle || 'Berhasil',
                        text: sweetText || '',
                        icon: 'success',
                        timer: 3500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-2xl!' },
                        target: document.querySelector('dialog[open]') || 'body'
                    });
                }
            }, 100);

            // Auto-open modal if parameters are passed in URL (open_create or open_show)
            const urlParams = new URLSearchParams(window.location.search);
            let shouldCleanUrl = false;

            const openCreateId = urlParams.get('open_create');
            if (openCreateId) {
                const btn = document.querySelector(`.js-process-procurement[data-id="${openCreateId}"]`);
                if (btn) {
                    btn.click();
                    shouldCleanUrl = true;
                }
            }

            const openShowId = urlParams.get('open_show');
            if (openShowId) {
                const btn = document.querySelector(`.js-show-procurement[data-id="${openShowId}"]`);
                if (btn) {
                    btn.click();
                    shouldCleanUrl = true;
                }
            }

            if (shouldCleanUrl) {
                const url = new URL(window.location.href);
                url.searchParams.delete('open_show');
                url.searchParams.delete('open_create');
                window.history.replaceState({}, '', url.toString());
            }
        });

        // Standalone revision modal functions
        function openRevisionModal(receiptId, qty, unitCost, goodsName, maxAllowed) {
            const modal = document.getElementById('modalRevisiReceipt');
            if (!modal) return;

            document.getElementById('formRevisiReceipt').action = '/procurement/receipt/' + receiptId + '/update';
            document.getElementById('revisionGoodsName').textContent = goodsName;
            
            const qtyInput = document.getElementById('revisionQuantity');
            qtyInput.value = qty;
            qtyInput.max = maxAllowed;
            document.getElementById('revisionQuantityMaxLabel').textContent = maxAllowed;
            
            const costInput = document.getElementById('revisionUnitCost');
            costInput.value = unitCost;
            
            // Format input using standard event
            const event = new Event('input', { bubbles: true });
            costInput.dispatchEvent(event);

            if (typeof modal.showModal === "function") {
                modal.showModal();
            } else {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeRevisionModal() {
            const modal = document.getElementById('modalRevisiReceipt');
            if (!modal) return;

            if (typeof modal.close === "function") {
                modal.close();
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Add pricing format formatting inside revision form
        document.addEventListener('DOMContentLoaded', function() {
            const revisionForm = document.getElementById('formRevisiReceipt');
            if (revisionForm) {
                // Initialize input formatting logic for the revision modal price input
                initializeBuyPriceInputs(revisionForm);
                revisionForm.addEventListener('submit', function(e) {
                    const costInput = document.getElementById('revisionUnitCost');
                    costInput.value = costInput.value.replace(/,/g, '');
                });
            }
        });

        // Tab switching logic for 3 tabs
        document.addEventListener('DOMContentLoaded', function() {
            const tabListingBtn = document.getElementById('tab-listing-btn');
            const tabNonListingBtn = document.getElementById('tab-non-listing-btn');
            const tabCombBtn = document.getElementById('tab-combined-btn');

            const contentListing = document.getElementById('tab-listing-content');
            const contentNonListing = document.getElementById('tab-non-listing-content');
            const contentComb = document.getElementById('tab-combined-content');

            function switchTab(activeBtn, activeContent) {
                [tabListingBtn, tabNonListingBtn, tabCombBtn].forEach(btn => {
                    if (btn) {
                        btn.classList.remove('text-white', 'border-b-2', 'border-white', 'font-extrabold');
                        btn.classList.add('text-slate-300', 'font-semibold');
                        const badge = btn.querySelector('span:last-child');
                        if (badge) {
                            badge.classList.remove('bg-white/20', 'text-white');
                            badge.classList.add('bg-white/10', 'text-slate-300');
                        }
                    }
                });

                [contentListing, contentNonListing, contentComb].forEach(content => {
                    if (content) {
                        content.classList.add('hidden');
                    }
                });

                if (activeBtn) {
                    activeBtn.classList.add('text-white', 'border-b-2', 'border-white', 'font-extrabold');
                    activeBtn.classList.remove('text-slate-300', 'font-semibold');
                    const badge = activeBtn.querySelector('span:last-child');
                    if (badge) {
                        badge.classList.add('bg-white/20', 'text-white');
                        badge.classList.remove('bg-white/10', 'text-slate-300');
                    }
                }

                if (activeContent) {
                    activeContent.classList.remove('hidden');
                }
            }

            if (tabListingBtn) {
                tabListingBtn.addEventListener('click', () => switchTab(tabListingBtn, contentListing));
            }
            if (tabNonListingBtn) {
                tabNonListingBtn.addEventListener('click', () => switchTab(tabNonListingBtn, contentNonListing));
            }
            if (tabCombBtn) {
                tabCombBtn.addEventListener('click', () => switchTab(tabCombBtn, contentComb));
            }

            // Auto-switch tab based on page param if needed
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('non_listing_page')) {
                switchTab(tabNonListingBtn, contentNonListing);
            }
        });
    </script>

    @vite(['resources/js/realtime-table-search.js', 'resources/js/table-sort.js'])
</x-app-layout>
