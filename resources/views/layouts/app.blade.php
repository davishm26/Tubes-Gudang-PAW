<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'StockMaster') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-[#E9F6F1] via-[#E9F6F1] to-white m-0 p-0">
        @include('layouts.navigation')

        @php
            $isDemoMode = session('is_demo', false) || session('demo_mode', false);
        @endphp

        <!-- Page Heading -->
        @isset($header)
            <header>
                {{ $header }}
            </header>
        @endisset

        <div class="min-h-screen {{ isset($header) ? '' : 'pt-16' }}">

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Demo Mode Script -->
        @if($isDemoMode)
            <script src="{{ asset('js/demo-mode.js') }}"></script>
            <script src="{{ asset('js/demo-display.js') }}"></script>
        @endif

        <!-- Additional Scripts Stack -->
        @stack('scripts')
    </body>
</html>






