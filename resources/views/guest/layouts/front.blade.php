<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="PT Indonusa Jaya Bersama - One Stop Solution for Building Materials & Hardware Tools. Supplier of quality laboratory equipment, material testing systems, and more.">
        <meta name="keywords" content="Indonusa Jaya Bersama, building materials, hardware tools, laboratory equipment, Surabaya distributor">

        <title>{{ config('app.name', 'PT Indonusa Jaya Bersama') }} - One Stop Solution</title>
        <link rel="icon" href="{{ asset('images/icon/ryu.png') }}">

        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>

    <body class="flex min-h-screen flex-col items-center font-sans text-[#1b1b18] lg:justify-center">
        {{-- Header --}}
        @include('guest.layouts.header')

        {{-- Main Content --}}
        {{ $slot }}

        @include('guest.layouts.footer')
    </body>

</html>
