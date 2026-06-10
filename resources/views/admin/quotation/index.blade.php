<x-app-layout>
    <div class="flex flex-col lg:h-[calc(100vh-112px)] overflow-hidden">

        <div
            class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex h-16 items-center justify-between overflow-hidden rounded-2xl bg-white px-4 shadow-md dark:bg-gray-800 shrink-0">
            <div
                class="flex w-full shrink-0 flex-col items-stretch justify-end space-y-2 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0">
                {{-- Tambah barang modal --}}
                <a href="{{ route('sales.quotation.create') }}"
                    class="flex flex-row items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 text-sm font-semibold text-white hover:bg-[#19426d]">
                    <svg class="mr-2 h-3.5 w-3.5" fill="currentColor" viewbox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    Quotation
                </a>

                <div class="flex gap-2">
                    <a href="{{ route('dashboard.sales.export.quotations') }}"
                        class="flex flex-row items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 text-sm font-semibold text-white hover:bg-[#19426d]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Quotation Report
                    </a>
                </div>
            </div>

            <div>
                {{-- Search --}}
                <form action="{{ route('sales.quotation.index') }}" method="GET" class="block pl-2" data-realtime-table-search data-search-input="#topbar-search"
                    data-search-target="#tableContainer" data-pagination-target="#pagination-nav" data-extra-fields="#pagination-nav select[name='perPage']">
                    <label for="topbar-search" class="sr-only">Search</label>
                    <div class="relative md:w-96">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                                </path>
                            </svg>
                        </div>
                        <input type="search" name="search" id="topbar-search" aria-controls="warehouseTable"
                            value="{{ request('search') }}"
                            class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            placeholder="Search" />
                    </div>
                </form>
            </div>
        </div>

        <div
            class="relative flex flex-1 min-h-0 flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
            <div class="shrink-0 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
            </div>
            <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
                <table id="" class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead
                        class="sticky top-0 z-10 bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="text-nowrap p-4">No. Dokumen</th>
                            <th scope="col" class="text-nowrap p-4">Customer, Item & Total</th>
                            <th scope="col" class="text-nowrap text-center p-4">Status & Stok</th>
                            <th scope="col" class="text-nowrap p-4">Tanggal</th>
                            <th scope="col" class="flex justify-end text-nowrap px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-nowrap">
                        @forelse($requestOrders as $ro)
                            @php
                                $discounts = $ro->items->pluck('diskon_percent')->map(fn($d) => (float) ($d ?? 0));
                                $hasBelow = $discounts->contains(fn($d) => $d > 0 && $d <= 20);
                                $hasAbove = $discounts->contains(fn($d) => $d > 20);
                            @endphp
                            <tr class="border-b border-gray-100 align-top dark:border-gray-700">
                                <td class="p-4">
                                    <div class="flex flex-col gap-1">
                                        <a href="{{ route('sales.quotation.show', $ro->id) }}"
                                            class="text-base font-bold text-[#0067B1] hover:underline">
                                            {{ $ro->quotation_number ?? '-' }}
                                        </a>
                                        <div class="grid grid-cols-[44px_1fr] gap-x-2 text-xs leading-relaxed">
                                            <span class="font-semibold uppercase text-slate-400">SO</span>
                                            <span
                                                class="text-slate-600 dark:text-slate-300">{{ $ro->sales_order_number ?? '-' }}</span>
                                            <span class="font-semibold uppercase text-slate-400">REQ</span>
                                            <span
                                                class="text-slate-600 dark:text-slate-300">{{ $ro->request_number ?? '-' }}</span>
                                            <span class="font-semibold uppercase text-slate-400">PO</span>
                                            <span class="text-slate-600 dark:text-slate-300">{{ $ro->no_po ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 align-middle">
                                    <div class="flex flex-col gap-2.5">
                                        <!-- Customer Details -->
                                        <div class="flex flex-col gap-1">
                                            <span class="text-base font-bold text-slate-900 dark:text-white">
                                                {{ $ro->customer_name }}
                                            </span>
                                            <span
                                                class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-3.5 w-3.5 text-slate-400 dark:text-slate-500"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                                <span class="font-medium">{{ $ro->pic->name ?? '-' }}</span>
                                                <span class="text-slate-300 dark:text-slate-600">•</span>
                                                <span
                                                    class="text-slate-400 dark:text-slate-500">{{ $ro->pic->position ?? 'PIC' }}</span>
                                            </span>
                                        </div>

                                        <!-- Divider -->
                                        <div class="border-t border-dashed border-gray-200 dark:border-gray-700/80"></div>

                                        <!-- Total & Items Summary -->
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                            <span class="text-base font-bold text-[#0067B1] dark:text-[#2798e6]">
                                                Rp {{ number_format($ro->grand_total, 0, '.', ',') }}
                                            </span>
                                            <span
                                                class="inline-flex items-center gap-1 rounded bg-slate-100 px-1.5 py-0.5 text-xs font-medium text-slate-600 dark:bg-gray-800 dark:text-gray-400">
                                                {{ $ro->items->count() }} item
                                            </span>
                                            @if ($hasBelow && $hasAbove)
                                                <span
                                                    class="inline-flex items-center justify-center rounded border border-amber-200 bg-amber-50 px-1.5 py-0.5 text-xs font-semibold text-amber-800 dark:border-amber-800/50 dark:bg-amber-950/30 dark:text-amber-300">
                                                    &lt;20% &amp; &gt;20%
                                                </span>
                                            @elseif ($hasAbove)
                                                <span
                                                    class="inline-flex items-center justify-center rounded border border-red-200 bg-red-50 px-1.5 py-0.5 text-xs font-semibold text-red-700 dark:border-red-800/50 dark:bg-red-950/30 dark:text-red-300">
                                                    &gt;20%
                                                </span>
                                            @elseif ($hasBelow)
                                                <span
                                                    class="inline-flex items-center justify-center rounded border border-green-200 bg-green-50 px-1.5 py-0.5 text-xs font-semibold text-green-700 dark:border-green-800/50 dark:bg-green-950/30 dark:text-green-300">
                                                    &lt;20%
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 align-middle">
                                    @php
                                        $stokKurangItems = [];
                                        $stokCukupCount = 0;
                                        $totalItemsCount = $ro->items->count();

                                        foreach ($ro->items as $roItem) {
                                            $barang = $roItem->barang;
                                            if (!$barang) {
                                                if (!empty($roItem->custom_product_name)) {
                                                    $stokGudang = 0;
                                                    $qtyDibutuhkan = (int) $roItem->quantity;
                                                    $satuan = $roItem->custom_product_unit ?? 'pcs';
                                                    $goodsName = $roItem->custom_product_name;
                                                } else {
                                                    continue;
                                                }
                                            } else {
                                                $stokGudang = (int) $barang->stock;
                                                $qtyDibutuhkan = (int) $roItem->quantity;
                                                $satuan = $barang->unit ?? '';
                                                $goodsName = $barang->goods_name;
                                            }

                                            if ($qtyDibutuhkan > $stokGudang) {
                                                $stokKurangItems[] = [
                                                    'nama' => $goodsName,
                                                    'stok' => $stokGudang,
                                                    'qty' => $qtyDibutuhkan,
                                                    'satuan' => $satuan,
                                                    'kurang' => $qtyDibutuhkan - $stokGudang,
                                                ];
                                            } else {
                                                $stokCukupCount++;
                                            }
                                        }

                                        $adaStokKurang = count($stokKurangItems) > 0;

                                        $orderStatus = $ro->order?->status ?? null;

                                        $statusLabelMap = [
                                            'open' => 'Open',
                                            'sent_to_supervisor' => 'Sent to Supervisor',
                                            'approved_supervisor' => 'Approved by Supervisor',
                                            'rejected_supervisor' => 'Rejected by Supervisor',
                                            'sent_to_warehouse' => 'Sent to Warehouse',
                                            'approved_warehouse' => 'Approved by Warehouse',
                                            'rejected_warehouse' => 'Rejected by Warehouse',
                                            'not_completed' => 'Partial Delivery',
                                            'completed' => 'Completed',
                                            'pending' => 'Pending',
                                        ];

                                        $displayStatus = $orderStatus ? $statusLabelMap[$orderStatus] ?? $orderStatus : $ro->status;

                                        if ($orderStatus === 'sent_to_warehouse' && $ro->custom_quotation_id) {
                                            $hasActiveProcurement = \App\Models\ProcurementOfGoods::where('custom_quotation_id', $ro->custom_quotation_id)
                                                ->where('status', '!=', 'completed')
                                                ->exists();
                                            $noProcurement = !\App\Models\ProcurementOfGoods::where('custom_quotation_id', $ro->custom_quotation_id)->exists();

                                            if ($hasActiveProcurement || $noProcurement) {
                                                $displayStatus = 'Under Procurement';
                                            }
                                        }

                                        $badgeBg = 'bg-gray-50 dark:bg-gray-900/30';
                                        $badgeText = 'text-gray-700 dark:text-gray-300';
                                        $badgeBorder = 'border border-gray-200 dark:border-gray-700/50';
                                        $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/></svg>';

                                        if (in_array($displayStatus, ['Completed', 'Approved by Supervisor', 'Approved by Warehouse', 'Open'])) {
                                            $badgeBg = 'bg-green-50 dark:bg-green-950/30';
                                            $badgeText = 'text-green-700 dark:text-green-300';
                                            $badgeBorder = 'border border-green-200 dark:border-green-800/50';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>';
                                        } elseif (in_array($displayStatus, ['Partial Delivery', 'Under Procurement'])) {
                                            $badgeBg = 'bg-amber-50 dark:bg-amber-950/30';
                                            $badgeText = 'text-amber-800 dark:text-amber-300';
                                            $badgeBorder = 'border border-amber-200 dark:border-amber-800/50';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
                                        } elseif (in_array($displayStatus, ['Pending', 'Sent to Supervisor', 'Sent to Warehouse'])) {
                                            $badgeBg = 'bg-blue-50 dark:bg-blue-950/30';
                                            $badgeText = 'text-blue-700 dark:text-blue-300';
                                            $badgeBorder = 'border border-blue-200 dark:border-blue-800/50';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
                                        } elseif (in_array($displayStatus, ['Rejected by Supervisor', 'Rejected by Warehouse'])) {
                                            $badgeBg = 'bg-red-50 dark:bg-red-950/30';
                                            $badgeText = 'text-red-700 dark:text-red-300';
                                            $badgeBorder = 'border border-red-200 dark:border-red-800/50';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>';
                                        }
                                    @endphp

                                    <div class="flex flex-col items-center justify-center gap-1.5 w-44 mx-auto">
                                        <!-- Status Badge -->
                                        <span
                                            class="inline-flex w-full items-center justify-center rounded-full px-2.5 py-1 text-center text-xs font-semibold {{ $badgeBg }} {{ $badgeText }} {{ $badgeBorder }}">
                                            {!! $iconSvg !!}{{ $displayStatus }}
                                        </span>

                                        <!-- Stock Badge -->
                                        @if ($totalItemsCount === 0)
                                            <span class="text-xs text-gray-300 dark:text-gray-600">-</span>
                                        @elseif ($adaStokKurang)
                                            <div class="group relative flex w-full justify-center hover:z-30">
                                                <span
                                                    class="inline-flex w-full cursor-pointer items-center justify-center rounded-full border border-red-200 bg-red-50 px-2 py-1 text-center text-xs font-semibold text-red-700 dark:border-red-800/50 dark:bg-red-950/30 dark:text-red-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-x-circle mr-1.5 shrink-0">
                                                        <circle cx="12" cy="12" r="10" />
                                                        <line x1="15" x2="9" y1="9" y2="15" />
                                                        <line x1="9" x2="15" y1="9" y2="15" />
                                                    </svg>
                                                    Insufficient Stock ({{ count($stokKurangItems) }})
                                                </span>
                                                <div
                                                    class="pointer-events-none absolute top-0 right-full z-50 mr-3 w-72 rounded-lg border border-red-200 bg-white p-3 text-left text-xs opacity-0 shadow-lg transition-opacity duration-200 group-hover:pointer-events-auto group-hover:opacity-100 dark:border-gray-600 dark:bg-gray-800">
                                                    <p class="mb-2 font-semibold text-red-600 dark:text-red-400">Items with insufficient stock:</p>
                                                    @foreach ($stokKurangItems as $sk)
                                                        <div
                                                            class="mb-1 border-b border-gray-100 pb-1 last:border-0 dark:border-gray-700">
                                                            <p class="font-medium text-gray-800 dark:text-gray-200">
                                                                {{ Str::limit($sk['nama'], 35) }}
                                                            </p>
                                                            <p class="text-gray-500 dark:text-gray-400">
                                                                Required: <span
                                                                    class="font-semibold text-gray-700 dark:text-gray-300">{{ $sk['qty'] }}
                                                                    {{ $sk['satuan'] }}</span>
                                                            </p>
                                                            <p class="text-gray-500 dark:text-gray-400">
                                                                Available: <span class="font-semibold text-red-600">{{ $sk['stok'] }}
                                                                    {{ $sk['satuan'] }}</span>
                                                                &nbsp;|&nbsp; Shortage: <span
                                                                    class="font-bold text-red-600">{{ $sk['kurang'] }}
                                                                    {{ $sk['satuan'] }}</span>
                                                            </p>
                                                        </div>
                                                    @endforeach
                                                    @if ($stokCukupCount > 0)
                                                        <p class="mt-1 text-green-600">{{ $stokCukupCount }} other item(s) have sufficient stock</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span
                                                class="inline-flex w-full items-center justify-center rounded-full border border-green-200 bg-green-50 px-2 py-1 text-center text-xs font-semibold text-green-700 dark:border-green-800/50 dark:bg-green-950/30 dark:text-green-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-check-circle mr-1.5 shrink-0">
                                                    <circle cx="12" cy="12" r="10" />
                                                    <path d="m9 12 2 2 4-4" />
                                                </svg>
                                                Sufficient Stock
                                            </span>
                                        @endif

                                        <!-- Partial Delivery Info if any -->
                                        @if ($orderStatus === 'not_completed' && $ro->order)
                                            @php
                                                $orderItems = $ro->order->items ?? collect();
                                                $sudahDikirim = $orderItems->filter(fn($i) => ($i->delivered_quantity ?? 0) > 0);
                                                $belumDikirim = $orderItems->filter(fn($i) => ($i->delivered_quantity ?? 0) < $i->quantity && ($i->status_item ?? '') !== 'delivered');
                                            @endphp
                                            @if ($orderItems->count() > 0)
                                                <div class="group relative mt-0.5 inline-block w-full hover:z-30">
                                                    <span
                                                        class="inline-flex w-full cursor-pointer items-center justify-center rounded-full border border-orange-200 bg-orange-50 px-2.5 py-1 text-center text-xs font-semibold text-orange-600 dark:border-orange-800/50 dark:bg-orange-950/30 dark:text-orange-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" class="mr-1.5 shrink-0">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ $sudahDikirim->count() }}/{{ $orderItems->count() }} delivered
                                                    </span>
                                                    <div
                                                        class="pointer-events-none absolute top-0 right-full z-50 mr-3 w-80 rounded-lg border border-gray-200 bg-white p-3 text-left text-xs opacity-0 shadow-xl transition-opacity duration-200 group-hover:pointer-events-auto group-hover:opacity-100">
                                                        @if ($sudahDikirim->count() > 0)
                                                            <p class="mb-1.5 font-bold text-green-600">✓ Delivered:</p>
                                                            @foreach ($sudahDikirim as $item)
                                                                <div
                                                                    class="mb-1 flex items-center justify-between border-b border-gray-100 pb-1 last:border-0">
                                                                    <span
                                                                        class="text-gray-700">{{ Str::limit($item->barang->goods_name ?? '-', 28) }}</span>
                                                                    <span class="ml-2 shrink-0 font-bold text-green-600">
                                                                        {{ $item->delivered_quantity }}/{{ $item->quantity }}
                                                                        {{ $item->barang->unit ?? '' }}
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                        @if ($belumDikirim->count() > 0)
                                                            <p class="mb-1.5 mt-2 font-bold text-orange-500">⏳ Remaining:</p>
                                                            @foreach ($belumDikirim as $item)
                                                                @php $sisa = $item->quantity - ($item->delivered_quantity ?? 0); @endphp
                                                                <div
                                                                    class="mb-1 flex items-center justify-between border-b border-gray-100 pb-1 last:border-0">
                                                                    <span
                                                                        class="text-gray-700">{{ Str::limit($item->barang->goods_name ?? '-', 28) }}</span>
                                                                    <span class="ml-2 shrink-0 font-bold text-orange-500">Remaining:
                                                                        {{ $sisa }}
                                                                        {{ $item->barang->unit ?? '' }}</span>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="text-nowrap p-4 align-middle">
                                    <div class="flex flex-col gap-1 text-sm text-slate-700 dark:text-slate-300">
                                        <span>{{ $ro->created_at->format('Y-m-d') }}</span>
                                        <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                                                <line x1="16" x2="16" y1="2" y2="6"></line>
                                                <line x1="8" x2="8" y1="2" y2="6"></line>
                                                <line x1="3" x2="21" y1="10" y2="10"></line>
                                            </svg>
                                            @if ($ro->valid_date)
                                                s/d {{ \Carbon\Carbon::parse($ro->valid_date)->format('Y-m-d') }}
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap p-4 text-right align-middle">
                                    <div class="flex justify-end">
                                        <div
                                            class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-300 ease-in-out dark:border-gray-600 dark:bg-gray-700">


                                            {{-- Detail --}}
                                            <a href="{{ route('sales.quotation.show', $ro->id) }}"
                                                class="group flex h-full items-center justify-center border-r border-blue-800 bg-blue-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:border-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900"
                                                title="View Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-eye h-4 w-4">
                                                    <path
                                                        d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0">
                                                    </path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                <span
                                                    class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Detail</span>
                                            </a>

                                            {{-- If supervisor rejects --}}
                                            @if ($ro->status == 'Ditolak Supervisor')
                                                {{-- Note modal --}}
                                                <button type="button"
                                                    class="note-btn group flex h-full cursor-pointer items-center justify-center border-r border-yellow-700 bg-yellow-600 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300 dark:border-yellow-500 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800"
                                                    data-catatan="{{ $ro->reason ?? '' }}" title="View Rejection Reason">
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
                                                        class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Note</span>
                                                </button>
                                            @endif

                                            {{-- Action Dropdown --}}
                                            <button
                                                class="group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                                popovertarget="popover-{{ $ro->id }}"
                                                style="anchor-name:--anchor-{{ $ro->id }}">
                                                <svg width="24px" height="24px" viewBox="0 0 16 16"
                                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                    class="bi bi-three-dots-vertical h-4 w-4">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                        stroke-linejoin="round"></g>
                                                    <g id="SVGRepo_iconCarrier">
                                                        <path
                                                            d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                                                        </path>
                                                    </g>
                                                </svg>
                                                <span
                                                    class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Action</span>
                                            </button>
                                            <ul class="dropdown dropdown-end menu rounded-box bg-base-100 w-52 shadow-sm"
                                                popover id="popover-{{ $ro->id }}"
                                                style="position-anchor:--anchor-{{ $ro->id }}">
                                                {{-- Edit --}}
                                                <li>
                                                    <a href="{{ route('sales.quotation.edit', $ro->id) }}"
                                                        class="flex items-center gap-2 text-yellow-600 hover:bg-yellow-50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-pencil">
                                                            <path
                                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                            </path>
                                                            <path d="m15 5 4 4"></path>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                </li>

                                                <li>
                                                    {{-- PDF --}}
                                                    @if ($ro->canDownloadPdf())
                                                        <a href="{{ route('sales.quotation.pdf', $ro->id) }}"
                                                            class="flex items-center gap-2 text-green-600 hover:bg-green-50"
                                                            target="_blank">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-file-text">
                                                                <path
                                                                    d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z">
                                                                </path>
                                                                <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                                                <path d="M10 9H8"></path>
                                                                <path d="M16 13H8"></path>
                                                                <path d="M16 17H8"></path>
                                                            </svg>
                                                            PDF
                                                        </a>
                                                    @else
                                                        <button type="button" disabled
                                                            class="flex w-full cursor-not-allowed items-center gap-2 text-gray-400"
                                                            title="Awaiting Supervisor Approval">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-lock">
                                                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                                            </svg>
                                                            PDF
                                                        </button>
                                                    @endif
                                                </li>

                                                {{-- Delete --}}
                                                <form action="{{ route('sales.quotation.destroy', $ro->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <li>
                                                        <button type="button"
                                                            onclick="confirmDelete(() => this.closest('form').submit())"
                                                            class="flex w-full items-center gap-2 text-red-600 hover:bg-red-50">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" class="lucide lucide-trash2">
                                                                <path d="M10 11v6"></path>
                                                                <path d="M14 11v6"></path>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                                                <path d="M3 6h18"></path>
                                                                <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    </li>
                                                </form>
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
            <nav id="pagination-nav"
                class="sticky bottom-0 z-20 flex shrink-0 flex-col items-start justify-between space-y-3 border-t border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
                aria-label="Table navigation">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        Menampilkan
                        <span
                            class="font-semibold text-gray-900 dark:text-white">{{ $requestOrders->firstItem() ?? 0 }}-{{ $requestOrders->lastItem() ?? 0 }}</span>
                        dari
                        <span
                            class="font-semibold text-gray-900 dark:text-white">{{ $requestOrders->total() ?? $requestOrders->count() }}</span>
                    </span>
                    <form method="GET" action="{{ route('sales.quotation.index') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="perPage" onchange="this.form.submit()"
                            class="mx-2 rounded-xl border border-gray-300 bg-gray-50 p-1 pl-2 pr-8 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @foreach ([10, 25, 50, 100] as $size)
                                <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
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
        <div style="position: absolute; width: 0; height: 0; overflow: hidden;">
            @vite(['resources/js/quotation.js', 'resources/js/realtime-table-search.js', 'resources/js/table-sort.js'])
            @include('admin.quotation.partials.modal-show-note')
        </div>
    </div>
</x-app-layout>
