<x-app-layout>
    <div class="relative h-full overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h2 class="mr-3 font-semibold text-white">Goods In</h2>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div class="flex w-full flex-shrink-0 flex-col items-center justify-end space-y-2 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0">
                <button
                    class="flex h-52 w-52 cursor-pointer flex-col items-center justify-evenly rounded-lg bg-gradient-to-b from-[#225A97] to-[#0D223A] px-4 py-2 text-sm font-medium text-white hover:bg-[#2868b3] focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                    onclick="tambahBarang.showModal()">
                    <svg width="97" height="86" viewBox="0 0 56 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_412_16)">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M28 3.68945L49 15.5973V39.413L28 51.3209L7 39.413V15.5973L28 3.68945ZM20.9999 25.917L21 42.0594L25.6667 44.7057V28.5632L20.9999 25.917ZM11.6667 20.6249V36.7668L16.3333 39.4131V23.271L11.6667 20.6249ZM37.1865 14.1909L23.4514 22.0151L28 24.5943L41.7667 16.7881L37.1865 14.1909ZM28 8.98183L14.2333 16.7881L18.7901 19.3719L32.5253 11.5478L28 8.98183Z"
                                fill="#E5E7EB" />
                            <mask id="path-2-outside-1_412_16" maskUnits="userSpaceOnUse" x="38.5" y="26" width="19" height="19" fill="black">
                                <rect fill="white" x="38.5" y="26" width="19" height="19" />
                                <path d="M55.57 37.81H50.35V43.15H45.49V37.81H40.27V33.22H45.49V27.85H50.35V33.22H55.57V37.81Z" />
                            </mask>
                            <path d="M55.57 37.81H50.35V43.15H45.49V37.81H40.27V33.22H45.49V27.85H50.35V33.22H55.57V37.81Z" fill="black" />
                            <path
                                d="M55.57 37.81V38.81H56.57V37.81H55.57ZM50.35 37.81V36.81H49.35V37.81H50.35ZM50.35 43.15V44.15H51.35V43.15H50.35ZM45.49 43.15H44.49V44.15H45.49V43.15ZM45.49 37.81H46.49V36.81H45.49V37.81ZM40.27 37.81H39.27V38.81H40.27V37.81ZM40.27 33.22V32.22H39.27V33.22H40.27ZM45.49 33.22V34.22H46.49V33.22H45.49ZM45.49 27.85V26.85H44.49V27.85H45.49ZM50.35 27.85H51.35V26.85H50.35V27.85ZM50.35 33.22H49.35V34.22H50.35V33.22ZM55.57 33.22H56.57V32.22H55.57V33.22ZM55.57 37.81V36.81H50.35V37.81V38.81H55.57V37.81ZM50.35 37.81H49.35V43.15H50.35H51.35V37.81H50.35ZM50.35 43.15V42.15H45.49V43.15V44.15H50.35V43.15ZM45.49 43.15H46.49V37.81H45.49H44.49V43.15H45.49ZM45.49 37.81V36.81H40.27V37.81V38.81H45.49V37.81ZM40.27 37.81H41.27V33.22H40.27H39.27V37.81H40.27ZM40.27 33.22V34.22H45.49V33.22V32.22H40.27V33.22ZM45.49 33.22H46.49V27.85H45.49H44.49V33.22H45.49ZM45.49 27.85V28.85H50.35V27.85V26.85H45.49V27.85ZM50.35 27.85H49.35V33.22H50.35H51.35V27.85H50.35ZM50.35 33.22V34.22H55.57V33.22V32.22H50.35V33.22ZM55.57 33.22H54.57V37.81H55.57H56.57V33.22H55.57Z"
                                fill="#E5E7EB" mask="url(#path-2-outside-1_412_16)" />
                        </g>
                        <defs>
                            <clipPath id="clip0_412_16">
                                <rect width="97" height="86" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>

                    <div class="flex items-center">
                        <svg class="mr-2 h-3.5 w-3.5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Tambah Barang
                    </div>
                </button>
                <a href="{{ route('add-stock.index') }}"
                    class="flex h-52 w-52 flex-col items-center justify-evenly rounded-lg bg-gradient-to-b from-[#225A97] to-[#0D223A] px-4 py-2 text-sm font-medium text-white hover:bg-[#2868b3] focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">

                    <svg viewBox="0 0 50 48" fill="none" width="85" height="79" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_412_23)">
                            <path
                                d="M39.9609 46.0125H10.0391C9.41406 46.0125 8.82813 45.9 8.24219 45.675C7.69531 45.45 7.1875 45.15 6.79687 44.7375C6.36719 44.325 6.05469 43.875 5.82031 43.35C5.58594 42.7875 5.46875 42.225 5.46875 41.625V11.7375C5.46875 11.1375 5.58594 10.575 5.82031 10.0125C6.05469 9.4875 6.36719 9 6.79687 8.625C7.22656 8.2125 7.69531 7.9125 8.24219 7.6875C8.82813 7.4625 9.41406 7.35 10.0391 7.35H14.5703V9.5625H10.0391C8.78906 9.5625 7.73437 10.5375 7.73437 11.775V41.6625C7.73437 42.8625 8.75 43.875 10.0391 43.875H39.9219C41.1719 43.875 42.2266 42.9 42.2266 41.6625V11.7375C42.2266 10.5375 41.2109 9.525 39.9219 9.525H35.4297V7.3125H39.9609C40.5859 7.3125 41.1719 7.425 41.7578 7.65C42.3047 7.875 42.8125 8.175 43.2031 8.5875C43.6328 9 43.9453 9.45 44.1797 9.975C44.4141 10.5375 44.5312 11.1 44.5312 11.7V41.5875C44.5312 42.1875 44.4141 42.75 44.1797 43.3125C43.9453 43.8375 43.6328 44.325 43.2031 44.7C42.7734 45.1125 42.3047 45.4125 41.7578 45.6375C41.1719 45.9 40.5859 46.0125 39.9609 46.0125Z"
                                fill="#E5E7EB" />
                            <path
                                d="M36.4844 13.9502H13.5156V6.7502C13.5156 5.2127 14.8047 3.9752 16.4063 3.9752H19.375C20.4297 1.9502 22.6172 0.637695 25.0391 0.637695C27.4609 0.637695 29.6484 1.9502 30.7031 3.9752H33.6719C35.2734 3.9752 36.5625 5.2127 36.5625 6.7502L36.4844 13.9502ZM15.8203 11.7377H34.2188V6.7502C34.2188 6.45019 33.9453 6.1877 33.6328 6.1877H29.0625L28.7891 5.4377C28.2422 3.9002 26.7188 2.8502 25 2.8502C23.2813 2.8502 21.7578 3.9002 21.2109 5.4377L20.9375 6.1877H16.3672C16.0547 6.1877 15.7813 6.45019 15.7813 6.7502V11.7377H15.8203Z"
                                fill="#E5E7EB" />
                            <mask id="path-3-outside-1_412_23" maskUnits="userSpaceOnUse" x="16" y="20" width="19" height="19" fill="black">
                                <rect fill="white" x="16" y="20" width="19" height="19" />
                                <path d="M33.07 31.81H27.85V37.15H22.99V31.81H17.77V27.22H22.99V21.85H27.85V27.22H33.07V31.81Z" />
                            </mask>
                            <path d="M33.07 31.81H27.85V37.15H22.99V31.81H17.77V27.22H22.99V21.85H27.85V27.22H33.07V31.81Z" fill="black" />
                            <path
                                d="M33.07 31.81V32.81H34.07V31.81H33.07ZM27.85 31.81V30.81H26.85V31.81H27.85ZM27.85 37.15V38.15H28.85V37.15H27.85ZM22.99 37.15H21.99V38.15H22.99V37.15ZM22.99 31.81H23.99V30.81H22.99V31.81ZM17.77 31.81H16.77V32.81H17.77V31.81ZM17.77 27.22V26.22H16.77V27.22H17.77ZM22.99 27.22V28.22H23.99V27.22H22.99ZM22.99 21.85V20.85H21.99V21.85H22.99ZM27.85 21.85H28.85V20.85H27.85V21.85ZM27.85 27.22H26.85V28.22H27.85V27.22ZM33.07 27.22H34.07V26.22H33.07V27.22ZM33.07 31.81V30.81H27.85V31.81V32.81H33.07V31.81ZM27.85 31.81H26.85V37.15H27.85H28.85V31.81H27.85ZM27.85 37.15V36.15H22.99V37.15V38.15H27.85V37.15ZM22.99 37.15H23.99V31.81H22.99H21.99V37.15H22.99ZM22.99 31.81V30.81H17.77V31.81V32.81H22.99V31.81ZM17.77 31.81H18.77V27.22H17.77H16.77V31.81H17.77ZM17.77 27.22V28.22H22.99V27.22V26.22H17.77V27.22ZM22.99 27.22H23.99V21.85H22.99H21.99V27.22H22.99ZM22.99 21.85V22.85H27.85V21.85V20.85H22.99V21.85ZM27.85 21.85H26.85V27.22H27.85H28.85V21.85H27.85ZM27.85 27.22V28.22H33.07V27.22V26.22H27.85V27.22ZM33.07 27.22H32.07V31.81H33.07H34.07V27.22H33.07Z"
                                fill="#E5E7EB" mask="url(#path-3-outside-1_412_23)" />
                        </g>
                        <defs>
                            <clipPath id="clip0_412_23">
                                <rect width="85" height="79" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>

                    <div class="flex items-center">
                        <svg class="mr-2 h-3.5 w-3.5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Tambah Stok Barang
                    </div>
                </a>
                <a href="{{ route('import-excel.index') }}"
                    class="flex h-52 w-52 cursor-pointer flex-col items-center justify-evenly rounded-lg bg-gradient-to-b from-[#225A97] to-[#0D223A] px-4 py-2 text-sm font-medium text-white hover:bg-[#2868b3] focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">

                    <svg width="97" height="86" viewBox="0 0 62 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_1070_27)">
                            <path
                                d="M10.3334 19.3748V7.74984C10.3334 6.3231 11.49 5.1665 12.9167 5.1665H49.0834C50.5101 5.1665 51.6667 6.3231 51.6667 7.74984V54.2498C51.6667 55.6766 50.5101 56.8332 49.0834 56.8332H12.9167C11.49 56.8332 10.3334 55.6766 10.3334 54.2498V42.6248"
                                stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
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

                    <div class="flex items-center">
                        <svg class="mr-2 h-3.5 w-3.5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Import Dari Excel
                    </div>

                </a>
                <a href=""
                    class="flex h-52 w-52 cursor-pointer flex-col items-center justify-evenly rounded-lg bg-gradient-to-b from-[#225A97] to-[#0D223A] px-4 py-2 text-sm font-medium text-white hover:bg-[#2868b3] focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">


                    <svg width="97" height="86" viewBox="0 0 62 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.3333 19.3748V7.74984C10.3333 6.3231 11.4899 5.1665 12.9167 5.1665H49.0833C50.5101 5.1665 51.6667 6.3231 51.6667 7.74984V54.2498C51.6667 55.6766 50.5101 56.8332 49.0833 56.8332H12.9167C11.4899 56.8332 10.3333 55.6766 10.3333 54.2498V42.6248"
                            stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M40.0417 19.375H43.9167" stroke="white" stroke-width="4" stroke-linecap="round" />
                        <path d="M36.1667 29.708H43.9167" stroke="white" stroke-width="4" stroke-linecap="round" />
                        <path d="M36.1667 40.0415H43.9167" stroke="white" stroke-width="4" stroke-linecap="round" />
                        <path d="M28.4167 19.375H5.16669V42.625H28.4167V19.375Z" fill="white" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12.9167 27.125L20.6667 34.875" stroke="black" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M20.6667 27.125L12.9167 34.875" stroke="#151414" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M50 39.5C52.3427 39.5 54.1501 39.499 55.5527 39.6875C56.9698 39.878 58.0481 40.2707 58.8887 41.1113C59.7293 41.9519 60.122 43.0303 60.3125 44.4473C60.501 45.8499 60.5 47.6572 60.5 50C60.5 52.3427 60.5011 54.1501 60.3125 55.5527C60.122 56.9697 59.7293 58.0481 58.8887 58.8887C58.0481 59.7293 56.9697 60.122 55.5527 60.3125C54.1501 60.5011 52.3427 60.5 50 60.5C47.6572 60.5 45.8499 60.501 44.4473 60.3125C43.0303 60.122 41.9519 59.7293 41.1113 58.8887C40.2707 58.0481 39.878 56.9698 39.6875 55.5527C39.499 54.1501 39.5 52.3427 39.5 50C39.5 47.6572 39.499 45.8499 39.6875 44.4473C39.878 43.0303 40.2707 41.9519 41.1113 41.1113C41.9519 40.2707 43.0303 39.878 44.4473 39.6875C45.8499 39.499 47.6572 39.5 50 39.5ZM46 54.75C45.8619 54.75 45.75 54.8619 45.75 55C45.75 55.1381 45.8619 55.25 46 55.25H54C54.1381 55.25 54.25 55.1381 54.25 55C54.25 54.8619 54.1381 54.75 54 54.75H46ZM50 44.75C49.8619 44.75 49.75 44.8619 49.75 45V51.3965L47.1768 48.8232C47.0791 48.7256 46.9209 48.7256 46.8232 48.8232C46.7256 48.9209 46.7256 49.0791 46.8232 49.1768L49.8232 52.1768L49.8613 52.208C49.902 52.2352 49.9502 52.25 50 52.25C50.0663 52.25 50.13 52.2235 50.1768 52.1768L53.1768 49.1768C53.2744 49.0791 53.2744 48.9209 53.1768 48.8232C53.0791 48.7256 52.9209 48.7256 52.8232 48.8232L50.25 51.3965V45C50.25 44.8619 50.1381 44.75 50 44.75Z"
                            fill="black" stroke="white" />
                    </svg>


                    <div class="flex items-center">
                        Download Template Excel
                    </div>

                </a>
            </div>
        </div>
    </div>


    <!-- Modals -->
    @include('admin.goods-in.partials.goods-in-modal-tambah', ['kategoriList' => $kategoriList])
    @vite(['resources/js/goods-in.js'])


</x-app-layout>
