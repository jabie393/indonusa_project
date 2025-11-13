<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('images/icon/ryu.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="grid h-screen max-h-screen grid-flow-row-dense grid-cols-1 grid-rows-6 gap-4 p-5 font-sans text-gray-900 antialiased lg:grid-cols-5 lg:grid-rows-1">
        <div class="rounded-4xl row-span-3 flex h-full w-full max-w-full flex-col justify-between bg-cover bg-center lg:col-span-2 lg:row-span-full"
            style="background-image: url('{{ asset('images/login_pic.png') }}')">
            <div class="rounded-4xl m-5 flex h-40 flex-row items-center justify-center gap-5 bg-black/30 backdrop-blur-sm">
                <a href="/">
                    <x-application-logo class="max-h-25 max-w-25 h-full w-full fill-current text-gray-500" />
                </a>
                <h1 class="text-lg font-bold text-white lg:text-3xl">INDONUSA JAYA BERSAMA</h1>
            </div>
            <div class="rounded-4xl relative bottom-0 m-5 flex flex-col items-start justify-between bg-black/30 backdrop-blur-sm">
                <svg class="max-w-25 m-5 h-full max-h-40 w-full" viewBox="0 0 151 74" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="39" cy="37" rx="39" ry="37" fill="#D9D9D9" fill-opacity="0.65" />
                    <ellipse cx="112" cy="37" rx="39" ry="37" fill="black" />
                    <path
                        d="M118.707 37.7071C119.098 37.3166 119.098 36.6834 118.707 36.2929L112.343 29.9289C111.953 29.5384 111.319 29.5384 110.929 29.9289C110.538 30.3195 110.538 30.9526 110.929 31.3431L116.586 37L110.929 42.6569C110.538 43.0474 110.538 43.6805 110.929 44.0711C111.319 44.4616 111.953 44.4616 112.343 44.0711L118.707 37.7071ZM37 37V38H118V37V36H37V37Z"
                        fill="white" />
                </svg>
                <p class="p-3 text-sm text-white lg:px-3 lg:text-xl">
                    Menjadi penyedia solusi terpadu yang paling andal dan inovatif di Indonesia, selalu siap memenuhi setiap kebutuhan pelanggan dengan layanan prima, kualitas terdepan, dan semangat
                    kebersamaan, demi kemajuan dan kesejahteraan bersama.
                </p>
            </div>
        </div>
        <div class="bg-linear-to-r rounded-4xl row-span-3 row-start-4 flex h-full flex-col items-center bg-[#E5E7EB] sm:justify-center lg:col-span-3 lg:col-start-3 lg:row-span-full">
            <div class="w-full overflow-hidden px-6 py-4 sm:max-w-md sm:rounded-lg">
                <div class="flex flex-col items-center justify-center">
                    <h1 class="text-center text-2xl font-bold">Login</h1>
                </div>
                {{ $slot }}
            </div>
        </div>

    </body>

</html>
