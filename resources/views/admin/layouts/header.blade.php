    <nav class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative z-50 flex h-full flex-row items-center justify-between rounded-xl bg-gradient-to-r from-[#225A97] to-[#0D223A] shadow-sm dark:bg-gradient-to-r dark:from-[#0D223A] dark:to-[#225A97]">
        <div class="ml-5 flex items-center">
            <div class="flex items-center border-gray-400">
                <a href="{{ route('dashboard') }}" class="items-center justify-between lg:flex">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mr-3 h-8">
                </a>
            </div>
            <hr class="mx-2 h-[40px] border-r-2 border-gray-400 dark:border-gray-600">
            @hasSection('header_content')
                @yield('header_content')
            @else
                @if (request()->routeIs('dashboard'))
                    <div class="hidden text-gray-100 md:block">
                        <h1 class="text-nowrap text-sm font-bold text-gray-100 md:text-xl">
                            Halo, {{ Auth::user()->name }}
                            <span class="hidden text-nowrap font-semibold text-gray-100 md:inline">
                                ({{ Auth::user()->role ?? 'User' }})
                            </span>
                        </h1>
                        <p class="md:text-md hidden text-xs text-gray-300 md:mt-1 md:block">
                            {{ __('Selamat datang di Dashboard!') }}
                        </p>
                    </div>
                @elseif (request()->routeIs('goods-in.*'))
                    <div class="text-gray-10 md:block0 hidden">
                        <h1 class="text-sm font-bold text-gray-100 md:text-xl">Goods In</h1>
                        <p class="md:text-md text-xs text-gray-300 md:mt-1">Manajemen Penerimaan Barang</p>
                    </div>
                @elseif (request()->routeIs('add-stock.*'))
                    <div class="hidden text-gray-100 md:block">
                        <h1 class="text-sm font-bold text-gray-100 md:text-xl">Add Stock</h1>
                        <p class="md:text-md text-xs text-gray-300 md:mt-1">Penambahan Stok Barang</p>
                    </div>
                @elseif (request()->routeIs('import-excel.*'))
                    <div class="hidden text-gray-100 md:block">
                        <h1 class="text-sm font-bold text-gray-100 md:text-xl">Import Excel</h1>
                        <p class="md:text-md text-xs text-gray-300 md:mt-1">Import Data Barang via Excel</p>
                    </div>
                @elseif (request()->routeIs('import-stock-excel.*'))
                    <div class="hidden text-gray-100 md:block">
                        <h1 class="text-sm font-bold text-gray-100 md:text-xl">Import Stock Excel</h1>
                        <p class="md:text-md text-xs text-gray-300 md:mt-1">Import Stok Barang via Excel</p>
                    </div>
                @elseif (request()->routeIs('goods-in-status.*'))
                    <div class="hidden text-gray-100 md:block">
                        <h1 class="text-sm font-bold text-gray-100 md:text-xl">Goods In Status</h1>
                        <p class="md:text-md text-xs text-gray-300 md:mt-1">Status Barang Masuk</p>
                    </div>
                @elseif (request()->routeIs('akun-sales.*'))
                    <div class="hidden text-gray-100 md:block">
                        <h1 class="text-sm font-bold text-gray-100 md:text-xl">Manajemen Akun Sales</h1>
                        <p class="md:text-md text-xs text-gray-300 md:mt-1">Kelola Pengguna Sales</p>
                    </div>
                @elseif (request()->routeIs('pics.*'))
                    <div class="hidden text-gray-100 md:block">
                        <h1 class="text-sm font-bold text-gray-100 md:text-xl">Manajemen PIC</h1>
                        <p class="md:text-md text-xs text-gray-300 md:mt-1">Kelola Person In Charge</p>
                    </div>
                @elseif (request()->routeIs('customer.*'))
                    <div class="hidden text-gray-100 md:block">
                        <h1 class="text-sm font-bold text-gray-100 md:text-xl">Manajemen Customer</h1>
                        <p class="md:text-md text-xs text-gray-300 md:mt-1">Kelola Data Pelanggan</p>
                    </div>
                @elseif (request()->routeIs('history.*'))
                    <div class="hidden text-gray-100 md:block">
                        <h1 class="text-sm font-bold text-gray-100 md:text-xl">History</h1>
                        <p class="md:text-md text-xs text-gray-300 md:mt-1">Riwayat Aktivitas</p>
                    </div>
                @elseif (request()->routeIs('supply-orders.*'))
                    <div class="hidden text-gray-100 md:block">
                        <h1 class="text-sm font-bold text-gray-100 md:text-xl">Supply Orders</h1>
                        <p class="md:text-md text-xs text-gray-300 md:mt-1">Pesanan Masuk dari Sales</p>
                    </div>
                @elseif (request()->routeIs('delivery-orders.*'))
                    <div class="hidden text-gray-100 md:block">
                        <h1 class="text-sm font-bold text-gray-100 md:text-xl">Delivery Orders</h1>
                        <p class="md:text-md text-xs text-gray-300 md:mt-1">Pesanan Siap Dikirim</p>
                    </div>
                @elseif (request()->routeIs('warehouse.*'))
                    <div class="hidden text-gray-100 md:block">
                        <h1 class="text-sm font-bold text-gray-100 md:text-xl">Warehouse</h1>
                        <p class="md:text-md text-xs text-gray-300 md:mt-1">Manajemen Gudang</p>
                    </div>
                @elseif (request()->routeIs('sales.request-order.*'))
                    @php
                        $ro = request()->route('request_order');
                        $requestNumber = optional($ro)->request_number ?? (optional($ro)->nomor_penawaran ?? '');
                    @endphp
                    <div class="hidden text-gray-100 md:block">
                        @if (request()->routeIs('sales.request-order.show'))
                            <h1 class="text-sm font-bold text-gray-100 md:text-xl">Detail Quotation</h1>
                            <p class="md:text-md text-xs text-gray-300 md:mt-1">{{ $requestNumber }}</p>
                        @elseif (request()->routeIs('sales.request-order.edit'))
                            <h1 class="text-sm font-bold text-gray-100 md:text-xl">Edit Quotation</h1>
                            <p class="md:text-md text-xs text-gray-300 md:mt-1">{{ $requestNumber }}</p>
                        @elseif (request()->routeIs('sales.request-order.create'))
                            <h1 class="text-sm font-bold text-gray-100 md:text-xl">Buat Quotation Baru</h1>
                            <p class="md:text-md text-xs text-gray-300 md:mt-1">Form Pembuatan Quotation</p>
                        @else
                            <h1 class="text-sm font-bold text-gray-100 md:text-xl">Quotation</h1>
                            <p class="md:text-md text-xs text-gray-300 md:mt-1">Buat dan Kelola Quotation</p>
                        @endif
                    </div>
                @elseif (request()->routeIs('sales.custom-penawaran.*'))
                    <div class="text-gray-100">
                        <h1 class="text-sm font-bold text-gray-100 md:text-xl">Custom Penawaran</h1>
                        <p class="md:text-md text-xs text-gray-300 md:mt-1">Penawaran Kustom (Non-Stok)</p>
                    </div>
                @elseif (request()->routeIs('admin.sent_penawaran'))
                    <div class="text-gray-100">
                        <h1 class="text-sm font-bold text-gray-100 md:text-xl">Approval Penawaran</h1>
                        <p class="md:text-md text-xs text-gray-300 md:mt-1">Persetujuan Penawaran Dikirim</p>
                    </div>
                @elseif (request()->routeIs('orders.history') || request()->routeIs('admin.orders.history'))
                    <div class="text-gray-100">
                        <h1 class="text-sm font-bold text-gray-100 md:text-xl">History Orders</h1>
                        <p class="md:text-md text-xs text-gray-300 md:mt-1">Riwayat Pesanan Selesai</p>
                    </div>
                @endif
            @endif
        </div>


        <div class="mr-5 flex items-center lg:order-2">
            <button id="theme-toggle" type="button" class="rounded-lg p-2.5 text-sm text-white hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-700">
                <svg id="theme-toggle-dark-icon" class="hidden h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
                <svg id="theme-toggle-light-icon" class="hidden h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                </svg>
            </button>
            <!-- Dropdown menu -->

            <button type="button" class="mx-3 flex rounded-full bg-[#225A97] text-sm hover:bg-[#1c4d81] md:mr-0" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown">
                <span class="sr-only">Open user menu</span>
                <p class="inline-flex items-center px-4 py-2 text-white">
                    {{ Auth::user()->name }} ({{ Auth::user()->role }})
                    <svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path d="M5.70711 9.71069C5.31658 10.1012 5.31658 10.7344 5.70711 11.1249L10.5993 16.0123C11.3805 16.7927 12.6463 16.7924 13.4271 16.0117L18.3174 11.1213C18.708 10.7308 18.708 10.0976 18.3174 9.70708C17.9269 9.31655 17.2937 9.31655 16.9032 9.70708L12.7176 13.8927C12.3271 14.2833 11.6939 14.2832 11.3034 13.8927L7.12132 9.71069C6.7308 9.32016 6.09763 9.32016 5.70711 9.71069Z" fill="#ffffff"></path>
                        </g>
                    </svg>
                </p>
            </button>
            <!-- Dropdown menu -->
            <div class="z-50 my-4 hidden w-56 list-none divide-y divide-gray-100 rounded rounded-xl bg-white text-base shadow dark:divide-gray-600 dark:bg-gray-700" id="dropdown">
                <div class="px-4 py-3">
                    <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }} ({{ Auth::user()->role }})</span>
                    <span class="block truncate text-sm text-gray-900 dark:text-white">{{ Auth::user()->email }}</span>
                </div>
                <ul class="py-1 text-gray-700 dark:text-gray-300" aria-labelledby="dropdown">
                    <li>
                        <a href="{{ route('profile.edit') }}" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none dark:text-gray-300 dark:hover:bg-gray-800 dark:focus:bg-gray-800">{{ __('Profile') }}</a>
                    </li>
                </ul>
                <ul class="py-1 text-gray-700 dark:text-gray-300" aria-labelledby="dropdown">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
