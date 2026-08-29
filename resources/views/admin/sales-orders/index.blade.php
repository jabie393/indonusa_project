<x-app-layout>
    <div class="flex flex-col overflow-hidden lg:h-[calc(100vh-112px)]">
        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex h-16 items-center justify-end overflow-show rounded-2xl bg-white px-4 shadow-md dark:bg-gray-800 shrink-0">
            <div class="flex items-center p-3">
                <div class="flex w-full shrink-0 flex-col items-stretch justify-end space-y-2 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0">
                </div>
            </div>

            <div>
                {{-- Search --}}
                <div class="">
                    <div class="flex flex-col md:flex-row">
                        <div class="relative flex-1">
                            <label for="topbar-search" class="sr-only">Search</label>
                            <div class="relative md:w-96">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
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
                                <a href="{{ route('sales.sales-orders.index') }}"
                                    class="whitespace-nowrap rounded-lg border border-gray-300 px-6 py-2 font-semibold text-gray-700 hover:bg-gray-100 dark:border-gray-500 dark:text-gray-300 dark:hover:bg-gray-600">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="shrink-0">
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


        <div class="relative flex min-h-0 flex-1 flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
            <div class="shrink-0 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
            </div>

            <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
                <table class="sortable w-full" id="">
                    <thead class="sticky top-0 z-30 border-b border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="text-nowrap px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                No. PO</th>
                            <th scope="col" class="text-nowrap px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                No. Dokumen</th>
                            <th scope="col" class="text-nowrap px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Customer, Item & Total</th>
                            <th scope="col" class="text-nowrap px-4 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Status & Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="text-nowrap">
                        @forelse ($results as $row)
                            @php
                                $discountValue = (float) ($row['diskon'] ?? 0);
                                $discountBadgeClass = 'border-green-200 bg-green-50 text-green-700 dark:border-green-800/50 dark:bg-green-950/30 dark:text-green-300';
                                if ($discountValue > 20) {
                                    $discountBadgeClass = 'border-red-200 bg-red-50 text-red-700 dark:border-red-800/50 dark:bg-red-950/30 dark:text-red-300';
                                } elseif ($discountValue <= 0) {
                                    $discountBadgeClass = 'border-gray-200 bg-gray-50 text-gray-500 dark:border-gray-700/50 dark:bg-gray-900/30 dark:text-gray-400';
                                }
                            @endphp
                            <tr class="border-b border-gray-100 align-top hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/30">
                                <td class="px-4 py-5">
                                    <div class="flex w-[245px] flex-col gap-2 rounded-xl border border-slate-200 bg-slate-50 p-3 shadow-sm dark:border-gray-600 dark:bg-gray-700/40">
                                        @if ($row['type'] === 'request_order')
                                            <input type="text" id="no-po-input-{{ $row['id'] }}"
                                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none dark:border-gray-500 dark:bg-gray-700 dark:text-white"
                                                value="{{ $row['no_po'] === '-' ? '' : ($row['no_po'] ?? '') }}" placeholder="-" onblur="saveNoPO({{ $row['id'] }}, this.value)"
                                                onkeypress="if (event.key === 'Enter') { event.preventDefault(); this.blur(); }" />
                                            <span class="text-[11px] leading-snug text-gray-500 dark:text-gray-400">No.PO dapat
                                                di edit langsung di sini.</span>
                                        @else
                                            <span
                                                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ $row['no_po'] ?? '-' }}</span>
                                        @endif

                                        <div class="flex flex-wrap gap-2">
                                            @if ($row['type'] === 'request_order' && ($row['customer_status'] ?? 'active') === 'active')
                                                <label id="upload-po-button-{{ $row['id'] }}-{{ $row['type'] }}"
                                                    class="inline-flex flex-1 cursor-pointer items-center justify-center gap-1 rounded-lg border border-dashed border-emerald-400 bg-white px-2 py-1.5 text-[10px] font-semibold text-emerald-600 shadow-sm transition-colors hover:bg-emerald-50"
                                                    style="{{ isset($row['image_po']) && $row['image_po'] ? 'display: none;' : '' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" x2="12" y1="3" y2="15" />
                                                    </svg>
                                                    Upload PO
                                                    <input type="file" class="hidden" accept="image/jpeg,image/png,image/jpg"
                                                        onchange="handleUploadImage(this, 'request_order', {{ $row['id'] }}, 'po')">
                                                </label>
                                                <label id="upload-pdf-po-button-{{ $row['id'] }}-{{ $row['type'] }}"
                                                    class="inline-flex flex-1 cursor-pointer items-center justify-center gap-1 rounded-lg border border-dashed border-violet-400 bg-white px-2 py-1.5 text-[10px] font-semibold text-violet-600 shadow-sm transition-colors hover:bg-violet-50"
                                                    style="{{ isset($row['pdf_po']) && $row['pdf_po'] ? 'display: none;' : '' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" x2="12" y1="3" y2="15" />
                                                    </svg>
                                                    Upload PDF
                                                    <input type="file" class="hidden" accept="application/pdf"
                                                        onchange="handleUploadImage(this, 'request_order', {{ $row['id'] }}, 'pdf_po')">
                                                </label>
                                            @endif
                                        </div>

                                        <div class="flex flex-wrap gap-2">
                                            <div id="image-po-preview-{{ $row['id'] }}-{{ $row['type'] }}" class="mt-1">
                                                @if (isset($row['image_po']) && $row['image_po'])
                                                    <div class="group relative inline-block">
                                                        <a href="{{ Storage::url($row['image_po']) }}" target="_blank">
                                                            <img src="{{ Storage::url($row['image_po']) }}" alt="PO Image"
                                                                class="h-10 w-10 rounded border border-gray-300 object-cover shadow-sm transition-transform hover:scale-110" />
                                                        </a>
                                                        <button
                                                            class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white opacity-0 shadow-lg transition-opacity group-hover:opacity-100"
                                                            onclick="handleDeleteImage('{{ $row['type'] }}', {{ $row['id'] }}, 'po')" title="Delete">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M18 6 6 18" />
                                                                <path d="m6 6 12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                            <div id="pdf-po-preview-{{ $row['id'] }}-{{ $row['type'] }}" class="mt-1">
                                                @if (isset($row['pdf_po']) && $row['pdf_po'])
                                                    <div class="group relative inline-block">
                                                        <a href="{{ Storage::url($row['pdf_po']) }}" target="_blank">
                                                            <div class="flex h-10 w-10 items-center justify-center rounded border border-red-300 bg-red-50">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600">
                                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                                                                    </path>
                                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                                </svg>
                                                            </div>
                                                        </a>
                                                        <button
                                                            class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white opacity-0 shadow-lg transition-opacity group-hover:opacity-100"
                                                            onclick="handleDeleteImage('{{ $row['type'] }}', {{ $row['id'] }}, 'pdf_po')" title="Delete">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M18 6 6 18" />
                                                                <path d="m6 6 12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-6 align-middle">
                                    <div class="flex flex-col gap-1">
                                        <a href="{{ $row['aksi_url'] }}" class="text-base font-bold text-[#0067B1] hover:underline">{{ $row['no_quotation'] ?? '-' }}</a>
                                        <div class="grid grid-cols-[32px_1fr] gap-x-2 text-xs leading-relaxed">
                                            <span class="font-semibold uppercase text-slate-400">SO</span>
                                            <span id="sales-order-number-{{ $row['id'] }}" class="text-slate-600 dark:text-slate-300">{{ $row['no_sales_order'] ?? '-' }}</span>
                                            <span class="font-semibold uppercase text-slate-400">REQ</span>
                                            <span class="text-slate-600 dark:text-slate-300">{{ $row['no_request'] ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-6 align-middle">
                                    <div class="flex flex-col gap-2.5">
                                        <!-- Customer Details -->
                                        <div class="flex flex-col gap-1">
                                            <span class="text-base font-bold text-slate-900 dark:text-white">{{ $row['customer_name'] ?? '-' }}</span>
                                            <span class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400 dark:text-slate-500" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                                <span class="font-medium">{{ $row['first_pic_name'] ?? (auth()->user()->name ?? '-') }}</span>
                                                <span class="text-slate-300">•</span>
                                                <span class="text-slate-400 dark:text-slate-500">{{ $row['first_pic_position'] ?? 'Sales' }}</span>
                                            </span>
                                            @if (($row['customer_status'] ?? 'active') === 'inactive')
                                                <div class="mt-1">
                                                    <span
                                                        class="rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-red-600 dark:bg-red-950/30 dark:text-red-400">Non
                                                        Aktif</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Divider -->
                                        <div class="border-t border-dashed border-gray-200 dark:border-gray-700/80"></div>

                                        <!-- Total & Items Summary -->
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                            <span class="text-base font-bold text-[#0067B1] dark:text-[#2798e6]">
                                                Rp {{ number_format($row['total'], 0, '.', ',') }}
                                            </span>
                                            <span class="inline-flex items-center gap-1 rounded bg-slate-100 px-1.5 py-0.5 text-xs font-medium text-slate-600 dark:bg-gray-800 dark:text-gray-400">
                                                {{ $row['jumlah_item'] ?? '-' }} item
                                            </span>
                                            <span class="{{ $discountBadgeClass }} inline-flex items-center justify-center rounded border px-1.5 py-0.5 text-xs font-semibold">
                                                {{ $discountValue > 20 ? '>20%' : ($discountValue > 0 ? '<20%' : '0%') }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-6 align-middle">
                                    <div class="mx-auto flex w-52 flex-col items-center justify-center gap-1.5">
                                        @php
                                            $badgeBg = 'bg-gray-50 dark:bg-gray-900/30';
                                            $badgeText = 'text-gray-700 dark:text-gray-300';
                                            $badgeBorder = 'border border-gray-200 dark:border-gray-700/50';
                                            $iconSvg =
                                                '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/></svg>';

                                            if (in_array($row['status'], ['Completed', 'Approved by Supervisor', 'Approved by Warehouse', 'Open'])) {
                                                $badgeBg = 'bg-green-50 dark:bg-green-950/30';
                                                $badgeText = 'text-green-700 dark:text-green-300';
                                                $badgeBorder = 'border border-green-200 dark:border-green-800/50';
                                                $iconSvg =
                                                    '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>';
                                            } elseif (in_array($row['status'], ['Partial Delivery', 'Waiting for Supervisor Approval', 'Under Procurement'])) {
                                                $badgeBg = 'bg-amber-50 dark:bg-amber-950/30';
                                                $badgeText = 'text-amber-800 dark:text-amber-300';
                                                $badgeBorder = 'border border-amber-200 dark:border-amber-800/50';
                                                $iconSvg =
                                                    '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
                                            } elseif (in_array($row['status'], ['Sent to Supervisor', 'Sent to Warehouse'])) {
                                                $badgeBg = 'bg-blue-50 dark:bg-blue-950/30';
                                                $badgeText = 'text-blue-700 dark:text-blue-300';
                                                $badgeBorder = 'border border-blue-200 dark:border-blue-800/50';
                                                $iconSvg =
                                                    '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
                                                                          } elseif (in_array($row['status'], ['Rejected by Supervisor', 'Rejected by Warehouse', 'Canceled', 'Partially Canceled'])) {
                                                $badgeBg = 'bg-red-50 dark:bg-red-950/30';
                                                $badgeText = 'text-red-700 dark:text-red-300';
                                                $badgeBorder = 'border border-red-200 dark:border-red-800/50';
                                                $iconSvg =
                                                    '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>';
                                            }
                                        @endphp
                                        <span
                                            class="{{ $badgeBg }} {{ $badgeText }} {{ $badgeBorder }} inline-flex w-full items-center justify-center rounded-full px-2 py-1 text-center text-xs font-semibold">
                                            {!! $iconSvg !!}{{ $row['status'] }}
                                        </span>

                                        <div class="flex flex-col gap-0.5 text-center text-xs text-slate-500 dark:text-slate-400">
                                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ $row['tanggal'] ?? '-' }}</span>
                                            @if ((!empty($row['berlaku_sampai']) && $row['berlaku_sampai'] !== '-') || (!empty($row['request_order']) && !empty($row['request_order']['valid_date_formatted'])))
                                                <span class="flex items-center justify-center gap-1 text-[11px] text-slate-400 dark:text-slate-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                                                        <line x1="16" x2="16" y1="2" y2="6"></line>
                                                        <line x1="8" x2="8" y1="2" y2="6"></line>
                                                        <line x1="3" x2="21" y1="10" y2="10"></line>
                                                    </svg>
                                                    @if (!empty($row['berlaku_sampai']) && $row['berlaku_sampai'] !== '-')
                                                        s/d {{ $row['berlaku_sampai'] }}
                                                    @else
                                                        s/d {{ $row['request_order']['valid_date_formatted'] }}
                                                    @endif
                                                </span>
                                            @endif
                                        </div>

                                        <div class="my-1 w-full border-t border-dashed border-gray-200 dark:border-gray-700/80"></div>

                                        <div
                                            class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-300 ease-in-out dark:border-gray-600 dark:bg-gray-700">
                                            {{-- Lihat Detail --}}
                                            <a href="{{ $row['aksi_url'] }}"
                                                class="group flex h-full items-center justify-center border-r border-blue-800 bg-blue-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:border-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900"
                                                title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                                <span
                                                    class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Detail</span>
                                            </a>                                            {{-- PDF --}}
                                            @if ($row['can_download_pdf'])
                                                <a href="{{ route('sales.quotation.pdf', ['quotation' => $row['id'], 'from' => 'sales_order']) }}"
                                                    target="_blank"
                                                    class="group flex h-full items-center justify-center border-r border-emerald-800 bg-emerald-700 p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 dark:border-emerald-500 dark:bg-emerald-600 dark:hover:bg-emerald-700 dark:focus:ring-emerald-900"
                                                    title="Download PDF">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                                        <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                                        <path d="M10 9H8" />
                                                        <path d="M16 13H8" />
                                                        <path d="M16 17H8" />
                                                    </svg>
                                                    <span
                                                        class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">PDF</span>
                                                </a>
                                            @endif
                                            
                                            @php
                                                $sudahDikirim = in_array($row['status'], [
                                                    'sent_to_warehouse',
                                                    'completed',
                                                    'not_completed',
                                                    'Dikirim ke Gudang',
                                                    'Disetujui Gudang',
                                                    'Selesai',
                                                    'Tidak Selesai',
                                                ]) || ($row['is_sent_to_warehouse'] ?? false);

                                                $canSendToWarehouse = !in_array(strtolower($row['status'] ?? ''), [
                                                    'sent_to_supervisor',
                                                    'waiting_for_supervisor_approval',
                                                    'rejected_by_supervisor',
                                                    'waiting for supervisor approval',
                                                    'rejected by supervisor',
                                                ], true);

                                                $sendToWarehouseRoute = null;
                                                $sendToWarehouseText = null;
                                                $sendToWarehouseButtonText = null;
                                                $sendToWarehouseBtnLabel = 'Send to Warehouse';
                                                $sendToWarehouseBtnClass = 'bg-indigo-700 hover:bg-indigo-800 focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800';

                                                if ($row['type'] === 'sales_order') {
                                                    $sendToWarehouseRoute = route('sales.sales-orders.sent-to-warehouse', $row['id']);
                                                    $sendToWarehouseText = 'Kirim Sales Order ini ke Warehouse?';
                                                    $sendToWarehouseButtonText = 'Ya, Kirim';
                                                } elseif ($row['type'] === 'request_order') {
                                                    $sendToWarehouseRoute = route('sales.quotation.sent-to-warehouse-from-so', $row['id']);
                                                    if (!empty($row['custom_quotation_id'])) {
                                                        $sendToWarehouseText = 'Send this Quotation for Procurement?';
                                                        $sendToWarehouseButtonText = 'Yes, Request';
                                                        $sendToWarehouseBtnLabel = 'Request Procurement';
                                                        $sendToWarehouseBtnClass = 'bg-amber-600 hover:bg-amber-700 focus:ring-amber-300 dark:bg-amber-500 dark:hover:bg-amber-600 dark:focus:ring-amber-800';
                                                    } else {
                                                        $quotModel = \App\Models\Quotation::find($row['id']);
                                                        $hasShortage = $quotModel ? \App\Services\StockAllocationService::hasShortageForQuotation($quotModel) : false;
                                                        if ($hasShortage) {
                                                            $sendToWarehouseText = 'Stok barang kurang. Kirim penawaran ini ke GA Procurement?';
                                                            $sendToWarehouseButtonText = 'Kirim ke Procurement';
                                                            $sendToWarehouseBtnLabel = 'Send to Procurement';
                                                            $sendToWarehouseBtnClass = 'bg-amber-600 hover:bg-amber-700 focus:ring-amber-300 dark:bg-amber-500 dark:hover:bg-amber-600 dark:focus:ring-amber-800';
                                                        } else {
                                                            $sendToWarehouseText = 'Stok barang cukup. Kirim penawaran ini ke Warehouse?';
                                                            $sendToWarehouseButtonText = 'Kirim ke Warehouse';
                                                            $sendToWarehouseBtnLabel = 'Send to Warehouse';
                                                            $sendToWarehouseBtnClass = 'bg-indigo-700 hover:bg-indigo-800 focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800';
                                                        }
                                                    }
                                                }
                                            @endphp

                                            @if (($row['customer_status'] ?? 'active') === 'active' && !$sudahDikirim && $canSendToWarehouse && $sendToWarehouseRoute)
                                                <form method="POST" action="{{ $sendToWarehouseRoute }}"
                                                    data-confirm-text="{{ $sendToWarehouseText }}" data-confirm-button-text="{{ $sendToWarehouseButtonText }}"
                                                    class="approve-form h-full">
                                                    @csrf
                                                    <button type="submit"
                                                        class="group flex h-full cursor-pointer items-center justify-center p-2 text-sm font-medium text-white transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 {{ $sendToWarehouseBtnClass }}">
                                                        @if (!empty($row['custom_quotation_id']))
                                                            <!-- Shopping Cart Icon for Procurement -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                                <circle cx="8" cy="21" r="1"/>
                                                                <circle cx="19" cy="21" r="1"/>
                                                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                                                            </svg>
                                                        @else
                                                            <!-- Box Icon for Warehouse -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                                <path
                                                                    d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                                                                <path d="m3.3 7 8.7 5 8.7-5" />
                                                                <path d="M12 22V12" />
                                                            </svg>
                                                        @endif
                                                        <span
                                                            class="max-w-0 overflow-hidden whitespace-nowrap opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">{{ $sendToWarehouseBtnLabel }}</span>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
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
                                            Coba ubah kata kunci pencarian atau <a href="{{ route('sales.sales-orders.index') }}" class="text-blue-600 hover:underline">reset pencarian</a>
                                        @else
                                            Mulai buat sales order baru dengan klik tombol di atas
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if (!$isSearch && $salesOrders)
                <nav id="pagination-nav"
                    class="sticky bottom-0 z-20 flex shrink-0 flex-col items-start justify-between space-y-3 border-t border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
                    aria-label="Table navigation">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            Menampilkan
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $salesOrders->firstItem() ?? 0 }}-{{ $salesOrders->lastItem() ?? 0 }}</span>
                            dari
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $salesOrders->total() ?? $salesOrders->count() }}</span>
                        </span>
                        <form method="GET" action="{{ route('sales.sales-orders.index') }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <select name="perPage" onchange="this.form.submit()"
                                class="mx-2 rounded-xl border border-gray-300 bg-gray-50 p-1 pl-2 pr-8 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @foreach ([10, 20, 50, 100] as $size)
                                    <option value="{{ $size }}" {{ request('perPage', 20) == $size ? 'selected' : '' }}>
                                        {{ $size }}</option>
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
    </div>

    <!-- Modal Detail Quotation -->
    <div id="quotationModal" class="fixed inset-0 z-50 flex hidden items-center justify-center bg-black/50 p-4">
        <div class="flex max-h-[90vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-800">
            <!-- Header -->
            <div class="flex items-center justify-between bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                <h2 class="text-2xl font-bold">Detail Quotation</h2>
                <button id="closeModal" class="rounded-full p-2 text-white transition hover:bg-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div id="modalContent" class="flex-1 overflow-y-auto p-6">
                <!-- Will be filled by JavaScript -->
            </div>
        </div>
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
                fetch(`{{ route('sales.sales-orders.search') }}?q=${encodeURIComponent(query)}`)
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
            form.action = '{{ route('sales.sales-orders.index') }}';
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
                const typeClass = item.type === 'quotation' ?
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

        function getStatusColor(status) {
            const colors = {
                'pending': {
                    bg: '#fef3c7',
                    text: '#b45309'
                },
                'in_process': {
                    bg: '#dbeafe',
                    text: '#1e40af'
                },
                'shipped': {
                    bg: '#e9d5ff',
                    text: '#6b21a8'
                },
                'completed': {
                    bg: '#dcfce7',
                    text: '#166534'
                },
                'cancelled': {
                    bg: '#fee2e2',
                    text: '#991b1b'
                },
                'draft': {
                    bg: '#f3f4f6',
                    text: '#374151'
                },
                'sent': {
                    bg: '#dbeafe',
                    text: '#1e40af'
                },
                'approved': {
                    bg: '#dcfce7',
                    text: '#166534'
                },
                'rejected': {
                    bg: '#fee2e2',
                    text: '#991b1b'
                },
            };
            return colors[status] || {
                bg: '#f3f4f6',
                text: '#374151'
            };
        }

        function getStatusLabel(status) {
            const labels = {
                'pending': 'Pending',
                'in_process': 'In Process',
                'shipped': 'Shipped',
                'completed': 'Completed',
                'cancelled': 'Cancelled',
                'draft': 'Draft',
                'sent': 'Sent',
                'approved': 'Approved',
                'rejected': 'Rejected',
            };
            return labels[status] || status;
        }

        // Tombol search untuk form submission
        searchBtn.addEventListener('click', function() {
            const query = searchInput.value.trim();
            if (query) {
                const form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('sales.sales-orders.index') }}';
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

        // Modal handling
        const modal = document.getElementById('quotationModal');
        const closeBtn = document.getElementById('closeModal');
        const modalContent = document.getElementById('modalContent');

        closeBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });

        // Function untuk display modal quotation detail
        function showQuotationDetail(quotationId) {
            fetch(`{{ route('sales.sales-orders.quotation-detail') }}?id=${quotationId}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        const data = result.data;
                        const itemsHtml = data.items.map(item => `
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="px-4 py-3">${item.nama_barang}</td>
                                <td class="px-4 py-3 text-center">${item.qty}</td>
                                <td class="px-4 py-3 text-center">${item.satuan}</td>
                                <td class="px-4 py-3 text-right">Rp ${formatCurrency(item.harga)}</td>
                                <td class="px-4 py-3 text-center">${item.diskon}%</td>
                                <td class="px-4 py-3 text-right font-semibold">Rp ${formatCurrency(item.subtotal)}</td>
                            </tr>
                        `).join('');

                        const statusColor = getStatusColor(data.status);

                        const statusLabel = {
                            'draft': 'Draft',
                            'sent': 'Terkirim',
                            'approved': 'Disetujui',
                            'rejected': 'Ditolak',
                        } [data.status] || data.status;

                        modalContent.innerHTML = `
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">No Quotation</h3>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">${data.quotation_number}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal</h3>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">${data.date}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tujuan (To)</h3>
                                    <p class="font-semibold text-gray-900 dark:text-white">${data.to}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Attn (Up)</h3>
                                    <p class="font-semibold text-gray-900 dark:text-white">${data.up || '-'}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email</h3>
                                    <p class="font-semibold text-gray-900 dark:text-white">${data.email}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</h3>
                                    <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold" style="
                                        background-color: ${statusColor.bg};
                                        color: ${statusColor.text};
                                    ">
                                        ${statusLabel}
                                    </span>
                                </div>
                            </div>

                            <hr class="my-6 border-gray-200 dark:border-gray-700">

                            <div class="mb-6">
                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Subject</h3>
                                <p class="text-gray-900 dark:text-white">${data.subject}</p>
                            </div>

                            ${data.intro_text ? `
                                            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Teks Pembuka</h3>
                                                <p class="text-gray-700 dark:text-gray-300">${data.intro_text}</p>
                                            </div>
                                        ` : ''}

                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detail Items</h3>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-100 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-4 py-3 text-left font-semibold text-gray-900 dark:text-white">Barang</th>
                                                <th class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">Qty</th>
                                                <th class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">Satuan</th>
                                                <th class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">Harga</th>
                                                <th class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">Diskon</th>
                                                <th class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${itemsHtml}
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                <div class="flex justify-end gap-8">
                                    <div>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm">Subtotal</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Rp ${formatCurrency(data.subtotal)}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm">PPN (Tax)</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">Rp ${formatCurrency(data.tax)}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm">Grand Total</p>
                                        <p class="text-xl font-bold text-blue-600 dark:text-blue-400">Rp ${formatCurrency(data.grand_total)}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        modal.classList.remove('hidden');
                    }
                });
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US').format(amount);
        }

        function handleUploadImage(input, type, id, imageType) {
            if (!input.files.length) return;
            const file = input.files[0];

            // Validasi tipe file
            if (imageType === 'pdf_po') {
                if (file.type !== 'application/pdf') {
                    Swal.fire({
                        title: 'Format Salah!',
                        text: 'Format file harus PDF',
                        icon: 'error',
                        customClass: {
                            popup: 'rounded-2xl!'
                        }
                    });
                    input.value = '';
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire({
                        title: 'File Terlalu Besar!',
                        text: 'Ukuran file PDF maksimal 5MB',
                        icon: 'error',
                        customClass: {
                            popup: 'rounded-2xl!'
                        }
                    });
                    input.value = '';
                    return;
                }
            } else {
                if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                    Swal.fire({
                        title: 'Format Salah!',
                        text: 'Format file harus JPG, JPEG, atau PNG',
                        icon: 'error',
                        customClass: {
                            popup: 'rounded-2xl!'
                        }
                    });
                    input.value = '';
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        title: 'File Terlalu Besar!',
                        text: 'Ukuran file maksimal 2MB',
                        icon: 'error',
                        customClass: {
                            popup: 'rounded-2xl!'
                        }
                    });
                    input.value = '';
                    return;
                }
            }

            const formData = new FormData();
            let endpoint = '';
            let fieldName = '';

            if (imageType === 'po') {
                endpoint = `/quotation/${id}/upload-image-po`;
                fieldName = 'image_po';
            } else if (imageType === 'pdf_po') {
                endpoint = `/quotation/${id}/upload-pdf-po`;
                fieldName = 'pdf_po';
            } else {
                return;
            }

            formData.append(fieldName, file);
            formData.append('_token', '{{ csrf_token() }}');

            let containerId = '';
            if (imageType === 'po') containerId = `image-po-preview-${id}-${type}`;
            else if (imageType === 'pdf_po') containerId = `pdf-po-preview-${id}-${type}`;
            else containerId = `image-preview-aksi-${id}-${type}`;

            const container = document.getElementById(containerId);
            const originalContent = container.innerHTML;
            container.innerHTML =
                `<div class="flex items-center justify-center p-2"><svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>`;

            fetch(endpoint, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: imageType === 'pdf_po' ? 'PDF berhasil diupload.' : 'Gambar berhasil diupload.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'rounded-2xl!'
                            }
                        });

                        if (imageType === 'pdf_po') {
                            container.innerHTML = `
                            <div class="relative inline-block group">
                                <a href="${data.image_url}" target="_blank">
                                    <div class="flex h-10 w-10 items-center justify-center rounded border border-red-300 bg-red-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                        </svg>
                                    </div>
                                </a>
                                <button class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg" onclick="handleDeleteImage('${type}', ${id}, '${imageType}')" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                </button>
                            </div>`;
                            const pdfUploadButton = document.getElementById(`upload-pdf-po-button-${id}-${type}`);
                            if (pdfUploadButton) pdfUploadButton.style.display = 'none';
                        } else if (imageType === 'po' || imageType === 'so') {
                            container.innerHTML = `
                            <div class="relative inline-block group">
                                <a href="${data.image_url}" target="_blank">
                                    <img src="${data.image_url}" alt="${imageType.toUpperCase()} Image" class="w-10 h-10 object-cover rounded border border-gray-300 shadow-sm transition-transform hover:scale-110" />
                                </a>
                                <button class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg" onclick="handleDeleteImage('${type}', ${id}, '${imageType}')" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                </button>
                            </div>`;
                            if (imageType === 'po') {
                                const poUploadButton = document.getElementById(`upload-po-button-${id}-${type}`);
                                if (poUploadButton) poUploadButton.style.display = 'none';
                            }
                        } else {
                            container.innerHTML = `
                            <div class="relative inline-block group">
                                <a href="${data.image_url}" target="_blank">
                                    <img src="${data.image_url}" alt="Image" class="w-8 h-8 object-cover rounded border border-gray-300 shadow-sm" />
                                </a>
                                <button class="absolute -top-1.5 -right-1.5 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-sm" onclick="handleDeleteImage('${type}', ${id}, 'main')" title="Ganti">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                </button>
                            </div>`;
                        }
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message || 'Upload gagal',
                            icon: 'error',
                            customClass: {
                                popup: 'rounded-2xl!'
                            }
                        });
                        container.innerHTML = originalContent;
                    }
                })
                .catch(() => {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan sistem',
                        icon: 'error',
                        customClass: {
                            popup: 'rounded-2xl!'
                        }
                    });
                    container.innerHTML = originalContent;
                });
        }

        function saveNoPO(id, value) {
            const trimmedValue = (value || '').trim();
            const inputEl = document.getElementById(`no-po-input-${id}`);

            if (!inputEl) return;

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('no_po', trimmedValue);

            fetch(`/quotation/${id}/update-no-po`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const salesOrderEl = document.getElementById(`sales-order-number-${id}`);
                        if (salesOrderEl) {
                            salesOrderEl.textContent = data.sales_order_number || '-';
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Tersimpan!',
                            text: data.sales_order_number ? `No.PO berhasil disimpan dan No. SO ${data.sales_order_number} dibuat.` : 'No.PO berhasil diperbarui.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            customClass: {
                                popup: 'rounded-2xl!'
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message || 'Tidak dapat menyimpan No.PO',
                            icon: 'error',
                            customClass: {
                                popup: 'rounded-2xl!'
                            }
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan sistem saat menyimpan No.PO',
                        icon: 'error',
                        customClass: {
                            popup: 'rounded-2xl!'
                        }
                    });
                });
        }

        function handleDeleteImage(type, id, imageType) {
            window.confirmDelete(() => {
                let endpoint = '';
                if (imageType === 'po') endpoint = `/quotation/${id}/upload-image-po`;
                else if (imageType === 'pdf_po') endpoint = `/quotation/${id}/upload-pdf-po`;
                else endpoint = `/sales-orders/${id}/upload-image`;

                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'DELETE');

                fetch(endpoint, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: 'File telah dihapus.',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'rounded-2xl!'
                                }
                            });

                            let containerId = '';
                            if (imageType === 'po') containerId = `image-po-preview-${id}-${type}`;
                            else if (imageType === 'pdf_po') containerId = `pdf-po-preview-${id}-${type}`;
                            else containerId = `image-preview-aksi-${id}-${type}`;

                            const container = document.getElementById(containerId);

                            if (imageType === 'po') {
                                container.innerHTML = '';
                                const poUploadButton = document.getElementById(`upload-po-button-${id}-${type}`);
                                if (poUploadButton) poUploadButton.style.display = '';
                            } else if (imageType === 'pdf_po') {
                                container.innerHTML = '';
                                const pdfUploadButton = document.getElementById(`upload-pdf-po-button-${id}-${type}`);
                                if (pdfUploadButton) pdfUploadButton.style.display = '';
                            } else {
                                container.innerHTML = `
                                <label class="cursor-pointer inline-flex items-center gap-1 px-2 py-1 bg-white border border-gray-300 text-gray-700 rounded-md text-[9px] font-semibold hover:bg-gray-50 transition-colors shadow-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                                    Gambar
                                    <input type="file" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="handleUploadImage(this, '${type}', ${id}, 'main')">
                                </label>`;
                            }
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: data.message || 'Gagal menghapus file',
                                icon: 'error',
                                customClass: {
                                    popup: 'rounded-2xl!'
                                }
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan sistem',
                            icon: 'error',
                            customClass: {
                                popup: 'rounded-2xl!'
                            }
                        });
                    });
            });
        }
    </script>

    @vite(['resources/js/table-sort.js'])
</x-app-layout>
