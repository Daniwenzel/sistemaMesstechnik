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
    <link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/feather.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/materialdesignicons.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{asset('css/vendor.bundle.addons.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/vendor.bundle.base.css')}}" rel="stylesheet" type="text/css">

</head>

<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper auth-page">
        <div class="content-wrapper d-flex align-items-center text-center error-page bg-success">
            <div class="row flex-grow">
                <div class="col-lg-7 mx-auto text-white">
                    <div class="row align-items-center d-flex flex-row">
                        <div class="col-lg-6 text-lg-right pr-lg-4">
                            <h2 class="display-1 mb-0">Acesso Negado</h2>
                        </div>
                        <div class="col-lg-6 error-page-divider text-lg-left pl-lg-4">
                            <h2>Desculpe!</h2>
                            <h3 class="font-weight-light">Você não tem permissão.</h3>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-12 text-center mt-xl-2">
                            <a class="text-white font-weight-medium" href="{{ route('dashboard') }}">Voltar para o início</a>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-12 mt-xl-2">
                            <p class="text-white font-weight-medium text-center">Copyright &copy; 2019 Messtechnik Comércio e Instrumentações LTDA.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>

<script src="{{ asset('js/app.js') }}" type="text/javascript" defer></script>
<script src="{{ asset('js/off-canvas.js') }}" type="text/javascript" defer></script>
<script src="{{ asset('js/misc.js') }}" type="text/javascript" defer></script>
<script src="{{ asset('js/dashboard.js') }}" type="text/javascript" defer></script>

</body>

</html>