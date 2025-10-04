<x-app-layout>
    <div class="relative overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
        <div class="flex flex-col items-center justify-center space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div class="flex w-full flex-shrink-0 flex-col items-stretch justify-end space-y-2 md:w-auto md:flex-row md:items-center md:space-x-3 md:space-y-0">
                <div x-data="{ tambahModalIsOpen: false }">
                    <button
                        class="flex h-52 w-52 flex-col items-center justify-evenly rounded-lg bg-[#225A97] px-4 py-2 text-sm font-medium text-white hover:bg-[#2868b3] focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                        onclick="tambahBarang.showModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="97" height="86" viewBox="0 0 97 86" fill="none">
                            <g clip-path="url(#clip0_377_33)">
                                <path
                                    d="M88.9166 10.75H8.08325V32.25H12.1249V71.6667C12.1249 75.6263 15.7422 78.8333 20.2083 78.8333H76.7916C81.2576 78.8333 84.8749 75.6263 84.8749 71.6667V32.25H88.9166V10.75ZM16.1666 17.9167H80.8333V25.0833H16.1666V17.9167ZM76.7916 71.6667H20.2083V32.25H76.7916V71.6667ZM52.5416 35.8333V54.3592L63.0095 45.1142L68.7082 50.1667L48.4999 68.0833L28.2916 50.1667L33.9903 45.0783L44.4583 54.3592V35.8333H52.5416Z"
                                    fill="white" />
                            </g>
                            <defs>
                                <clipPath id="clip0_377_33">
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
                    class="flex h-52 w-52 flex-col items-center justify-evenly rounded-lg bg-[#225A97] px-4 py-2 text-sm font-medium text-white hover:bg-[#2868b3] focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="85" height="79" viewBox="0 0 85 79" fill="none">
                        <path
                            d="M73.8847 29.7769C74.535 29.778 75.1616 30.0043 75.6422 30.4116C76.1227 30.8189 76.4227 31.378 76.4837 31.9798V66.2385C76.4776 68.1163 75.6956 69.9201 74.2993 71.2773C72.9029 72.6344 70.9989 73.4412 68.9808 73.5308H16.3462C14.3258 73.5251 12.3849 72.7983 10.9247 71.5005C9.46446 70.2027 8.59646 68.4332 8.50006 66.5575V32.2077C8.50126 31.6032 8.74473 31.0209 9.18296 30.5743C9.62119 30.1276 10.2227 29.8488 10.8702 29.7921H73.8847V29.7769ZM53.6318 39.9102L53.452 40.0317L38.4789 55.1177L31.6952 49.0408C31.3511 48.7205 30.8911 48.5306 30.4046 48.5082C29.9182 48.4857 29.4401 48.6322 29.0635 48.9192L28.9001 49.0408L26.1539 51.35C25.9858 51.4823 25.8482 51.6448 25.7493 51.8278C25.6505 52.0107 25.5924 52.2104 25.5787 52.4147C25.565 52.619 25.5958 52.8239 25.6694 53.0168C25.7429 53.2098 25.8577 53.387 26.0068 53.5377L26.1539 53.6896L35.7327 62.1214C36.4788 62.8041 37.481 63.1909 38.5279 63.2C39.0492 63.2129 39.5676 63.1234 40.0492 62.9376C40.5309 62.7517 40.9649 62.4736 41.3231 62.1214L49.3327 54.2821L49.9702 53.6592L50.8203 52.8237L58.9933 44.7717C59.2741 44.4652 59.4428 44.0838 59.4751 43.6824C59.5075 43.281 59.4017 42.8804 59.1731 42.5385L59.0587 42.4017L56.2472 40.0621C55.91 39.7385 55.4549 39.5435 54.9711 39.5154C54.4874 39.4873 54.0097 39.6281 53.6318 39.9102ZM73.8847 5.46924C75.9643 5.47325 77.9575 6.24284 79.428 7.60954C80.8985 8.97624 81.7265 10.8287 81.7308 12.7615V20.0539C81.7308 20.6985 81.4553 21.3168 80.9648 21.7727C80.4743 22.2285 79.8091 22.4846 79.1154 22.4846H5.88467C5.19103 22.4846 4.5258 22.2285 4.03532 21.7727C3.54484 21.3168 3.26929 20.6985 3.26929 20.0539V12.7615C3.27361 10.8287 4.10164 8.97624 5.57214 7.60954C7.04265 6.24284 9.03584 5.47325 11.1154 5.46924H73.8847Z"
                            fill="white" />
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
