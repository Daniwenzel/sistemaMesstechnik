<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Messtechnik Comercio e Instrumentações</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{asset('css/all.css')}}">
</head>

<body>
<div class="container-scroller" id="app">
    @include('partials._navbar')
    <div class="container-fluid page-body-wrapper">
        @include('partials._sidebar')
        <div class="main-panel">
            @yield('content')
            @include('partials._footer')
        </div>
    </div>
</div>

<script src="{{asset('js/all.js')}}" type="text/javascript"></script>
</body>
</html>