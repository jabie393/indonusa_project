<x-app-layout>
    <div class="relative overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-lg h-full">
        <div class="flex flex-col items-center justify-center space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div class="flex w-full flex-shrink-0 flex-col items-stretch justify-end space-y-2 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0">
                <div x-data="{ tambahModalIsOpen: false }">
                    <button
                        class="flex h-52 w-52 cursor-pointer flex-col items-center justify-evenly rounded-lg bg-gradient-to-b from-[#225A97] to-[#0B1D31] px-4 py-2 text-sm font-medium text-white hover:bg-[#2868b3] focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
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
                </div>
                <a href="{{ route('add-stock.index') }}"
                    class="flex h-52 w-52 flex-col items-center justify-evenly rounded-lg bg-gradient-to-b from-[#225A97] to-[#0B1D31] px-4 py-2 text-sm font-medium text-white hover:bg-[#2868b3] focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">

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
            </div>
        </div>
    </div>


    <!-- Modals -->
    @include('components.goods-in-modal-tambah')
    @vite(['resources/js/goods-in.js'])


</x-app-layout>
