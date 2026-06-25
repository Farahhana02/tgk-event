<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kedah Forward')</title>

    <!-- Global CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fonts.css') }}">

    @stack('styles') 
</head>

<body >

    {{-- =========================
         TOP BAR
    ========================== --}}
    @include('components.topbar')

    {{-- DARK OVERLAY --}}
    <div id="overlay"></div>

    {{-- =========================
         SIDEBAR
    ========================== --}}
    @include('components.sidebar')

    {{-- =========================
         SOCIAL MEDIA BAR
    ========================== --}}
    @include('components.social')

    {{-- =========================
         PAGE CONTENT (DYNAMIC)
    ========================== --}}
    <main style="margin-top:70px;">
        @yield('content')
    </main>

    {{-- =========================
         FOOTER
    ========================== --}}
    @include('components.footer')

    @include('components.backtotop')

    {{-- Global JS --}}
    @include('components.scripts')

    @stack('scripts')

</body>
</html>
