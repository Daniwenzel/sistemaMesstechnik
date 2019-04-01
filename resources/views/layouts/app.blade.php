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

    <!-- Styles -->
    <link href="{{asset('css/all.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/vendor.bundle.addons.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/vendor.bundle.base.css')}}" rel="stylesheet" type="text/css">

</head>
<body>
<div class="container-scroller" id="app">
    @auth
        @include('partials._navbar')
        <div class="container-fluid page-body-wrapper">
            @include('partials._sidebar')
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                @include('partials._footer')
            </div>
        </div>
    @else
        @yield('content')
    @endauth
</div>

<script src="{{ asset('js/app.js') }}" type="text/javascript" defer></script>
<script src="{{ asset('js/off-canvas.js') }}" type="text/javascript" defer></script>
<script src="{{ asset('js/misc.js') }}" type="text/javascript" defer></script>
<script src="{{ asset('js/dashboard.js') }}" type="text/javascript" defer></script>
<!--<script src="{{ asset('js/vendor.bundle.base.js') }}" type="text/javascript" defer></script>
<script src="{{ asset('js/vendor.bundle.addons.js') }}" type="text/javascript" defer></script>-->

</body>
</html>
