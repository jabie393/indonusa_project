<x-app-layout>
    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-xl font-bold">
                    Halo, {{ Auth::user()->name }} (
                    <span class="font-semibold">
                        {{ Auth::user()->role ?? 'User' }}
                    </span> )
                </h1>
                <p class="mt-2">
                    {{ __('Selamat datang di Dashboard!') }}
                </p>
            </div>
            <div class="flex-end rounded-full bg-[#225A97] px-6 py-3">
                <button class="inline-flex cursor-pointer align-middle text-white">
                    <svg width="22" height="22" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7.07793 8.45801H4.93785C4.75611 8.45801 4.60901 8.59174 4.60901 8.75695V10.7025C4.60901 10.8675 4.75611 11.0014 4.93785 11.0014H7.07793C7.25967 11.0014 7.40677 10.8675 7.40677 10.7025V8.75695C7.40677 8.59194 7.25967 8.45801 7.07793 8.45801ZM6.74909 10.4035H5.26669V9.0559H6.74909V10.4035Z"
                            fill="white" />
                        <path
                            d="M12.0693 8.45801H9.92918C9.74744 8.45801 9.60034 8.59174 9.60034 8.75695V10.7025C9.60034 10.8675 9.74744 11.0014 9.92918 11.0014H12.0693C12.2508 11.0014 12.3981 10.8675 12.3981 10.7025V8.75695C12.3981 8.59194 12.2508 8.45801 12.0693 8.45801ZM11.7404 10.4035H10.258V9.0559H11.7404V10.4035Z"
                            fill="white" />
                        <path
                            d="M16.9067 8.45801H14.7666C14.5851 8.45801 14.4378 8.59174 14.4378 8.75695V10.7025C14.4378 10.8675 14.5851 11.0014 14.7666 11.0014H16.9067C17.0882 11.0014 17.2356 10.8675 17.2356 10.7025V8.75695C17.2356 8.59194 17.0882 8.45801 16.9067 8.45801ZM16.5779 10.4035H15.0955V9.0559H16.5779V10.4035Z"
                            fill="white" />
                        <path
                            d="M7.07793 12.8896H4.93785C4.75611 12.8896 4.60901 13.0236 4.60901 13.1886V15.1341C4.60901 15.2991 4.75611 15.4331 4.93785 15.4331H7.07793C7.25967 15.4331 7.40677 15.2991 7.40677 15.1341V13.1886C7.40677 13.0236 7.25967 12.8896 7.07793 12.8896ZM6.74909 14.8352H5.26669V13.4875H6.74909V14.8352Z"
                            fill="white" />
                        <path
                            d="M12.0693 12.8896H9.92918C9.74744 12.8896 9.60034 13.0236 9.60034 13.1886V15.1341C9.60034 15.2991 9.74744 15.4331 9.92918 15.4331H12.0693C12.2508 15.4331 12.3981 15.2991 12.3981 15.1341V13.1886C12.3981 13.0236 12.2508 12.8896 12.0693 12.8896ZM11.7404 14.8352H10.258V13.4875H11.7404V14.8352Z"
                            fill="white" />
                        <path
                            d="M16.9067 12.8896H14.7666C14.5851 12.8896 14.4378 13.0236 14.4378 13.1886V15.1341C14.4378 15.2991 14.5851 15.4331 14.7666 15.4331H16.9067C17.0882 15.4331 17.2356 15.2991 17.2356 15.1341V13.1886C17.2356 13.0236 17.0882 12.8896 16.9067 12.8896ZM16.5779 14.8352H15.0955V13.4875H16.5779V14.8352Z"
                            fill="white" />
                        <path
                            d="M19.5111 2.66925H17.3393C17.3364 2.66925 17.334 2.66985 17.3312 2.67005V2.27185C17.3312 1.62055 16.7484 1.09082 16.032 1.09082C15.3156 1.09082 14.7329 1.62075 14.7329 2.27185V2.66925H12.2857V2.27185C12.2857 1.62055 11.703 1.09082 10.9865 1.09082C10.2701 1.09082 9.68739 1.62075 9.68739 2.27185V2.66925H7.23995V2.27185C7.23995 1.62055 6.65725 1.09082 5.94082 1.09082C5.22439 1.09082 4.64169 1.62075 4.64169 2.27185V2.66945C4.64059 2.66945 4.63949 2.66925 4.6384 2.66925H2.46696C2.28522 2.66925 2.13812 2.80298 2.13812 2.96819V18.4627C2.13812 18.6277 2.28522 18.7616 2.46696 18.7616H19.5111C19.6927 18.7616 19.84 18.6277 19.84 18.4627V2.96819C19.84 2.80298 19.6927 2.66925 19.5111 2.66925ZM15.3906 2.27185C15.3906 1.95019 15.6782 1.68871 16.032 1.68871C16.3858 1.68871 16.6735 1.95019 16.6735 2.27185V3.92143C16.6735 4.24289 16.3858 4.50457 16.032 4.50457C15.6782 4.50457 15.3906 4.24309 15.3906 3.92143V2.27185ZM10.9865 1.68871C11.3401 1.68871 11.628 1.95019 11.628 2.27185V3.92143C11.628 4.24289 11.3404 4.50457 10.9865 4.50457C10.6327 4.50457 10.3451 4.24309 10.3451 3.92143V2.99609C10.3459 2.98673 10.3481 2.97776 10.3481 2.96799C10.3481 2.95823 10.3459 2.94926 10.3451 2.93989V2.27185C10.3451 1.95019 10.6327 1.68871 10.9865 1.68871ZM5.29936 2.27185C5.29936 1.95019 5.58699 1.68871 5.94082 1.68871C6.29443 1.68871 6.58227 1.95019 6.58227 2.27185V3.92143C6.58227 4.24289 6.29465 4.50457 5.94082 4.50457C5.58699 4.50457 5.29936 4.24309 5.29936 3.92143V2.27185ZM19.1823 18.1638H2.7958V3.26714H4.6384C4.63949 3.26714 4.64059 3.26694 4.64169 3.26694V3.92163C4.64169 4.57293 5.22461 5.10266 5.94082 5.10266C6.65703 5.10266 7.23995 4.57293 7.23995 3.92163V3.26734H9.68739V3.92163C9.68739 4.57293 10.2703 5.10266 10.9865 5.10266C11.7027 5.10266 12.2857 4.57293 12.2857 3.92163V3.26734H14.7329V3.92163C14.7329 4.57293 15.3156 5.10266 16.032 5.10266C16.7484 5.10266 17.3312 4.57293 17.3312 3.92163V3.26634C17.334 3.26634 17.3364 3.26714 17.3393 3.26714H19.1823V18.1638Z"
                            fill="white" />
                    </svg>
                    14 Aug 2025
                </button>
            </div>
        </div>
        <div class="grid auto-rows-max grid-cols-8 gap-6 p-6 pt-0">

            <div class="col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Jumlah Barang</h1>
                </div>
                <div class="flex flex-col justify-center">
                    <div class="flex flex-col items-center">
                        <div class="flex w-full flex-row justify-center items-end">
                            <h1 class="text-end text-4xl lg:text-6xl font-bold">
                                12
                            </h1>
                            <span class="text-lg text-gray-500">Total Barang</span>
                        </div>
                        <div class="flex w-full flex-row items-end">
                            <p class="w-full pr-2 text-end text-lg font-bold text-gray-700">
                                12423
                            </p>
                            <span class="w-full text-sm text-gray-500">last month</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Jumlah Stok</h1>
                </div>
                <div class="flex flex-col justify-center">
                    <div class="flex flex-col items-center">
                        <div class="flex w-full flex-row justify-center items-end">
                            <h1 class="text-end text-4xl lg:text-6xl font-bold">
                                12423
                            </h1>
                            <span class="text-lg text-gray-500">Total Stok</span>
                        </div>
                        <div class="flex w-full flex-row items-end">
                            <p class="w-full pr-2 text-end text-lg font-bold text-gray-700">
                                12423
                            </p>
                            <span class="w-full text-sm text-gray-500">last month</span>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Barang masuk hari ini</h1>
                </div>
                <div class="flex h-full flex-col justify-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="flex w-full flex-row justify-center items-end">
                            <h1 class="text-end text-4xl lg:text-6xl font-bold">
                                12
                            </h1>
                            <span class="text-lg text-gray-500">Barang</span>
                        </div>
                        <div class="flex w-full flex-row items-end">
                            <p class="w-full pr-2 text-end text-lg font-bold text-gray-700">
                                234
                            </p>
                            <span class="w-full text-sm text-gray-500">last month</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-8 flex w-full flex-col rounded-2xl shadow-md md:col-span-2">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Barang keluar hari ini</h1>
                </div>
                <div class="flex h-full flex-col justify-center">

                    <div class="flex w-full flex-row  justify-center items-end">
                        <h1 class="text-end text-4xl lg:text-6xl font-bold">
                            12
                        </h1>
                        <span class="text-lg text-gray-500">Barang</span>
                    </div>
                    <div class="flex w-full flex-row items-end">
                        <p class="w-full pr-2 text-end text-lg font-bold text-gray-700">
                            12423
                        </p>
                        <span class="w-full text-sm text-gray-500">last month</span>
                    </div>
                </div>
            </div>

            <div class="col-span-8 flex w-full flex-col rounded-2xl shadow-md">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Stok barang rendah</h1>
                </div>
                <div class="grid h-full grid-cols-4 justify-center gap-2 lg:gap-5 divide-x">
                    <div class="col-span-1 p-2 md:p-5">
                        <div class="flex flex-col">
                            <h1 class="font-bold">
                                Laptop ASUS
                            </h1>
                            <h1>
                                BR4214
                            </h1>
                        </div>
                        <div class="flex w-full flex-row items-end">
                            <h1 class="w-full text-end text-4xl md:text-6xl font-bold">
                                63
                            </h1>
                            <span class="w-full text-sm text-gray-500">Stok</span>
                        </div>
                    </div>
                    <div class="col-span-1 p-2 md:p-5">
                        <div class="flex flex-col">
                            <h1 class="font-bold">
                                Laptop ASUS
                            </h1>
                            <h1>
                                BR241
                            </h1>
                        </div>
                        <div class="flex w-full flex-row items-end">
                            <h1 class="w-full text-end text-4xl md:text-6xl font-bold">
                                13
                            </h1>
                            <span class="w-full text-sm text-gray-500">Stok</span>
                        </div>
                    </div>
                    <div class="col-span-1 p-2 md:p-5">
                        <div class="flex flex-col">
                            <h1 class="font-bold">
                                Laptop ASUS
                            </h1>
                            <h1>
                                BR2143
                            </h1>
                        </div>
                        <div class="flex w-full flex-row items-end">
                            <h1 class="w-full text-end text-4xl md:text-6xl font-bold">
                                42
                            </h1>
                            <span class="w-full text-sm text-gray-500">Stok</span>
                        </div>
                    </div>
                    <div class="col-span-1 p-2 md:p-5">
                        <div class="flex flex-col">
                            <h1 class="font-bold">
                                Laptop ASUS
                            </h1>
                            <h1>
                                BR4213
                            </h1>
                        </div>
                        <div class="flex w-full flex-row items-end">
                            <h1 class="w-full text-end text-4xl md:text-6xl font-bold">
                                42
                            </h1>
                            <span class="w-full text-sm text-gray-500">Stok</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-4">
                <div class="inline-flex w-full justify-between rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Inventory Movement Chart</h1>
                    <button class="m-3 inline-flex cursor-pointer items-center justify-center rounded-full bg-[#225A97] px-5 py-2">
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

            <div class="col-span-8 flex min-h-0 w-full flex-col rounded-2xl shadow-md md:col-span-4">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Stock Value Chart</h1>
                </div>
                <div class="min-h-0 flex-1 overflow-hidden">
                    <div class="h-full w-full">
                        <canvas id="SVC" class="block h-full w-full"></canvas>
                    </div>
                </div>

            </div>
            <div class="col-span-8 w-full rounded-2xl shadow-md">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Low Stock Items Table</h1>
                </div>
                <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Item</th>
                            <th scope="col" class="px-4 py-3">Stock</th>
                            <th scope="col" class="px-4 py-3">Minimum</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="h-min-[300px]">
                        <tr>
                            <td class="px-4 py-3">Laptop</td>
                            <td class="px-4 py-3">52</td>
                            <td class="px-4 py-3">10</td>
                            <td class="px-4 py-3">Pending</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-span-8 w-full rounded-2xl shadow-md">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
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
                            </th>
                        </tr>
                    </thead>
                    <tbody class="h-min-[300px]">
                        <tr>
                            <td class="px-4 py-3">25 Nov 2025</td>
                            <td class="px-4 py-3">Laptop</td>
                            <td class="px-4 py-3">40</td>
                            <td class="px-4 py-3">PT INDONUSA</td>
                            <td class="px-4 py-3">Complete</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-span-8 w-full rounded-2xl shadow-md">
                <div class="w-full rounded-t-2xl bg-gradient-to-r from-[#225A97] to-[#0D223A]">
                    <h1 class="p-5 text-lg font-bold text-white lg:text-2xl">Recent Outbound Items</h1>
                </div>
                <table id="DataTable3" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">Item</th>
                            <th scope="col" class="px-4 py-3">Stock</th>
                            <th scope="col" class="px-4 py-3">Minimum</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="h-min-[300px]">
                        <tr>
                            <td class="px-4 py-3">Laptop</td>
                            <td class="px-4 py-3">52</td>
                            <td class="px-4 py-3">10</td>
                            <td class="px-4 py-3">Pending</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Kelola data Chart ada di JS --}}
    @vite(['resources/js/chart-dashboard.js'])
</x-app-layout>
