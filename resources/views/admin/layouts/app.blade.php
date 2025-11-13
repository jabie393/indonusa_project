<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="[scrollbar-gutter:auto]">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('images/icon/ryu.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- sweetalert --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- DaisyUI --}}
        <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

        {{-- DataTable --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">

        <style>
            div.dt-container div.dt-layout-row {
                margin: 0;
            }
        </style>


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            // On page load or when changing themes, best to add inline in `head` to avoid FOUC
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
    </head>



    <body class="font-sans antialiased">
        <div
            class="grid bg-[#E5E7EB] dark:bg-gray-900 max-h-screen min-h-screen grid-flow-row grid-cols-[1fr_1fr_1fr_1fr_1fr] grid-rows-[64px_1fr_1fr_1fr_1fr] gap-4 overflow-hidden p-4 lg:grid-cols-[0.75fr_1fr_1fr_1fr_1fr] lg:grid-rows-[64px_1fr_1fr_1fr_1fr]">
            <div class="z-50 col-span-1 col-start-1 row-span-1 row-start-1 flex items-center justify-center rounded-xl bg-[#225A97]">
                <div class="flex items-center h-full w-full lg:h-[unset] justify-center ">
                    <button data-drawer-target="drawer-navigation" data-drawer-toggle="drawer-navigation" aria-controls="drawer-navigation"
                        class=" cursor-pointer h-full w-full flex flex-wrap content-center justify-center text-center rounded-xl p-2 text-white hover:bg-gray-100 hover:text-gray-900 focus:text-gray-900 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:bg-gray-700 dark:focus:ring-gray-700 lg:hidden">
                        <svg aria-hidden="true" class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <svg aria-hidden="true" class="hidden h-6 w-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Toggle sidebar</span>
                    </button>

                    {{-- logo --}}
                    <a href="{{ route('dashboard') }}" class="mr-4 hidden items-center justify-between lg:flex">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mr-3 h-8">
                        <span class="self-center whitespace-nowrap text-2xl font-semibold text-white">Indonusa</span>
                    </a>
                </div>
            </div>
            <nav class="col-span-4 col-start-2 row-span-1 row-start-1 rounded-xl">
                @include('admin.layouts.header')
            </nav>
            <aside class="rounded-xl lg:col-span-1 lg:col-start-1 lg:row-span-4 lg:row-start-2">
                @include('admin.layouts.sidebar')
            </aside>
            <div class="col-span-5 col-start-1 row-span-4 row-start-2 overflow-scroll no-scrollbar rounded-xl lg:col-span-4 lg:col-start-2">
                {{ $slot }}
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


        {{-- SweetAlert --}}
        @vite(['resources/js/sweetalert.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            window.sweetTitle = @json(session('title'));
            window.sweetText = @json(session('text'));
        </script>

        {{-- DataTable --}}
        <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

    </body>

</html>
