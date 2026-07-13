<x-app-layout>
    <div class="flex flex-col overflow-hidden lg:h-[calc(100vh-112px)]">

        <!-- Topbar Page Header -->
        <div
            class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex h-16 shrink-0 items-center justify-between overflow-hidden rounded-2xl bg-white px-4 shadow-md dark:bg-gray-800">

            <div class="flex items-center gap-2 md:gap-3">
                <button type="button" id="btn-filter"
                    class="flex cursor-pointer flex-row items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 text-sm font-semibold text-white shadow transition-all duration-200 hover:bg-[#19426d] focus:outline-none focus:ring-2 focus:ring-[#225A97]/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Filter & Export
                </button>
            </div>

            <div>
                {{-- Search --}}
                <form action="{{ route('sales-report.index') }}" method="GET" class="block pl-2">
                    <!-- Forward current filters -->
                    @foreach (request()->except(['search', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                    @endforeach
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
                        <input type="search" name="search" id="topbar-search"
                            value="{{ request('search') }}"
                            class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-[#225A97] focus:ring-[#225A97] dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            placeholder="Cari..." />
                    </div>
                </form>
            </div>
        </div>

        <!-- Filter Panel -->
        <div id="filter-panel"
            class="collapse mb-0 shrink-0 rounded-2xl border-0 bg-white shadow-none transition-all duration-300 dark:bg-gray-800 [&.collapse-open]:mb-5 [&.collapse-open]:border [&.collapse-open]:border-gray-100 [&.collapse-open]:shadow-md [&.collapse-open]:dark:border-gray-700/50">
            <div class="collapse-content !p-0">
                <div class="p-5">
                    <form id="filter-form" action="{{ route('sales-report.index') }}" method="GET">
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
                                    <option value="daily" {{ request('periode_type') === 'daily' ? 'selected' : '' }}>Harian (Daily)</option>
                                    <option value="weekly" {{ request('periode_type') === 'weekly' ? 'selected' : '' }}>Mingguan (Weekly)</option>
                                    <option value="monthly" {{ request('periode_type', 'monthly') === 'monthly' ? 'selected' : '' }}>Bulanan (Monthly)</option>
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
                                    <option value="belum_diproses" {{ request('status') === 'belum_diproses' ? 'selected' : '' }}>Belum Diproses</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending / Waiting Approval</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved / Open</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="sent_to_warehouse" {{ request('status') === 'sent_to_warehouse' ? 'selected' : '' }}>Sent to Warehouse</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial Delivery</option>
                                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired (Custom Only)</option>
                                </select>
                            </div>

                            <!-- Sales Filter (Disabled for role Sales) -->
                            <div class="flex flex-col gap-1.5">
                                <label for="sales_id" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Pilih Sales Agent</label>
                                @php
                                    $user = Auth::user();
                                    $isSales = trim(strtolower($user->role ?? '')) === 'sales';
                                @endphp
                                @if ($isSales)
                                    <select name="sales_id" id="sales_id" disabled
                                        class="rounded-xl border border-gray-300 bg-gray-100 p-2.5 text-sm text-gray-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                        <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                                    </select>
                                    <input type="hidden" name="sales_id" value="{{ $user->id }}" />
                                @else
                                    <select name="sales_id" id="sales_id"
                                        class="rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option value="">Semua Sales Agent</option>
                                        @foreach ($salesUsers as $salesUser)
                                            <option value="{{ $salesUser->id }}" {{ request('sales_id') == $salesUser->id ? 'selected' : '' }}>{{ $salesUser->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
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

                            <!-- Monthly Fields (uses same month/year parameters, only shows dropdowns) -->
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
                            <input type="hidden" name="month_val" id="month_val" value="{{ request('month', date('n')) }}" />
                            <input type="hidden" name="year_val" id="year_val" value="{{ request('year', date('Y')) }}" />



                            <!-- Submit & Reset Actions -->
                            <div class="col-span-2 flex items-end justify-between gap-2 md:col-start-3">
                                <div class="flex gap-3">
                                    <a href="{{ route('sales-report.index') }}"
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

        <!-- Table Container Card -->
        <div class="relative flex min-h-0 flex-1 flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
            <div class="flex shrink-0 flex-wrap gap-y-2 items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-3 px-4">
                <span class="text-sm font-bold uppercase tracking-wider text-white">Daftar Dokumen Penawaran</span>
                <div class="flex items-center gap-3 text-white text-xs">
                    <div class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg border border-white/10">
                        <span class="text-blue-100">Jumlah Transaksi:</span>
                        <span class="font-extrabold text-sm text-amber-300">{{ number_format($totalCount) }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg border border-white/10">
                        <span class="text-emerald-100">Total Kinerja Sales:</span>
                        <span class="font-extrabold text-sm text-emerald-300">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
                <table class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="sticky top-0 z-10 bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="text-nowrap p-4">No. Dokumen</th>
                            <th scope="col" class="text-nowrap p-4">Sales Agent</th>
                            <th scope="col" class="text-nowrap p-4">Customer</th>
                            <th scope="col" class="text-nowrap p-4">Perihal / Subject</th>
                            <th scope="col" class="text-nowrap p-4">Tanggal Buat</th>
                            <th scope="col" class="text-nowrap p-4 text-right">Nilai Penawaran</th>
                            <th scope="col" class="text-nowrap p-4 text-center">Tipe</th>
                            <th scope="col" class="text-nowrap p-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-nowrap">
                        @forelse($results as $index => $row)
                            @php
                                $statusData = \App\Http\Controllers\Admin\SalesReportController::getStatusDetails($row->type, $row->order_status, $row->direct_status, $row->custom_quotation_id);
                                $rowNumber = $results->firstItem() + $index;
                            @endphp
                            <tr class="border-b border-gray-100 align-top hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/30">
                                <td class="p-4 align-middle">
                                    <div class="flex flex-col gap-1">
                                        @if ($row->type === 'Standard Quotation')
                                            <a href="{{ route('sales.quotation.show', $row->id) }}" class="text-sm font-bold text-[#225A97] hover:underline dark:text-blue-400">
                                                {{ $row->quotation_number ?? '-' }}
                                            </a>
                                        @else
                                            <a href="{{ route('sales.custom-quotation.show', $row->id) }}" class="text-sm font-bold text-[#225A97] hover:underline dark:text-blue-400">
                                                {{ $row->quotation_number ?? '-' }}
                                            </a>
                                        @endif
                                        <div class="grid grid-cols-[32px_1fr] gap-x-2 text-xs leading-relaxed mt-1">
                                            <span class="font-semibold uppercase text-slate-400">SO</span>
                                            <span class="text-slate-600 dark:text-slate-300">{{ $row->sales_order_number ?? '-' }}</span>
                                            <span class="font-semibold uppercase text-slate-400">DO</span>
                                            <span class="text-slate-600 dark:text-slate-300">{{ $row->order_number ?? '-' }}</span>
                                            <span class="font-semibold uppercase text-slate-400">PO</span>
                                            <span class="text-slate-600 dark:text-slate-300">{{ $row->no_po ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 align-middle font-semibold text-slate-800 dark:text-slate-200">{{ $row->sales_name ?? '-' }}</td>
                                <td class="max-w-xs whitespace-normal p-4 align-middle font-bold text-slate-800 dark:text-slate-200">{{ $row->customer_name ?? '-' }}</td>
                                <td class="max-w-sm whitespace-normal p-4 align-middle text-slate-600 dark:text-slate-400">{{ $row->subject ?? '-' }}</td>
                                <td class="p-4 align-middle text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i') }}</td>
                                <td class="p-4 text-right align-middle text-slate-900 dark:text-white">
                                    <div class="flex flex-col items-end gap-1">
                                        @foreach($row->items as $item)
                                            @php
                                                $taxRate = ($row->subtotal > 0) ? ($row->tax / $row->subtotal) : 0.11;
                                                $priceWithTax = $item->price * (1 + $taxRate);
                                            @endphp
                                            <div class="flex items-center gap-1.5 justify-end">
                                                <span class="text-sm font-bold text-[#0067B1] dark:text-blue-400">
                                                    Rp {{ number_format($priceWithTax, 0, ',', '.') }}
                                                </span>
                                                <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600 dark:bg-slate-700 dark:text-slate-300">
                                                    {{ $item->qty }} {{ $item->qty > 1 ? 'items' : 'item' }}
                                                </span>
                                                @if($item->discount > 0)
                                                    <span class="inline-flex items-center rounded-lg border border-emerald-500 bg-emerald-50/50 px-1.5 py-0.5 text-[10px] font-semibold text-emerald-600 dark:bg-emerald-950/20 dark:text-emerald-400">
                                                        {{ $item->discount }}%
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                        <div class="text-xs text-slate-400 dark:text-slate-500 mt-1 border-t border-dashed border-slate-100 pt-1 dark:border-slate-700 w-full text-right">
                                            Total: <span class="font-extrabold text-slate-800 dark:text-slate-200 text-sm">Rp {{ number_format($row->grand_total, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-center align-middle">
                                    @if ($row->type === 'Standard Quotation')
                                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">Standard</span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-semibold text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">Custom</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center align-middle">
                                    <span class="{{ $statusData['class'] }} inline-flex items-center justify-center rounded-full border px-2.5 py-1 text-xs font-semibold">
                                        {{ $statusData['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-8 text-center font-bold text-slate-400 dark:text-slate-500">Tidak ada data penawaran sales yang ditemukan untuk filter aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Pagination and Navigation -->
            <nav id="pagination-nav"
                class="sticky bottom-0 z-20 flex shrink-0 flex-col items-start justify-between space-y-3 border-t border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
                aria-label="Table navigation">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        Menampilkan
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $results->firstItem() ?? 0 }}-{{ $results->lastItem() ?? 0 }}</span>
                        dari
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $results->total() }}</span>
                    </span>
                    <form method="GET" action="{{ route('sales-report.index') }}">
                        @foreach (request()->except(['perPage', 'page']) as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                        @endforeach
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
                    {{ $results->links() }}
                </div>
            </nav>

        </div>

    </div>

    <!-- Period Fields Toggling Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const periodeSelect = document.getElementById('periode_type');

            // Monthly Select Sync
            const monthlyMonth = document.getElementById('monthly_month');
            const monthlyYear = document.getElementById('monthly_year');
            const weeklyMonth = document.getElementById('weekly_month');
            const weeklyYear = document.getElementById('weekly_year');
            const yearlyYear = document.getElementById('yearly_year');

            const monthVal = document.getElementById('month_val');
            const yearVal = document.getElementById('year_val');

            function syncValueInputs() {
                const currentPeriod = periodeSelect.value;
                if (currentPeriod === 'weekly') {
                    monthVal.value = weeklyMonth.value;
                    yearVal.value = weeklyYear.value;
                } else if (currentPeriod === 'monthly') {
                    monthVal.value = monthlyMonth.value;
                    yearVal.value = monthlyYear.value;
                } else if (currentPeriod === 'yearly') {
                    yearVal.value = yearlyYear.value;
                }
            }

            // Bind sync on change
            weeklyMonth.addEventListener('change', syncValueInputs);
            weeklyYear.addEventListener('change', syncValueInputs);
            monthlyMonth.addEventListener('change', syncValueInputs);
            monthlyYear.addEventListener('change', syncValueInputs);
            yearlyYear.addEventListener('change', syncValueInputs);

            function togglePeriodFields() {
                const value = periodeSelect.value;

                // Hide all first
                document.querySelectorAll('.period-field-group').forEach(group => {
                    group.classList.add('hidden');
                });

                // Show target group
                const targetGroup = document.getElementById('period-fields-' + value);
                if (targetGroup) {
                    targetGroup.classList.remove('hidden');
                }

                syncValueInputs();
            }

            periodeSelect.addEventListener('change', togglePeriodFields);
            togglePeriodFields(); // Run once initially

            // Filter Panel Toggle
            const btnFilter = document.getElementById('btn-filter');
            const filterPanel = document.getElementById('filter-panel');
            if (btnFilter && filterPanel) {
                btnFilter.addEventListener('click', function(e) {
                    e.preventDefault();
                    filterPanel.classList.toggle('collapse-open');
                });
            }

            // Export Actions Interceptor
            document.getElementById('btn-export-excel').addEventListener('click', function(e) {
                e.preventDefault();
                syncValueInputs();
                const form = document.getElementById('filter-form');
                const formData = new FormData(form);
                const params = new URLSearchParams(formData).toString();
                window.location.href = "{{ route('sales-report.excel') }}?" + params;
            });

            document.getElementById('btn-export-pdf').addEventListener('click', function(e) {
                e.preventDefault();
                syncValueInputs();
                const form = document.getElementById('filter-form');
                const formData = new FormData(form);
                const params = new URLSearchParams(formData).toString();
                window.open("{{ route('sales-report.pdf') }}?" + params, '_blank');
            });
        });
    </script>
</x-app-layout>
