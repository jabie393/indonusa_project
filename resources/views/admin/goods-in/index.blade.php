<x-app-layout>
    <div class="relative h-full overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-lg inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">
        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0 inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">
        </div>

        <div class="">
            <div class="flex flex-col flex-wrap items-center justify-center gap-6 p-8 sm:flex-row sm:gap-8 ">
                <!-- Tambah Barang (Manual) -->
                <button onclick="tambahBarang.showModal()" class="group relative flex h-56 w-full max-w-[220px] cursor-pointer flex-col items-center justify-center gap-4 rounded-2xl bg-white p-6 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-[#225A97]/50 focus:ring-offset-2 focus:ring-offset-gray-100 dark:bg-gray-800 dark:ring-offset-gray-900 inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-b from-[#225A97] to-[#0D223A] opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                    <div class="relative z-10 flex flex-col items-center gap-4">
                        <div class="flex h-20 w-20 items-center justify-center rounded-xl bg-gray-200 text-[#225A97] transition-all duration-300 group-hover:bg-white/20 group-hover:text-white dark:bg-gray-700 dark:text-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package h-10 w-10">
                                <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                                <path d="M12 22V12"></path>
                                <path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"></path>
                                <path d="m7.5 4.27 9 5.15"></path>
                            </svg>
                        </div>
                        <div class="flex flex-col items-center gap-1 text-center">
                            <span class="flex items-center gap-2 text-sm font-semibold text-gray-900 transition-colors duration-300 group-hover:text-white dark:text-white">

                                Tambah Barang
                            </span>
                            <span class="text-xs text-gray-600 transition-colors duration-300 group-hover:text-white/70 dark:text-gray-400">Input barang baru manual</span>
                        </div>
                    </div>
                    <!-- Decorative corner accent -->
                    <div class="absolute -right-1 -top-1 h-8 w-8 rounded-full bg-[#225A97]/20 opacity-0 blur-xl transition-opacity duration-300 group-hover:opacity-100"></div>
                </button>

                <!-- Tambah Stok (Link) -->
                <a href="{{ route('add-stock.index') }}" class="group relative flex h-56 w-full max-w-[220px] flex-col items-center justify-center gap-4 rounded-2xl bg-white p-6 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-[#225A97]/50 focus:ring-offset-2 focus:ring-offset-gray-100 dark:bg-gray-800 dark:ring-offset-gray-900 inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-b from-[#225A97] to-[#0D223A] opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                    <div class="relative z-10 flex flex-col items-center gap-4">
                        <div class="flex h-20 w-20 items-center justify-center rounded-xl bg-gray-200 text-[#225A97] transition-all duration-300 group-hover:bg-white/20 group-hover:text-white dark:bg-gray-700 dark:text-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-plus h-10 w-10">
                                <path d="M16 16h6"></path>
                                <path d="M19 13v6"></path>
                                <path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"></path>
                                <path d="m7.5 4.27 9 5.15"></path>
                                <polyline points="3.29 7 12 12 20.71 7"></polyline>
                                <line x1="12" x2="12" y1="22" y2="12"></line>
                            </svg>
                        </div>
                        <div class="flex flex-col items-center gap-1 text-center">
                            <span class="flex items-center gap-2 text-sm font-semibold text-gray-900 transition-colors duration-300 group-hover:text-white dark:text-white">

                                Tambah Stok Barang
                            </span>
                            <span class="text-xs text-gray-600 transition-colors duration-300 group-hover:text-white/70 dark:text-gray-400">Update stok barang yang ada</span>
                        </div>
                    </div>
                    <!-- Decorative corner accent -->
                    <div class="absolute -right-1 -top-1 h-8 w-8 rounded-full bg-[#225A97]/20 opacity-0 blur-xl transition-opacity duration-300 group-hover:opacity-100"></div>
                </a>

                <!-- Import Excel (Modal) -->
                <button onclick="ExcelImportOptionModal.showModal()" class="group relative flex h-56 w-full max-w-[220px] cursor-pointer flex-col items-center justify-center gap-4 rounded-2xl bg-white p-6 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-[#225A97]/50 focus:ring-offset-2 focus:ring-offset-gray-100 dark:bg-gray-800 dark:ring-offset-gray-900 inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-b from-[#225A97] to-[#0D223A] opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                    <div class="relative z-10 flex flex-col items-center gap-4">
                        <div class="flex h-20 w-20 items-center justify-center rounded-xl bg-gray-200 text-[#225A97] transition-all duration-300 group-hover:bg-white/20 group-hover:text-white dark:bg-gray-700 dark:text-blue-400">
                            <svg width="40" height="40" viewBox="0 0 62 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_1070_27)">
                                    <path d="M10.3334 19.3748V7.74984C10.3334 6.3231 11.49 5.1665 12.9167 5.1665H49.0834C50.5101 5.1665 51.6667 6.3231 51.6667 7.74984V54.2498C51.6667 55.6766 50.5101 56.8332 49.0834 56.8332H12.9167C11.49 56.8332 10.3334 55.6766 10.3334 54.2498V42.6248" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M40.0417 19.375H43.9167" stroke="currentColor" stroke-width="4" stroke-linecap="round" />
                                    <path d="M36.1667 29.708H43.9167" stroke="currentColor" stroke-width="4" stroke-linecap="round" />
                                    <path d="M36.1667 40.0415H43.9167" stroke="currentColor" stroke-width="4" stroke-linecap="round" />
                                    <path d="M28.4167 19.375H5.16669V42.625H28.4167V19.375Z" fill="currentColor" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12.9167 27.125L20.6667 34.875" class="stroke-white transition-colors duration-300 group-hover:stroke-black" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M20.6667 27.125L12.9167 34.875" class="stroke-white transition-colors duration-300 group-hover:stroke-black" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                                    <mask id="path-8-outside-1_1070_27" maskUnits="userSpaceOnUse" x="41" y="44" width="19" height="19" fill="black">
                                        <rect fill="currentColor" x="41" y="44" width="19" height="19" />
                                        <path d="M58.07 55.81H52.85V61.15H47.99V55.81H42.77V51.22H47.99V45.85H52.85V51.22H58.07V55.81Z" />
                                    </mask>
                                    <path d="M58.07 55.81H52.85V61.15H47.99V55.81H42.77V51.22H47.99V45.85H52.85V51.22H58.07V55.81Z" fill="black" />
                                    <path
                                        d="M58.07 55.81V56.81H59.07V55.81H58.07ZM52.85 55.81V54.81H51.85V55.81H52.85ZM52.85 61.15V62.15H53.85V61.15H52.85ZM47.99 61.15H46.99V62.15H47.99V61.15ZM47.99 55.81H48.99V54.81H47.99V55.81ZM42.77 55.81H41.77V56.81H42.77V55.81ZM42.77 51.22V50.22H41.77V51.22H42.77ZM47.99 51.22V52.22H48.99V51.22H47.99ZM47.99 45.85V44.85H46.99V45.85H47.99ZM52.85 45.85H53.85V44.85H52.85V45.85ZM52.85 51.22H51.85V52.22H52.85V51.22ZM58.07 51.22H59.07V50.22H58.07V51.22ZM58.07 55.81V54.81H52.85V55.81V56.81H58.07V55.81ZM52.85 55.81H51.85V61.15H52.85H53.85V55.81H52.85ZM52.85 61.15V60.15H47.99V61.15V62.15H52.85V61.15ZM47.99 61.15H48.99V55.81H47.99H46.99V61.15H47.99ZM47.99 55.81V54.81H42.77V55.81V56.81H47.99V55.81ZM42.77 55.81H43.77V51.22H42.77H41.77V55.81H42.77ZM42.77 51.22V52.22H47.99V51.22V50.22H42.77V51.22ZM47.99 51.22H48.99V45.85H47.99H46.99V51.22H47.99ZM47.99 45.85V46.85H52.85V45.85V44.85H47.99V45.85ZM52.85 45.85H51.85V51.22H52.85H53.85V45.85H52.85ZM52.85 51.22V52.22H58.07V51.22V50.22H52.85V51.22ZM58.07 51.22H57.07V55.81H58.07H59.07V51.22H58.07Z"
                                        fill="#E5E7EB" mask="url(#path-8-outside-1_1070_27)" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_1070_27">
                                        <rect width="62" height="62" fill="currentColor" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <div class="flex flex-col items-center gap-1 text-center">
                            <span class="flex items-center gap-2 text-sm font-semibold text-gray-900 transition-colors duration-300 group-hover:text-white dark:text-white">

                                Import Dari Excel
                            </span>
                            <span class="text-xs text-gray-600 transition-colors duration-300 group-hover:text-white/70 dark:text-gray-400">Import data massal via Excel</span>
                        </div>
                    </div>
                    <!-- Decorative corner accent -->
                    <div class="absolute -right-1 -top-1 h-8 w-8 rounded-full bg-[#225A97]/20 opacity-0 blur-xl transition-opacity duration-300 group-hover:opacity-100"></div>
                </button>
            </div>
            <div class="mt-12 text-center">
                <p class="text-gray-500 text-sm">Pilih salah satu metode untuk menambahkan barang ke inventori</p>
            </div>
        </div>
    </div>


    <!-- Modals -->
    @include('admin.goods-in.partials.goods-in-modal-tambah', ['kategoriList' => $kategoriList])
    @include('admin.goods-in.partials.excel-option-modal')
    @vite(['resources/js/goods-in.js'])


</x-app-layout>
