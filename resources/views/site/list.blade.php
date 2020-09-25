@extends('layouts.app')

@section('content')
	@foreach($torres as $torre)
		<div class="row text-md-center">
            <div class="col-md-12 stretch-card grid-margin" onclick="window.location='{{ url('station',$torre->codigo) }}'">
                <div class="card bg-gradient-info card-img-holder text-white card-button">
                    <div class="card-body btn">
                        <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                        <img src="{{ asset('images/wind-turbine.svg') }}" class="card-img-absolute" alt="wind-turbine"/>
                        <h4 class="font-weight-normal mb-3">{{ $torre->estacao }}</h4>
                        <h2 class="mb-5">{{ $torre->sitename }}</h2>
                    </div>
                </div>
            </div>
        </div>
	@endforeach
@endsection