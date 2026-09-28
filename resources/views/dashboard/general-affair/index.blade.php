<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 p-6 md:flex-row md:space-x-4 md:space-y-0">
            <!-- FILTER FORM -->
            <div class="flex-end inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm rounded-2xl p-5 shadow-md">
                <form id="filters-form" action="{{ route('dashboard') }}" method="GET" class="flex flex-col items-center gap-2 md:flex-row">
                    <div class="flex flex-col">
                        <label class="py-2 text-sm text-gray-700 dark:text-gray-300">Filter tanggal</label>
                        <div class="flex flex-col items-center md:flex-row">
                            <input type="date" name="date_start" class="rounded-xl border px-2 py-1"
                                value="{{ $selectedDateStart ?? '' }}" />
                            <span class="text-sm text-gray-700 dark:text-gray-300">-</span>
                            <input type="date" name="date_end" class="rounded-xl border px-2 py-1"
                                value="{{ $selectedDateEnd ?? '' }}" />
                            <button type="submit"
                                class="mt-3 flex cursor-pointer flex-row items-center rounded-xl bg-[#225A97] px-4 py-1 text-white md:ml-3 md:mt-0">
                                <svg class="pr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter h-4 w-4">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg> Filter</button>
                            <a href="{{ route('dashboard') }}"
                                class="mt-3 flex flex-row items-center rounded-xl px-4 py-1 text-blue-950 hover:bg-blue-100 dark:text-gray-300 dark:hover:text-blue-950 md:ml-3 md:mt-0">
                                <svg class="pr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-rotate-ccw h-4 w-4">
                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                    <path d="M3 3v5h5"></path>
                                </svg>
                                Atur Ulang
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- DASHBOARD PURCHASING CONTENT -->
        <div class="grid auto-rows-max grid-cols-8 gap-6 p-6 pt-0">
            <!-- Total Purchase Card -->
            <div class="col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Purchase</h1>
                </div>
                <div class="flex h-full flex-col justify-center p-4">
                    <div class="flex w-full flex-col items-center justify-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h2 id="procurement-total-value" class="text-center text-xl font-bold text-gray-900 dark:text-gray-100 lg:text-3xl">
                                Rp{{ number_format($totalValueProcurement ?? 0, 0, ',', '.') }}
                            </h2>
                        </div>
                        <div class="mt-2 flex flex-row items-center justify-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <span><strong id="procurement-pending-value" class="text-gray-900 dark:text-gray-100">Rp{{ number_format($totalPendingValueProcurement ?? 0, 0, ',', '.') }}</strong> Pending</span>
                            <span>•</span>
                            <span><strong id="procurement-finish-value" class="text-gray-900 dark:text-gray-100">Rp{{ number_format($totalFinishValueProcurement ?? 0, 0, ',', '.') }}</strong> Finish</span>
                        </div>
                    </div>
                </div> 
            </div>

            <!-- Average Timeline SO - GR Chart -->
            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-3">
                <div class="inline-flex w-full justify-between rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A] p-5">
                    <h1 class="text-lg font-bold text-white">Average Timeline SO - GR</h1>
                </div>
                <div class="min-h-0 flex-1 overflow-hidden rounded-b-2xl bg-white dark:bg-gray-800">
                    <div class="h-64 w-full p-4">
                        <canvas id="averageTimelineChart" class="block h-full w-full"
                            data-labels='["Sales Order ke Purchasing", "Purchase Order ke Vendor", "Barang Tiba dari Vendor"]'
                            data-values="{{ json_encode($timeline_values) }}"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tren Belanja Bulanan Chart -->
            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-3">
                <div
                    class="inline-flex w-full justify-between rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A] p-5">
                    <h1 class="text-lg font-bold text-white">Tren Belanja Bulanan</h1>
                    <select id="purchasing-trend-year-select"
                        class="rounded-full border-2 border-white/30 bg-white/20 px-4 py-1.5 text-sm font-semibold text-white focus:border-white focus:outline-none focus:ring-2 focus:ring-white/50 cursor-pointer shadow-sm">
                        @foreach ($purchasing_years as $py)
                            <option value="{{ $py }}" {{ $py == $selectedYear ? 'selected' : '' }} class="text-black font-normal">{{ $py }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-h-0 flex-1 overflow-hidden rounded-b-2xl bg-white dark:bg-gray-800">
                    <div class="h-64 w-full p-4">
                        <canvas id="purchasingTrendChart" class="block h-full w-full"
                            data-labels="{{ json_encode($purchasing_months) }}"
                            data-values="{{ json_encode($monthly_purchasing_spending) }}"></canvas>
                    </div>
                </div>
            </div>

            <!-- Kategori Produk Teratas (Donut Chart) -->
            <div class="col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-4">
                <div class="inline-flex w-full justify-between rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A] p-5">
                    <h1 class="text-lg font-bold text-white">Kategori Produk Teratas</h1>
                </div>
                <div class="flex flex-1 flex-col justify-between rounded-b-2xl bg-white p-5 dark:bg-gray-800">
                    <div class="relative flex h-56 w-full items-center justify-center">
                        <canvas id="purchasingCategoryChart" class="relative z-10 block h-full w-full"
                            data-labels="{{ json_encode($purchasing_category_labels) }}"
                            data-values="{{ json_encode($purchasing_category_values) }}"
                            data-colors="{{ json_encode($purchasing_category_colors) }}"
                            data-has-data="{{ $purchasing_category_has_data ? 'true' : 'false' }}"></canvas>
                        
                        <!-- Center Cutout Text -->
                        <div class="pointer-events-none absolute inset-0 z-0 flex flex-col items-center justify-center text-center">
                            <span class="text-xs font-extrabold uppercase tracking-wider text-gray-700 dark:text-gray-200">Total Spending</span>
                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400">Breakdown</span>
                        </div>
                    </div>

                    <!-- Legend Cards -->
                    <div id="purchasing-category-legends" class="mt-4 grid grid-cols-2 gap-2 text-xs md:grid-cols-3">
                        @forelse ($purchasing_categories as $cat)
                            <div class="flex items-center gap-1.5 rounded-xl border border-gray-100 bg-gray-50/80 p-2 dark:border-gray-700 dark:bg-gray-800">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background-color: {{ $cat['color'] }}"></span>
                                <div class="flex flex-col truncate">
                                    <span class="font-medium text-gray-600 dark:text-gray-400 truncate">{{ $cat['name'] }}</span>
                                    <span class="font-bold text-gray-900 dark:text-gray-100">{{ $cat['percentage'] }}%</span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 md:col-span-3 text-center py-4 text-xs text-gray-400">
                                Belum ada data kedatangan barang yang disetujui.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Database Vendor (Donut Chart) -->
            <div class="col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-4">
                <div class="inline-flex w-full items-center justify-between rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A] p-5">
                    <div>
                        <h1 class="text-lg font-bold text-white">Database Vendor</h1>
                        <p class="text-xs text-blue-100 opacity-80">Total Vendors Registered</p>
                    </div>
                </div>
                <div class="flex flex-1 flex-col justify-between rounded-b-2xl bg-white p-5 dark:bg-gray-800">
                    <div class="relative flex h-56 w-full items-center justify-center">
                        <canvas id="vendorDatabaseChart" class="relative z-10 block h-full w-full"
                            data-labels='@json($vendor_stats["labels"])'
                            data-values='@json($vendor_stats["values"])'
                            data-colors='@json($vendor_stats["colors"])'
                            data-has-data="{{ $vendor_stats['has_data'] ? 'true' : 'false' }}"></canvas>
                        
                        <!-- Center Cutout Text with Icon -->
                        <div class="pointer-events-none absolute inset-0 z-0 flex flex-col items-center justify-center text-center">
                            <svg class="mb-1 h-6 w-6 text-[#225A97] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                            <span id="vendor-total-text" class="text-sm font-extrabold text-gray-900 dark:text-gray-100">Total: {{ number_format($vendor_stats['total']) }}</span>
                        </div>
                    </div>

                    <!-- Legend Cards -->
                    <div class="mt-4 grid grid-cols-1 gap-2 text-xs md:grid-cols-2">
                        <div class="flex items-start gap-2 rounded-xl border border-gray-100 bg-gray-50/80 p-2.5 dark:border-gray-700 dark:bg-gray-800">
                            <span class="mt-0.5 h-3 w-3 shrink-0 rounded bg-[#225A97]"></span>
                            <div class="flex flex-col">
                                <span id="vendor-pkp-legend" class="font-bold text-gray-900 dark:text-gray-100">1. PKP - {{ number_format($vendor_stats['pkp_count']) }} Vendors ({{ $vendor_stats['pkp_percentage'] }}%)</span>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400">Pengusaha Kena Pajak - Includes all registered PKP vendors.</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2 rounded-xl border border-gray-100 bg-gray-50/80 p-2.5 dark:border-gray-700 dark:bg-gray-800">
                            <span class="mt-0.5 h-3 w-3 shrink-0 rounded bg-[#f97316]"></span>
                            <div class="flex flex-col">
                                <span id="vendor-nonpkp-legend" class="font-bold text-gray-900 dark:text-gray-100">2. Non PKP - {{ number_format($vendor_stats['non_pkp_count']) }} Vendors ({{ $vendor_stats['non_pkp_percentage'] }}%)</span>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400">Includes all non-registered and micro-vendors.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kelola data Chart ada di JS --}}
    @vite(['resources/js/chart-dashboard-general-affair.js'])
</x-app-layout>