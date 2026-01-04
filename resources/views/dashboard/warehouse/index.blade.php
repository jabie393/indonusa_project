<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 p-6 md:flex-row md:space-x-4 md:space-y-0">


            <!-- FILTER FORM -->
            <div class="flex-end inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm rounded-2xl p-5 shadow-md">
                <form id="filters-form" action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-2">
                    <div class="flex flex-col">
                        <label class="py-2 text-sm text-gray-700 dark:text-gray-300">Threshold stok</label>
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
                                <svg class="pr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-filter h-4 w-4">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg> Filter</button>
                            <a href="{{ route('dashboard') }}" class="ml-3 flex flex-row items-center rounded-xl px-4 py-1 text-blue-950 hover:bg-blue-100 dark:text-gray-300 dark:hover:text-blue-950">
                                <svg class="pr-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw h-4 w-4">
                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                    <path d="M3 3v5h5"></path>
                                </svg>
                                Reset</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>



        <div class="grid auto-rows-max grid-cols-8 gap-6 p-6 pt-0">
            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white">Jumlah Barang</h1>
                </div>
                <div class="flex h-full flex-col justify-center">
                    <div class="flex flex-col items-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h1 class="text-end text-4xl font-bold text-gray-900 dark:text-gray-100 lg:text-6xl">
                                {{ $totalBarang ?? 0 }}
                            </h1>
                            <span class="text-lg text-gray-500 dark:text-gray-400">Total Barang</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white">Jumlah Stok</h1>
                </div>
                <div class="flex h-full flex-col justify-center">
                    <div class="flex flex-col items-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h1 class="text-end text-4xl font-bold text-gray-900 dark:text-gray-100 lg:text-6xl">
                                {{ $totalStok ?? 0 }}
                            </h1>
                            <span class="text-lg text-gray-500 dark:text-gray-400">Total Stok</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white">Barang masuk hari ini</h1>
                </div>
                <div class="flex h-full flex-col justify-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h1 class="text-end text-4xl font-bold text-gray-900 dark:text-gray-100 lg:text-6xl">
                                {{ $barangMasukToday ?? 0 }}
                            </h1>
                            <span class="text-lg text-gray-500 dark:text-gray-400">Barang</span>
                        </div>
                        <div class="flex w-full flex-row items-end">
                            <p class="w-full pr-2 text-end text-lg font-bold text-gray-700 dark:text-gray-300">
                                {{ $barangMasukLastMonth ?? 0 }}
                            </p>
                            <span class="w-full text-sm text-gray-500 dark:text-gray-400">last month</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white dark:text-white">Barang keluar hari ini</h1>
                </div>
                <div class="flex h-full flex-col justify-center">

                    <div class="flex w-full flex-row items-end justify-center">
                        <h1 class="text-end text-4xl font-bold text-gray-900 dark:text-gray-100 lg:text-6xl">
                            {{ $barangKeluarToday ?? 0 }}
                        </h1>
                        <span class="text-lg text-gray-500 dark:text-gray-400">Barang</span>
                    </div>
                    <div class="flex w-full flex-row items-end">
                        <p class="w-full pr-2 text-end text-lg font-bold text-gray-700 dark:text-gray-300">
                            {{ $barangKeluarLastMonth ?? 0 }}
                        </p>
                        <span class="w-full text-sm text-gray-500 dark:text-gray-400">last month</span>
                    </div>
                </div>
            </div>

            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Stok barang rendah</h1>
                </div>

                <div class="grid h-full grid-cols-4 justify-center gap-2 divide-x lg:gap-5">
                    @if ($lowStockItems->isEmpty())
                        <div class="col-span-4 p-6">
                            <div class="flex h-full items-center justify-center rounded-lg border border-gray-200 bg-gray-50 p-6 dark:border-gray-600 dark:bg-gray-700">
                                <p class="font-medium text-gray-600 dark:text-gray-300">Belum ada barang lagi yang stoknya rendah</p>
                            </div>
                        </div>
                    @else
                        @foreach ($lowStockItems as $item)
                            <div class="col-span-1 p-2 md:p-5">
                                <div class="flex flex-col">
                                    <h1 class="font-bold text-gray-900 dark:text-gray-100">{{ $item->nama_barang }}</h1>
                                    <h1 class="text-sm text-gray-500 dark:text-gray-400">{{ $item->kode_barang ?? '-' }}</h1>
                                </div>
                                <div class="flex w-full flex-row items-end">
                                    <h1 class="w-full text-end text-4xl font-bold text-gray-900 dark:text-gray-100 md:text-6xl">
                                        {{ $item->stok }}
                                    </h1>
                                    <span class="w-full text-sm text-gray-500 dark:text-gray-400">Stok</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 w-full rounded-2xl shadow-md">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Low Stock Items Table</h1>
                </div>
                <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Item</th>
                            <th scope="col" class="px-4 py-3">Stock</th>
                            <th scope="col" class="px-4 py-3">Minimum</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="h-min-[300px]">
                        @forelse($allLowStockItems as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $item->nama_barang }}</td>
                                <td class="px-4 py-3">{{ $item->stok }}</td>
                                <td class="px-4 py-3">20</td>
                                <td class="px-4 py-3">{{ $item->stok <= 0 ? 'Out' : 'Low' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-3" colspan="4">Tidak ada barang stok rendah</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-4">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm inline-flex w-full justify-between rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Inventory Movement Chart</h1>
                    @php
                        $currentYear = now()->year;
                        $selected = $selectedYear ?? $currentYear;
                        $years = $imc_years ?? [$currentYear];
                    @endphp
                    <select id="imc-year-select" class="m-3 rounded-full border py-1 pl-3 pr-7">
                        @foreach ($years as $y)
                            <option value="{{ $y }}" {{ (int) $selected === (int) $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="min-h-0 flex-1 overflow-hidden">
                    <div class="h-full w-full">
                        <canvas id="IMC" class="block h-full w-full" data-labels='@json($imc_labels)' data-masuk='@json($imc_masuk)'
                            data-keluar='@json($imc_keluar)' data-endpoint='{{ route('dashboard.chart.data') }}'></canvas>
                    </div>
                </div>
            </div>

            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-4">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Stock Value Chart</h1>
                </div>
                <div class="min-h-0 flex-1 overflow-hidden">
                    <div class="h-full w-full">
                        <canvas id="SVC" class="block h-full w-full" data-labels='@json($svc_labels)' data-values='@json($svc_data)'></canvas>
                    </div>
                </div>

            </div>
            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 w-full rounded-2xl shadow-md">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Recent Inbound Items</h1>
                </div>
                <table id="DataTable2" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Date</th>
                            <th scope="col" class="px-4 py-3">Item</th>
                            <th scope="col" class="px-4 py-3">Qty</th>
                            <th scope="col" class="px-4 py-3">Supplier</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="h-min-[300px]">
                        @forelse($recentInbound as $in)
                            <tr>
                                <td class="px-4 py-3">{{ $in->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-3">{{ $in->nama_barang }}</td>
                                <td class="px-4 py-3">{{ $in->stok ?? '-' }}</td>
                                <td class="px-4 py-3">{{ optional($in->formUser)->display_name ?? '-' }}</td> {{-- tampilkan name (role) dari kolom form --}}
                                <td class="px-4 py-3">{{ $in->status ?? 'Complete' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-3">-</td>
                                <td class="px-4 py-3">-</td>
                                <td class="px-4 py-3">-</td>
                                <td class="px-4 py-3">-</td>
                                <td class="px-4 py-3">Tidak ada data inbound</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 w-full rounded-2xl shadow-md">
                <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Recent Outbound Items</h1>
                </div>
                <table id="DataTable3" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Date</th>
                            <th scope="col" class="px-4 py-3">Item</th>
                            <th scope="col" class="px-4 py-3">Qty</th>
                            <th scope="col" class="px-4 py-3">Seller</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="h-min-[300px]">
                        @forelse($recentOutbound as $out)
                            <tr>
                                <td class="px-4 py-3">{{ optional($out->changed_at)->format('d M Y') ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $out->nama_barang }}</td>
                                <td class="px-4 py-3">{{ $out->stok ?? '-' }}</td>
                                <td class="px-4 py-3">{{ optional($out->formUser)->display_name ?? '-' }}</td> {{-- tampilkan name (role) dari kolom form --}}
                                <td class="px-4 py-3">{{ $out->status ?? ($out->new_status ?? 'Keluar') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-3">-</td>
                                <td class="px-4 py-3">-</td>
                                <td class="px-4 py-3">-</td>
                                <td class="px-4 py-3">-</td>
                                <td class="px-4 py-3">Tidak ada data outbound</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Kelola data Chart ada di JS --}}
    @vite(['resources/js/chart-dashboard-warehouse.js'])
</x-app-layout>
