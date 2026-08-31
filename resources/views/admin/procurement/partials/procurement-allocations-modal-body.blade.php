<!-- Header / Breadcrumbs -->
<header class="relative flex items-center justify-between px-7 py-5 text-white shrink-0"
    style="background-image: linear-gradient(135deg, #225A97 0%, #0D223A 100%)">
    <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold leading-tight">Rincian Alokasi Sales Order (FIFO)</h3>
            <p class="text-xs text-white/80">No. Pengadaan: <span class="font-mono font-bold">{{ $procurement->procurement_number }}</span></p>
        </div>
    </div>
    <button type="button" class="js-allocations-modal-close ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
        <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
    </button>
</header>

<!-- Scrollable Body -->
<div class="overflow-y-auto p-6 flex-1 space-y-6">
    <!-- Top Stats & Search Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Search input -->
        <div class="relative w-full sm:w-80">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" id="allocationSearchInput" 
                placeholder="Cari No SO atau Customer" 
                class="w-full rounded-xl border border-gray-300 bg-gray-50 py-2 pl-9 pr-4 text-xs font-medium text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        </div>

        <!-- Stats Pill -->
        @php
            $totalCount = $allLinkedSos->count();
            $fulfilledCount = $allLinkedSos->filter(fn($s) => ($s->shortage_quantity == 0) || ($s->order_status === 'sent_to_warehouse'))->count();
            $pendingCount = $totalCount - $fulfilledCount;
        @endphp
        <div class="flex items-center gap-2 text-xs">
            <span class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-slate-100 px-2.5 py-1 text-slate-700 font-semibold dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                Total: <strong>{{ $totalCount }} SO</strong>
            </span>
            <span class="inline-flex items-center gap-1 rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-emerald-700 font-semibold dark:border-emerald-800/50 dark:bg-emerald-950/30 dark:text-emerald-300">
                Terpenuhi: <strong>{{ $fulfilledCount }}</strong>
            </span>
            <span class="inline-flex items-center gap-1 rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-1 text-amber-800 font-semibold dark:border-amber-800/50 dark:bg-amber-950/30 dark:text-amber-300">
                Menunggu: <strong>{{ $pendingCount }}</strong>
            </span>
        </div>
    </div>

    <!-- Allocations Table -->
    @if($allLinkedSos->isEmpty())
        <div class="flex flex-col items-center justify-center p-8 text-center bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
            <svg class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Tidak ada data alokasi Sales Order</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pengadaan ini belum terhubung dengan alokasi SO aktif.</p>
        </div>
    @else
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow-2xs">
            <table id="allocationsDataTable" class="w-full border-collapse text-sm text-left">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 text-slate-700 dark:text-slate-200 text-xs font-bold uppercase">
                        <th class="px-4 py-3">No. Sales Order</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Barang</th>
                        <th class="px-4 py-3 text-center">Kebutuhan</th>
                        <th class="px-4 py-3 text-center">Teralokasi</th>
                        <th class="px-4 py-3 text-center">Status Pemenuhan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @foreach($allLinkedSos as $soAlloc)
                        @php
                            $isFulfilled = ($soAlloc->shortage_quantity == 0) || ($soAlloc->order_status === 'sent_to_warehouse');
                            $isPartial = !$isFulfilled && ($soAlloc->allocated_quantity > 0);
                            $searchableText = strtolower($soAlloc->order_number . ' ' . ($soAlloc->customer_name ?? '') . ' ' . $soAlloc->goods_name . ' ' . $soAlloc->goods_code);
                        @endphp
                        <tr class="allocation-row hover:bg-gray-50/80 dark:hover:bg-gray-700/40 transition-colors" data-search="{{ $searchableText }}">
                            <!-- No. SO & Antrean -->
                            <td class="px-4 py-3">
                                <span class="font-bold text-slate-800 dark:text-white font-mono text-xs">
                                    {{ $soAlloc->order_number }}
                                </span>
                                <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 flex items-center gap-1">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Antrean: {{ \Carbon\Carbon::parse($soAlloc->queue_at ?? $soAlloc->created_at)->format('Y-m-d H:i') }}</span>
                                </div>
                            </td>

                            <!-- Customer -->
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $soAlloc->customer_name ?? '-' }}
                            </td>

                            <!-- Barang -->
                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-800 dark:text-white">{{ $soAlloc->goods_name }}</div>
                                <span class="font-mono text-[11px] font-bold text-blue-600 dark:text-blue-400">[{{ $soAlloc->goods_code }}]</span>
                            </td>

                            <!-- Kebutuhan -->
                            <td class="px-4 py-3 text-center font-bold text-slate-900 dark:text-white whitespace-nowrap">
                                {{ $soAlloc->qty_needed }} {{ $soAlloc->unit }}
                            </td>

                            <!-- Teralokasi -->
                            <td class="px-4 py-3 text-center font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                {{ $soAlloc->allocated_quantity }} {{ $soAlloc->unit }}
                            </td>

                            <!-- Status Pemenuhan -->
                            <td class="px-4 py-3 text-center">
                                @if($isFulfilled)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 border border-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:border-emerald-800/50">
                                        <svg class="h-3.5 w-3.5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        Terpenuhi
                                    </span>
                                @elseif($isPartial)
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-800 border border-amber-200 dark:bg-amber-950/30 dark:text-amber-300 dark:border-amber-800/50">
                                        Sebagian (Sisa: {{ $soAlloc->shortage_quantity }} {{ $soAlloc->unit }})
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700 border border-blue-200 dark:bg-blue-950/30 dark:text-blue-300 dark:border-blue-800/50">
                                        Menunggu Pengadaan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr id="noAllocationSearchMatch" class="hidden">
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            Tidak ditemukan Sales Order yang sesuai dengan pencarian.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Footer -->
<footer class="flex items-center justify-end border-t border-gray-200 px-7 py-4 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 shrink-0">
    <button type="button" class="js-allocations-modal-close px-6 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-200 rounded-xl dark:text-gray-300 dark:hover:bg-gray-700">
        Tutup
    </button>
</footer>

<script>
    (function() {
        const searchInput = document.getElementById('allocationSearchInput');
        const rows = document.querySelectorAll('.allocation-row');
        const noMatch = document.getElementById('noAllocationSearchMatch');

        if (searchInput && rows.length > 0) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                let visibleCount = 0;

                rows.forEach(row => {
                    const searchData = row.getAttribute('data-search') || '';
                    if (!query || searchData.includes(query)) {
                        row.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        row.classList.add('hidden');
                    }
                });

                if (noMatch) {
                    if (visibleCount === 0 && query !== '') {
                        noMatch.classList.remove('hidden');
                    } else {
                        noMatch.classList.add('hidden');
                    }
                }
            });
        }
    })();
</script>
