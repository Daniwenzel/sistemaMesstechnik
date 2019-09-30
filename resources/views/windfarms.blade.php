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
                    <option {{ $empresa_selecionada == 'all' ? 'selected' : ''}} value="all">Todas</option>
                    @foreach($empresas as $empresa)
                        <option {{ $empresa_selecionada == $empresa->id ? 'selected' : '' }} value="{{ $empresa->id }}">{{ $empresa->nome }}</option>
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
        @foreach($windfarms as $windfarm)
            <div class="row text-md-center">
                <div class="col-md-12 stretch-card grid-margin">
                    <div class="card bg-gradient-info card-img-holder text-white card-button">
                        <div class="card-body btn" onclick="window.location='{{ route('windfarms.show', $windfarm->id) }}'">
                            <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                            <img src="{{ asset('images/wind-turbine.svg') }}" class="card-img-absolute" alt="wind-turbine"/>
                            <h4 class="font-weight-normal mb-3">{{ $windfarm->cod_EPE }}</h4>
                            <h2 class="mb-5">{{ $windfarm->nome }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="row justify-content-md-center">
            {{ $windfarms->appends(['search' => $filtro, 'empresa' => $empresa_selecionada])->links() }}
        </div>
    </div>
@endsection