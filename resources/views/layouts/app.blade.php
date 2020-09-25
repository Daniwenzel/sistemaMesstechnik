<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="Description" content="Medição de potencial eólico, monitoramento do vento, produtos e serviços para a instalação
    e manutenção de estações de medição anemométrica">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema Messtechnik') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/image.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/defer.js') }}" type="text/javascript" defer></script>
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
        <main>
            <div data-barba="container">
                @yield('content')
            </div>
        </main>
    @endauth

</div>


<!-- Deferred Styles -->

<link rel="preload" href="{{ asset('css/style.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('css/style.css') }}"></noscript>

<!-- <link rel="preload" href="{{ asset('css/all.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('css/all.css') }}"></noscript>
<link rel="preload" href="{{ asset('css/animate.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('css/animate.min.css') }}"></noscript> -->

<!-- Deferred Scripts -->

@stack('scripts')

</body>
</html>