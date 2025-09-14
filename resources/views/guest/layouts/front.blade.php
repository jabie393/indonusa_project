<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
        @endif
    </head>

    <body class="text-[#1b1b18] flex items-center lg:justify-center min-h-screen flex-col">
        {{-- Header --}}
        @include('guest.layouts.header')

        {{-- Main Content --}}
        {{ $slot }}

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
        
        @include('guest.layouts.footer')
    </body>


</html>
