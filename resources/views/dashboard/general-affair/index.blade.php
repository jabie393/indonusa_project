<x-app-layout>
    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 p-6 md:flex-row md:space-x-4 md:space-y-0">


            <!-- FILTER FORM -->
            <div
                class="flex-end inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm rounded-2xl p-5 shadow-md">
                <form id="filters-form" action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-2">
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

        <div class="grid auto-rows-max grid-cols-8 gap-6 p-6 pt-0">
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
    </div>

    {{-- Kelola data Chart ada di JS --}}
    @vite(['resources/js/chart-dashboard-general-affair.js'])
</x-app-layout>