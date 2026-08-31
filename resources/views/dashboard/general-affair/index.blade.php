<x-app-layout>
    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800" x-data="{ activeTab: '{{ request()->query('tab', 'ga') }}' }">
        <div class="flex flex-col items-center justify-between space-y-3 p-6 md:flex-row md:space-x-4 md:space-y-0">


            <!-- FILTER FORM -->
            <div
                class="flex-end inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm rounded-2xl p-5 shadow-md">
                <form id="filters-form" action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="tab" id="filters-tab" :value="activeTab" />
                    <div class="flex flex-col">
                        <label class="py-2 text-sm text-gray-700 dark:text-gray-300">Filter tanggal</label>
                        <div class="flex flex-row items-center">
                            <input type="date" name="date_start" class="rounded-xl border px-2 py-1"
                                value="{{ $selectedDateStart ?? '' }}" />
                            <span class="text-sm text-gray-700 dark:text-gray-300">-</span>
                            <input type="date" name="date_end" class="rounded-xl border px-2 py-1"
                                value="{{ $selectedDateEnd ?? '' }}" />
                            <button type="submit"
                                class="ml-3 flex cursor-pointer flex-row items-center rounded-xl bg-[#225A97] px-4 py-1 text-white">
                                <svg class="pr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter h-4 w-4">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg> Filter</button>
                            <a href="{{ route('dashboard') }}"
                                class="ml-3 flex flex-row items-center rounded-xl px-4 py-1 text-blue-950 hover:bg-blue-100 dark:text-gray-300 dark:hover:text-blue-950">
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

        <!-- MAIN DASHBOARD TAB NAVIGATION BAR -->
        <div class="mx-6 mb-6 flex flex-row items-center gap-2 border-b border-gray-200 dark:border-gray-700">
            <button type="button"
                @click="activeTab = 'ga'; if(document.getElementById('filters-tab')) document.getElementById('filters-tab').value = 'ga'; $nextTick(() => window.dispatchEvent(new Event('resize')))"
                :class="activeTab === 'ga' 
                    ? 'border-b-2 border-[#225A97] text-[#225A97] dark:border-blue-400 dark:text-blue-400 font-bold' 
                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-medium'"
                class="flex items-center gap-2 px-5 pb-3 pt-2 text-sm tracking-wide transition-colors cursor-pointer focus:outline-none">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span>Dashboard GA</span>
            </button>
            <button type="button"
                @click="activeTab = 'purchasing'; if(document.getElementById('filters-tab')) document.getElementById('filters-tab').value = 'purchasing'; $nextTick(() => window.dispatchEvent(new Event('resize')))"
                :class="activeTab === 'purchasing' 
                    ? 'border-b-2 border-[#225A97] text-[#225A97] dark:border-blue-400 dark:text-blue-400 font-bold' 
                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-medium'"
                class="flex items-center gap-2 px-5 pb-3 pt-2 text-sm tracking-wide transition-colors cursor-pointer focus:outline-none">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span>Dashboard Purchasing</span>
            </button>
        </div>

        <!-- TAB 1: DASHBOARD GA -->
        <div x-show="activeTab === 'ga'" class="grid auto-rows-max grid-cols-8 gap-6 p-6 pt-0">
            <!-- 1st Card: Total Revenue -->
            <div
                class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-3 group transition-transform hover:scale-[1.02]">
                <div
                    class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-4 text-xs font-bold uppercase tracking-wider text-white opacity-90">Total Pendapatan
                    </h1>
                </div>
                <div class="relative overflow-hidden flex h-full flex-col justify-center rounded-b-2xl bg-white p-5 dark:bg-gray-800">
                    <div class="relative z-10 flex flex-col">
                        <h2 class="text-2xl font-extrabold text-blue-900 dark:text-blue-400">
                            Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
                        </h2>
                        <span class="text-[10px] font-semibold uppercase text-gray-400">Total Keseluruhan</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-2 bottom-2 h-12 w-12 text-blue-500/[0.08] dark:text-blue-500/[0.03] pointer-events-none z-0 group-hover:scale-110 transition-transform duration-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="6" width="20" height="12" rx="2" />
                        <circle cx="12" cy="12" r="2" />
                        <path d="M6 12h.01M18 12h.01" />
                    </svg>
                </div>
            </div>

            <!-- 2nd Card: Total Orders -->
            <div
                class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2 group transition-transform hover:scale-[1.02]">
                <div
                    class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-4 text-xs font-bold uppercase tracking-wider text-white opacity-90">Total Pesanan</h1>
                </div>
                <div class="relative overflow-hidden flex h-full flex-col justify-center rounded-b-2xl bg-white p-5 dark:bg-gray-800">
                    <div class="relative z-10 flex flex-col">
                        <h2 class="text-4xl font-black text-blue-900 dark:text-blue-400">
                            {{ $totalOrders ?? 0 }}
                        </h2>
                        <span class="text-[10px] font-semibold uppercase text-gray-400">Selesai</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-2 bottom-2 h-12 w-12 text-blue-500/[0.08] dark:text-blue-500/[0.03] pointer-events-none z-0 group-hover:scale-110 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>

            <!-- 3rd Card: Tugas Goods In & Procurement (With Breakdown) -->
            <a href="{{ route('goods-in.index') }}"
                class="group inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-3 transition-transform hover:scale-[1.02]">
                <div
                    class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-4 text-xs font-bold uppercase tracking-wider text-white opacity-90">Tugas Goods In &
                        Procurement</h1>
                </div>
                <div class="relative overflow-hidden flex h-full flex-col justify-center rounded-b-2xl bg-white p-5 dark:bg-gray-800">
                    <div class="relative z-10 flex items-center justify-between gap-4 w-full">
                        <div class="flex flex-col flex-shrink-0">
                            <h2 class="text-4xl font-black text-blue-900 dark:text-blue-400">
                                {{ ($procurementPendingCount ?? 0) + ($procurementRejectedCount ?? 0) + ($goodsInRevisionCount ?? 0) }}
                            </h2>
                            <span class="text-[10px] font-semibold uppercase text-gray-400">Total Tugas</span>
                        </div>
                        <div class="flex-1 space-y-2 border-l border-gray-100 pl-4 dark:border-gray-700">
                            <div class="flex justify-between text-[10px] gap-2">
                                <span class="text-gray-500 truncate">Request Sales</span>
                                <span
                                    class="font-bold text-blue-900 dark:text-blue-400">{{ $procurementPendingCount ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between text-[10px] gap-2">
                                <span class="text-gray-500 truncate">Revisi Goods In</span>
                                <span
                                    class="font-bold text-blue-900 dark:text-blue-400">{{ $goodsInRevisionCount ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between text-[10px] gap-2">
                                <span class="text-gray-500 truncate">Revisi Proc</span>
                                <span
                                    class="font-bold text-blue-900 dark:text-blue-400">{{ $procurementRejectedCount ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-2 bottom-2 h-12 w-12 text-blue-500/[0.08] dark:text-blue-500/[0.03] pointer-events-none z-0 group-hover:scale-110 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </a>

            <!-- Charts Section -->
            <div
                class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md lg:col-span-4">
                <div
                    class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm inline-flex w-full justify-between rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold uppercase tracking-wider text-white lg:text-xl">Tren Pendapatan
                    </h1>
                    <div class="m-3">
                        <select id="imc-year-select"
                            class="rounded-full border-none bg-[#0D223A] px-4 py-2 text-xs text-white focus:ring-0">
                            @foreach ($imc_years as $year)
                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="min-h-0 flex-1 overflow-hidden rounded-b-2xl bg-white dark:bg-gray-800">
                    <div class="h-64 w-full p-4">
                        <canvas id="IMC" class="block h-full w-full"
                            data-endpoint="{{ route('dashboard.general-affair.chart.data') }}"
                            data-labels='@json($imc_labels)' data-masuk='@json($imc_masuk)'
                            data-keluar='@json($imc_keluar)'></canvas>
                    </div>
                </div>
            </div>

            <div
                class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md lg:col-span-4">
                <div
                    class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold uppercase tracking-wider text-white lg:text-xl">Barang Terlaris
                        Utama</h1>
                </div>
                <div class="min-h-0 flex-1 overflow-hidden rounded-b-2xl bg-white dark:bg-gray-800">
                    <div class="h-64 w-full p-4">
                        <canvas id="SVC" class="block h-full w-full" data-labels='@json($svc_labels)'
                            data-values='@json($svc_data)'></canvas>
                    </div>
                </div>
            </div>

            <!-- New: Top Performers (Sales & Customers) -->
            <div class="col-span-8 flex flex-col gap-6">
                <!-- Top Sales -->
                <div class="overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
                    <div
                        class="flex items-center justify-between border-b border-gray-100 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-700 dark:bg-gray-900">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-100">Performa Terbaik (Sales)
                        </h3>
                        <span
                            class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold text-blue-800">BERDASARKAN
                            PENDAPATAN</span>
                    </div>
                    <div class="space-y-4 p-4">
                        @foreach ($topSales as $index => $s)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="{{ $index == 0 ? 'bg-yellow-400 text-yellow-900' : 'bg-gray-100 text-gray-500' }} flex h-6 w-6 items-center justify-center rounded-full text-[10px] font-bold">
                                        {{ $index + 1 }}
                                    </span>
                                    <span
                                        class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $s->name }}</span>
                                </div>
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100">Rp
                                    {{ number_format($s->revenue, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        <!-- TAB 2: DASHBOARD PURCHASING -->
        <div x-show="activeTab === 'purchasing'" class="grid auto-rows-max grid-cols-8 gap-6 p-6 pt-0" style="display: none;">
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
                            data-labels='["PKP (850 Vendors)", "Non PKP (650 Vendors)"]'
                            data-values='[850, 650]'></canvas>
                        
                        <!-- Center Cutout Text with Icon -->
                        <div class="pointer-events-none absolute inset-0 z-0 flex flex-col items-center justify-center text-center">
                            <svg class="mb-1 h-6 w-6 text-[#225A97] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                            <span class="text-sm font-extrabold text-gray-900 dark:text-gray-100">Total: 1,500</span>
                        </div>
                    </div>

                    <!-- Legend Cards -->
                    <div class="mt-4 grid grid-cols-1 gap-2 text-xs md:grid-cols-2">
                        <div class="flex items-start gap-2 rounded-xl border border-gray-100 bg-gray-50/80 p-2.5 dark:border-gray-700 dark:bg-gray-800">
                            <span class="mt-0.5 h-3 w-3 shrink-0 rounded bg-[#225A97]"></span>
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-900 dark:text-gray-100">1. PKP (56.7%)</span>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400">Pengusaha Kena Pajak - Includes all registered PKP vendors.</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2 rounded-xl border border-gray-100 bg-gray-50/80 p-2.5 dark:border-gray-700 dark:bg-gray-800">
                            <span class="mt-0.5 h-3 w-3 shrink-0 rounded bg-[#f97316]"></span>
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-900 dark:text-gray-100">2. Non PKP (43.3%)</span>
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