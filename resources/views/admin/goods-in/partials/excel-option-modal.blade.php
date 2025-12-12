<dialog id="ExcelImportOptionModal" class="modal">
    <div class="modal-box relative flex h-fit w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        <div class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <div class="flex items-center gap-3">
                <svg class="h-6 w-6 text-white" viewBox="0 0 62 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_1070_27)">
                        <path d="M10.3334 19.3748V7.74984C10.3334 6.3231 11.49 5.1665 12.9167 5.1665H49.0834C50.5101 5.1665 51.6667 6.3231 51.6667 7.74984V54.2498C51.6667 55.6766 50.5101 56.8332 49.0834 56.8332H12.9167C11.49 56.8332 10.3334 55.6766 10.3334 54.2498V42.6248" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M40.0417 19.375H43.9167" stroke="white" stroke-width="4" stroke-linecap="round" />
                        <path d="M36.1667 29.708H43.9167" stroke="white" stroke-width="4" stroke-linecap="round" />
                        <path d="M36.1667 40.0415H43.9167" stroke="white" stroke-width="4" stroke-linecap="round" />
                        <path d="M28.4167 19.375H5.16669V42.625H28.4167V19.375Z" fill="white" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12.9167 27.125L20.6667 34.875" stroke="black" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M20.6667 27.125L12.9167 34.875" stroke="#151414" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <mask id="path-8-outside-1_1070_27" maskUnits="userSpaceOnUse" x="41" y="44" width="19" height="19" fill="black">
                            <rect fill="white" x="41" y="44" width="19" height="19" />
                            <path d="M58.07 55.81H52.85V61.15H47.99V55.81H42.77V51.22H47.99V45.85H52.85V51.22H58.07V55.81Z" />
                        </mask>
                        <path d="M58.07 55.81H52.85V61.15H47.99V55.81H42.77V51.22H47.99V45.85H52.85V51.22H58.07V55.81Z" fill="black" />
                        <path
                            d="M58.07 55.81V56.81H59.07V55.81H58.07ZM52.85 55.81V54.81H51.85V55.81H52.85ZM52.85 61.15V62.15H53.85V61.15H52.85ZM47.99 61.15H46.99V62.15H47.99V61.15ZM47.99 55.81H48.99V54.81H47.99V55.81ZM42.77 55.81H41.77V56.81H42.77V55.81ZM42.77 51.22V50.22H41.77V51.22H42.77ZM47.99 51.22V52.22H48.99V51.22H47.99ZM47.99 45.85V44.85H46.99V45.85H47.99ZM52.85 45.85H53.85V44.85H52.85V45.85ZM52.85 51.22H51.85V52.22H52.85V51.22ZM58.07 51.22H59.07V50.22H58.07V51.22ZM58.07 55.81V54.81H52.85V55.81V56.81H58.07V55.81ZM52.85 55.81H51.85V61.15H52.85H53.85V55.81H52.85ZM52.85 61.15V60.15H47.99V61.15V62.15H52.85V61.15ZM47.99 61.15H48.99V55.81H47.99H46.99V61.15H47.99ZM47.99 55.81V54.81H42.77V55.81V56.81H47.99V55.81ZM42.77 55.81H43.77V51.22H42.77H41.77V55.81H42.77ZM42.77 51.22V52.22H47.99V51.22V50.22H42.77V51.22ZM47.99 51.22H48.99V45.85H47.99H46.99V51.22H47.99ZM47.99 45.85V46.85H52.85V45.85V44.85H47.99V45.85ZM52.85 45.85H51.85V51.22H52.85H53.85V45.85H52.85ZM52.85 51.22V52.22H58.07V51.22V50.22H52.85V51.22ZM58.07 51.22H57.07V55.81H58.07H59.07V51.22H58.07Z"
                            fill="#E5E7EB" mask="url(#path-8-outside-1_1070_27)" />
                    </g>
                    <defs>
                        <clipPath id="clip0_1070_27">
                            <rect width="62" height="62" fill="white" />
                        </clipPath>
                    </defs>
                </svg>
                <div>
                    <h2 class="text-lg font-semibold text-white">Excel Import</h2>
                    <p class="text-sm text-white/70">Pilih jenis import yang diinginkan</p>
                </div>
            </div>
            <div class="modal-action m-0">
                <form method="dialog">
                    <!-- if there is a button in form, it will close the modal -->
                    <button class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </form>
            </div>
        </div>
        <div class="p-8">
            <div class="flex flex-col items-center justify-center gap-6 sm:flex-row sm:gap-8">


                <a href="{{ route('import-excel.index') }}" class="group relative flex h-56 w-full max-w-[220px] flex-col items-center justify-center gap-4 rounded-2xl bg-white p-6 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-[#225A97]/50 focus:ring-offset-2 focus:ring-offset-gray-100 dark:bg-gray-800 dark:ring-offset-gray-900 inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">
                    <!-- Gradient overlay on hover -->
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-b from-[#225A97] to-[#0D223A] opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>

                    <!-- Content -->
                    <div class="relative z-10 flex flex-col items-center gap-4">
                        <!-- Icon container -->
                        <div class="flex h-20 w-20 items-center justify-center rounded-xl bg-gray-200 text-[#225A97] transition-all duration-300 group-hover:bg-white/20 group-hover:text-white dark:bg-gray-700 dark:text-blue-400">
                            <!-- Replace with your icon -->
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

                        <!-- Text content -->
                        <div class="flex flex-col items-center gap-1 text-center">
                            <span class="flex items-center gap-2 text-sm font-semibold text-gray-900 transition-colors duration-300 group-hover:text-white dark:text-white">
                                <!-- Plus icon -->
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Barang
                            </span>
                            <span class="text-xs text-gray-600 transition-colors duration-300 group-hover:text-white/70 dark:text-gray-400">
                                Import data barang baru
                            </span>
                        </div>
                    </div>

                    <!-- Decorative corner accent -->
                    <div class="absolute -right-1 -top-1 h-8 w-8 rounded-full bg-[#225A97]/20 opacity-0 blur-xl transition-opacity duration-300 group-hover:opacity-100"></div>
                </a>
                <a href="{{ route('import-excel.index') }}" class="group relative flex h-56 w-full max-w-[220px] flex-col items-center justify-center gap-4 rounded-2xl bg-white p-6 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-[#225A97]/50 focus:ring-offset-2 focus:ring-offset-gray-100 dark:bg-gray-800 dark:ring-offset-gray-900 inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm">
                    <!-- Gradient overlay on hover -->
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-b from-[#225A97] to-[#0D223A] opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>

                    <!-- Content -->
                    <div class="relative z-10 flex flex-col items-center gap-4">
                        <!-- Icon container -->
                        <div class="flex h-20 w-20 items-center justify-center rounded-xl bg-gray-200 text-[#225A97] transition-all duration-300 group-hover:bg-white/20 group-hover:text-white dark:bg-gray-700 dark:text-blue-400">
                            <!-- Replace with your icon -->
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

                        <!-- Text content -->
                        <div class="flex flex-col items-center gap-1 text-center">
                            <span class="flex items-center gap-2 text-sm font-semibold text-gray-900 transition-colors duration-300 group-hover:text-white dark:text-white">
                                <!-- Plus icon -->
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Stok Barang
                            </span>
                            <span class="text-xs text-gray-600 transition-colors duration-300 group-hover:text-white/70 dark:text-gray-400">
                                Update stok barang yang ada
                            </span>
                        </div>
                    </div>

                    <!-- Decorative corner accent -->
                    <div class="absolute -right-1 -top-1 h-8 w-8 rounded-full bg-[#225A97]/20 opacity-0 blur-xl transition-opacity duration-300 group-hover:opacity-100"></div>
                </a>

            </div>
            <p class="text-gray-600 dark:text-gray-400 mt-8 text-center text-xs">File yang didukung: .xlsx, .xls, .csv</p>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    window.CSRF_TOKEN = "{{ csrf_token() }}";
    window.CHECK_KODE_BARANG_URL = "{{ route('check.kode.barang') }}";
</script>

@vite(['resources/js/checker.js'])
