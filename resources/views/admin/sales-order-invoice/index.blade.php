<x-app-layout>

    <div>
        @if (session('title'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 dark:border-green-900 dark:bg-green-900/30">
                <p class="font-semibold text-green-800 dark:text-green-300">{{ session('title') }}</p>
                @if (session('text'))
                    <p class="mt-1 text-sm text-green-700 dark:text-green-400">{{ session('text') }}</p>
                @endif
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-900 dark:bg-red-900/30">
                <p class="font-semibold text-red-800 dark:text-red-300">Terjadi kesalahan:</p>
                <ul class="mt-2 list-inside list-disc text-sm text-red-700 dark:text-red-400">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>


    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm overflow-show relative mb-5 flex justify-between rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex items-center p-3">
            <div class="flex w-full shrink-0 flex-col items-stretch justify-end space-y-2 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0">

                <a href="{{ route('sales-order-invoice.export', ['search' => $search]) }}"
                    class="flex flex-row items-center justify-center gap-2 rounded-lg bg-[#225A97] px-4 py-2 text-sm font-semibold text-white hover:bg-[#19426d]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Export Excel
                </a>

            </div>
        </div>

        <div class="p-3">
            {{-- Search --}}
            <div class="flex flex-col gap-2 md:flex-row">
                <div class="relative flex-1">
                    <label for="topbar-search" class="sr-only">Search</label>
                    <div class="relative md:w-96">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                                </path>
                            </svg>
                        </div>
                        <input type="text" id="searchInput" placeholder="Cari berdasarkan No.SO, Customer, Subject, atau Email..." value="{{ $search }}" autocomplete="off"
                            class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500">
                    </div>
                    <!-- Search Results Dropdown -->
                    <div id="searchResults"
                        class="z-99 absolute left-0 right-0 top-full mt-1 hidden max-h-96 overflow-y-auto rounded-lg border border-gray-300 bg-white shadow-lg dark:border-gray-500 dark:bg-gray-600">
                    </div>
                </div>
                <div class="flex gap-2">
                    @if ($search)
                        <a href="{{ route('sales-order-invoice.index') }}"
                            class="whitespace-nowrap rounded-lg border border-gray-300 px-6 py-2 font-semibold text-gray-700 hover:bg-gray-100 dark:border-gray-500 dark:text-gray-300 dark:hover:bg-gray-600">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative flex max-h-[calc(100vh-200px)] flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex shrink-0 items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
        </div>

        <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
            <table class="sortable w-full">
                <thead class="sticky top-0 z-30 text-nowrap border-b border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-700">
                    <tr>
                        <th class="text-nowrap px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">No. Dokumen</th>
                        <th class="text-nowrap px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Customer</th>
                        <th class="text-nowrap px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Item & Total</th>
                        <th class="text-nowrap px-4 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">Status</th>
                        <th class="text-nowrap px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300" data-type="date">Tanggal</th>
                        <th class="no-sort text-nowrap px-4 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-nowrap">
                    @forelse ($results as $row)
                        <tr class="border-b border-gray-200 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700">
                            <td class="whitespace-nowrap px-4 py-3.5 text-gray-900 dark:text-white">
                                <div>
                                    <a href="{{ route('invoice.index', $row['id']) }}?type={{ $row['type'] }}"
                                        target="_self"
                                        class="js-invoice-history font-bold text-[#225A97] hover:underline dark:text-blue-400"
                                        data-id="{{ $row['id'] }}"
                                        data-order-number="{{ $row['no_sales_order'] ?? '-' }}"
                                        data-has-batches="{{ $row['has_batches'] ? 'true' : 'false' }}"
                                        data-invoice-url="{{ route('invoice.index', $row['id']) }}?type={{ $row['type'] }}"
                                        data-history-url="{{ route('sales-order-invoice.invoice-history', $row['id']) }}">
                                        {{ $row['no_sales_order'] ?? '-' }}
                                    </a>
                                </div>
                                <div class="mt-1 flex items-center text-xs font-normal text-gray-500 dark:text-gray-400">
                                    <span class="w-8 text-gray-400 dark:text-gray-500">PNW</span>
                                    <span class="mx-1.5 font-bold text-gray-300 dark:text-gray-600">·</span>
                                    <span>{{ $row['no_penawaran'] ?? '-' }}</span>
                                </div>
                                <div class="mt-0.5 flex items-center text-xs font-normal text-gray-500 dark:text-gray-400">
                                    <span class="w-8 text-gray-400 dark:text-gray-500">REQ</span>
                                    <span class="mx-1.5 font-bold text-gray-300 dark:text-gray-600">·</span>
                                    <span>{{ $row['no_request'] ?? '-' }}</span>
                                </div>
                                <div class="mt-0.5 flex items-center gap-1.5 text-xs font-normal text-gray-500 dark:text-gray-400">
                                    <span class="w-8 text-gray-400 dark:text-gray-500">PO</span>
                                    <span class="font-bold text-gray-300 dark:text-gray-600">·</span>
                                    <span>{{ $row['no_po'] ?? '-' }}</span>
                                    @if (!empty($row['image_po']))
                                        <a href="{{ Storage::url($row['image_po']) }}" target="_blank" class="ml-1 inline-flex items-center text-blue-500 hover:text-blue-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image">
                                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                                                <circle cx="9" cy="9" r="2" />
                                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                                            </svg>
                                        </a>
                                    @endif
                                    @if (!empty($row['pdf_po']))
                                        <a href="{{ Storage::url($row['pdf_po']) }}" target="_blank" class="ml-1 inline-flex items-center text-red-500 hover:text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text">
                                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                                <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                                <path d="M10 9H8" />
                                                <path d="M16 13H8" />
                                                <path d="M16 17H8" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-gray-900 dark:text-white">
                                <div class="text-[14px] font-bold text-gray-900 dark:text-white">
                                    {{ $row['customer_name'] ?? '-' }}
                                </div>
                                @if (!empty($row['first_pic_name']))
                                    <div class="mt-1 flex items-center text-[12px] font-normal text-gray-500 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user mr-1.5 shrink-0 text-gray-400 dark:text-gray-500">
                                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                            <circle cx="12" cy="7" r="4" />
                                        </svg>
                                        <span class="truncate">{{ $row['first_pic_name'] }}</span>
                                        @if (!empty($row['first_pic_position']))
                                            <span class="mx-1.5 font-bold text-gray-300 dark:text-gray-600">·</span>
                                            <span class="truncate text-gray-400 dark:text-gray-500">{{ $row['first_pic_position'] }}</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-gray-900 dark:text-white">
                                <div class="text-[14px] font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format($row['total'] ?? 0, 0, '.', ',') }}
                                </div>
                                <div class="mt-1 flex items-center text-xs font-normal text-gray-500 dark:text-gray-400">
                                    <span>{{ $row['jumlah_item'] ?? 0 }} {{ ($row['jumlah_item'] ?? 0) > 1 ? 'items' : 'item' }}</span>
                                    @if (!empty($row['diskon']) && $row['diskon'] > 0)
                                        <span
                                            class="ml-1.5 inline-flex items-center gap-0.5 rounded border border-amber-200 bg-amber-50 px-1.5 py-0.5 text-[10px] font-bold text-amber-700 dark:border-amber-800/50 dark:bg-amber-950/30 dark:text-amber-400">
                                            % {{ $row['diskon'] }}%
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-center text-gray-900 dark:text-white">
                                @php
                                    $statusText = $row['status'] ?? '';
                                    $badgeBg = '';
                                    $badgeText = '';
                                    $badgeBorder = '';
                                    $iconSvg = '';

                                    if (in_array($statusText, ['Completed', 'Approved by Supervisor', 'Approved by Warehouse'])) {
                                        $badgeBg = 'bg-green-50 dark:bg-green-950/30';
                                        $badgeText = 'text-green-700 dark:text-green-300';
                                        $badgeBorder = 'border border-green-200 dark:border-green-800/50';
                                        $iconSvg =
                                            '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>';
                                    } elseif (in_array($statusText, ['Partial Delivery', 'Open'])) {
                                        $badgeBg = 'bg-amber-50 dark:bg-amber-950/30';
                                        $badgeText = 'text-amber-800 dark:text-amber-300';
                                        $badgeBorder = 'border border-amber-200 dark:border-amber-800/50';
                                        $iconSvg =
                                            '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
                                    } elseif (in_array($statusText, ['Pending', 'Waiting for Supervisor Approval', 'Sent to Supervisor', 'Sent to Warehouse', 'Belum Diproses'])) {
                                        $badgeBg = 'bg-blue-50 dark:bg-blue-950/30';
                                        $badgeText = 'text-blue-700 dark:text-blue-300';
                                        $badgeBorder = 'border border-blue-200 dark:border-blue-800/50';
                                        $iconSvg =
                                            '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
                                    } else {
                                        $badgeBg = 'bg-red-50 dark:bg-red-950/30';
                                        $badgeText = 'text-red-700 dark:text-red-300';
                                        $badgeBorder = 'border border-red-200 dark:border-red-800/50';
                                        $iconSvg =
                                            '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>';
                                    }
                                @endphp
                                <span class="{{ $badgeBg }} {{ $badgeText }} {{ $badgeBorder }} flex items-center justify-center rounded-full px-2 py-1 text-xs font-semibold">
                                    {!! $iconSvg !!}{{ $statusText }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-gray-900 dark:text-white">
                                @php
                                    $formattedDate = '-';
                                    if (!empty($row['tanggal']) && $row['tanggal'] !== '-') {
                                        try {
                                            $formattedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $row['tanggal'])->format('Y-m-d');
                                        } catch (\Exception $e) {
                                            $formattedDate = $row['tanggal'];
                                        }
                                    }

                                    $formattedExpiry = null;
                                    $expiryVal = $row['berlaku_sampai'] ?? '-';
                                    if ($expiryVal !== '-') {
                                        try {
                                            $formattedExpiry = \Carbon\Carbon::parse($expiryVal)->format('Y-m-d');
                                        } catch (\Exception $e) {
                                            $formattedExpiry = $expiryVal;
                                        }
                                    }
                                @endphp
                                <div class="text-[14px] font-bold text-gray-900 dark:text-white">
                                    {{ $formattedDate }}
                                </div>
                                @if ($formattedExpiry)
                                    <div class="mt-1 flex items-center text-xs font-normal text-gray-500 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar mr-1.5 shrink-0 text-gray-400 dark:text-gray-500">
                                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                            <line x1="16" x2="16" y1="2" y2="6" />
                                            <line x1="8" x2="8" y1="2" y2="6" />
                                            <line x1="3" x2="21" y1="10" y2="10" />
                                        </svg>
                                        <span>s/d {{ $formattedExpiry }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('invoice.index', $row['id']) }}?type={{ $row['type'] }}"
                                    target="_self"
                                    class="js-invoice-history group inline-flex items-center rounded-lg bg-green-600 p-2 text-xs font-semibold text-white transition-all duration-300 ease-in-out hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                    data-id="{{ $row['id'] }}"
                                    data-order-number="{{ $row['no_sales_order'] ?? '-' }}"
                                    data-has-batches="{{ $row['has_batches'] ? 'true' : 'false' }}"
                                    data-invoice-url="{{ route('invoice.index', $row['id']) }}?type={{ $row['type'] }}"
                                    data-history-url="{{ route('sales-order-invoice.invoice-history', $row['id']) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <path d="M9 13h6" />
                                        <path d="M9 17h3" />
                                    </svg>
                                    <span
                                        class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Invoice</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-gray-400 dark:text-gray-600">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                                <p class="text-lg font-semibold">
                                    @if ($search)
                                        Tidak ada hasil untuk pencarian "{{ $search }}"
                                    @else
                                        Tidak ada data
                                    @endif
                                </p>
                                <p class="mt-1 text-sm">
                                    @if ($search)
                                        Coba ubah kata kunci pencarian atau <a href="{{ route('sales-order-invoice.index') }}" class="text-blue-600 hover:underline">reset pencarian</a>
                                    @else
                                        Data sales order belum tersedia
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (!$isSearch && $salesOrders)
            <nav class="sticky bottom-0 z-20 flex flex-col items-start justify-between space-y-3 bg-white p-4 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
                aria-label="Table navigation">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        Showing
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $salesOrders->firstItem() ?? 0 }}-{{ $salesOrders->lastItem() ?? 0 }}</span>
                        of
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $salesOrders->total() ?? $salesOrders->count() }}</span>
                    </span>
                    <form method="GET" action="{{ route('sales-order-invoice.index') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="perPage" onchange="this.form.submit()"
                            class="mx-2 rounded-xl border border-gray-300 bg-gray-50 p-1 pl-2 pr-8 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @foreach ([10, 25, 50, 100] as $size)
                                <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    <span class="text-sm text-gray-500 dark:text-gray-400">per halaman</span>
                </div>
                <div>
                    {{ $salesOrders->links() }}
                </div>
            </nav>
        @endif
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        const searchBtn = document.getElementById('searchBtn');
        let searchTimeout;

        // Autocomplete search dengan AJAX
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 1) {
                searchResults.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('sales-order-invoice.search') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data.length > 0) {
                            displaySearchResults(data.data);
                        } else {
                            searchResults.innerHTML = `
								<div class="p-4 text-center text-gray-500 dark:text-gray-400">
									<p>Tidak ada hasil yang ditemukan</p>
								</div>
							`;
                            searchResults.classList.remove('hidden');
                        }
                    });
            }, 300);
        });

        function selectSearchResult(query) {
            searchInput.value = query;
            searchResults.classList.add('hidden');

            // Trigger actual search
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = '{{ route('sales-order-invoice.index') }}';
            form.innerHTML = `<input type="hidden" name="search" value="${query}">`;
            document.body.appendChild(form);
            form.submit();
        }

        function displaySearchResults(results) {
            if (!results || results.length === 0) {
                searchResults.innerHTML = `
					<div class="p-6 text-center">
						<svg class="mx-auto h-10 w-10 text-gray-400 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
						</svg>
						<p class="text-gray-500 dark:text-gray-400 font-medium">Data tidak ditemukan</p>
					</div>
				`;
                searchResults.classList.remove('hidden');
                return;
            }

            searchResults.innerHTML = results.map(item => {
                const typeClass = item.type === 'penawaran' ?
                    'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20' :
                    'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20';

                // Use the order number or reference for searching
                const searchTerm = (item.sales_order_number || '').replace(/'/g, "\\'");

                return `
					<div onclick="selectSearchResult('${searchTerm}')" class="cursor-pointer block p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-blue-50/50 dark:hover:bg-blue-900/20 transition-colors">
						<div class="flex justify-between items-start">
							<div class="flex-1">
								<div class="flex items-center gap-2 mb-1">
									<span class="font-bold text-gray-900 dark:text-white">${item.sales_order_number || 'No Number'}</span>
									<span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-medium ${typeClass}">
										${item.badge}
									</span>
								</div>
								<div class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
									<svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
									${item.customer_name}
								</div>
								<div class="mt-2 text-[10px] font-bold uppercase tracking-wider text-yellow-600 flex items-center gap-1">
									<svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
									PO: ${item.no_po || '<span class="italic text-gray-300">tidak ada</span>'}
								</div>
							</div>
							<svg class="w-5 h-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
						</div>
					</div>
				`;
            }).join('');
            searchResults.classList.remove('hidden');
        }

        // Tombol search untuk form submission
        searchBtn.addEventListener('click', function() {
            const query = searchInput.value.trim();
            if (query) {
                const form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('sales-order-invoice.index') }}';
                form.innerHTML = `<input type="hidden" name="search" value="${query}">`;
                document.body.appendChild(form);
                form.submit();
            }
        });

        // Enter key untuk search
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchBtn.click();
            }
        });

        // Close dropdown ketika klik di luar
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#searchInput') && !e.target.closest('#searchResults')) {
                searchResults.classList.add('hidden');
            }
        });
    </script>
    @include('admin.sales-order-invoice.partials.invoice-history-modal')

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const invoiceButtons = document.querySelectorAll(".js-invoice-history");
            const historyModal = document.getElementById("invoiceHistoryModal");
            const historySalesOrderNumberEl = document.getElementById("historySalesOrderNumber");
            const invoiceHistoryTableBody = document.getElementById("invoiceHistoryTableBody");

            function openHistoryModal() {
                if (!historyModal) return;
                if (typeof historyModal.showModal === "function") {
                    historyModal.showModal();
                } else {
                    historyModal.style.display = "flex";
                }
            }

            function closeHistoryModal() {
                if (!historyModal) return;
                if (typeof historyModal.close === "function") {
                    historyModal.close();
                } else {
                    historyModal.style.display = "none";
                }
            }

            invoiceButtons.forEach((btn) => {
                btn.addEventListener("click", function (e) {
                    const hasBatches = btn.getAttribute("data-has-batches") === "true";
                    const invoiceUrl = btn.getAttribute("data-invoice-url");

                    if (!hasBatches) {
                        // Let the browser navigate naturally in the same window/tab since target is _self
                        return;
                    }

                    // It has batches, prevent the default behavior and show the modal
                    e.preventDefault();

                    const orderId = btn.getAttribute("data-id");
                    const orderNumber = btn.getAttribute("data-order-number");
                    const historyUrl = btn.getAttribute("data-history-url");

                    if (historySalesOrderNumberEl)
                        historySalesOrderNumberEl.textContent = orderNumber;
                    if (invoiceHistoryTableBody)
                        invoiceHistoryTableBody.innerHTML =
                            '<tr><td colspan="4" class="p-4 text-center">Loading...</td></tr>';

                    openHistoryModal();

                    fetch(historyUrl)
                        .then((res) => res.json())
                        .then((data) => {
                            if (invoiceHistoryTableBody) {
                                invoiceHistoryTableBody.innerHTML = "";
                                if (data.length === 0) {
                                    invoiceHistoryTableBody.innerHTML =
                                        '<tr><td colspan="4" class="p-4 text-center">Belum ada histori invoice.</td></tr>';
                                    return;
                                }

                                data.forEach((batch) => {
                                    const tr = document.createElement("tr");
                                    tr.className = "border-b dark:border-gray-600";

                                    const itemsList = batch.items
                                        .map(
                                            (item) =>
                                                `<li>${item.goods_name} (${item.quantity_sent})</li>`,
                                        )
                                        .join("");

                                    tr.innerHTML = `
                                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">Batch #${batch.batch_number}</td>
                                        <td class="px-4 py-3">${batch.created_at}</td>
                                        <td class="px-4 py-3">
                                            <ul class="list-disc pl-4 text-xs font-normal">
                                                ${itemsList}
                                            </ul>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="${batch.invoice_url}" target="_self" class="inline-flex items-center justify-center rounded-lg bg-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300">Cetak Invoice</a>
                                        </td>
                                    `;
                                    invoiceHistoryTableBody.appendChild(tr);
                                });
                            }
                        })
                        .catch((err) => {
                            console.error("Failed to fetch invoice history", err);
                            if (invoiceHistoryTableBody)
                                invoiceHistoryTableBody.innerHTML =
                                    '<tr><td colspan="4" class="p-4 text-center text-red-500">Gagal mengambil data.</td></tr>';
                        });
                });
            });

            // Close buttons for history modal
            document
                .querySelectorAll('[data-modal-hide="invoiceHistoryModal"]')
                .forEach((btn) => {
                    btn.addEventListener("click", closeHistoryModal);
                });

            // allow clicking overlay to close
            document.addEventListener("click", function (e) {
                if (historyModal && e.target === historyModal) closeHistoryModal();
            });
        });
    </script>
    @vite(['resources/js/table-sort.js'])
</x-app-layout>
