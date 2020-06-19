@extends('layouts.app')

@section('content')
    <div data-barba-namespace="company">
        <form method="get" action="{{ route('company.index') }}" class="navbar-form navbar-left mt-3">
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
        @foreach($clientes as $cliente)
            <div class="row text-md-center">
                <div class="col-md-12 stretch-card grid-margin" onclick="window.location='{{ url('clients',$cliente->codigo) }}'">
                    <div class="card bg-gradient-info card-img-holder text-white card-button">
                        <div class="card-body btn">
                            <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                            <img src="{{ asset('images/wind-turbine.svg') }}" class="card-img-absolute" alt="wind-turbine"/>
                            <h4 class="font-weight-normal mb-3">{{ $cliente->codigo }}</h4>
                            <h2 class="mb-5">{{ $cliente->razaosocial }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection