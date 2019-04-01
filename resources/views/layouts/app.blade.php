<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <script src="{{ asset('js/all.js') }}" type="text/javascript" defer></script>

    <!-- Styles -->
    <link href="{{asset('css/all.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/sass.css')}}" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container-scroller" id="app">
    @auth
        @include('partials._navbar')
        <div class="container-fluid page-body-wrapper">
            @include('partials._sidebar')
            <div class="main-panel">
                @yield('content')
                @include('partials._footer')
            </div>
        </div>
    @else
        <div class="main-panel">
            @yield('content')
        </div>
    @endauth


</div>
</body>
</html>
