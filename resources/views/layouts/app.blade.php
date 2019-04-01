<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <script src="{{ asset('js/all.js') }}" type="text/javascript" defer></script>
    <script src="{{ asset('js/vendor.bundle.addons.js') }}" type="text/javascript" defer></script>
    <script src="{{ asset('js/vendor.bundle.base.js') }}" type="text/javascript" defer></script>

    <!-- Styles -->
    <link href="{{asset('css/all.css')}}" rel="stylesheet" type="text/css">
    <!--<link href="{{asset('css/sass.css')}}" rel="stylesheet" type="text/css">-->
    <link href="{{asset('css/vendor.bundle.addons.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/vendor.bundle.base.css')}}" rel="stylesheet" type="text/css">

</head>
<body>
<div class="container-scroller" id="app">
    @auth
        @include('partials._navbar')
        @include('partials._sidebar')
        @yield('content')
        @include('partials._footer')

    @else
        @yield('content')
    @endauth
</div>
</body>
</html>
