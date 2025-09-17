@php
    $keranjang = session('keranjang', []);
    $jumlahKeranjang = collect($keranjang)->sum('qty');
@endphp
<header class="sticky left-0 top-0 z-50 w-full bg-gradient-to-r from-[#225A97] to-[#0B1D31]">
    <nav class="px-4 py-2.5 lg:px-6">
        <div class="mx-auto flex max-w-screen-xl flex-wrap items-center justify-between">
            <a href="/" class="flex items-center">
                <x-application-logo class="mr-3 h-6 sm:h-9"></x-application-logo>
                <span class="self-center hidden md:block whitespace-nowrap text-sm font-semibold text-white md:text-xl">INDONUSA JAYA BERSAMA</span>
            </a>
            @if (Route::has('login'))
                <div class="flex items-center lg:order-2">
                    @if ($jumlahKeranjang > 0)
                        <a href="{{ route('keranjang.index') }}" class="relative mr-3">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A1 1 0 007.5 17h9a1 1 0 00.85-1.53L17 13M7 13V6a1 1 0 011-1h6a1 1 0 011 1v7"></path>
                            </svg>
                            <span class="absolute -right-2 -top-2 rounded-full bg-red-600 px-1.5 py-0.5 text-xs text-white">{{ $jumlahKeranjang }}</span>
                        </a>
                    @endif
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="inline-block rounded-sm border border-[#19140035] bg-white px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-block rounded-sm border border-transparent bg-white px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]">
                            Log in
                        </a>
                    @endauth
                    <button data-collapse-toggle="mobile-menu-2" type="button"
                        class="ml-1 inline-flex items-center rounded-lg p-2 text-sm text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 lg:hidden"
                        aria-controls="mobile-menu-2" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd">
                            </path>
                        </svg>
                        <svg class="hidden h-6 w-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            @endif
            <div class="hidden w-full items-center justify-between lg:order-1 lg:flex lg:w-auto lg:bg-transparent" id="mobile-menu-2">
                <ul class="mt-4 flex flex-col rounded-lg bg-white font-medium lg:mt-0 lg:flex-row lg:space-x-8 lg:bg-transparent">
                    <li>
                        <a href="{{ url('/#') }}"
                            class="block rounded-lg py-2 pl-3 pr-4 text-gray-700 hover:bg-gray-300 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white lg:border-0 lg:p-0 lg:text-white lg:hover:bg-transparent lg:hover:text-primary-700 lg:dark:hover:bg-transparent lg:dark:hover:text-white"
                            aria-current="page">Home</a>
                    </li>
                    <li>
                        <a href="{{ url('/#about') }}"
                            class="block rounded-lg py-2 pl-3 pr-4 text-gray-700 hover:bg-gray-300 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white lg:border-0 lg:p-0 lg:text-white lg:hover:bg-transparent lg:hover:text-primary-700 lg:dark:hover:bg-transparent lg:dark:hover:text-white">About
                            Us</a>
                    </li>
                    <li>
                        <a href="{{ url('/#layanan') }}"
                            class="block rounded-lg py-2 pl-3 pr-4 text-gray-700 hover:bg-gray-300 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white lg:border-0 lg:p-0 lg:text-white lg:hover:bg-transparent lg:hover:text-primary-700 lg:dark:hover:bg-transparent lg:dark:hover:text-white">Layanan</a>
                    </li>
                    <li>
                        <a href="{{ route('order') }}"
                            class="block rounded-lg py-2 pl-3 pr-4 text-gray-700 hover:bg-gray-300 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white lg:border-0 lg:p-0 lg:text-white lg:hover:bg-transparent lg:hover:text-primary-700 lg:dark:hover:bg-transparent lg:dark:hover:text-white">Order</a>
                    </li>

                </ul>
            </div>
        </div>

    </nav>
</header>
