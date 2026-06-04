<x-app-layout>
    <div class="flex flex-col lg:h-[calc(100vh-112px)] overflow-hidden">
        <div
            class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-end overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800 shrink-0">

            <div class="p-4">
                {{-- Search --}}
                <form action="{{ route('supervisor.custom-quotation-approval.index') }}" method="GET"
                    class="block pl-2" data-realtime-table-search data-search-input="#topbar-search"
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
                        <input type="search" name="search" id="topbar-search" value="{{ request('search') }}"
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                            placeholder="Search" />
                    </div>
                </form>
            </div>
        </div>

        <div
            class="relative flex flex-1 min-h-0 flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
            <div
                class="shrink-0 flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
            </div>
            <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
                <table class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400" id="">
                    <thead
                        class="sticky top-0 z-30 bg-gray-50 text-nowrap text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="text-nowrap px-4 py-3">Quotation</th>
                            <th class="text-nowrap px-4 py-3">Keterangan (Subject)</th>
                            <th class="text-nowrap px-4 py-3">Sales</th>
                            <th class="text-nowrap px-4 py-3">Tgl. Kirim</th>
                            <th class="text-nowrap px-6 py-3 no-sort text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-nowrap">
                        @forelse($quotations as $index => $quotation)
                            @php
                                $detailRoute = $quotation->offer_type === 'custom' ? route('admin.custom-quotation-approval.show', $quotation->id) : route('sales.quotation.show', $quotation->id);

                                $maxDiskon = $quotation->offer_type === 'custom' ? ($quotation->items->max('diskon') ?? 0) : ($quotation->items->max('diskon_percent') ?? 0);

                                $keteranganText = null;
                                $highDiscountItem = $quotation->items->first(function ($item) use ($quotation) {
                                    $disc = $quotation->offer_type === 'custom' ? ($item->diskon ?? 0) : ($item->diskon_percent ?? 0);
                                    return $disc > 20 && !empty($item->keterangan);
                                });
                                if ($highDiscountItem) {
                                    $keteranganText = $highDiscountItem->keterangan;
                                } else {
                                    $anyKeteranganItem = $quotation->items->first(function ($item) {
                                        return !empty($item->keterangan);
                                    });
                                    if ($anyKeteranganItem) {
                                        $keteranganText = $anyKeteranganItem->keterangan;
                                    }
                                }
                                if (empty($keteranganText) && !empty($quotation->reason)) {
                                    $keteranganText = $quotation->reason;
                                }
                                if (empty($keteranganText) && !empty($quotation->subject)) {
                                    $keteranganText = $quotation->subject;
                                }

                                $tglKirim = null;
                                if ($quotation->offer_type === 'custom') {
                                    $tglKirim = $quotation->date ? $quotation->date->format('Y-m-d') : ($quotation->created_at ? $quotation->created_at->format('Y-m-d') : '-');
                                } else {
                                    $tglKirim = $quotation->required_date ? $quotation->required_date->format('Y-m-d') : ($quotation->created_at ? $quotation->created_at->format('Y-m-d') : '-');
                                }

                                $status = $quotation->order?->status ?? $quotation->status;

                                $statusMap = [
                                    'pending' => [
                                        'label' => 'Pending',
                                        'bg' => 'bg-gray-50 dark:bg-gray-900/30',
                                        'text' => 'text-gray-700 dark:text-gray-300',
                                        'border' => 'border border-gray-200 dark:border-gray-700/50',
                                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/></svg>'
                                    ],
                                    'open' => [
                                        'label' => 'Open',
                                        'bg' => 'bg-green-50 dark:bg-green-950/30',
                                        'text' => 'text-green-700 dark:text-green-300',
                                        'border' => 'border border-green-200 dark:border-green-800/50',
                                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>'
                                    ],
                                    'sent' => [
                                        'label' => 'Sent',
                                        'bg' => 'bg-blue-50 dark:bg-blue-950/30',
                                        'text' => 'text-blue-700 dark:text-blue-300',
                                        'border' => 'border border-blue-200 dark:border-blue-800/50',
                                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>'
                                    ],
                                    'sent_to_supervisor' => [
                                        'label' => 'Waiting for Approval',
                                        'bg' => 'bg-blue-50 dark:bg-blue-950/30',
                                        'text' => 'text-blue-700 dark:text-blue-300',
                                        'border' => 'border border-blue-200 dark:border-blue-800/50',
                                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>'
                                    ],
                                    'approved_supervisor' => [
                                        'label' => 'Approved by Supervisor',
                                        'bg' => 'bg-green-50 dark:bg-green-950/30',
                                        'text' => 'text-green-700 dark:text-green-300',
                                        'border' => 'border border-green-200 dark:border-green-800/50',
                                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>'
                                    ],
                                    'rejected_supervisor' => [
                                        'label' => 'Rejected by Supervisor',
                                        'bg' => 'bg-red-50 dark:bg-red-950/30',
                                        'text' => 'text-red-700 dark:text-red-300',
                                        'border' => 'border border-red-200 dark:border-red-800/50',
                                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>'
                                    ],
                                    'sent_to_warehouse' => [
                                        'label' => 'Sent to Warehouse',
                                        'bg' => 'bg-blue-50 dark:bg-blue-950/30',
                                        'text' => 'text-blue-700 dark:text-blue-300',
                                        'border' => 'border border-blue-200 dark:border-blue-800/50',
                                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>'
                                    ],
                                    'approved_warehouse' => [
                                        'label' => 'Approved by Warehouse',
                                        'bg' => 'bg-green-50 dark:bg-green-950/30',
                                        'text' => 'text-green-700 dark:text-green-300',
                                        'border' => 'border border-green-200 dark:border-green-800/50',
                                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>'
                                    ],
                                    'rejected_warehouse' => [
                                        'label' => 'Rejected by Warehouse',
                                        'bg' => 'bg-red-50 dark:bg-red-950/30',
                                        'text' => 'text-red-700 dark:text-red-300',
                                        'border' => 'border border-red-200 dark:border-red-800/50',
                                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>'
                                    ],
                                    'completed' => [
                                        'label' => 'Completed',
                                        'bg' => 'bg-green-50 dark:bg-green-950/30',
                                        'text' => 'text-green-700 dark:text-green-300',
                                        'border' => 'border border-green-200 dark:border-green-800/50',
                                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>'
                                    ],
                                    'not_completed' => [
                                        'label' => 'Partial Delivery',
                                        'bg' => 'bg-amber-50 dark:bg-amber-950/30',
                                        'text' => 'text-amber-700 dark:text-amber-300',
                                        'border' => 'border border-amber-200 dark:border-amber-800/50',
                                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>'
                                    ],
                                ];

                                $currentStatus = $statusMap[$status] ?? [
                                    'label' => ucfirst(str_replace('_', ' ', $status)),
                                    'bg' => 'bg-gray-50 dark:bg-gray-900/30',
                                    'text' => 'text-gray-700 dark:text-gray-300',
                                    'border' => 'border border-gray-200 dark:border-gray-700/50',
                                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/></svg>'
                                ];

                                $picName = null;
                                $picPosition = null;
                                if ($quotation->offer_type === 'custom') {
                                    $picName = $quotation->up;
                                } else {
                                    $picName = $quotation->pic->name ?? ($quotation->customer?->pics?->first()?->name ?? null);
                                    $picPosition = $quotation->pic->position ?? ($quotation->customer?->pics?->first()?->position ?? null);
                                }
                            @endphp
                            <tr
                                class="group border-b border-gray-50 transition-colors hover:bg-gray-50/80 dark:border-gray-700/50 dark:hover:bg-gray-700/30">
                                {{-- Quotation & Customer --}}
                                <td class="whitespace-nowrap px-4 py-3.5 text-gray-900 dark:text-white align-middle">
                                    <div class="flex flex-col gap-2.5">
                                        <!-- Quotation & Customer Details -->
                                        <div class="flex flex-col gap-1">
                                            <div>
                                                <a href="{{ $detailRoute }}"
                                                    class="text-[#225A97] dark:text-blue-400 font-bold hover:underline">
                                                    {{ $quotation->offer_type === 'custom' ? $quotation->quotation_number : $quotation->request_number }}
                                                </a>
                                            </div>
                                            <span class="text-base font-bold text-slate-900 dark:text-white">
                                                {{ $quotation->offer_type === 'custom' ? $quotation->to : ($quotation->customer?->nama_customer ?? $quotation->customer_name) }}
                                            </span>
                                            @if ($picName)
                                                <span
                                                    class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-3.5 w-3.5 text-slate-400 dark:text-slate-500"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4"></circle>
                                                    </svg>
                                                    <span class="font-medium">{{ $picName }}</span>
                                                    @if ($picPosition)
                                                        <span class="text-slate-300 dark:text-slate-600">•</span>
                                                        <span class="text-slate-400 dark:text-slate-500">{{ $picPosition }}</span>
                                                    @endif
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Divider -->
                                        <div class="border-t border-dashed border-gray-200 dark:border-gray-700/80"></div>

                                        <!-- Total & Items Summary -->
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                            <span class="text-base font-bold text-[#0067B1] dark:text-[#2798e6]">
                                                Rp {{ number_format($quotation->grand_total, 0, '.', ',') }}
                                            </span>
                                            <span
                                                class="inline-flex items-center gap-1 rounded bg-slate-100 px-1.5 py-0.5 text-xs font-medium text-slate-600 dark:bg-gray-800 dark:text-gray-400">
                                                {{ $quotation->items->count() }} item
                                            </span>
                                            @if ($maxDiskon > 20)
                                                <span
                                                    class="inline-flex items-center justify-center rounded border border-red-200 bg-red-50 px-1.5 py-0.5 text-xs font-semibold text-red-700 dark:border-red-800/50 dark:bg-red-950/30 dark:text-red-300">
                                                    >20%
                                                </span>
                                            @elseif ($maxDiskon > 0)
                                                <span
                                                    class="inline-flex items-center justify-center rounded border border-green-200 bg-green-50 px-1.5 py-0.5 text-xs font-semibold text-green-700 dark:border-green-800/50 dark:bg-green-950/30 dark:text-green-300">
                                                    <20% </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Remarks --}}
                                <td class="px-4 py-3 align-middle">
                                    @if (!empty($keteranganText))
                                        <div
                                            class="max-w-[220px] whitespace-normal break-words line-clamp-2 text-xs text-gray-500 dark:text-gray-400">
                                            {{ $keteranganText }}
                                        </div>
                                    @else
                                        <span class="italic text-gray-400 dark:text-gray-500 text-xs">No remarks</span>
                                    @endif
                                </td>

                                {{-- Sales --}}
                                <td class="whitespace-nowrap px-4 py-3.5 text-gray-900 dark:text-white font-semibold">
                                    {{ optional($quotation->sales)->name ?? '-' }}
                                </td>

                                {{-- Tgl. Kirim --}}
                                <td class="whitespace-nowrap px-4 py-3 text-gray-500 dark:text-gray-400">
                                    {{ $tglKirim }}
                                </td>

                                {{-- Action --}}
                                <td class="whitespace-nowrap px-4 py-3 text-right align-middle">
                                    <div class="flex justify-end">
                                        <div
                                            class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-300 ease-in-out divide-x divide-gray-200 dark:divide-gray-600 dark:border-gray-600 dark:bg-gray-700">
                                            {{-- Detail --}}
                                            @php
                                                $detailRoute = $quotation->offer_type === 'custom' ? route('admin.custom-quotation-approval.show', $quotation->id) : route('sales.quotation.show', $quotation->id);
                                            @endphp
                                            <a href="{{ $detailRoute }}"
                                                class="group/btn flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                                title="Lihat Detail">
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
                                                    class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover/btn:max-w-xs group-hover/btn:pl-2 group-hover/btn:opacity-100">Detail</span>
                                            </a>

                                            {{-- Approve --}}
                                            @php
                                                $approveRoute = $quotation->offer_type === 'custom' ? route('admin.custom-quotation-approval.approval', $quotation->id) : route('supervisor.quotation.approve', $quotation->id);
                                            @endphp
                                            <form action="{{ $approveRoute }}" method="POST" class="approve-form m-0 p-0"
                                                data-confirm-text="Are you sure you want to approve this quotation?">
                                                @csrf
                                                @if ($quotation->offer_type === 'custom')
                                                    <input type="hidden" name="action" value="approve">
                                                @endif
                                                <button type="submit"
                                                    class="group/btn flex h-full cursor-pointer items-center justify-center bg-green-600 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                                    title="Approve">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span
                                                        class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover/btn:max-w-xs group-hover/btn:pl-2 group-hover/btn:opacity-100">Approve</span>
                                                </button>
                                            </form>

                                            {{-- Reject --}}
                                            <button type="button"
                                                class="group/btn flex h-full cursor-pointer items-center justify-center bg-red-600 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                                onclick="openTolakModal('{{ $quotation->offer_type }}', '{{ $quotation->id }}', '{{ $quotation->offer_type === 'custom' ? $quotation->quotation_number : $quotation->request_number }}')"
                                                title="Reject">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-x-circle h-4 w-4">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <path d="m15 9-6 6"></path>
                                                    <path d="m9 9 6 6"></path>
                                                </svg>
                                                <span
                                                    class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover/btn:max-w-xs group-hover/btn:pl-2 group-hover/btn:opacity-100">Reject</span>
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

            <nav id="pagination-nav" class="sticky bottom-0 z-20 flex flex-col items-start justify-between space-y-3 bg-white p-4 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
                aria-label="Table navigation">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        Menampilkan
                        <span
                            class="font-semibold text-gray-900 dark:text-white">{{ $quotations->firstItem() ?? 0 }}-{{ $quotations->lastItem() ?? 0 }}</span>
                        dari
                        <span
                            class="font-semibold text-gray-900 dark:text-white">{{ $quotations->total() ?? $quotations->count() }}</span>
                    </span>
                    <form method="GET" action="{{ route('supervisor.custom-quotation-approval.index') }}">
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
                    {{ $quotations->links() }}
                </div>
            </nav>
        </div>
    </div>

    @include('admin.custom-quotation-approval.partials.custom-quotation-approval-modal-reject')

    @vite(['resources/js/realtime-table-search.js', 'resources/js/table-sort.js'])
</x-app-layout>
