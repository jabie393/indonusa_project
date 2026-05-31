<x-app-layout>

    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-end overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">

        <div class="p-4">
            {{-- Search --}}
            <form action="{{ route('supervisor.custom-quotation-approval.index') }}"
                method="GET"
                class="block pl-2">
                <label for="topbar-search"
                    class="sr-only">Search</label>
                <div class="relative md:w-96">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400"
                            fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                            </path>
                        </svg>
                    </div>
                    <input type="search"
                        name="search"
                        id="topbar-search"
                        value="{{ request('search') }}"
                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                        placeholder="Search" />
                </div>
            </form>
        </div>
    </div>

    <div class="relative flex max-h-[calc(100vh-210px)] flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="shrink-0 flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
        </div>
        <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
            <table class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400" id="">
                <thead class="sticky top-0 z-30 bg-gray-50 text-nowrap text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="text-nowrap px-4 py-3">Quotation</th>
                        <th class="text-nowrap px-4 py-3">Sales</th>
                        <th class="text-nowrap px-4 py-3">Item & Keterangan (Subject)</th>
                        <th class="text-nowrap px-4 py-3">Tgl. Kirim</th>
                        <th class="text-nowrap px-4 py-3 no-sort text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="text-nowrap">
                    @forelse($penawarans as $index => $penawaran)
                    @php
                        $detailRoute = $penawaran->offer_type === 'custom' ? route('admin.custom-quotation-approval.show', $penawaran->id) : route('admin.quotation.show', $penawaran->id);
                        
                        $maxDiskon = $penawaran->items->max('diskon_percent') ?? 0;
                        
                        $keteranganText = null;
                        $highDiscountItem = $penawaran->items->first(function($item) {
                            return ($item->diskon_percent ?? 0) > 20 && !empty($item->keterangan);
                        });
                        if ($highDiscountItem) {
                            $keteranganText = $highDiscountItem->keterangan;
                        } else {
                            $anyKeteranganItem = $penawaran->items->first(function($item) {
                                return !empty($item->keterangan);
                            });
                            if ($anyKeteranganItem) {
                                $keteranganText = $anyKeteranganItem->keterangan;
                            }
                        }
                        if (empty($keteranganText) && !empty($penawaran->reason)) {
                            $keteranganText = $penawaran->reason;
                        }
                        if (empty($keteranganText) && !empty($penawaran->subject)) {
                            $keteranganText = $penawaran->subject;
                        }

                        $tglKirim = null;
                        if ($penawaran->offer_type === 'custom') {
                            $tglKirim = $penawaran->date ? $penawaran->date->format('Y-m-d') : ($penawaran->created_at ? $penawaran->created_at->format('Y-m-d') : '-');
                        } else {
                            $tglKirim = $penawaran->tanggal_kebutuhan ? $penawaran->tanggal_kebutuhan->format('Y-m-d') : ($penawaran->created_at ? $penawaran->created_at->format('Y-m-d') : '-');
                        }

                        $status = $penawaran->order?->status ?? $penawaran->status;
                        
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
                    @endphp
                    <tr class="group border-b border-gray-50 transition-colors hover:bg-gray-50/80 dark:border-gray-700/50 dark:hover:bg-gray-700/30">
                        {{-- Penawaran & Customer --}}
                        <td class="whitespace-nowrap px-4 py-3.5 text-gray-900 dark:text-white">
                            <div>
                                <a href="{{ $detailRoute }}"
                                    class="text-[#225A97] dark:text-blue-400 font-bold hover:underline">
                                    {{ $penawaran->offer_type === 'custom' ? $penawaran->penawaran_number : $penawaran->request_number }}
                                </a>
                            </div>
                            <div class="font-bold text-gray-900 dark:text-white text-[14px] mt-1.5">
                                {{ $penawaran->offer_type === 'custom' ? $penawaran->to : ($penawaran->customer?->nama_customer ?? $penawaran->customer_name) }}
                            </div>
                            @php
                                $firstPic = $penawaran->offer_type === 'custom' ? null : $penawaran->customer?->pics?->first();
                            @endphp
                            @if ($firstPic)
                                <div class="flex items-center text-[12px] text-gray-500 dark:text-gray-400 mt-1 font-normal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user text-gray-400 dark:text-gray-500 mr-1.5 shrink-0">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                    <span class="truncate">{{ $firstPic->name }}</span>
                                    @if ($firstPic->position)
                                        <span class="text-gray-300 dark:text-gray-600 font-bold mx-1.5">·</span>
                                        <span class="text-gray-400 dark:text-gray-500 truncate">{{ $firstPic->position }}</span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        
                        {{-- Sales --}}
                        <td class="whitespace-nowrap px-4 py-3.5 text-gray-900 dark:text-white font-semibold">
                            {{ optional($penawaran->sales)->name ?? '-' }}
                        </td>
                        
                        {{-- Item & Remarks --}}
                        <td class="px-4 py-3">
                            <div class="flex flex-col items-start gap-1">
                                <div class="flex flex-row items-center gap-2">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-0.5 text-xs font-semibold text-[#225A97] dark:bg-blue-950/30 dark:text-blue-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
                                            <path d="m7.5 4.27 9 5.15"/>
                                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                                            <path d="m3.3 7 8.7 5 8.7-5"/>
                                            <path d="M12 22V12"/>
                                        </svg>
                                        {{ $penawaran->items->count() }} items
                                    </span>
                                    @if ($maxDiskon > 20)
                                        <span class="inline-flex items-center justify-center rounded-full border border-red-200 bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 dark:border-red-800/50 dark:bg-red-950/30 dark:text-red-300">
                                            >20%
                                        </span>
                                    @elseif ($maxDiskon > 0)
                                        <span class="inline-flex items-center justify-center rounded-full border border-green-200 bg-green-50 px-2 py-0.5 text-xs font-semibold text-green-700 dark:border-green-800/50 dark:bg-green-950/30 dark:text-green-300">
                                            <20%
                                        </span>
                                    @else
                                        <span class="text-gray-300 dark:text-gray-600 text-xs font-semibold">-</span>
                                    @endif
                                </div>
                                @if (!empty($keteranganText))
                                    <div class="max-w-[220px] whitespace-normal break-words line-clamp-2 text-xs text-gray-500 dark:text-gray-400">
                                        {{ $keteranganText }}
                                    </div>
                                @else
                                    <span class="italic text-gray-400 dark:text-gray-500 text-xs">No remarks</span>
                                @endif
                            </div>
                        </td>
                        
                        {{-- Tgl. Kirim --}}
                        <td class="whitespace-nowrap px-4 py-3 text-gray-500 dark:text-gray-400">
                            {{ $tglKirim }}
                        </td>
                        
                        {{-- Action --}}
                        <td class="whitespace-nowrap px-4 py-3 text-right align-middle">
                            <div class="flex justify-center">
                                <div class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-300 ease-in-out divide-x divide-gray-200 dark:divide-gray-600 dark:border-gray-600 dark:bg-gray-700">
                                    {{-- Detail --}}
                                    @php
                                    $detailRoute = $penawaran->offer_type === 'custom' ? route('admin.custom-quotation-approval.show', $penawaran->id) : route('admin.quotation.show', $penawaran->id);
                                    @endphp
                                    <a href="{{ $detailRoute }}"
                                        class="group/btn flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                        title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-eye h-4 w-4">
                                            <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                            <circle cx="12"
                                                cy="12"
                                                r="3"></circle>
                                        </svg>
                                        <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover/btn:max-w-xs group-hover/btn:pl-2 group-hover/btn:opacity-100">Detail</span>
                                    </a>

                                    {{-- Approve --}}
                                    @php
                                    $approveRoute = $penawaran->offer_type === 'custom' ? route('admin.custom-quotation-approval.approval', $penawaran->id) : route('supervisor.quotation.approve', $penawaran->id);
                                    @endphp
                                    <form action="{{ $approveRoute }}"
                                        method="POST"
                                        class="approve-form m-0 p-0"
                                        data-confirm-text="Are you sure you want to approve this quotation?">
                                        @csrf
                                        @if ($penawaran->offer_type === 'custom')
                                        <input type="hidden"
                                            name="action"
                                            value="approve">
                                        @endif
                                        <button type="submit"
                                            class="group/btn flex h-full cursor-pointer items-center justify-center bg-green-600 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                            title="Approve">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover/btn:max-w-xs group-hover/btn:pl-2 group-hover/btn:opacity-100">Approve</span>
                                        </button>
                                    </form>

                                    {{-- Reject --}}
                                    <button type="button"
                                        class="group/btn flex h-full cursor-pointer items-center justify-center bg-red-600 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                        onclick="openTolakModal('{{ $penawaran->offer_type }}', '{{ $penawaran->id }}', '{{ $penawaran->offer_type === 'custom' ? $penawaran->penawaran_number : $penawaran->request_number }}')"
                                        title="Reject">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-x-circle h-4 w-4">
                                            <circle cx="12"
                                                cy="12"
                                                r="10"></circle>
                                            <path d="m15 9-6 6"></path>
                                            <path d="m9 9 6 6"></path>
                                        </svg>
                                        <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover/btn:max-w-xs group-hover/btn:pl-2 group-hover/btn:opacity-100">Reject</span>
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

        <nav class="sticky bottom-0 z-20 flex flex-col items-start justify-between space-y-3 bg-white p-4 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
            aria-label="Table navigation">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Showing
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $penawarans->firstItem() ?? 0 }}-{{ $penawarans->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $penawarans->total() ?? $penawarans->count() }}</span>
                </span>
                <form method="GET"
                    action="{{ route('supervisor.custom-quotation-approval.index') }}">
                    <input type="hidden"
                        name="search"
                        value="{{ request('search') }}">
                    <select name="perPage"
                        onchange="this.form.submit()"
                        class="mx-2 rounded-xl border border-gray-300 bg-gray-50 p-1 pl-2 pr-8 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @foreach ([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}"
                            {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </form>
                <span class="text-sm text-gray-500 dark:text-gray-400">per page</span>
            </div>
            <div>
                {{ $penawarans->links() }}
            </div>
        </nav>
    </div>

    @include('admin.custom-quotation-approval.partials.custom-quotation-approval-modal-reject')

    @vite(['resources/js/table-sort.js'])
</x-app-layout>
