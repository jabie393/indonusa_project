<x-app-layout>
    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 p-6 md:flex-row md:space-x-4 md:space-y-0">
            <!-- FILTER FORM -->
            <div id="filters-form" class="flex-end inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm rounded-2xl p-5 shadow-md">
                <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div class="flex flex-col">
                        <label class="py-1 text-sm text-gray-700 dark:text-gray-300">Stok Min (Threshold)</label>
                        <select name="threshold" class="rounded-xl border bg-white px-3 py-1 dark:bg-gray-700">
                            @foreach ([5, 10, 20, 50, 100] as $val)
                                <option value="{{ $val }}" {{ ($selectedThreshold ?? 20) == $val ? 'selected' : '' }}>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="py-1 text-sm text-gray-700 dark:text-gray-300">Filter Tanggal</label>
                        <div class="flex flex-row items-center gap-2">
                            <input type="date" name="date_start" class="rounded-xl border px-2 py-1" value="{{ $selectedDateStart ?? '' }}" />
                            <span class="text-sm text-gray-700 dark:text-gray-300">-</span>
                            <input type="date" name="date_end" class="rounded-xl border px-2 py-1" value="{{ $selectedDateEnd ?? '' }}" />
                            <button type="submit" class="flex cursor-pointer flex-row items-center rounded-xl bg-[#225A97] px-4 py-1 text-white">
                                <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg> Filter
                            </button>
                            <a href="{{ route('dashboard') }}" class="flex flex-row items-center rounded-xl px-4 py-1 text-blue-950 hover:bg-blue-100 dark:text-gray-300 dark:hover:text-blue-950">
                                <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                    <path d="M3 3v5h5"></path>
                                </svg> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- DOWNLOAD REPORTS -->
            <div class="flex gap-2">
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-outline btn-primary capitalize">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M16 10l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Laporan Sales
                    </label>
                    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                        <li><a href="{{ route('dashboard.supervisor.export.performance', ['type' => 'weekly']) }}">Weekly Performance</a></li>
                        <li><a href="{{ route('dashboard.supervisor.export.performance', ['type' => 'monthly']) }}">Monthly Performance</a></li>
                    </ul>
                </div>
                <a href="{{ route('dashboard.supervisor.export.quotations') }}" class="btn btn-primary capitalize">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Report Penawaran
                </a>
            </div>
        </div>

        <div class="grid auto-rows-max grid-cols-8 gap-6 p-6 pt-0">
            {{-- Stat Cards --}}
            <div class="col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Menunggu Persetujuan</h1>
                </div>
                <div class="flex flex-col justify-center p-6">
                    <div class="flex flex-col items-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 lg:text-6xl">{{ $totalPending }}</h1>
                        </div>
                        <div class="mt-2 flex w-full flex-row items-center justify-center gap-2">
                            <p class="text-lg font-bold text-gray-700 dark:text-gray-300">{{ $lastMonthPending }}</p>
                            <span class="text-xs text-gray-500">bulan lalu</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Pesanan Disetujui (Bulan Ini)</h1>
                </div>
                <div class="flex flex-col justify-center p-6">
                    <div class="flex flex-col items-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 lg:text-6xl">{{ $totalApproved }}</h1>
                        </div>
                        <div class="mt-2 flex w-full flex-row items-center justify-center gap-2">
                            <p class="text-lg font-bold text-gray-700 dark:text-gray-300">{{ $lastMonthApproved }}</p>
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
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 lg:text-3xl">{{ number_format($totalRevenue, 0, ',', '.') }}</h1>
                        </div>
                        <div class="mt-2 flex w-full flex-row items-center justify-center gap-2">
                            <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Rp{{ number_format($lastMonthRevenue, 0, ',', '.') }}</p>
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
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 lg:text-6xl">{{ $salesPerformance }}</h1>
                        </div>
                        <div class="mt-2 flex w-full flex-row items-center justify-center gap-2">
                            <p class="text-lg font-bold text-gray-700 dark:text-gray-300">{{ $lastMonthPerf }}</p>
                            <span class="text-xs text-gray-500">bulan lalu</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90 ">Profit Sales Per Akun</h1>
                </div>

                <div class="grid h-full grid-cols-4 justify-center gap-2 divide-x lg:gap-5">
                    @if (empty($salesPerfData))
                        <div class="col-span-4 p-6">
                            <div class="flex h-full items-center justify-center rounded-lg border border-gray-200 bg-gray-50 p-6 dark:border-gray-600 dark:bg-gray-700">
                                <p class="font-medium text-gray-600 dark:text-gray-300">Belum ada data profit sales</p>
                            </div>
                        </div>
                    @else
                        @foreach ($salesPerfData as $perf)
                            <div class="col-span-1 p-2 md:p-5">
                                <div class="flex flex-col">
                                    <h1 class="w-full truncate font-bold text-gray-900 dark:text-gray-100" title="{{ $perf['name'] }}">{{ $perf['name'] }}</h1>
                                    <h1 class="text-xs text-gray-500 dark:text-gray-400">Pencapaian: {{ $perf['percentage'] }}%</h1>
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
                <div class="inline-flex w-full justify-between rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A] p-5">
                    <h1 class="text-lg font-bold text-white ">Tren Penjualan (Rp)</h1>
                    <select id="imc-year-select" class="rounded-full border-none bg-white/10 px-4 py-1 text-sm text-white focus:outline-none">
                        @foreach ($imc_years as $y)
                            <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }} class="text-black">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="p-4" style="height: 300px;">
                    <canvas id="IMC" data-endpoint="{{ route('dashboard.supervisor.chart.data') }}" data-labels='@json($imc_labels)' data-masuk='@json($imc_masuk)' data-keluar='@json($imc_keluar)'></canvas>
                </div>
            </div>

            <div class="col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-4">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A] p-5">
                    <h1 class="text-lg font-bold text-white ">Distribusi Status Pesanan</h1>
                </div>
                <div class="p-4" style="height: 300px;">
                    <canvas id="SVC" data-labels='@json($svc_labels)' data-values='@json($svc_data)'></canvas>
                </div>
            </div>

            {{-- Tables --}}
            <div class="col-span-8 w-full rounded-2xl shadow-md">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A] p-5">
                    <h1 class="text-lg font-bold text-white ">Menunggu Persetujuan Penawaran</h1>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3">No Penawaran</th>
                                <th class="px-4 py-3">Customer</th>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Sales</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingOrders as $order)
                                <tr class="border-b hover:bg-gray-50 dark:border-gray-700">
                                    <td class="px-4 py-3 font-medium">{{ $order->nomor_penawaran }}</td>
                                    <td class="px-4 py-3">{{ $order->customer?->nama_customer }}</td>
                                    <td class="px-4 py-3">{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">{{ $order->sales?->name }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.request-order.show', $order->id) }}" class="text-blue-600 hover:underline">Review</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center">Tidak ada antrean persetujuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-span-8 w-full overflow-hidden rounded-2xl shadow-md">
                <div class="w-full bg-gradient-to-r from-[#225A97] to-[#0D223A] p-5">
                    <h1 class="text-lg font-bold text-white ">Performa Sales</h1>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3">Nama Sales</th>
                                <th class="px-4 py-3 text-center">Total Penawaran</th>
                                <th class="px-4 py-3 text-center">Disetujui</th>
                                <th class="px-4 py-3">Akurasi (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salesPerfData as $perf)
                                <tr class="border-b hover:bg-gray-50 dark:border-gray-700">
                                    <td class="px-4 py-3 font-medium">{{ $perf['name'] }}</td>
                                    <td class="px-4 py-3 text-center">{{ $perf['total'] }}</td>
                                    <td class="px-4 py-3 text-center">{{ $perf['approved'] }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="h-2.5 w-full max-w-[100px] rounded-full bg-gray-200 dark:bg-gray-700">
                                                <div class="h-2.5 rounded-full bg-blue-600" style="width: {{ $perf['percentage'] }}%"></div>
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

            <div class="col-span-8 w-full overflow-hidden rounded-2xl shadow-md">
                <div class="w-full bg-gradient-to-r from-[#225A97] to-[#0D223A] p-5">
                    <h1 class="text-lg font-bold text-white ">Aktivitas Pelanggan Terbaru</h1>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3">Customer</th>
                                <th class="px-4 py-3">Pesanan Terakhir</th>
                                <th class="px-4 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customerActivity as $order)
                                <tr class="border-b hover:bg-gray-50 dark:border-gray-700">
                                    <td class="px-4 py-3 font-medium">{{ $order->customer?->nama_customer ?? $order->customer_name }}</td>
                                    <td class="px-4 py-3">{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="{{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} badge ">
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
    </div>

    @vite(['resources/js/chart-dashboard-supervisor.js'])
</x-app-layout>
