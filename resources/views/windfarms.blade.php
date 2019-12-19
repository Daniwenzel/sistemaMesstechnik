@extends('layouts.app')

@section('content')
    <div data-barba-namespace="windfarms">
        @role('Admin')
        <form method="get" action="{{ route('windfarms.create') }}">
            <button type="submit" class="btn btn-outline-primary btn-md btn-block">
                <i class="mdi mdi-wind-turbine mdi-24px"></i>{{ __('buttons.register_windfarm') }}
            </button>
        </form>
        <form method="get" action="{{ route('windfarms.index') }}" class="navbar-form navbar-left mt-3">
            <div class="form-group">
                <select class="form-control mt-3" id="empresa" name="empresa" onchange="this.form.submit();">
                    <option {{ $cliente_selecionado == 'all' ? 'selected' : ''}} value="all">Todas</option>
                    @foreach($clientes as $cliente)
                        <option {{ $cliente_selecionado == $cliente->codigo ? 'selected' : '' }} value="{{ $cliente->codigo }}">{{ $cliente->razaosocial }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        @endrole
        <form method="get" action="{{ route('windfarms.index') }}" class="navbar-form navbar-left mt-3">
            <div class="form-group">
                <div class="input-group">
                    <input name="search" type="text" class="form-control" placeholder="{{ __('labels.search') }}" aria-label="Username" aria-describedby="colored-addon3">
                    <div class="input-group-append bg-primary border-primary">
                        <button type="submit" class="btn input-group-text bg-transparent card-button">
                            <i class="mdi mdi-account-search text-white"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        @foreach($torres as $torre)
            <div class="row text-md-center">
                <div class="col-md-12 stretch-card grid-margin">
                    <div class="card bg-gradient-info card-img-holder text-white card-button">
                        <div class="card-body btn" onclick="window.location='{{ route('windfarms.show', $torre->codigo) }}'">
                            <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                            <img src="{{ asset('images/wind-turbine.svg') }}" class="card-img-absolute" alt="wind-turbine"/>
{{--                            <h4 class="font-weight-normal mb-3">{{ $torre->cod_EPE }}</h4>--}}
                            <h2 class="mb-5">{{ $torre->sitename }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="row justify-content-md-center">
            {{ $torres->appends(['search' => $filtro, 'torre' => $cliente_selecionado])->links() }}
        </div>
    </div>
@endsection