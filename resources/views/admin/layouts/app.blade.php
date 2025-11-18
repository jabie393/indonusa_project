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
            class="grid max-h-screen min-h-screen grid-flow-row grid-cols-[1.3fr_1fr_1fr_1fr_1fr] grid-rows-[64px_1fr_1fr_1fr_1fr] gap-4 overflow-hidden bg-[#E5E7EB] p-4 dark:bg-gray-900 xl:grid-cols-[1fr_1fr_1fr_1fr_1fr] 2xl:grid-cols-[0.8fr_1fr_1fr_1fr_1fr] ">
            <div class="z-50 col-span-1 col-start-1 row-span-1 row-start-1 flex items-center justify-center rounded-xl bg-[#225A97] dark:bg-[#0D223A]">
                <div class="flex h-full w-full items-center justify-center lg:h-[unset]">
                    <button data-drawer-target="drawer-navigation" data-drawer-toggle="drawer-navigation" aria-controls="drawer-navigation"
                        class="flex h-full w-full cursor-pointer flex-wrap content-center justify-center rounded-xl p-2 text-center text-white hover:bg-gray-100 hover:text-gray-900 focus:bg-gray-100 focus:text-gray-900 focus:ring-2 focus:ring-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:bg-gray-700 dark:focus:ring-gray-700 lg:hidden">
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
            <div class="no-scrollbar col-span-5 col-start-1 row-span-4 row-start-2 overflow-scroll rounded-xl lg:col-span-4 lg:col-start-2">
                {{ $slot }}
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
        <div id="notification-container" class="fixed top-4 right-4 z-50 hidden bg-blue-500 text-white text-sm font-medium rounded-lg px-4 py-2 shadow-lg">
            <span id="notification-message">Notifikasi</span>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


        {{-- SweetAlert --}}
        @vite(['resources/js/sweetalert.js', 'resources/js/dataTable.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            window.sweetTitle = @json(session('title'));
            window.sweetText = @json(session('text'));
        </script>

        {{-- DataTable --}}
        <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

        <script>
            // Polling session tiap 3 detik
            const sessionCheckInterval = setInterval(() => {
                fetch('/session/check')
                    .then(res => res.json())
                    .then(data => {
                        if (!data.valid) {
                            clearInterval(sessionCheckInterval); // hentikan polling

                            Swal.fire({
                                title: "Perhatian!",
                                text: "Akun Anda digunakan di perangkat lain.",
                                icon: "warning",
                                confirmButtonText: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                allowEnterKey: false,
                                confirmButtonColor: '#225A97',
                                customClass: {
                                    popup: 'rounded-2xl!',
                                }
                            }).then(() => {
                                fetch("/logout", {
                                    method: "POST",
                                    headers: {
                                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        "Accept": "application/json",
                                        "Content-Type": "application/json",
                                    },
                                    body: JSON.stringify({})
                                }).then(() => {
                                    window.location.href = "/login"; // setelah logout, redirect manual
                                });
                            });
                        }
                    });
            }, 3000);
        </script>

        @php
            $userRole = auth()->user()->role ?? null;
        @endphp

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const userRole = @json($userRole);

                if (userRole === 'Warehouse') {
                    Echo.channel('orders')
                        .listen('OrderStatusUpdated', (e) => {
                            const notificationContainer = document.getElementById('notification-container');
                            const notificationMessage = document.getElementById('notification-message');

                            if (notificationContainer && notificationMessage) {
                                notificationMessage.textContent = `Order ID ${e.orderId} telah dikirim ke warehouse. Total orders: ${e.orderCount}`;
                                notificationContainer.classList.remove('hidden');

                                setTimeout(() => {
                                    notificationContainer.classList.add('hidden');
                                }, 5000); // Sembunyikan notifikasi setelah 5 detik
                            }
                        });
                }
            });
        </script>

    </body>

</html>
