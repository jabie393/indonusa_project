<x-app-layout>
    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 p-6 md:flex-row md:space-x-4 md:space-y-0">


            <!-- FILTER FORM -->
            <div class="flex-end inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm rounded-2xl p-5 shadow-md">
                <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-2">
                    <div class="flex flex-col">
                        <label class="text-nowrap py-2 text-sm text-gray-700 dark:text-gray-300">Threshold stok</label>
                        <select name="threshold" class="rounded-xl border py-1 pr-9">
                            <option value="10" {{ ($selectedThreshold ?? 20) == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ ($selectedThreshold ?? 20) == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ ($selectedThreshold ?? 20) == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="py-2 text-sm text-gray-700 dark:text-gray-300">Filter tanggal</label>
                        <div class="flex flex-row items-center">
                            <input type="date" name="date_start" class="rounded-xl border px-2 py-1" value="{{ $selectedDateStart ?? '' }}" />
                            <span class="text-sm text-gray-700 dark:text-gray-300">-</span>
                            <input type="date" name="date_end" class="rounded-xl border px-2 py-1" value="{{ $selectedDateEnd ?? '' }}" />
                            <button type="submit" class="ml-3 flex cursor-pointer flex-row items-center rounded-xl bg-[#225A97] px-4 py-1 text-white">
                                <svg class="pr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter h-4 w-4">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg> Filter</button>
                            <a href="{{ route('dashboard') }}" class="ml-3 flex flex-row items-center rounded-xl px-4 py-1 text-blue-950 hover:bg-blue-100 dark:text-gray-300 dark:hover:text-blue-950">
                                <svg class="pr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw h-4 w-4">
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
            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-3">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-sm font-bold uppercase tracking-wider text-white opacity-90">Total Pendapatan</h1>
                </div>
                <div class="flex h-full flex-col justify-center rounded-b-2xl bg-white p-6 dark:bg-gray-800">
                    <div class="flex flex-col">
                        <span class="text-sm font-medium uppercase text-gray-500">Total Keseluruhan (IDR)</span>
                        <h2 class="mt-2 text-4xl font-extrabold text-blue-900 dark:text-blue-400">
                            Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
                        </h2>
                    </div>
                </div>
            </div>

            <!-- 2nd Card: Total Orders -->
            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-[#0D223A]">
                    <h1 class="p-5 text-sm font-bold uppercase tracking-wider text-white opacity-90">Total Pesanan</h1>
                </div>
                <div class="flex h-full flex-col justify-center rounded-b-2xl bg-white p-6 dark:bg-gray-800">
                    <div class="flex items-end gap-2">
                        <h2 class="text-5xl font-black text-gray-800 dark:text-gray-100">
                            {{ $totalOrders ?? 0 }}
                        </h2>
                        <span class="mb-1 text-xs font-semibold uppercase text-gray-400">Selesai</span>
                    </div>
                </div>
            </div>

            <!-- 3rd Card: Operational Health (Pending) -->
            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-3">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-[#0D223A]">
                    <h1 class="p-5 text-sm font-bold uppercase tracking-wider text-white opacity-90">Alur Tertunda</h1>
                </div>
                <div class="flex h-full flex-col justify-center rounded-b-2xl bg-white p-6 dark:bg-gray-800">
                    <div class="flex items-center gap-6">
                        <div class="flex flex-col">
                            <h2 class="text-5xl font-black">
                                {{ $totalPending ?? 0 }}
                            </h2>
                            <span class="text-xs font-bold uppercase text-gray-400">Belum Diproses</span>
                        </div>
                        <div class="flex-1 space-y-2 border-l border-gray-100 pl-6 dark:border-gray-700">
                            @php $p = $statusBreakdown ?? []; @endphp
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">Supervisor</span>
                                <span class="font-bold text-gray-800 dark:text-gray-200">{{ $p['sent_to_supervisor'] ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500">Gudang</span>
                                <span class="font-bold text-gray-800 dark:text-gray-200">{{ $p['sent_to_warehouse'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md lg:col-span-4">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm inline-flex w-full justify-between rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold uppercase tracking-wider text-white lg:text-xl">Tren Pendapatan</h1>
                    <div class="m-3">
                        <select id="imc-year-select" class="rounded-full border-none bg-[#0D223A] px-4 py-2 text-xs text-white focus:ring-0">
                            @foreach ($imc_years as $year)
                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="min-h-0 flex-1 overflow-hidden rounded-b-2xl bg-white dark:bg-gray-800">
                    <div class="h-64 w-full p-4">
                        <canvas id="IMC" class="block h-full w-full" data-endpoint="{{ route('dashboard.general-affair.chart.data') }}" data-labels='@json($imc_labels)' data-masuk='@json($imc_masuk)' data-keluar='@json($imc_keluar)'></canvas>
                    </div>
                </div>
            </div>

            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md lg:col-span-4">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold uppercase tracking-wider text-white lg:text-xl">Barang Terlaris Utama</h1>
                </div>
                <div class="min-h-0 flex-1 overflow-hidden rounded-b-2xl bg-white dark:bg-gray-800">
                    <div class="h-64 w-full p-4">
                        <canvas id="SVC" class="block h-full w-full" data-labels='@json($svc_labels)' data-values='@json($svc_data)'></canvas>
                    </div>
                </div>
            </div>

            <!-- New: Top Performers (Sales & Customers) -->
            <div class="col-span-8 flex flex-col gap-6">
                <!-- Top Sales -->
                <div class="overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
                    <div class="flex items-center justify-between border-b border-gray-100 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-700 dark:bg-gray-900">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-100">Performa Terbaik (Sales)</h3>
                        <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold text-blue-800">BERDASARKAN PENDAPATAN</span>
                    </div>
                    <div class="space-y-4 p-4">
                        @foreach ($topSales as $index => $s)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="{{ $index == 0 ? 'bg-yellow-400 text-yellow-900' : 'bg-gray-100 text-gray-500' }} flex h-6 w-6 items-center justify-center rounded-full text-[10px] font-bold">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $s->name }}</span>
                                </div>
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($s->revenue, 0, ',', '.') }}</span>
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
