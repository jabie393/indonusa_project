<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-stretch justify-between gap-4 p-4 sm:p-6 lg:flex-row">
            <!-- FILTER FORM (LEFT SIDE) -->
            <div
                class="flex flex-shrink-0 items-center inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm rounded-2xl p-4 sm:p-5 shadow-md bg-white dark:bg-gray-800">
                <form id="filters-form" action="{{ route('dashboard') }}" method="GET"
                    class="flex w-full flex-col items-stretch gap-3 sm:flex-row sm:items-end sm:gap-4">
                    <input type="hidden" name="sales_id" id="filters-sales-id" value="{{ request()->query('sales_id', 'all') }}" />
                    <div class="flex flex-col">
                        <label class="pb-1.5 text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Filter tanggal</label>
                        <div class="flex flex-col items-stretch gap-1 sm:flex-row sm:items-center sm:gap-2">
                            <input type="date" name="date_start" class="w-full rounded-xl border border-gray-300 px-2.5 py-1 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 sm:w-auto"
                                value="{{ $selectedDateStart ?? '' }}" />
                            <span class="hidden text-sm text-gray-500 dark:text-gray-400 sm:inline">-</span>
                            <input type="date" name="date_end" class="w-full rounded-xl border border-gray-300 px-2.5 py-1 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 sm:w-auto"
                                value="{{ $selectedDateEnd ?? '' }}" />
                        </div>
                    </div>

                    <div class="flex flex-row items-center gap-2 pt-1 sm:pt-0">
                        <button type="submit"
                            class="flex flex-1 cursor-pointer items-center justify-center rounded-xl bg-[#225A97] px-4 py-1.5 text-sm font-medium text-white transition hover:bg-[#1b487a] sm:flex-initial">
                            <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg> Filter
                        </button>
                        <a href="{{ route('dashboard') }}"
                            class="flex flex-1 items-center justify-center rounded-xl px-3 py-1.5 text-sm font-medium text-blue-950 transition hover:bg-blue-100 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white sm:flex-initial">
                            <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw">
                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                <path d="M3 3v5h5"></path>
                            </svg> Atur Ulang
                        </a>
                    </div>
                </form>
            </div>

            <!-- BLUE BAR (RIGHT SIDE) WITH FILTER SALES & HEADER INFO -->
            <div class="flex min-w-0 flex-1 flex-col justify-between gap-4 rounded-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 sm:p-5 shadow-md sm:flex-row sm:items-center">
                <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                    <label for="supervisor-sales-select" class="text-xs sm:text-sm font-bold uppercase tracking-wider text-white shrink-0">Filter Sales:</label>
                    <div class="w-full sm:w-auto">
                        <select id="supervisor-sales-select" class="w-full sm:w-auto rounded-full border-2 border-white/30 bg-white/20 px-4 py-1.5 text-xs sm:text-sm font-semibold text-white focus:border-white focus:outline-none focus:ring-2 focus:ring-white/50 cursor-pointer shadow-sm">
                            <option value="all" {{ ($selectedSalesId ?? 'all') == 'all' ? 'selected' : '' }} class="text-black font-normal">Semua Sales</option>
                            @foreach ($salesList as $salesUser)
                                <option value="{{ $salesUser->id }}" {{ ($selectedSalesId ?? '') == $salesUser->id ? 'selected' : '' }} class="text-black font-normal">
                                    {{ $salesUser->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="text-left sm:text-right shrink-0">
                    <h2 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-white opacity-95">Data Metrics & Performa Sales</h2>
                    <p class="text-[11px] sm:text-xs text-blue-100 opacity-80">Ringkasan quotation, sales order, dan chart performa</p>
                </div>
            </div>
        </div>

        <!-- DATA METRICS & PERFORMA SALES CONTENT -->
        <div class="grid auto-rows-max grid-cols-8 gap-6 p-6 pt-0">

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

    @vite(['resources/js/chart-dashboard-supervisor.js'])
</x-app-layout>