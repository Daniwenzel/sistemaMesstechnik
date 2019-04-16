@extends('layouts.app')

@section('content')
    @foreach($torres->chunk(3) as $chunk)
        <div class="row text-md-center">
            @foreach($chunk as $torre)
                <div class="col-md-4 stretch-card grid-margin d-md-block">
                    <div class="card bg-gradient-green card-img-holder text-white" style="cursor: pointer" onclick="window.location='{{ route('tower.info', $torre->id ) }}'">
                        <div class="card-body">
                            <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                            <h4 class="font-weight-normal mb-3">{{ $torre->cod_MSTK}}
                                <i class="mdi mdi-weather-windy mdi-24px float-right"></i>
                            </h4>
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
@endsection