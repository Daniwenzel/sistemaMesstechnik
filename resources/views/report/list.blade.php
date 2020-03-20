@extends('layouts.app')

@section('content')

    @foreach($nomes as $key => $nome)
        <div class="row text-md-center">
            <div class="col-md-12 stretch-card grid-margin" onclick="window.location='{{ route("reports.plots",['folder' => $key]) }}'">
                <div class="card bg-gradient-info card-img-holder text-white card-button">
                    <div class="card-body btn">
                        <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                        <img src="{{ asset('images/wind-turbine.svg') }}" class="card-img-absolute-left" alt="wind-turbine"/>
                        <img src="{{ asset('images/wind-turbine.svg') }}" class="card-img-absolute" alt="wind-turbine"/>
                        <h4 class="font-weight-normal mb-3">{{ $key }}</h4>
                        <h2 class="mb-5">{{ $nome }}</h2>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection