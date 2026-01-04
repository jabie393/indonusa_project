<x-app-layout>
    <div
        class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 p-6 md:flex-row md:space-x-4 md:space-y-0">


            <!-- FILTER FORM -->
            <div
                class="flex-end inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm rounded-2xl p-5 shadow-md">
                <form action="{{ route('dashboard') }}" method="GET"
                    class="flex flex-col items-center gap-2 md:flex-row">
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
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="grid auto-rows-max grid-cols-8 gap-6 p-6 pt-0">
            <div
                class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div
                    class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white">Pending Orders</h1>
                </div>
                <div class="flex flex-col justify-center">
                    <div class="flex flex-col items-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h1 class="text-end text-4xl font-bold text-gray-900 dark:text-gray-100 lg:text-6xl">
                                32
                            </h1>
                            <span class="text-lg text-gray-500 dark:text-gray-400">Order</span>
                        </div>
                        <div class="flex w-full flex-row items-end">
                            <p class="w-full pr-2 text-end text-lg font-bold text-gray-700 dark:text-gray-300">
                                12423
                            </p>
                            <span class="w-full text-sm text-gray-500 dark:text-gray-400">last month</span>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div
                    class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white">Approved Orders</h1>
                </div>
                <div class="flex flex-col justify-center">
                    <div class="flex flex-col items-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h1 class="text-end text-4xl font-bold text-gray-900 dark:text-gray-100 lg:text-6xl">
                                41
                            </h1>
                            <span class="text-lg text-gray-500 dark:text-gray-400">Order</span>
                        </div>
                        <div class="flex w-full flex-row items-end">
                            <p class="w-full pr-2 text-end text-lg font-bold text-gray-700 dark:text-gray-300">
                                12423
                            </p>
                            <span class="w-full text-sm text-gray-500 dark:text-gray-400">last month</span>
                        </div>

                    </div>
                </div>
            </div>

            <div
                class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div
                    class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white">Total Sales</h1>
                </div>
                <div class="flex h-full flex-col justify-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="flex w-full flex-row items-end justify-center">
                            <h1 class="text-end text-4xl font-bold text-gray-900 dark:text-gray-100 lg:text-6xl">
                                52
                            </h1>
                            <span class="text-lg text-gray-500 dark:text-gray-400">Sales</span>
                        </div>
                        <div class="flex w-full flex-row items-end">
                            <p class="w-full pr-2 text-end text-lg font-bold text-gray-700 dark:text-gray-300">
                                234
                            </p>
                            <span class="w-full text-sm text-gray-500 dark:text-gray-400">last month</span>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div
                    class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white dark:text-white">Customers</h1>
                </div>
                <div class="flex h-full flex-col justify-center">

                    <div class="flex w-full flex-row items-end justify-center">
                        <h1 class="text-end text-4xl font-bold text-gray-900 dark:text-gray-100 lg:text-6xl">
                            32
                        </h1>
                        <span class="text-lg text-gray-500 dark:text-gray-400">Customer</span>
                    </div>
                    <div class="flex w-full flex-row items-end">
                        <p class="w-full pr-2 text-end text-lg font-bold text-gray-700 dark:text-gray-300">
                            12423
                        </p>
                        <span class="w-full text-sm text-gray-500 dark:text-gray-400">last month</span>
                    </div>
                </div>
            </div>

            <div
                class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-4">
                <div
                    class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm inline-flex w-full justify-between rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Sales Performance</h1>
                    <button
                        class="m-3 inline-flex cursor-pointer items-center justify-center rounded-full bg-[#225A97] px-5 py-2">
                        <span class="text-white">
                            Tahun Ini
                        </span>
                    </button>
                </div>
                <div class="min-h-0 flex-1 overflow-hidden">
                    <div class="h-full w-full">
                        <canvas id="IMC" class="block h-full w-full"></canvas>
                    </div>
                </div>
            </div>

            <div
                class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-4">
                <div
                    class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Sales By Item</h1>
                </div>
                <div class="min-h-0 flex-1 overflow-hidden">
                    <div class="h-full w-full">
                        <canvas id="SVC" class="block h-full w-full"></canvas>
                    </div>
                </div>

            </div>

            <div
                class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm col-span-8 w-full rounded-2xl shadow-md">
                <div
                    class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Sales Order</h1>
                </div>
                <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">No. Sales Order</th>
                            <th class="px-4 py-3">Request Order</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Nama Customer</th>
                            <th class="px-4 py-3">Jumlah Item</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-3">
                                <strong>ROP-4215</strong>
                            </td>
                            <td class="px-4 py-3">
                                <a href="" class="text-decoration-none">
                                    TER-41133
                                </a>
                            </td>
                            <td class="px-4 py-3">12 nov 2025</td>
                            <td class="px-4 py-3">Hilmi</td>
                            <td class="px-4 py-3">243 item(s)</td>
                            <td class="px-4 py-3">
                                <span class="badge pending">Peding</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex h-full items-center gap-2 px-4 py-3">
                                    <a href=""
                                        class="btn mb-2 me-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                        title="Lihat Detail" title="Lihat Detail">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Kelola data Chart ada di JS --}}
    @vite(['resources/js/chart-dashboard-sales.js'])
</x-app-layout>
