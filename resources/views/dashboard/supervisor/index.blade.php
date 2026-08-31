<x-app-layout>
    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800" x-data="{ activeTab: '{{ request()->query('tab', request()->has('sales_id') ? 'sales' : 'supervisor') }}' }">
        <div class="flex flex-col items-center justify-between space-y-3 p-6 md:flex-row md:space-x-4 md:space-y-0">
            <!-- FILTER FORM -->
            <div
                class="flex-end inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm rounded-2xl p-5 shadow-md">
                <form id="filters-form" action="{{ route('dashboard') }}" method="GET"
                    class="flex flex-wrap items-end gap-4">
                    <input type="hidden" name="tab" id="filters-tab" :value="activeTab" />
                    <input type="hidden" name="sales_id" id="filters-sales-id" value="{{ request()->query('sales_id', 'all') }}" />
                    <div class="flex flex-col">
                        <label class="py-1 text-sm text-gray-700 dark:text-gray-300">Filter Tanggal</label>
                        <div class="flex flex-row items-center gap-2">
                            <input type="date" name="date_start" class="rounded-xl border px-2 py-1"
                                value="{{ $selectedDateStart ?? '' }}" />
                            <span class="text-sm text-gray-700 dark:text-gray-300">-</span>
                            <input type="date" name="date_end" class="rounded-xl border px-2 py-1"
                                value="{{ $selectedDateEnd ?? '' }}" />
                            <button type="submit"
                                class="flex cursor-pointer flex-row items-center rounded-xl bg-[#225A97] px-4 py-1 text-white">
                                <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg> Filter
                            </button>
                            <a href="{{ route('dashboard') }}"
                                class="flex flex-row items-center rounded-xl px-4 py-1 text-blue-950 hover:bg-blue-100 dark:text-gray-300 dark:hover:text-blue-950">
                                <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                    <path d="M3 3v5h5"></path>
                                </svg> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- MAIN DASHBOARD TAB NAVIGATION BAR -->
        <div class="mx-6 mb-6 flex flex-row items-center gap-2 border-b border-gray-200 dark:border-gray-700">
            <button type="button"
                @click="activeTab = 'supervisor'; if(document.getElementById('filters-tab')) document.getElementById('filters-tab').value = 'supervisor'; $nextTick(() => window.dispatchEvent(new Event('resize')))"
                :class="activeTab === 'supervisor' 
                    ? 'border-b-2 border-[#225A97] text-[#225A97] dark:border-blue-400 dark:text-blue-400 font-bold' 
                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-medium'"
                class="flex items-center gap-2 px-5 pb-3 pt-2 text-sm tracking-wide transition-colors cursor-pointer focus:outline-none">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span>Ringkasan Supervisor</span>
            </button>
            <button type="button"
                @click="activeTab = 'sales'; if(document.getElementById('filters-tab')) document.getElementById('filters-tab').value = 'sales'; $nextTick(() => window.dispatchEvent(new Event('resize')))"
                :class="activeTab === 'sales' 
                    ? 'border-b-2 border-[#225A97] text-[#225A97] dark:border-blue-400 dark:text-blue-400 font-bold' 
                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-medium'"
                class="flex items-center gap-2 px-5 pb-3 pt-2 text-sm tracking-wide transition-colors cursor-pointer focus:outline-none">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>Data Metrics & Performa Sales</span>
            </button>
        </div>

        <!-- TAB 1: RINGKASAN SUPERVISOR -->
        <div x-show="activeTab === 'supervisor'" class="grid auto-rows-max grid-cols-8 gap-6 p-6 pt-0">
            {{-- Stat Cards --}}
            <div class="col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Menunggu Persetujuan</h1>
                </div>
                <div class="flex h-full flex-col items-center justify-center p-6">
                    <h1 id="sup-total-pending" class="text-4xl font-bold text-gray-900 dark:text-gray-100 lg:text-6xl">{{ $totalPending }}</h1>
                </div>
            </div>

            <div class="col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Pesanan Disetujui (Bulan Ini)</h1>
                </div>
                <div class="flex flex-col justify-center p-6">
                    <div class="flex flex-col items-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h1 id="sup-total-approved" class="text-4xl font-bold text-gray-900 dark:text-gray-100 lg:text-6xl">
                                {{ $totalApproved }}</h1>
                        </div>
                        <div class="mt-2 flex w-full flex-row items-center justify-center gap-2">
                            <p id="sup-last-month-approved" class="text-lg font-bold text-gray-700 dark:text-gray-300">{{ $lastMonthApproved }}</p>
                            <span class="text-xs text-gray-500">bulan lalu</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Pendapatan Bulan Ini</h1>
                </div>
                <div class="flex h-full flex-col justify-center p-6">
                    <div class="flex h-full flex-col items-center justify-center">
                        <div class="flex w-full flex-row items-center justify-center">
                            <span class="mr-1 text-xl font-bold text-gray-400">Rp</span>
                            <h1 id="sup-total-revenue" class="text-2xl font-bold text-gray-900 dark:text-gray-100 lg:text-3xl">
                                {{ number_format($totalRevenue, 0, ',', '.') }}</h1>
                        </div>
                        <div class="mt-2 flex w-full flex-row items-center justify-center gap-2">
                            <p id="sup-last-month-revenue" class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                Rp{{ number_format($lastMonthRevenue, 0, ',', '.') }}</p>
                            <span class="text-xs text-gray-500">bulan lalu</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Penjualan Selesai</h1>
                </div>
                <div class="flex flex-col justify-center p-6">
                    <div class="flex flex-col items-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h1 id="sup-sales-perf" class="text-4xl font-bold text-gray-900 dark:text-gray-100 lg:text-6xl">
                                {{ $salesPerformance }}</h1>
                        </div>
                        <div class="mt-2 flex w-full flex-row items-center justify-center gap-2">
                            <p id="sup-last-month-perf" class="text-lg font-bold text-gray-700 dark:text-gray-300">{{ $lastMonthPerf }}</p>
                            <span class="text-xs text-gray-500">bulan lalu</span>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md">
                <div
                    class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Profit Sales Per Akun</h1>
                </div>

                <div class="grid h-full grid-cols-4 justify-center gap-2 divide-x lg:gap-5">
                    @if (empty($salesPerfData))
                        <div class="col-span-4 p-6">
                            <div
                                class="flex h-full items-center justify-center rounded-lg border border-gray-200 bg-gray-50 p-6 dark:border-gray-600 dark:bg-gray-700">
                                <p class="font-medium text-gray-600 dark:text-gray-300">Belum ada data profit sales</p>
                            </div>
                        </div>
                    @else
                        @foreach ($salesPerfData as $perf)
                            <div class="col-span-1 p-2 md:p-5">
                                <div class="flex flex-col">
                                    <h1 class="w-full truncate font-bold text-gray-900 dark:text-gray-100"
                                        title="{{ $perf['name'] }}">{{ $perf['name'] }}</h1>
                                    <h1 class="text-xs text-gray-500 dark:text-gray-400">Pencapaian: {{ $perf['percentage'] }}%
                                    </h1>
                                </div>
                                <div class="mt-2 flex w-full flex-col items-start">
                                    <span class="text-[10px] uppercase tracking-wider text-gray-400">Total Profit</span>
                                    <h1 class="text-xl font-bold text-[#225A97] dark:text-blue-400 md:text-2xl">
                                        Rp{{ number_format($perf['revenue'], 0, ',', '.') }}
                                    </h1>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Charts --}}
            <div class="col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-4">
                <div
                    class="inline-flex w-full justify-between rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A] p-5">
                    <h1 class="text-lg font-bold text-white">Tren Penjualan (Rp)</h1>
                    <select id="imc-year-select"
                        class="rounded-full border-2 border-white/30 bg-white/20 px-4 py-1.5 text-sm font-semibold text-white focus:border-white focus:outline-none focus:ring-2 focus:ring-white/50 cursor-pointer shadow-sm">
                        @foreach ($imc_years as $y)
                            <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }} class="text-black font-normal">{{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="p-4" style="height: 300px;">
                    <canvas id="IMC" data-endpoint="{{ route('dashboard.supervisor.chart.data') }}"
                        data-labels='@json($imc_labels)' data-masuk='@json($imc_masuk)'
                        data-keluar='@json($imc_keluar)'></canvas>
                </div>
            </div>

            <div class="col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-4">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A] p-5">
                    <h1 class="text-lg font-bold text-white">Distribusi Status Pesanan</h1>
                </div>
                <div class="p-4" style="height: 300px;">
                    <canvas id="SVC" data-labels='@json($svc_labels)' data-values='@json($svc_data)'></canvas>
                </div>
            </div>

            {{-- Tables --}}
            <div
                class="col-span-8 flex max-h-[400px] flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
                <div class="shrink-0 w-full bg-gradient-to-r from-[#225A97] to-[#0D223A] p-5">
                    <h1 class="text-lg font-bold text-white">Menunggu Persetujuan Quotation</h1>
                </div>
                <div id="tableContainer1" class="grow overflow-x-auto overflow-y-auto">
                    <table class="sortable w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead
                            class="sticky top-0 z-30 bg-gray-50 text-nowrap text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="text-nowrap px-4 py-3">No Quotation</th>
                                <th class="text-nowrap px-4 py-3">Customer</th>
                                <th class="text-nowrap px-4 py-3">Tanggal</th>
                                <th class="text-nowrap px-4 py-3">Total</th>
                                <th class="text-nowrap px-4 py-3">Sales</th>
                                <th class="text-nowrap px-4 py-3 text-right no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-nowrap">
                            @forelse($pendingOrders as $order)
                                <tr class="border-b hover:bg-gray-50 dark:border-gray-700">
                                    <td class="px-4 py-3 font-medium">{{ $order->quotation_number }}</td>
                                    <td class="px-4 py-3">{{ $order->customer?->nama_customer }}</td>
                                    <td class="px-4 py-3">{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">{{ $order->sales?->name }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('sales.quotation.show', $order->id) }}"
                                            class="inline-flex items-center rounded-lg bg-blue-700 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Review</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center">Tidak ada antrean persetujuan.</td>
                                    <td class="hidden"></td>
                                    <td class="hidden"></td>
                                    <td class="hidden"></td>
                                    <td class="hidden"></td>
                                    <td class="hidden"></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                class="col-span-8 flex max-h-[400px] flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
                <div class="shrink-0 w-full bg-gradient-to-r from-[#225A97] to-[#0D223A] p-5">
                    <h1 class="text-lg font-bold text-white">Performa Sales</h1>
                </div>
                <div id="tableContainer2" class="grow overflow-x-auto overflow-y-auto">
                    <table class="sortable w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead
                            class="sticky top-0 z-30 bg-gray-50 text-nowrap text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="text-nowrap px-4 py-3">Nama Sales</th>
                                <th class="text-nowrap px-4 py-3 text-center">Total Quotation</th>
                                <th class="text-nowrap px-4 py-3 text-center">Disetujui</th>
                                <th class="text-nowrap px-4 py-3 no-sort">Akurasi (%)</th>
                            </tr>
                        </thead>
                        <tbody class="text-nowrap">
                            @foreach ($salesPerfData as $perf)
                                <tr class="border-b hover:bg-gray-50 dark:border-gray-700">
                                    <td class="px-4 py-3 font-medium">{{ $perf['name'] }}</td>
                                    <td class="px-4 py-3 text-center">{{ $perf['total'] }}</td>
                                    <td class="px-4 py-3 text-center">{{ $perf['approved'] }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="h-2.5 w-full max-w-[100px] rounded-full bg-gray-200 dark:bg-gray-700">
                                                <div class="h-2.5 rounded-full bg-blue-600"
                                                    style="width: {{ $perf['percentage'] }}%"></div>
                                            </div>
                                            <span>{{ $perf['percentage'] }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                class="col-span-8 flex max-h-[400px] flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
                <div class="shrink-0 w-full bg-gradient-to-r from-[#225A97] to-[#0D223A] p-5">
                    <h1 class="text-lg font-bold text-white">Aktivitas Pelanggan Terbaru</h1>
                </div>
                <div id="tableContainer3" class="grow overflow-x-auto overflow-y-auto">
                    <table class="sortable w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead
                            class="sticky top-0 z-30 bg-gray-50 text-nowrap text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="text-nowrap px-4 py-3">Customer</th>
                                <th class="text-nowrap px-4 py-3">Pesanan Terakhir</th>
                                <th class="text-nowrap px-4 py-3 text-center no-sort">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-nowrap">
                            @foreach ($customerActivity as $order)
                                <tr class="border-b hover:bg-gray-50 dark:border-gray-700">
                                    <td class="px-4 py-3 font-medium">
                                        {{ $order->customer?->nama_customer ?? $order->customer_name }}</td>
                                    <td class="px-4 py-3">{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="{{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} badge">
                                            {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 2: DATA METRICS & PERFORMA SALES -->
        <div x-show="activeTab === 'sales'" class="grid auto-rows-max grid-cols-8 gap-6 p-6 pt-0" style="display: none;">
            <!-- DEDICATED SALES DATA SECTION HEADER & DROPDOWN -->
            <div class="col-span-8 flex flex-col justify-between space-y-4 rounded-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A] p-6 md:flex-row md:items-center md:space-y-0 shadow-md">
                <div class="flex items-center gap-3">
                    <label for="supervisor-sales-select" class="text-base font-bold uppercase tracking-wider text-white shrink-0">Filter Sales:</label>
                    <select id="supervisor-sales-select" class="rounded-full border-2 border-white/30 bg-white/20 px-6 py-2.5 text-base font-semibold text-white focus:border-white focus:outline-none focus:ring-2 focus:ring-white/50 cursor-pointer shadow-sm">
                        <option value="all" {{ ($selectedSalesId ?? 'all') == 'all' ? 'selected' : '' }} class="text-black font-normal">Semua Sales</option>
                        @foreach ($salesList as $salesUser)
                            <option value="{{ $salesUser->id }}" {{ ($selectedSalesId ?? '') == $salesUser->id ? 'selected' : '' }} class="text-black font-normal">
                                {{ $salesUser->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="text-left md:text-right">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-white opacity-90">Data Metrics & Performa Sales</h2>
                    <p class="text-xs text-blue-100 opacity-75">Ringkasan quotation, sales order, dan chart performa tim sales</p>
                </div>
            </div>

            <!-- SALES STAT CARDS -->
            <div class="col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Quotation</h1>
                </div>
                <div class="flex h-full flex-col justify-center p-4">
                    <div class="flex flex-col items-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h1 id="sales-total-quotation" class="text-end text-xl font-bold text-gray-900 dark:text-gray-100 lg:text-3xl">
                                {{ $totalQuotation ?? 0 }}
                            </h1>
                            <span class="text-lg text-gray-500 dark:text-gray-400"> Quotations</span>
                        </div>
                        <div class="mt-2 flex flex-row items-center justify-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <span><strong id="sales-failed-quotation" class="text-gray-900 dark:text-gray-100">{{ $totalFailedQuotation ?? 0 }}</strong> Failed</span>
                            <span>•</span>
                            <span><strong id="sales-goal-quotation" class="text-gray-900 dark:text-gray-100">{{ $totalGoalQuotation ?? 0 }}</strong> Goal</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Value Quotation</h1>
                </div>
                <div class="flex h-full flex-col justify-center p-4">
                    <div class="flex w-full flex-col items-center justify-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h2 id="sales-total-value-quotation" class="text-center text-xl font-bold text-gray-900 dark:text-gray-100 lg:text-3xl">
                                Rp{{ number_format($totalValueQuotation ?? 0, 0, ',', '.') }}
                            </h2>
                        </div>
                        <div class="mt-2 flex flex-row items-center justify-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <span><strong id="sales-failed-value-quotation" class="text-gray-900 dark:text-gray-100">Rp{{ number_format($totalFailedValueQuotation ?? 0, 0, ',', '.') }}</strong> Failed</span>
                            <span>•</span>
                            <span><strong id="sales-goal-value-quotation" class="text-gray-900 dark:text-gray-100">Rp{{ number_format($totalGoalValueQuotation ?? 0, 0, ',', '.') }}</strong> Goal</span>
                        </div>
                    </div>
                </div> 
            </div>

            <div class="col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Sales Order</h1>
                </div>
                <div class="flex h-full flex-col justify-center p-4">
                    <div class="flex flex-col items-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h2 id="sales-total-so" class="text-center text-xl font-bold text-gray-900 dark:text-gray-100 lg:text-3xl">
                                {{ $totalSalesOrder ?? 0 }}
                            </h2>
                            <span class="text-lg text-gray-500 dark:text-gray-400"> Sales Order</span>
                        </div>
                        <div class="mt-2 flex flex-row items-center justify-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <span><strong id="sales-process-so" class="text-gray-900 dark:text-gray-100">{{ $totalProcess ?? 0 }}</strong> Proses</span>
                            <span>•</span>
                            <span><strong id="sales-finish-so" class="text-gray-900 dark:text-gray-100">{{ $totalFinish ?? 0 }}</strong> Finish</span>
                        </div>
                    </div>
                </div>
            </div>            

            <div class="col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Value Sales Order</h1>
                </div>
                <div class="flex h-full flex-col justify-center p-4">
                    <div class="flex w-full flex-col items-center justify-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h2 id="sales-total-value-so" class="text-center text-xl font-bold text-gray-900 dark:text-gray-100 lg:text-3xl">
                                Rp{{ number_format($totalValueSalesOrder ?? 0, 0, ',', '.') }}
                            </h2>
                        </div>
                        <div class="mt-2 flex flex-row items-center justify-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <span><strong id="sales-process-value-so" class="text-gray-900 dark:text-gray-100">Rp{{ number_format($totalProcessValueSalesOrder ?? 0, 0, ',', '.') }}</strong> Proses</span>
                            <span>•</span>
                            <span><strong id="sales-finish-value-so" class="text-gray-900 dark:text-gray-100">Rp{{ number_format($totalFinishValueSalesOrder ?? 0, 0, ',', '.') }}</strong> Finish</span>
                        </div>
                    </div>
                </div> 
            </div>

            <!-- SALES CHARTS -->
            <div class="col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-4">
                <div class="inline-flex w-full justify-between rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A] p-5">
                    <h1 class="text-md font-bold uppercase tracking-wider text-white opacity-90">Sales Performance</h1>
                    <div>
                        <select id="sales-imc-year-select" class="rounded-full border-2 border-white/30 bg-white/20 px-4 py-1.5 text-sm font-semibold text-white focus:border-white focus:outline-none focus:ring-2 focus:ring-white/50 cursor-pointer shadow-sm">
                            @foreach ($sales_imc_years as $year)
                                <option value="{{ $year }}" {{ $year == $selectedSalesYear ? 'selected' : '' }} class="text-black font-normal">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="min-h-0 flex-1 overflow-hidden">
                    <div class="h-64 w-full p-4">
                        <canvas id="salesIMC" class="block h-full w-full"
                            data-labels='@json($sales_imc_labels)'
                            data-masuk='@json($sales_imc_masuk)'
                            data-keluar='@json($sales_imc_keluar)'></canvas>
                    </div>
                </div>
            </div>

            <!-- Sales Order Tracking Donut Chart -->
            <div class="col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-4">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Sales Order Tracking</h1>
                </div>
                <div class="flex flex-1 flex-col justify-between p-4">
                    <div class="relative flex h-52 w-full items-center justify-center">
                        <canvas id="salesOrderTrackingChart" class="relative z-10 block h-full w-full hover:z-20"
                            data-finish="{{ $totalFinish ?? 0 }}"
                            data-process="{{ $totalProcess ?? 0 }}"
                            data-total-orders="{{ $totalSalesOrder ?? 0 }}"
                            data-total-value="{{ $totalValueSalesOrder ?? 0 }}"></canvas>
                        
                        <!-- Center Cutout Text -->
                        <div class="pointer-events-none absolute inset-0 z-0 flex flex-col items-center justify-center text-center">
                            <svg class="mb-0.5 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Sales Order</span>
                            <span id="sales-tracking-center-count" class="text-base font-extrabold text-gray-900 dark:text-gray-100">{{ number_format($totalSalesOrder ?? 0) }} Orders</span>
                            <span id="sales-tracking-center-value" class="text-[11px] font-bold text-gray-600 dark:text-gray-300">Rp {{ number_format($totalValueSalesOrder ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Legend Cards -->
                    <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/80 p-2.5 shadow-xs dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-center gap-1.5">
                                <span class="h-2.5 w-2.5 rounded-full bg-[#225A97]"></span>
                                <span class="font-medium text-gray-600 dark:text-gray-400">Finish Orders</span>
                            </div>
                            <div class="mt-1 font-bold text-[#225A97] dark:text-blue-400" id="sales-tracking-finish-info">
                                {{ number_format($totalFinish ?? 0) }} ({{ ($totalSalesOrder ?? 0) > 0 ? round(($totalFinish / $totalSalesOrder) * 100) : 0 }}%)
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/80 p-2.5 shadow-xs dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-center gap-1.5">
                                <span class="h-2.5 w-2.5 rounded-full bg-[#10B981]"></span>
                                <span class="font-medium text-gray-600 dark:text-gray-400">Proses Orders</span>
                            </div>
                            <div class="mt-1 font-bold text-[#10B981] dark:text-emerald-400" id="sales-tracking-process-info">
                                {{ number_format($totalProcess ?? 0) }} ({{ ($totalSalesOrder ?? 0) > 0 ? round(($totalProcess / $totalSalesOrder) * 100) : 0 }}%)
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-4">
                <div class="inline-flex w-full items-center justify-between rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Sales Order Quarter</h1>
                    <div class="m-3">
                        <select id="sales-so-status-select" class="rounded-full border-2 border-white/30 bg-white/20 px-5 py-2 text-sm font-semibold text-white focus:border-white focus:outline-none focus:ring-2 focus:ring-white/50 cursor-pointer shadow-sm">
                            <option value="all" {{ ($selectedSalesStatus ?? 'all') == 'all' ? 'selected' : '' }} class="text-black font-normal">Semua Status</option>
                            <option value="completed" {{ ($selectedSalesStatus ?? '') == 'completed' ? 'selected' : '' }} class="text-black font-normal">Completed (Selesai)</option>
                            <option value="not_completed" {{ ($selectedSalesStatus ?? '') == 'not_completed' ? 'selected' : '' }} class="text-black font-normal">Partially Delivered (Sebagian Terkirim)</option>
                            <option value="under_procurement" {{ ($selectedSalesStatus ?? '') == 'under_procurement' ? 'selected' : '' }} class="text-black font-normal">Under Procurement (Dalam Pengadaan)</option>
                            <option value="sent_to_warehouse" {{ ($selectedSalesStatus ?? '') == 'sent_to_warehouse' ? 'selected' : '' }} class="text-black font-normal">Sent to Warehouse (Dikirim ke Gudang)</option>
                            <option value="approved_warehouse" {{ ($selectedSalesStatus ?? '') == 'approved_warehouse' ? 'selected' : '' }} class="text-black font-normal">Approved Warehouse (Disetujui Gudang)</option>
                            <option value="approved_supervisor" {{ ($selectedSalesStatus ?? '') == 'approved_supervisor' ? 'selected' : '' }} class="text-black font-normal">Approved Supervisor (Disetujui Supervisor)</option>
                            <option value="rejected_supervisor" {{ ($selectedSalesStatus ?? '') == 'rejected_supervisor' ? 'selected' : '' }} class="text-black font-normal">Rejected Supervisor (Ditolak Supervisor)</option>
                            <option value="rejected_warehouse" {{ ($selectedSalesStatus ?? '') == 'rejected_warehouse' ? 'selected' : '' }} class="text-black font-normal">Rejected Warehouse (Ditolak Gudang)</option>
                            <option value="canceled" {{ ($selectedSalesStatus ?? '') == 'canceled' ? 'selected' : '' }} class="text-black font-normal">Canceled (Dibatalkan)</option>
                        </select>
                    </div>
                </div>
                <div class="min-h-0 flex-1 overflow-hidden">
                    <div class="h-64 w-full p-4">
                        <canvas id="salesTargetQuarterChart" class="block h-full w-full" data-targets='@json($sales_quarter_targets)'></canvas>
                    </div>
                </div>
            </div>

            <div class="col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-4">
                <div class="inline-flex w-full justify-between rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 id="sales-monthly-target-title" class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Sales Order Bulanan (Jan - Mar)</h1>
                </div>
                <div class="min-h-0 flex-1 overflow-hidden">
                    <div class="h-64 w-full p-4">
                        <canvas id="salesMonthlyTargetChart" class="block h-full w-full" data-monthly='@json($sales_monthly_targets)'></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/chart-dashboard-supervisor.js', 'resources/js/table-sort.js'])
</x-app-layout>