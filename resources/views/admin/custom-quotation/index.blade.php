<x-app-layout>
    <div class="flex flex-col lg:h-[calc(100vh-112px)] overflow-hidden">

        <div
            class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex h-16 items-center justify-between overflow-hidden rounded-2xl bg-white px-4 shadow-md dark:bg-gray-800 shrink-0">
            <div class="flex items-center gap-2 md:gap-3">
                <a href="{{ route('sales.custom-quotation.create') }}"
                    class="flex flex-row items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 text-sm font-semibold text-white shadow transition-all duration-200 hover:bg-[#19426d] focus:outline-none focus:ring-2 focus:ring-[#225A97]/50">
                    <svg class="mr-2 h-4 w-4 shrink-0" fill="currentColor" viewbox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    Custom Quotation
                </a>
            </div>

            <div>
                {{-- Search --}}
                <form action="{{ route('sales.custom-quotation.index') }}" method="GET" class="block pl-2"
                    data-realtime-table-search data-search-input="#topbar-search" data-search-target="#tableContainer"
                    data-pagination-target="#pagination-nav" data-extra-fields="#pagination-nav select[name='perPage']">
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
                            <th scope="col" class="text-nowrap p-4">Kepada, Item & Total</th>
                            <th scope="col" class="text-nowrap p-4">Tanggal</th>
                            <th scope="col" class="text-nowrap p-4 text-center">Status</th>
                            <th scope="col" class="flex justify-end text-nowrap px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-nowrap">
                        @foreach ($customQuotations as $quotation)
                            @php
                                $discounts = $quotation->items->pluck('diskon')->map(fn($d) => (float) ($d ?? 0));
                                $hasBelow = $discounts->contains(fn($d) => $d > 0 && $d <= 20);
                                $hasAbove = $discounts->contains(fn($d) => $d > 20);
                                $expiredAt = $quotation->expired_at ?: $quotation->created_at->copy()->addDays(14);
                            @endphp
                            <tr
                                class="border-b border-gray-100 align-top hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/30">
                                <td class="p-4">
                                    <div class="flex flex-col gap-1">
                                        <a href="{{ route('sales.custom-quotation.show', $quotation->id) }}"
                                            class="text-base font-bold text-[#0067B1] hover:underline">
                                            {{ $quotation->quotation_number ?? '-' }}
                                        </a>
                                        <div class="grid grid-cols-[44px_1fr] gap-x-2 text-xs leading-relaxed">
                                            <span class="font-semibold uppercase text-slate-400">REF</span>
                                            <span
                                                class="text-slate-600 dark:text-slate-300">{{ $quotation->our_ref ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 align-middle">
                                    <div class="flex flex-col gap-2.5">
                                        <!-- Recipient Details -->
                                        <div class="flex flex-col gap-1">
                                            <span class="text-base font-bold text-slate-900 dark:text-white">
                                                {{ $quotation->to ?? '-' }}
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
                                                <span class="font-medium">{{ $quotation->up ?? '-' }}</span>
                                                @if ($quotation->email)
                                                    <span class="text-slate-300 dark:text-slate-600">•</span>
                                                    <span
                                                        class="text-slate-400 dark:text-slate-500">{{ $quotation->email }}</span>
                                                @endif
                                            </span>
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
                                <td class="text-nowrap p-4 align-middle">
                                    <div class="flex flex-col gap-1 text-sm text-slate-700 dark:text-slate-300">
                                        <span>{{ \Carbon\Carbon::parse($quotation->created_at)->format('Y-m-d') }}</span>
                                        <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                                                <line x1="16" x2="16" y1="2" y2="6"></line>
                                                <line x1="8" x2="8" y1="2" y2="6"></line>
                                                <line x1="3" x2="21" y1="10" y2="10"></line>
                                            </svg>
                                            s/d {{ \Carbon\Carbon::parse($expiredAt)->format('Y-m-d') }}
                                        </span>
                                        @if ($quotation->expired_at && $quotation->isExpired())
                                            <span class="text-xs font-semibold text-red-600">Kadaluarsa</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4 align-middle">
                                    @php
                                        $statusLabel =
                                            [
                                                'draft' => 'Draft',
                                                'pending_approval' => 'Waiting for Supervisor Approval',
                                                'open' => 'Open',
                                                'sent_to_warehouse' => 'Sent to Warehouse',
                                                'sent_to_quotation' => 'Sent to Quotation',
                                                'approved' => 'Approved / Open',
                                                'rejected' => 'Rejected',
                                                'expired' => 'Expired',
                                                'approved_supervisor' => 'Approved by Supervisor',
                                                'rejected_supervisor' => 'Rejected by Supervisor',
                                                'procurement_pending' => 'Procurement Pending',
                                                'partially_available' => 'Partially Available',
                                                'ready_for_delivery' => 'Ready for Delivery',
                                                'completed' => 'Completed',
                                            ][$quotation->status] ?? $quotation->status;

                                        $badgeBg = 'bg-gray-50 dark:bg-gray-900/30';
                                        $badgeText = 'text-gray-700 dark:text-gray-300';
                                        $badgeBorder = 'border border-gray-200 dark:border-gray-700/50';
                                        $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/></svg>';

                                        if (in_array($quotation->status, ['approved', 'approved_supervisor'])) {
                                            $badgeBg = 'bg-green-50 dark:bg-green-950/30';
                                            $badgeText = 'text-green-700 dark:text-green-300';
                                            $badgeBorder = 'border border-green-200 dark:border-green-800/50';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>';
                                        } elseif (in_array($quotation->status, ['rejected', 'rejected_supervisor'])) {
                                            $badgeBg = 'bg-red-50 dark:bg-red-950/30';
                                            $badgeText = 'text-red-700 dark:text-red-300';
                                            $badgeBorder = 'border border-red-200 dark:border-red-800/50';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>';
                                        } elseif (in_array($quotation->status, ['pending_approval', 'draft'])) {
                                            $badgeBg = 'bg-amber-50 dark:bg-amber-950/30';
                                            $badgeText = 'text-amber-800 dark:text-amber-300';
                                            $badgeBorder = 'border border-amber-200 dark:border-amber-800/50';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
                                        } elseif (in_array($quotation->status, ['open', 'sent_to_warehouse', 'sent_to_quotation'])) {
                                            $badgeBg = 'bg-blue-50 dark:bg-blue-950/30';
                                            $badgeText = 'text-blue-700 dark:text-blue-300';
                                            $badgeBorder = 'border border-blue-200 dark:border-blue-800/50';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
                                        } elseif ($quotation->status === 'procurement_pending') {
                                            $badgeBg = 'bg-amber-50 dark:bg-amber-950/30';
                                            $badgeText = 'text-amber-800 dark:text-amber-300';
                                            $badgeBorder = 'border border-amber-200 dark:border-amber-800/50';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
                                        } elseif ($quotation->status === 'partially_available') {
                                            $badgeBg = 'bg-blue-50 dark:bg-blue-950/30';
                                            $badgeText = 'text-blue-700 dark:text-blue-300';
                                            $badgeBorder = 'border border-blue-200 dark:border-blue-800/50';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
                                        } elseif ($quotation->status === 'ready_for_delivery') {
                                            $badgeBg = 'bg-indigo-50 dark:bg-indigo-950/30';
                                            $badgeText = 'text-indigo-700 dark:text-indigo-300';
                                            $badgeBorder = 'border border-indigo-200 dark:border-indigo-800/50';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>';
                                        } elseif ($quotation->status === 'completed') {
                                            $badgeBg = 'bg-emerald-50 dark:bg-emerald-950/30';
                                            $badgeText = 'text-emerald-700 dark:text-emerald-300';
                                            $badgeBorder = 'border border-emerald-200 dark:border-emerald-800/50';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>';
                                        } elseif ($quotation->status === 'expired') {
                                            $badgeBg = 'bg-gray-50 dark:bg-gray-900/30';
                                            $badgeText = 'text-gray-700 dark:text-gray-300';
                                            $badgeBorder = 'border border-gray-200 dark:border-gray-700/50';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock3 mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l3 3"/></svg>';
                                        }
                                    @endphp
                                    <div class="flex items-center justify-center mx-auto">
                                        <span
                                            class="inline-flex w-full items-center justify-center rounded-full px-2.5 py-1 text-center text-xs font-semibold {{ $badgeBg }} {{ $badgeText }} {{ $badgeBorder }}">
                                            {!! $iconSvg !!}{{ $statusLabel }}
                                        </span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap p-4 text-right align-middle">
                                    <div class="flex justify-end">
                                        <div
                                            class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-300 ease-in-out dark:border-gray-600 dark:bg-gray-700">
                                            {{-- Detail --}}
                                            <a href="{{ route('sales.custom-quotation.show', $quotation->id) }}"
                                                class="group flex h-full items-center justify-center border-r border-blue-800 bg-blue-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:border-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900"
                                                title="Detail">
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



                                            {{-- Action Dropdown --}}
                                            <button type="button"
                                                class="group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                                popovertarget="popover-{{ $quotation->id }}"
                                                style="anchor-name:--anchor-{{ $quotation->id }}" title="Menu Action">
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
                                            @php
                                                $isCqProcessed = in_array($quotation->status, ['sent_to_warehouse', 'sent_to_quotation']) || 
                                                                 ($quotation->order && in_array($quotation->order->status, ['under_procurement', 'sent_to_warehouse', 'approved_warehouse', 'rejected_warehouse', 'completed', 'not_completed']));
                                            @endphp
                                            <ul class="dropdown dropdown-end menu rounded-box bg-base-100 w-52 shadow-sm"
                                                popover id="popover-{{ $quotation->id }}"
                                                style="position-anchor:--anchor-{{ $quotation->id }}">
                                                @if (!$isCqProcessed)
                                                    <li>
                                                        {{-- Edit --}}
                                                        <a href="{{ route('sales.custom-quotation.edit', $quotation->id) }}"
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
                                                            {{ $quotation->status === 'rejected_supervisor' ? 'Revise' : 'Edit' }}
                                                        </a>
                                                    </li>
                                                    {{-- Tombol Delete --}}
                                                    <form action="{{ route('sales.custom-quotation.destroy', $quotation->id) }}"
                                                        method="POST" style="display:inline;">
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
                                                @endif
                                                <li>
                                                    {{-- PDF --}}
                                                    @php
                                                        // apakah ada item dengan diskon > 20%
                                                        $hasHighDiscount = $quotation->items->where('diskon', '>', 20)->isNotEmpty();
                                                        $isExpired = $quotation->isExpired();
                                                    @endphp
                                                    @if (!in_array($quotation->status, ['pending_approval', 'rejected_supervisor']) && !$isExpired)
                                                        <a href="{{ route('sales.custom-quotation.pdf', $quotation->id) }}"
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
                                                            title="{{ $isExpired ? 'Quotation sudah kadaluarsa' : ($quotation->status === 'rejected_supervisor' ? 'Quotation ditolak oleh Supervisor' : 'Menunggu persetujuan Supervisor') }}">
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
                                                        </button>
                                                    @endif
                                                </li>
                                                {{-- Sent to Warehouse removed per request --}}

                                                {{-- Send to Quotation --}}
                                                @if ($quotation->status === 'approved_supervisor')
                                                    <form
                                                        action="{{ route('sales.custom-quotation.sent-to-quotation', $quotation->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        <li>
                                                            <button type="button"
                                                                class="flex items-center gap-2 text-blue-700 hover:bg-blue-50"
                                                                onclick="confirmApprove(() => this.closest('form').submit(), 'Kirim Quotation ini ke Quotation?', 'Ya, Kirim')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                                </svg>
                                                                Send to Quotation
                                                            </button>
                                                        </li>
                                                    </form>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
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
                            class="font-semibold text-gray-900 dark:text-white">{{ $customQuotations->firstItem() ?? 0 }}-{{ $customQuotations->lastItem() ?? 0 }}</span>
                        dari
                        <span
                            class="font-semibold text-gray-900 dark:text-white">{{ $customQuotations->total() ?? $customQuotations->count() }}</span>
                    </span>
                    <form method="GET" action="{{ route('sales.custom-quotation.index') }}">
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
                    {{ $customQuotations->links() }}
                </div>
            </nav>
        </div>
        <div style="position: absolute; width: 0; height: 0; overflow: hidden;">
            @vite(['resources/js/custom-quotation.js', 'resources/js/realtime-table-search.js', 'resources/js/table-sort.js'])
        </div>
    </div>
</x-app-layout>