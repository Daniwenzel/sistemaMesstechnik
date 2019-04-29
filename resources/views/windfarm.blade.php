@extends('layouts.app')

@section('content')

    @foreach($windfarms as $windfarm)
        <div class="row text-md-center">
            <div class="col-md-12 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white">
                    <div class="card-body btn" onclick="window.location='{{ route('windfarm.info', $windfarm->id) }}'">
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
        {{ $windfarms->links() }}
    </div>
@endsection