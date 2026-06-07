<x-app-layout>
    <div class="flex flex-col lg:h-[calc(100vh-112px)] overflow-hidden">
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

        <div class="relative flex flex-1 min-h-0 flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
            <div class="flex shrink-0 items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" id="tab-pending-btn"
                        class="flex items-center rounded-lg bg-white px-4 py-2 text-sm font-medium text-[#225A97] transition-all">
                        Menunggu Pengadaan
                        @if ($pendingQuotations->total() > 0)
                            <span class="ml-2 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1.5 text-[10px] text-white">
                                {{ $pendingQuotations->total() }}
                            </span>
                        @endif
                    </button>
                    <button type="button" id="tab-active-btn"
                        class="flex items-center rounded-lg px-4 py-2 text-sm font-medium text-white transition-all hover:bg-white/10">
                        Daftar Pengadaan Aktif
                        @if ($procurements->total() > 0)
                            <span class="ml-2 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1.5 text-[10px] text-white">
                                {{ $procurements->total() }}
                            </span>
                        @endif
                    </button>
                </div>
            </div>

            <div id="procurementTableContent" class="flex flex-1 min-h-0 flex-col">
                <div id="tab-pending-panel" class="flex flex-1 min-h-0 flex-col">
                    <div class="grow overflow-x-auto overflow-y-auto">
                        <table class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="sticky top-0 z-30 text-nowrap bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="text-nowrap px-4 py-3">Quotation</th>
                                <th scope="col" class="text-nowrap px-4 py-3">Customer</th>
                                <th scope="col" class="text-nowrap px-4 py-3">Subject</th>
                                <th scope="col" class="text-nowrap px-4 py-3">Date</th>
                                <th scope="col" class="text-nowrap px-4 py-3 text-center">Items</th>
                                <th scope="col" class="flex justify-end text-nowrap px-6 py-3 text-right no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingQuotations as $pending)
                                <tr class="border-b transition-colors duration-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50">
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                            {{ $pending->quotation_number }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Custom Quotation
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="max-w-[240px] truncate font-medium text-gray-900 dark:text-white">
                                            {{ $pending->to }}
                                        </div>
                                    </td>
                                    <td class="max-w-xs px-4 align-middle">
                                        <div class="line-clamp-3 max-w-[320px] break-words">
                                            {{ $pending->subject }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-nowrap">
                                        {{ \Carbon\Carbon::parse($pending->date)->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">
                                        {{ $pending->items->count() }}
                                    </td>
                                    <td class="px-4 py-3 text-right align-middle">
                                        <div class="flex justify-end">
                                            <div class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-300 ease-in-out dark:border-gray-600 dark:bg-gray-700">
                                                <a href="{{ route('general-affair.procurement.create', $pending->id) }}"
                                                    class="group flex h-full cursor-pointer items-center justify-center bg-green-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                     <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M11 16L15 12M15 12L11 8M15 12H3M4.51555 17C6.13007 19.412 8.87958 21 12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C8.87958 3 6.13007 4.58803 4.51555 7" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                                     <span class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Proses</span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        Tidak ada barang kustom menunggu pengadaan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        </table>
                    </div>
                    <nav id="pending-pagination-nav"
                        class="sticky bottom-0 z-20 flex shrink-0 flex-col items-start justify-between space-y-3 border-t border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
                        aria-label="Pending procurement table navigation">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                Menampilkan
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $pendingQuotations->firstItem() ?? 0 }}-{{ $pendingQuotations->lastItem() ?? 0 }}</span>
                                dari
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $pendingQuotations->total() }}</span>
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
                            {{ $pendingQuotations->links() }}
                        </div>
                    </nav>
                </div>

                <div id="tab-active-panel" class="hidden flex flex-1 min-h-0 flex-col">
                    <div class="grow overflow-x-auto overflow-y-auto">
                        <table class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="sticky top-0 z-30 text-nowrap bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="text-nowrap px-4 py-3">Procurement</th>
                                <th scope="col" class="text-nowrap px-4 py-3">Custom Quotation</th>
                                <th scope="col" class="text-nowrap px-4 py-3">Created By</th>
                                <th scope="col" class="text-nowrap px-4 py-3 text-center">Items</th>
                                <th scope="col" class="text-nowrap px-4 py-3 text-center">Status</th>
                                <th scope="col" class="flex justify-end text-nowrap px-6 py-3 text-right no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($procurements as $procurement)
                                @php
                                    $statusLabel = [
                                        'pending' => 'Pending',
                                        'partial_received' => 'Parsial Diterima',
                                        'completed' => 'Selesai',
                                    ][$procurement->status] ?? $procurement->status;

                                    $badgeClass = 'bg-amber-50 dark:bg-amber-950/30 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800/50';
                                    if ($procurement->status === 'completed') {
                                        $badgeClass = 'bg-green-50 dark:bg-green-950/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800/50';
                                    } elseif ($procurement->status === 'partial_received') {
                                        $badgeClass = 'bg-blue-50 dark:bg-blue-950/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800/50';
                                    }
                                @endphp
                                <tr class="border-b transition-colors duration-200 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('general-affair.procurement.show', $procurement->id) }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-400">
                                            {{ $procurement->procurement_number }}
                                        </a>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $procurement->created_at->format('Y-m-d H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $procurement->customQuotation->quotation_number ?? '-' }}
                                        </div>
                                        <div class="max-w-[240px] truncate text-xs text-gray-500 dark:text-gray-400">
                                            {{ $procurement->customQuotation->to ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $procurement->generalAffair->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">
                                        {{ $procurement->items->count() }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center justify-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right align-middle">
                                        <div class="flex justify-end">
                                            <div class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-300 ease-in-out dark:border-gray-600 dark:bg-gray-700">
                                                <a href="{{ route('general-affair.procurement.show', $procurement->id) }}"
                                                    class="group flex h-full cursor-pointer items-center justify-center bg-yellow-600 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                                                    <svg fill="none" height="14" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                                        width="14" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    <span class="max-w-0 overflow-hidden text-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Detail</span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        Belum ada pengadaan barang yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        </table>
                    </div>
                    <nav id="procurement-pagination-nav"
                        class="sticky bottom-0 z-20 flex shrink-0 flex-col items-start justify-between space-y-3 border-t border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
                        aria-label="Active procurement table navigation">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                Menampilkan
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $procurements->firstItem() ?? 0 }}-{{ $procurements->lastItem() ?? 0 }}</span>
                                dari
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $procurements->total() }}</span>
                            </span>
                            <form method="GET" action="{{ route('general-affair.procurement.index') }}">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="tab" value="active">
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
                            {{ $procurements->links() }}
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabPendingBtn = document.getElementById('tab-pending-btn');
            const tabActiveBtn = document.getElementById('tab-active-btn');

            function activateTab(tab) {
                const tabPendingPanel = document.getElementById('tab-pending-panel');
                const tabActivePanel = document.getElementById('tab-active-panel');
                const activeClasses = ['bg-white', 'text-[#225A97]'];
                const inactiveClasses = ['text-white', 'hover:bg-white/10'];

                if (tab === 'active') {
                    tabPendingBtn.classList.remove(...activeClasses);
                    tabPendingBtn.classList.add(...inactiveClasses);
                    tabActiveBtn.classList.add(...activeClasses);
                    tabActiveBtn.classList.remove(...inactiveClasses);
                    tabPendingPanel.classList.add('hidden');
                    tabActivePanel.classList.remove('hidden');
                    localStorage.setItem('procurement_active_tab', 'active');
                    return;
                }

                tabActiveBtn.classList.remove(...activeClasses);
                tabActiveBtn.classList.add(...inactiveClasses);
                tabPendingBtn.classList.add(...activeClasses);
                tabPendingBtn.classList.remove(...inactiveClasses);
                tabActivePanel.classList.add('hidden');
                tabPendingPanel.classList.remove('hidden');
                localStorage.setItem('procurement_active_tab', 'pending');
            }

            tabPendingBtn.addEventListener('click', () => activateTab('pending'));
            tabActiveBtn.addEventListener('click', () => activateTab('active'));
            document.addEventListener('realtime-table-search:updated', () => {
                activateTab(localStorage.getItem('procurement_active_tab') === 'active' ? 'active' : 'pending');
            });

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('pending_page')) {
                activateTab('pending');
            } else if (urlParams.has('proc_page') || urlParams.get('tab') === 'active' || localStorage.getItem('procurement_active_tab') === 'active') {
                activateTab('active');
            }
        });
    </script>

    @vite(['resources/js/realtime-table-search.js', 'resources/js/table-sort.js'])
</x-app-layout>
