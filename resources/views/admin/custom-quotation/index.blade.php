<x-app-layout>

    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-between overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="item-center flex p-3">
            <div class="flex w-full shrink-0 flex-col items-stretch justify-end space-y-2 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0">

                <a href="{{ route('sales.custom-quotation.create') }}"
                    class="flex flex-row items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 text-sm font-semibold text-white hover:bg-[#19426d]">
                    + Buat New Quotation
                </a>

            </div>
        </div>

        <div class="p-3">
            {{-- Search --}}
            <form action="{{ route('sales.custom-quotation.index') }}" method="GET" class="block pl-2">
                <label for="topbar-search" class="sr-only">Search</label>
                <div class="relative md:w-96">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                            </path>
                        </svg>
                    </div>
                    <input type="search" name="search" id="topbar-search dt-search-0" aria-controls="warehouseTable" value="{{ request('search') }}"
                        class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                        placeholder="Search" />
                </div>
            </form>
        </div>
    </div>


    <div class="relative flex max-h-[calc(100vh-210px)] flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="shrink-0 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
        </div>

        <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
            <table id="" class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="sticky top-0 z-10 bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="selectCol text-nowrap px-4 py-3"></th>
                        <th class="text-nowrap px-4 py-3">No Penawaran</th>
                        <th class="text-nowrap px-4 py-3">Kepada</th>
                        <th class="text-nowrap px-4 py-3">Subject</th>
                        <th class="text-nowrap px-4 py-3">Our Ref</th>
                        <th class="text-nowrap px-4 py-3">Diskon</th>
                        <th class="text-nowrap px-4 py-3">Total</th>
                        <th class="text-nowrap px-4 py-3">Tanggal Dibuat</th>
                        <th class="text-nowrap px-4 py-3">Berlaku Sampai</th>
                        <th class="text-nowrap px-4 py-3">Status</th>
                        <th class="flex justify-center text-nowrap px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-nowrap">
                    @foreach ($customPenawarans as $penawaran)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-4">{{ $penawaran->id }}</td>
                            <td class="px-4 py-4">
                                <span class="text-nowrap font-semibold">{{ $penawaran->quotation_number }}</span>
                            </td>
                            <td class="text-nowrap px-4 py-4">{{ $penawaran->to }}</td>
                            <td class="px-4 py-4">{{ Str::limit($penawaran->subject, 30) }}</td>
                            <td class="text-nowrap px-4 py-4">{{ $penawaran->our_ref }}</td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $discounts = $penawaran->items->pluck('diskon')->map(fn($d) => (float) ($d ?? 0));
                                    $hasBelow = $discounts->contains(fn($d) => $d > 0 && $d <= 20);
                                    $hasAbove = $discounts->contains(fn($d) => $d > 20);
                                @endphp
                                @if ($hasBelow && $hasAbove)
                                    <span
                                        class="inline-flex items-center justify-center rounded-full border border-amber-200 bg-amber-50 px-2 py-1 text-xs font-semibold text-amber-800 dark:border-amber-800/50 dark:bg-amber-950/30 dark:text-amber-300">
                                        &lt;20% &amp; &gt;20%
                                    </span>
                                @elseif ($hasAbove)
                                    <span
                                        class="inline-flex items-center justify-center rounded-full border border-red-200 bg-red-50 px-2 py-1 text-xs font-semibold text-red-700 dark:border-red-800/50 dark:bg-red-950/30 dark:text-red-300">
                                        &gt;20%
                                    </span>
                                @elseif ($hasBelow)
                                    <span
                                        class="inline-flex items-center justify-center rounded-full border border-green-200 bg-green-50 px-2 py-1 text-xs font-semibold text-green-700 dark:border-green-800/50 dark:bg-green-950/30 dark:text-green-300">
                                        &lt;20%
                                    </span>
                                @else
                                    <span class="text-gray-300 dark:text-gray-600">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex w-full items-center justify-between">
                                    <span>Rp</span>
                                    <span>{{ number_format($penawaran->grand_total, 0, '.', ',') }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                {{ \Carbon\Carbon::parse($penawaran->created_at)->format('d F Y') }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if ($penawaran->expired_at)
                                    {{ \Carbon\Carbon::parse($penawaran->expired_at)->format('d F Y') }}
                                    @if ($penawaran->isExpired())
                                        <br><small class="text-danger">Kadaluarsa</small>
                                    @else
                                        @php
                                            $daysLeft = (int) $penawaran->expired_at->diffInDays(now());
                                        @endphp
                                        <br><small class="text-success">Berlaku {{ $daysLeft }} hari lagi</small>
                                    @endif
                                @else
                                    {{ \Carbon\Carbon::parse($penawaran->created_at)->addDays(14)->format('d/m/Y') }}
                                    <br><small class="text-success">Berlaku 14 hari dari dibuat</small>
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $statusLabel =
                                        [
                                            'draft' => 'Draft',
                                            'pending_approval' => 'Waiting for Supervisor Approval',
                                            'open' => 'Open',
                                            'sent_to_warehouse' => 'Sent to Warehouse',
                                            'sent_to_penawaran' => 'Sent to Penawaran',
                                            'approved' => 'Approved / Open',
                                            'rejected' => 'Rejected',
                                            'expired' => 'Expired',
                                            'approved_supervisor' => 'Approved by Supervisor',
                                            'rejected_supervisor' => 'Rejected by Supervisor',
                                        ][$penawaran->status] ?? $penawaran->status;

                                    $badgeBg = 'bg-gray-50 dark:bg-gray-900/30';
                                    $badgeText = 'text-gray-700 dark:text-gray-300';
                                    $badgeBorder = 'border border-gray-200 dark:border-gray-700/50';
                                    $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/></svg>';

                                    if (in_array($penawaran->status, ['approved', 'approved_supervisor'])) {
                                        $badgeBg = 'bg-green-50 dark:bg-green-950/30';
                                        $badgeText = 'text-green-700 dark:text-green-300';
                                        $badgeBorder = 'border border-green-200 dark:border-green-800/50';
                                        $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>';
                                    } elseif (in_array($penawaran->status, ['rejected', 'rejected_supervisor'])) {
                                        $badgeBg = 'bg-red-50 dark:bg-red-950/30';
                                        $badgeText = 'text-red-700 dark:text-red-300';
                                        $badgeBorder = 'border border-red-200 dark:border-red-800/50';
                                        $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>';
                                    } elseif (in_array($penawaran->status, ['pending_approval', 'draft'])) {
                                        $badgeBg = 'bg-amber-50 dark:bg-amber-950/30';
                                        $badgeText = 'text-amber-800 dark:text-amber-300';
                                        $badgeBorder = 'border border-amber-200 dark:border-amber-800/50';
                                        $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
                                    } elseif (in_array($penawaran->status, ['open', 'sent_to_warehouse', 'sent_to_penawaran'])) {
                                        $badgeBg = 'bg-blue-50 dark:bg-blue-950/30';
                                        $badgeText = 'text-blue-700 dark:text-blue-300';
                                        $badgeBorder = 'border border-blue-200 dark:border-blue-800/50';
                                        $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
                                    } elseif ($penawaran->status === 'expired') {
                                        $badgeBg = 'bg-gray-50 dark:bg-gray-900/30';
                                        $badgeText = 'text-gray-700 dark:text-gray-300';
                                        $badgeBorder = 'border border-gray-200 dark:border-gray-700/50';
                                        $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock3 mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l3 3"/></svg>';
                                    }
                                @endphp
                                <span
                                    class="inline-flex items-center justify-center rounded-full px-2 py-1 text-xs font-semibold {{ $badgeBg }} {{ $badgeText }} {{ $badgeBorder }}">
                                    {!! $iconSvg !!}{{ $statusLabel }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-right align-middle">
                                <div class="flex justify-center">
                                    <div class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-300 ease-in-out dark:border-gray-600 dark:bg-gray-700">
                                        {{-- Detail --}}
                                        <a href="{{ route('sales.custom-quotation.show', $penawaran->id) }}"
                                            class="group flex h-full items-center justify-center border-r border-blue-800 bg-blue-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:border-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900"
                                            title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye h-4 w-4">
                                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0">
                                                </path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span
                                                class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Detail</span>
                                        </a>

                                        {{-- If supervisor rejects --}}
                                        @if ($penawaran->status == 'rejected_supervisor')
                                            {{-- Note modal --}}
                                            <button type="button"
                                                class="note-btn group flex h-full cursor-pointer items-center justify-center border-r border-yellow-700 bg-yellow-600 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300 dark:border-yellow-500 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800"
                                                data-catatan="{{ $penawaran->reason ?? '' }}" title="Lihat Alasan Penolakan">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sticky-note h-4 w-4">
                                                    <path d="M15.5 3H5a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V8.5L15.5 3Z">
                                                    </path>
                                                    <path d="M15 3v6h6"></path>
                                                </svg>
                                                <span
                                                    class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Note</span>
                                            </button>
                                        @endif

                                        {{-- Action Dropdown --}}
                                        <button type="button"
                                            class="group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                            popovertarget="popover-{{ $penawaran->id }}" style="anchor-name:--anchor-{{ $penawaran->id }}" title="Menu Action">
                                            <svg width="24px" height="24px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                class="bi bi-three-dots-vertical h-4 w-4">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                                                    </path>
                                                </g>
                                            </svg>
                                            <span
                                                class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Action</span>
                                        </button>
                                        <ul class="dropdown dropdown-end menu rounded-box bg-base-100 w-52 shadow-sm" popover id="popover-{{ $penawaran->id }}"
                                            style="position-anchor:--anchor-{{ $penawaran->id }}">
                                            <li>
                                                {{-- Edit --}}
                                                <a href="{{ route('sales.custom-quotation.edit', $penawaran->id) }}" class="flex items-center gap-2 text-yellow-600 hover:bg-yellow-50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil">
                                                        <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                        </path>
                                                        <path d="m15 5 4 4"></path>
                                                    </svg>
                                                    Edit
                                                </a>
                                            </li>
                                            {{-- Tombol Delete --}}
                                            <form action="{{ route('sales.custom-quotation.destroy', $penawaran->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <li>
                                                    <button type="button" onclick="confirmDelete(() => this.closest('form').submit())"
                                                        class="flex w-full items-center gap-2 text-red-600 hover:bg-red-50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2">
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
                                            <li>
                                                {{-- PDF --}}
                                                @php
                                                    // apakah ada item dengan diskon > 20%
                                                    $hasHighDiscount = $penawaran->items->where('diskon', '>', 20)->isNotEmpty();
                                                    $isExpired = $penawaran->isExpired();
                                                @endphp
                                                @if ($penawaran->status === 'approved_supervisor' && !$isExpired)
                                                    <a href="{{ route('sales.custom-quotation.pdf', $penawaran->id) }}" class="flex items-center gap-2 text-green-600 hover:bg-green-50"
                                                        target="_blank">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                        Download PDF
                                                    </a>
                                                @else
                                                    <button type="button" disabled class="flex w-full cursor-not-allowed items-center gap-2 text-gray-400"
                                                        title="{{ $isExpired ? 'Penawaran sudah kadaluarsa' : 'Menunggu persetujuan Supervisor' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text">
                                                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z">
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
                                            {{-- Sent to Warehouse --}}
                                            @if ($penawaran->status === 'approved_supervisor')
                                                <form action="{{ route('sales.custom-quotation.sent-to-warehouse', $penawaran->id) }}" method="POST">
                                                    @csrf
                                                    <li>
                                                        <button type="button" onclick="confirmApprove(() => this.closest('form').submit(), 'Kirim Penawaran ini ke Warehouse?', 'Ya, Kirim')"
                                                            class="flex w-full items-center gap-2 text-yellow-600 hover:bg-yellow-50">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M3 10h2l1 2h13l1-2h2M5 12v6a2 2 0 002 2h10a2 2 0 002-2v-6" />
                                                            </svg>
                                                            Send to Warehouse
                                                        </button>
                                                    </li>
                                                </form>
                                            @endif

                                            {{-- Send to Penawaran --}}
                                            @if (in_array($penawaran->status, ['open', 'approved_supervisor']))
                                                <form action="{{ route('sales.custom-quotation.sent-to-penawaran', $penawaran->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <li>
                                                        <button type="button" class="flex items-center gap-2 text-blue-700 hover:bg-blue-50"
                                                            onclick="confirmApprove(() => this.closest('form').submit(), 'Kirim Penawaran ini ke Penawaran?', 'Ya, Kirim')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                            </svg>
                                                            Send to Penawaran
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
                    Showing
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $customPenawarans->firstItem() ?? 0 }}-{{ $customPenawarans->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $customPenawarans->total() ?? $customPenawarans->count() }}</span>
                </span>
                <form method="GET" action="{{ route('sales.custom-quotation.index') }}">
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
                {{ $customPenawarans->links() }}
            </div>
        </nav>
    </div>
    @vite(['resources/js/custom-quotation.js', 'resources/js/table-sort.js'])
    @include('admin.custom-quotation.partials.modal-show-note')
</x-app-layout>

