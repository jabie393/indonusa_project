<x-app-layout>
    <div class="flex flex-col lg:h-[calc(100vh-112px)] overflow-hidden">
        <div class="shrink-0">
            @if (session('title'))
                <div
                    class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 dark:border-green-900 dark:bg-green-900/30">
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


        <div
            class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm overflow-show relative mb-5 flex justify-between overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800 shrink-0">
            <div class="flex items-center p-3">
                <div
                    class="flex w-full shrink-0 flex-col items-stretch justify-end space-y-2 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0">

                    <button type="button" id="btn-filter"
                        class="flex cursor-pointer flex-row items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 text-sm font-semibold text-white shadow transition-all duration-200 hover:bg-[#19426d] focus:outline-none focus:ring-2 focus:ring-[#225A97]/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Filter & Export
                    </button>

                </div>
            </div>

            <div class="p-3">
                {{-- Search --}}
                <form action="{{ route('sales-order-invoices.index') }}" method="GET" class="flex flex-col gap-2 md:flex-row" data-realtime-table-search data-search-input="#searchInput"
                    data-search-target="#tableContainer" data-pagination-target="#pagination-nav" data-extra-fields="#pagination-nav select[name='perPage']">
                    <!-- Forward current filters -->
                    @foreach (request()->except(['search', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                    @endforeach
                    <div class="relative flex-1">
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
                            <input type="text" name="search" id="searchInput"
                                placeholder="Cari berdasarkan No.SO, Customer, Subject, atau Email..."
                                value="{{ $search }}" autocomplete="off"
                                class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500">
                        </div>
                        <!-- Search Results Dropdown -->
                        <div id="searchResults"
                            class="z-99 absolute left-0 right-0 top-full mt-1 hidden max-h-96 overflow-y-auto rounded-lg border border-gray-300 bg-white shadow-lg dark:border-gray-500 dark:bg-gray-600">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @if ($search)
                            <a href="{{ route('sales-order-invoices.index') }}"
                                class="whitespace-nowrap rounded-lg border border-gray-300 px-6 py-2 font-semibold text-gray-700 hover:bg-gray-100 dark:border-gray-500 dark:text-gray-300 dark:hover:bg-gray-600">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Filter Panel -->
        <div id="filter-panel"
            class="collapse mb-0 shrink-0 rounded-2xl border-0 bg-white shadow-none transition-all duration-300 dark:bg-gray-800 [&.collapse-open]:mb-5 [&.collapse-open]:border [&.collapse-open]:border-gray-100 [&.collapse-open]:shadow-md [&.collapse-open]:dark:border-gray-700/50">
            <div class="collapse-content !p-0">
                <div class="p-5">
                    <form id="filter-form" action="{{ route('sales-order-invoices.index') }}" method="GET">
                        <!-- Forward current search parameter -->
                        <input type="hidden" name="search" value="{{ request('search') }}" />

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">

                            <!-- Report Type Filter -->
                            <div class="flex flex-col gap-1.5">
                                <label for="report_type" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tipe Laporan</label>
                                <select name="report_type" id="report_type"
                                    class="rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    <option value="all" {{ request('report_type') === 'all' ? 'selected' : '' }}>Semua Tipe</option>
                                    <option value="quotation" {{ request('report_type') === 'quotation' ? 'selected' : '' }}>Standard Quotation</option>
                                    <option value="custom_quotation" {{ request('report_type') === 'custom_quotation' ? 'selected' : '' }}>Custom Quotation</option>
                                </select>
                            </div>

                            <!-- Periode Type Filter -->
                            <div class="flex flex-col gap-1.5">
                                <label for="periode_type" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Jenis Periode</label>
                                <select name="periode_type" id="periode_type"
                                    class="rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    <option value="all" {{ request('periode_type', 'all') === 'all' ? 'selected' : '' }}>Semua Periode</option>
                                    <option value="daily" {{ request('periode_type') === 'daily' ? 'selected' : '' }}>Harian (Daily)</option>
                                    <option value="weekly" {{ request('periode_type') === 'weekly' ? 'selected' : '' }}>Mingguan (Weekly)</option>
                                    <option value="monthly" {{ request('periode_type') === 'monthly' ? 'selected' : '' }}>Bulanan (Monthly)</option>
                                    <option value="yearly" {{ request('periode_type') === 'yearly' ? 'selected' : '' }}>Tahunan (Yearly)</option>
                                    <option value="custom" {{ request('periode_type') === 'custom' ? 'selected' : '' }}>Custom Range</option>
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div class="flex flex-col gap-1.5">
                                <label for="status" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Status Transaksi</label>
                                <select name="status" id="status"
                                    class="rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="not_completed" {{ request('status') === 'not_completed' ? 'selected' : '' }}>Partial Delivery</option>
                                    <option value="rejected_supervisor" {{ request('status') === 'rejected_supervisor' ? 'selected' : '' }}>Rejected by Supervisor</option>
                                    <option value="rejected_warehouse" {{ request('status') === 'rejected_warehouse' ? 'selected' : '' }}>Rejected by Warehouse</option>
                                    <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Canceled</option>
                                    <option value="partial_canceled" {{ request('status') === 'partial_canceled' ? 'selected' : '' }}>Partially Canceled</option>
                                </select>
                            </div>

                        </div>

                        <!-- Dynamic Period Fields Container -->
                        <div class="mt-4 grid grid-cols-1 gap-4 border-t border-dashed border-slate-100 pt-4 dark:border-slate-700 md:grid-cols-4">

                            <!-- Daily Fields -->
                            <div id="period-fields-daily" class="period-field-group col-span-2 flex hidden flex-col gap-1.5">
                                <label for="date" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Pilih Tanggal</label>
                                <input type="date" name="date" id="date" value="{{ request('date', date('Y-m-d')) }}"
                                    class="rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                            </div>

                            <!-- Weekly Fields -->
                            <div id="period-fields-weekly" class="period-field-group col-span-3 grid hidden grid-cols-3 gap-3">
                                <div class="flex flex-col gap-1.5">
                                    <label for="week" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Minggu Ke-</label>
                                    <select name="week" id="week"
                                        class="rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        @foreach ([1, 2, 3, 4, 5] as $w)
                                            <option value="{{ $w }}" {{ request('week', 1) == $w ? 'selected' : '' }}>Minggu Ke-{{ $w }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="weekly_month" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Bulan</label>
                                    <select name="month" id="weekly_month"
                                        class="rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        @foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $num => $name)
                                            <option value="{{ $num }}" {{ request('month', date('n')) == $num ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="weekly_year" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tahun</label>
                                    <select name="year" id="weekly_year"
                                        class="rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        @for ($y = date('Y'); $y >= 2020; $y--)
                                            <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <!-- Monthly Fields -->
                            <div id="period-fields-monthly" class="period-field-group col-span-2 grid hidden grid-cols-2 gap-3">
                                <div class="flex flex-col gap-1.5">
                                    <label for="monthly_month" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Bulan</label>
                                    <select id="monthly_month"
                                        class="monthly-sync rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        @foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $num => $name)
                                            <option value="{{ $num }}" {{ request('month', date('n')) == $num ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="monthly_year" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tahun</label>
                                    <select id="monthly_year"
                                        class="monthly-sync rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        @for ($y = date('Y'); $y >= 2020; $y--)
                                            <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <!-- Yearly Fields -->
                            <div id="period-fields-yearly" class="period-field-group col-span-2 flex hidden flex-col gap-1.5">
                                <label for="yearly_year" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tahun</label>
                                <select id="yearly_year"
                                    class="yearly-sync rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    @for ($y = date('Y'); $y >= 2020; $y--)
                                        <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Custom Fields -->
                            <div id="period-fields-custom" class="period-field-group col-span-2 grid hidden grid-cols-2 gap-3">
                                <div class="flex flex-col gap-1.5">
                                    <label for="start_date" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Mulai Tanggal</label>
                                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date', date('Y-m-01')) }}"
                                        class="rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label for="end_date" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Sampai Tanggal</label>
                                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date', date('Y-m-d')) }}"
                                        class="rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                                </div>
                            </div>

                            <!-- Hidden Fields to store synchronized values -->
                            <input type="hidden" name="month" id="month_val" value="{{ request('month', date('n')) }}" />
                            <input type="hidden" name="year" id="year_val" value="{{ request('year', date('Y')) }}" />

                            <!-- Submit, Reset & Export Actions -->
                            <div class="col-span-2 flex items-end justify-between gap-2 md:col-start-3">
                                <div class="flex gap-3">
                                    <a href="{{ route('sales-order-invoices.index') }}"
                                        class="flex w-fit flex-row items-center rounded-xl bg-gray-100 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition duration-150 hover:bg-gray-200">
                                        <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                            <path d="M3 3v5h5"></path>
                                        </svg>
                                        Reset</a>
                                    <button type="submit"
                                        class="w-fit flex flex-row items-center rounded-xl bg-[#225A97] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition duration-150 hover:bg-[#19426d]">
                                        <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                        </svg>
                                        Filter
                                    </button>
                                </div>

                                <div class="flex gap-3">
                                    <button type="button" id="btn-export-excel"
                                        class="flex w-fit cursor-pointer flex-row items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow transition duration-200 hover:bg-emerald-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Export Excel
                                    </button>
                                    <button type="button" id="btn-export-pdf"
                                        class="flex w-fit cursor-pointer flex-row items-center justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow transition duration-200 hover:bg-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Export PDF
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div
            class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative flex flex-1 min-h-0 flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
            <div class="flex shrink-0 items-center justify-between rounded-t-2xl bg-[#225A97] p-[1rem] text-white">
            </div>

            <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
                <table class="sortable w-full">
                    <thead
                        class="sticky top-0 z-30 text-nowrap border-b border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-700">
                        <tr>
                            <th
                                class="text-nowrap px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                No. Dokumen</th>
                            <th
                                class="text-nowrap px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Customer, Item & Total</th>
                            <th
                                class="text-nowrap px-4 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Status</th>
                            <th class="text-nowrap px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-300"
                                data-type="date">Tanggal</th>
                            <th
                                class="no-sort text-nowrap px-6 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-nowrap">
                        @forelse ($results as $row)
                            <tr
                                class="border-b border-gray-200 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:hover:bg-gray-700">
                                <td class="whitespace-nowrap px-4 py-3.5 text-gray-900 dark:text-white">
                                    <div>
                                        <a href="{{ route('invoice.index', $row['id']) }}?type={{ $row['type'] }}"
                                            target="_self"
                                            class="js-invoice-history font-bold text-[#225A97] hover:underline dark:text-blue-400"
                                            data-id="{{ $row['id'] }}"
                                            data-order-number="{{ $row['no_sales_order'] ?? '-' }}"
                                            data-has-batches="{{ $row['has_batches'] ? 'true' : 'false' }}"
                                            data-invoice-url="{{ route('invoice.index', $row['id']) }}?type={{ $row['type'] }}"
                                            data-history-url="{{ route('sales-order-invoices.invoice-history', $row['id']) }}">
                                            {{ $row['no_sales_order'] ?? '-' }}
                                        </a>
                                    </div>
                                    <div
                                        class="mt-1 flex items-center text-xs font-normal text-gray-500 dark:text-gray-400">
                                        <span class="w-8 text-gray-400 dark:text-gray-500">PNW</span>
                                        <span class="mx-1.5 font-bold text-gray-300 dark:text-gray-600">·</span>
                                        <span>{{ $row['no_quotation'] ?? '-' }}</span>
                                    </div>
                                    <div
                                        class="mt-0.5 flex items-center text-xs font-normal text-gray-500 dark:text-gray-400">
                                        <span class="w-8 text-gray-400 dark:text-gray-500">REQ</span>
                                        <span class="mx-1.5 font-bold text-gray-300 dark:text-gray-600">·</span>
                                        <span>{{ $row['no_request'] ?? '-' }}</span>
                                    </div>
                                    <div
                                        class="mt-0.5 flex items-center gap-1.5 text-xs font-normal text-gray-500 dark:text-gray-400">
                                        <span class="w-8 text-gray-400 dark:text-gray-500">PO</span>
                                        <span class="font-bold text-gray-300 dark:text-gray-600">·</span>
                                        <span>{{ $row['no_po'] ?? '-' }}</span>
                                        @if (!empty($row['image_po']))
                                            <a href="{{ Storage::url($row['image_po']) }}" target="_blank"
                                                class="ml-1 inline-flex items-center text-blue-500 hover:text-blue-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image">
                                                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                                                    <circle cx="9" cy="9" r="2" />
                                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                                                </svg>
                                            </a>
                                        @endif
                                        @if (!empty($row['pdf_po']))
                                            <a href="{{ Storage::url($row['pdf_po']) }}" target="_blank"
                                                class="ml-1 inline-flex items-center text-red-500 hover:text-red-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-file-text">
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
                                <td class="px-4 py-3.5 align-middle text-gray-900 dark:text-white">
                                    <div class="flex flex-col gap-2.5">
                                        <!-- Customer Details -->
                                        <div class="flex flex-col gap-1">
                                            <span class="text-base font-bold text-slate-900 dark:text-white">
                                                {{ $row['customer_name'] ?? '-' }}
                                            </span>
                                            @if (!empty($row['first_pic_name']))
                                                <span
                                                    class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-3.5 w-3.5 text-slate-400 dark:text-slate-500"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4"></circle>
                                                    </svg>
                                                    <span class="font-medium">{{ $row['first_pic_name'] }}</span>
                                                    @if (!empty($row['first_pic_position']))
                                                        <span class="text-slate-300 dark:text-slate-600">•</span>
                                                        <span
                                                            class="text-slate-400 dark:text-slate-500">{{ $row['first_pic_position'] }}</span>
                                                    @endif
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Divider -->
                                        <div class="border-t border-dashed border-gray-200 dark:border-gray-700/80"></div>

                                        <!-- Total & Items Summary -->
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                            <span class="text-base font-bold text-[#0067B1] dark:text-[#2798e6]">
                                                Rp {{ number_format($row['total'] ?? 0, 0, '.', ',') }}
                                            </span>
                                            <span
                                                class="inline-flex items-center gap-1 rounded bg-slate-100 px-1.5 py-0.5 text-xs font-medium text-slate-600 dark:bg-gray-800 dark:text-gray-400">
                                                {{ $row['jumlah_item'] ?? 0 }} {{ ($row['jumlah_item'] ?? 0) > 1 ? 'items' : 'item' }}
                                            </span>
                                            @if (!empty($row['diskon']) && $row['diskon'] > 0)
                                                <span
                                                    class="inline-flex items-center gap-0.5 rounded border border-amber-200 bg-amber-50 px-1.5 py-0.5 text-[10px] font-bold text-amber-700 dark:border-amber-800/50 dark:bg-amber-950/30 dark:text-amber-400">
                                                    % {{ $row['diskon'] }}%
                                                </span>
                                            @endif
                                        </div>
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
                                    <span
                                        class="{{ $badgeBg }} {{ $badgeText }} {{ $badgeBorder }} flex items-center justify-center rounded-full px-2 py-1 text-xs font-semibold">
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
                                        <div
                                            class="mt-1 flex items-center text-xs font-normal text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-calendar mr-1.5 shrink-0 text-gray-400 dark:text-gray-500">
                                                <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                                <line x1="16" x2="16" y1="2" y2="6" />
                                                <line x1="8" x2="8" y1="2" y2="6" />
                                                <line x1="3" x2="21" y1="10" y2="10" />
                                            </svg>
                                            <span>s/d {{ $formattedExpiry }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('invoice.index', $row['id']) }}?type={{ $row['type'] }}"
                                        target="_self"
                                        class="js-invoice-history group inline-flex items-center rounded-lg bg-green-600 p-2 text-xs font-semibold text-white transition-all duration-300 ease-in-out hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                        data-id="{{ $row['id'] }}" data-order-number="{{ $row['no_sales_order'] ?? '-' }}"
                                        data-has-batches="{{ $row['has_batches'] ? 'true' : 'false' }}"
                                        data-invoice-url="{{ route('invoice.index', $row['id']) }}?type={{ $row['type'] }}"
                                        data-history-url="{{ route('sales-order-invoices.invoice-history', $row['id']) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path
                                                d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
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
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="mx-auto mb-4 text-gray-400 dark:text-gray-600">
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
                                            Coba ubah kata kunci pencarian atau <a
                                                href="{{ route('sales-order-invoices.index') }}"
                                                class="text-blue-600 hover:underline">reset pencarian</a>
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
                <nav id="pagination-nav" class="sticky bottom-0 z-20 flex flex-col items-start justify-between space-y-3 bg-white p-4 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
                    aria-label="Table navigation">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            Menampilkan
                            <span
                                class="font-semibold text-gray-900 dark:text-white">{{ $salesOrders->firstItem() ?? 0 }}-{{ $salesOrders->lastItem() ?? 0 }}</span>
                            dari
                                <span
                                    class="font-semibold text-gray-900 dark:text-white">{{ $salesOrders->total() ?? $salesOrders->count() }}</span>
                            </span>
                            <form method="GET" action="{{ route('sales-order-invoices.index') }}">
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
                            {{ $salesOrders->links() }}
                        </div>
                    </nav>
            @endif
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        const searchForm = searchInput?.closest('form');
        let searchTimeout;

        // Autocomplete search dengan AJAX
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 1) {
                searchResults.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('sales-order-invoices.search') }}?q=${encodeURIComponent(query)}`)
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

            searchForm?.requestSubmit();
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

        // Enter key untuk search
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm?.requestSubmit();
            }
        });

        // Close dropdown ketika klik di luar
        document.addEventListener('click', function (e) {
            if (!e.target.closest('#searchInput') && !e.target.closest('#searchResults')) {
                searchResults.classList.add('hidden');
            }
        });
    </script>
    @include('admin.sales-order-invoices.partials.invoice-history-modal')

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

            // Filter Panel & Export Scripts
            const periodeSelect = document.getElementById('periode_type');
            const monthlyMonth = document.getElementById('monthly_month');
            const monthlyYear = document.getElementById('monthly_year');
            const weeklyMonth = document.getElementById('weekly_month');
            const weeklyYear = document.getElementById('weekly_year');
            const yearlyYear = document.getElementById('yearly_year');

            const monthVal = document.getElementById('month_val');
            const yearVal = document.getElementById('year_val');

            function syncValueInputs() {
                if (!periodeSelect || !monthVal || !yearVal) return;
                const currentPeriod = periodeSelect.value;
                if (currentPeriod === 'weekly') {
                    if (weeklyMonth) monthVal.value = weeklyMonth.value;
                    if (weeklyYear) yearVal.value = weeklyYear.value;
                } else if (currentPeriod === 'monthly') {
                    if (monthlyMonth) monthVal.value = monthlyMonth.value;
                    if (monthlyYear) yearVal.value = monthlyYear.value;
                } else if (currentPeriod === 'yearly') {
                    if (yearlyYear) yearVal.value = yearlyYear.value;
                }
            }

            if (weeklyMonth) weeklyMonth.addEventListener('change', syncValueInputs);
            if (weeklyYear) weeklyYear.addEventListener('change', syncValueInputs);
            if (monthlyMonth) monthlyMonth.addEventListener('change', syncValueInputs);
            if (monthlyYear) monthlyYear.addEventListener('change', syncValueInputs);
            if (yearlyYear) yearlyYear.addEventListener('change', syncValueInputs);

            function togglePeriodFields() {
                if (!periodeSelect) return;
                const value = periodeSelect.value;

                document.querySelectorAll('.period-field-group').forEach(group => {
                    group.classList.add('hidden');
                });

                const targetGroup = document.getElementById('period-fields-' + value);
                if (targetGroup) {
                    targetGroup.classList.remove('hidden');
                }

                syncValueInputs();
            }

            if (periodeSelect) {
                periodeSelect.addEventListener('change', togglePeriodFields);
                togglePeriodFields();
            }

            const btnFilter = document.getElementById('btn-filter');
            const filterPanel = document.getElementById('filter-panel');
            if (btnFilter && filterPanel) {
                btnFilter.addEventListener('click', function(e) {
                    e.preventDefault();
                    filterPanel.classList.toggle('collapse-open');
                });
            }

            const btnExportExcel = document.getElementById('btn-export-excel');
            if (btnExportExcel) {
                btnExportExcel.addEventListener('click', function(e) {
                    e.preventDefault();
                    syncValueInputs();
                    const form = document.getElementById('filter-form');
                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData).toString();
                    window.location.href = "{{ route('sales-order-invoices.export') }}?" + params;
                });
            }

            const btnExportPdf = document.getElementById('btn-export-pdf');
            if (btnExportPdf) {
                btnExportPdf.addEventListener('click', function(e) {
                    e.preventDefault();
                    syncValueInputs();
                    const form = document.getElementById('filter-form');
                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData).toString();
                    window.open("{{ route('sales-order-invoices.pdf') }}?" + params, '_blank');
                });
            }
        });
    </script>
    @vite(['resources/js/realtime-table-search.js', 'resources/js/table-sort.js'])
</x-app-layout>
