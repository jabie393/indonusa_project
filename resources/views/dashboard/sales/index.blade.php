<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 p-6 md:flex-row md:space-x-4 md:space-y-0">


            <!-- FILTER FORM -->
            <div class="flex-end inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm rounded-2xl p-5 shadow-md">
                <form action="{{ route('dashboard') }}" method="GET" id="filters-form" class="flex flex-col items-center gap-2 md:flex-row">
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
                        <div class="flex flex-col items-center md:flex-row">
                            <input type="date" name="date_start" class="rounded-xl border px-2 py-1" value="{{ $selectedDateStart ?? '' }}" />
                            <span class="text-sm text-gray-700 dark:text-gray-300">-</span>
                            <input type="date" name="date_end" class="rounded-xl border px-2 py-1" value="{{ $selectedDateEnd ?? '' }}" />
                            <button type="submit" class="mt-3 flex cursor-pointer flex-row items-center rounded-xl bg-[#225A97] px-4 py-1 text-white md:ml-3 md:mt-0">
                                <svg class="pr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter h-4 w-4">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg> Filter</button>
                            <a href="{{ route('dashboard') }}" class="mt-3 flex flex-row items-center rounded-xl px-4 py-1 text-blue-950 hover:bg-blue-100 dark:text-gray-300 dark:hover:text-blue-950 md:ml-3 md:mt-0">
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

            <!-- DOWNLOAD REPORTS -->
            <div class="flex gap-2">
                <a href="{{ route('dashboard.sales.export.quotations') }}" class="flex flex-row items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 font-semibold text-white hover:bg-[#19426d]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Report Penawaran
                </a>
            </div>
        </div>

        <div class="grid auto-rows-max grid-cols-8 gap-6 p-6 pt-0">
            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Quotation</h1>
                </div>
                <div class="flex h-full flex-col justify-center">
                    <div class="flex flex-col items-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h1 class="text-end text-4xl font-bold text-gray-900 dark:text-gray-100 lg:text-6xl">
                                {{ $totalQuotation ?? 0 }}
                            </h1>
                            <span class="text-lg text-gray-500 dark:text-gray-400">Quotations</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Sales Order</h1>
                </div>
                <div class="flex h-full flex-col justify-center">
                    <div class="flex flex-col items-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h1 class="text-end text-4xl font-bold text-gray-900 dark:text-gray-100 lg:text-6xl">
                                {{ $totalApproved ?? 0 }}
                            </h1>
                            <span class="text-lg text-gray-500 dark:text-gray-400">Sales Order</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Total Penjualan</h1>
                </div>
                <div class="flex h-full flex-col justify-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h1 class="text-end text-4xl font-bold text-gray-900 dark:text-gray-100 lg:text-6xl">
                                {{ $totalSales ?? 0 }}
                            </h1>
                            <span class="text-lg text-gray-500 dark:text-gray-400">Penjualan</span>
                        </div>
                        <div class="flex w-full flex-row items-end">
                            <p class="w-full pr-2 text-end text-lg font-bold text-gray-700 dark:text-gray-300">
                                {{ $lastMonthSales ?? 0 }}
                            </p>
                            <span class="w-full text-sm text-gray-500 dark:text-gray-400">bulan lalu</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Total Nominal Terjual</h1>
                </div>
                <div class="flex h-full flex-col justify-center p-4">
                    <div class="flex w-full flex-col items-center justify-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h2 class="text-center text-xl font-bold text-gray-900 dark:text-gray-100 lg:text-2xl">
                                Rp{{ number_format($totalProfit ?? 0, 0, ',', '.') }}
                            </h2>
                        </div>
                        <div class="mt-2 flex w-full flex-row items-center justify-center gap-1">
                            <p class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                Rp{{ number_format($lastMonthProfit ?? 0, 0, ',', '.') }}
                            </p>
                            <span class="text-[10px] text-gray-500 dark:text-gray-400">bulan lalu</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-4">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm inline-flex w-full justify-between rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Sales Performance</h1>
                    <div class="m-3">
                        <select id="imc-year-select" class="rounded-full border-none bg-[#225A97] px-5 py-2 text-white focus:ring-0">
                            @foreach ($imc_years as $year)
                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="min-h-0 flex-1 overflow-hidden">
                    <div class="h-64 w-full p-4">
                        <canvas id="IMC" class="block h-full w-full" data-endpoint="{{ route('dashboard.sales.chart.data') }}" data-labels='@json($imc_labels)' data-masuk='@json($imc_masuk)' data-keluar='@json($imc_keluar)'></canvas>
                    </div>
                </div>
            </div>

            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-4">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Penjualan per Barang</h1>
                </div>
                <div class="min-h-0 flex-1 overflow-hidden">
                    <div class="h-64 w-full p-4">
                        <canvas id="SVC" class="block h-full w-full" data-labels='@json($svc_labels)' data-values='@json($svc_data)'></canvas>
                    </div>
                </div>

            </div>

            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 w-full rounded-2xl shadow-md">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="text-md p-5 font-bold uppercase tracking-wider text-white opacity-90">Riwayat Request Order</h1>
                </div>
                <div class="overflow-x-auto">
                    <table id="dashTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3">No. Request Order</th>
                                <th class="px-4 py-3">No. Sales Order</th>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Nama Pelanggan</th>
                                <th class="px-4 py-3">Jumlah Item</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salesOrders as $order)
                                <tr>
                                    <td class="px-4 py-3">
                                        <strong>{{ $order->request_number }}</strong>
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $order->sales_order_number ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">{{ $order->created_at->format('d M Y') }}</td>
                                    <td class="px-4 py-3">{{ $order->customer_name }}</td>
                                    <td class="px-4 py-3">{{ $order->items->count() }} item</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $st = $order->order?->status ?? 'open';
                                            $statusClass = [
                                                'open' => 'bg-yellow-100 text-yellow-800',
                                                'pending_approval' => 'bg-yellow-100 text-yellow-800',
                                                'approved_supervisor' => 'bg-green-100 text-green-800',
                                                'approved_warehouse' => 'bg-blue-100 text-blue-800',
                                                'sent_to_warehouse' => 'bg-indigo-100 text-indigo-800',
                                                'completed' => 'bg-green-200 text-green-900',
                                                'rejected' => 'bg-red-100 text-red-800',
                                            ];
                                            $statusNames = [
                                                'open' => 'Open',
                                                'pending_approval' => 'Menunggu Persetujuan',
                                                'approved_supervisor' => 'Disetujui Supervisor',
                                                'approved_warehouse' => 'Disetujui Gudang',
                                                'sent_to_warehouse' => 'Dikirim ke Gudang',
                                                'completed' => 'Selesai',
                                                'rejected' => 'Ditolak',
                                            ];
                                            $class = $statusClass[$st] ?? 'bg-gray-100 text-gray-800';
                                            $name = $statusNames[$st] ?? ucwords(str_replace('_', ' ', $st));
                                        @endphp
                                        <span class="{{ $class }} badge inset-ring px-2.5 py-0.5 text-xs font-medium">
                                            {{ $name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('sales.request-order.show', $order->id) }}" class="inline-flex items-center rounded-lg bg-blue-700 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                Detail
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    {{-- Kelola data Chart ada di JS --}}
    @vite(['resources/js/chart-dashboard-sales.js'])
</x-app-layout>
