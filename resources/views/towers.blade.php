@extends('layouts.app')

@section('content')
    <div data-barba-namespace="windfarms-info">
        <button type="button" class="btn btn-outline-primary btn-md btn-block" onclick="window.location=
                '{{ route('show.register.tower', $farm_id) }}'">
            <i class="mdi mdi-wind-turbine mdi-24px"></i>{{ __('buttons.register_tower') }}
        </button>
        <form method="get" action="{{ route('windfarm.info', $farm_id) }}" class="navbar-form navbar-left mt-4">
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
        @if (Session::has('message'))
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-primary text-md-center">{{ Session::get('message') }}</div>
                </div>
            </div>
        @endif
        @foreach($torres->chunk(3) as $chunk)
            <div class="row text-md-center">
                @foreach($chunk as $torre)
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-green card-img-holder text-white card-tower card-button" onclick="window.location='{{ route('tower', $torre->id ) }}'">
                            <div class="card-body">
                                <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                                <img src="{{ asset('images/botao-transparent.png') }}" class="card-img-absolute" alt="circle-image"/>
                                <h4 class="font-weight-normal mb-3">{{ $torre->cod_MSTK }}</h4>
                                <h2 class="mb-5">{{ $torre->cod_cliente }}</h2>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        <div class="row justify-content-md-center">
            {{ $torres->links() }}
        </div>
    </div>
@endsection