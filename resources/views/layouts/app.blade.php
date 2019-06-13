<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema Messtechnik') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Scripts -->
    <script src="{{ asset('js/inline.js') }}" type="text/javascript"></script>
</head>

<body data-barba="wrapper">
<div class="container-scroller" id="app">
    @auth
        <div data-barba="container">
            @include('partials._navbar')
            <div class="container-fluid page-body-wrapper">
                @include('partials._sidebar')
                <div class="page-transition-top"></div>
                <div class="page-transition-bot"></div>

                <div class="main-panel">
                    <div class="content-wrapper">
                        @yield('content')
                    </div>
                    @include('partials._footer')
                </div>
            </div>
        </div>
    @else
        <div data-barba="container">
            @yield('content')
        </div>
    @endauth
</div>

<!-- Styles -->
{{--<link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">--}}
{{--<link href="{{ asset('css/all.css') }}" rel="stylesheet" type="text/css">--}}
{{--<link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet" type="text/css">--}}
{{--<link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet" type="text/css">--}}
<link rel="preload" href="{{ asset('css/style.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('css/style.css') }}"></noscript>
<link rel="preload" href="{{ asset('css/all.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('css/all.css') }}"></noscript>
<link rel="preload" href="{{ asset('css/font-awesome.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('css/font-awesome.css') }}"></noscript>
<link rel="preload" href="{{ asset('css/sweetalert2.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}"></noscript>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" type="text/javascript" defer></script>
<script src="{{ asset('js/alerts.js') }}" type="text/javascript" defer></script>
<script src="{{ asset('js/chart.js') }}" type="text/javascript" defer></script>
<script src="{{ asset('js/login.js') }}" type="text/javascript" defer></script>
<script src="{{ asset('js/password.js') }}" type="text/javascript" defer></script>
<script src="{{ asset('js/toggle-graph.js') }}" type="text/javascript" defer></script>
@stack('scripts')

</body>
</html>