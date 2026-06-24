<x-app-layout>
    <div class="flex flex-col lg:h-[calc(100vh-112px)] overflow-hidden">

        <!-- Topbar Page Header -->
        <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex h-16 items-center justify-between overflow-hidden rounded-2xl bg-white px-4 shadow-md dark:bg-gray-800 shrink-0">
            <div class="flex items-center gap-3">
                <span class="text-lg font-bold text-slate-800 dark:text-white">Laporan Kinerja Sales</span>
            </div>
            
            <div class="flex gap-2">
                <button type="button" id="btn-export-excel" class="cursor-pointer flex flex-row items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition duration-200 shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Excel
                </button>
                <button type="button" id="btn-export-pdf" class="cursor-pointer flex flex-row items-center justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition duration-200 shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Export PDF
                </button>
            </div>
        </div>

        <!-- Filter Panel -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-md mb-5 shrink-0 border border-gray-100 dark:border-gray-700/50">
            <form id="filter-form" action="{{ route('sales-report.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- Report Type Filter -->
                    <div class="flex flex-col gap-1.5">
                        <label for="report_type" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tipe Laporan</label>
                        <select name="report_type" id="report_type" class="rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="all" {{ request('report_type') === 'all' ? 'selected' : '' }}>Semua Tipe</option>
                            <option value="quotation" {{ request('report_type') === 'quotation' ? 'selected' : '' }}>Standard Quotation</option>
                            <option value="custom_quotation" {{ request('report_type') === 'custom_quotation' ? 'selected' : '' }}>Custom Quotation</option>
                        </select>
                    </div>

                    <!-- Periode Type Filter -->
                    <div class="flex flex-col gap-1.5">
                        <label for="periode_type" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Jenis Periode</label>
                        <select name="periode_type" id="periode_type" class="rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
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
                        <select name="status" id="status" class="rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
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
                        @if($isSales)
                            <select name="sales_id" id="sales_id" disabled class="rounded-xl border border-gray-300 bg-gray-100 p-2.5 text-sm text-gray-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                            </select>
                            <input type="hidden" name="sales_id" value="{{ $user->id }}" />
                        @else
                            <select name="sales_id" id="sales_id" class="rounded-xl border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Semua Sales Agent</option>
                                @foreach($salesUsers as $salesUser)
                                    <option value="{{ $salesUser->id }}" {{ request('sales_id') == $salesUser->id ? 'selected' : '' }}>{{ $salesUser->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                </div>

                <!-- Dynamic Period Fields Container -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4 pt-4 border-t border-dashed border-slate-100 dark:border-slate-700">
                    
                    <!-- Daily Fields -->
                    <div id="period-fields-daily" class="period-field-group hidden flex flex-col gap-1.5 col-span-2">
                        <label for="date" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Pilih Tanggal</label>
                        <input type="date" name="date" id="date" value="{{ request('date', date('Y-m-d')) }}" class="rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                    </div>

                    <!-- Weekly Fields -->
                    <div id="period-fields-weekly" class="period-field-group hidden col-span-3 grid grid-cols-3 gap-3">
                        <div class="flex flex-col gap-1.5">
                            <label for="week" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Minggu Ke-</label>
                            <select name="week" id="week" class="rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @foreach([1, 2, 3, 4, 5] as $w)
                                    <option value="{{ $w }}" {{ request('week', 1) == $w ? 'selected' : '' }}>Minggu Ke-{{ $w }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="weekly_month" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Bulan</label>
                            <select name="month" id="weekly_month" class="rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @foreach([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $num => $name)
                                    <option value="{{ $num }}" {{ request('month', date('n')) == $num ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="weekly_year" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tahun</label>
                            <select name="year" id="weekly_year" class="rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @for($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Monthly Fields (uses same month/year parameters, only shows dropdowns) -->
                    <div id="period-fields-monthly" class="period-field-group hidden col-span-2 grid grid-cols-2 gap-3">
                        <div class="flex flex-col gap-1.5">
                            <label for="monthly_month" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Bulan</label>
                            <select id="monthly_month" class="monthly-sync rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @foreach([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $num => $name)
                                    <option value="{{ $num }}" {{ request('month', date('n')) == $num ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="monthly_year" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tahun</label>
                            <select id="monthly_year" class="monthly-sync rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @for($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Yearly Fields -->
                    <div id="period-fields-yearly" class="period-field-group hidden flex flex-col gap-1.5 col-span-2">
                        <label for="yearly_year" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tahun</label>
                        <select id="yearly_year" class="yearly-sync rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Custom Fields -->
                    <div id="period-fields-custom" class="period-field-group hidden col-span-2 grid grid-cols-2 gap-3">
                        <div class="flex flex-col gap-1.5">
                            <label for="start_date" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Mulai Tanggal</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date', date('Y-m-01')) }}" class="rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="end_date" class="text-xs font-semibold text-slate-500 dark:text-slate-400">Sampai Tanggal</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date', date('Y-m-d')) }}" class="rounded-xl border border-gray-300 bg-gray-50 p-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        </div>
                    </div>

                    <!-- Hidden Fields to store synchronized values -->
                    <input type="hidden" name="month_val" id="month_val" value="{{ request('month', date('n')) }}" />
                    <input type="hidden" name="year_val" id="year_val" value="{{ request('year', date('Y')) }}" />

                    <!-- Submit & Reset Actions -->
                    <div class="col-span-1 md:col-start-4 flex items-end gap-2 justify-end">
                        <a href="{{ route('sales-report.index') }}" class="w-full text-center rounded-xl bg-gray-100 hover:bg-gray-200 text-slate-700 px-4 py-2.5 text-sm font-semibold transition duration-150">Reset</a>
                        <button type="submit" class="w-full rounded-xl bg-[#225A97] hover:bg-[#19426d] text-white px-4 py-2.5 text-sm font-semibold transition duration-150 shadow-sm">Filter</button>
                    </div>

                </div>
            </form>
        </div>

        <!-- Summary Cards Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 shrink-0 mb-5">
            <!-- Total Documents Card -->
            <div class="bg-gradient-to-r from-blue-700 to-indigo-800 rounded-2xl p-5 shadow-lg flex items-center justify-between text-white">
                <div>
                    <p class="text-xs uppercase font-bold text-blue-100 tracking-wider">Jumlah Transaksi</p>
                    <h3 class="text-3xl font-extrabold mt-1">{{ number_format($totalCount) }}</h3>
                </div>
                <div class="bg-white/10 p-3.5 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>

            <!-- Total Sales Amount Card -->
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-2xl p-5 shadow-lg flex items-center justify-between text-white">
                <div>
                    <p class="text-xs uppercase font-bold text-emerald-100 tracking-wider">Total Kinerja Sales</p>
                    <h3 class="text-3xl font-extrabold mt-1">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white/10 p-3.5 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-20c5.303 0 9.603 4.3 9.603 9.603s-4.3 9.603-9.603 9.603S2.397 18.906 2.397 13.603 6.697 4 12 4z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Table Container Card -->
        <div class="relative flex flex-1 min-h-0 flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
            <div class="shrink-0 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 flex justify-between items-center">
                <span class="text-sm font-bold text-white uppercase tracking-wider">Daftar Dokumen Penawaran</span>
                <div>
                    <!-- Search Input -->
                    <form action="{{ route('sales-report.index') }}" method="GET" class="relative">
                        <!-- Forward current filters -->
                        @foreach(request()->except(['search', 'page']) as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                        @endforeach
                        <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari..." class="rounded-xl border border-transparent bg-white/20 px-3 py-1.5 text-xs text-white placeholder-white/70 focus:bg-white focus:text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 w-64 transition duration-200" />
                    </form>
                </div>
            </div>

            <!-- Data Table -->
            <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
                <table class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="sticky top-0 z-10 bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="text-nowrap p-4 w-[5%]">No</th>
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
                                <td class="p-4 text-slate-400 text-center align-middle font-bold">{{ $rowNumber }}</td>
                                <td class="p-4 align-middle">
                                    @if($row->type === 'Standard Quotation')
                                        <a href="{{ route('sales.quotation.show', $row->id) }}" class="text-sm font-bold text-[#225A97] hover:underline dark:text-blue-400">
                                            {{ $row->quotation_number ?? '-' }}
                                        </a>
                                    @else
                                        <a href="{{ route('sales.custom-quotation.show', $row->id) }}" class="text-sm font-bold text-[#225A97] hover:underline dark:text-blue-400">
                                            {{ $row->quotation_number ?? '-' }}
                                        </a>
                                    @endif
                                </td>
                                <td class="p-4 align-middle text-slate-800 dark:text-slate-200 font-semibold">{{ $row->sales_name ?? '-' }}</td>
                                <td class="p-4 align-middle text-slate-800 dark:text-slate-200 font-bold whitespace-normal max-w-xs">{{ $row->customer_name ?? '-' }}</td>
                                <td class="p-4 align-middle text-slate-600 dark:text-slate-400 whitespace-normal max-w-sm">{{ $row->subject ?? '-' }}</td>
                                <td class="p-4 align-middle text-slate-500 dark:text-slate-400 text-xs">{{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i') }}</td>
                                <td class="p-4 align-middle text-right text-slate-900 dark:text-white font-bold text-sm">
                                    Rp {{ number_format($row->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="p-4 align-middle text-center">
                                    @if($row->type === 'Standard Quotation')
                                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">Standard</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-semibold text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">Custom</span>
                                    @endif
                                </td>
                                <td class="p-4 align-middle text-center">
                                    <span class="inline-flex items-center justify-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $statusData['class'] }}">
                                        {{ $statusData['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-8 text-center text-slate-400 font-bold dark:text-slate-500">Tidak ada data penawaran sales yang ditemukan untuk filter aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Pagination and Navigation -->
            <nav id="pagination-nav" class="sticky bottom-0 z-20 flex shrink-0 flex-col items-start justify-between space-y-3 border-t border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        Menampilkan
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $results->firstItem() ?? 0 }}-{{ $results->lastItem() ?? 0 }}</span>
                        dari
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $results->total() }}</span>
                    </span>
                    <form method="GET" action="{{ route('sales-report.index') }}">
                        @foreach(request()->except(['perPage', 'page']) as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                        @endforeach
                        <select name="perPage" onchange="this.form.submit()" class="mx-2 rounded-xl border border-gray-300 bg-gray-50 p-1 pl-2 pr-8 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
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
