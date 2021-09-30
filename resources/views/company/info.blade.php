@extends('layouts.app')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
    	<div class="card">
    		<div class="card-body">
                @foreach($sites as $site)
                    <div class="row text-md-center {{ $site->nome_mstk }}">
                        <div class="col-md-12 stretch-card grid-margin" onclick="window.location='{{ url('station', $site->codigo) }}'">
                            <div class="card bg-gradient-info card-img-holder text-white card-button">
                                <div class="card-body btn">
                                    <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                                    <img src="{{ asset('images/wind-turbine.svg') }}" class="card-img-absolute" alt="wind-turbine"/>
                                    <h4 class="font-weight-normal mb-3">{{ $site->cliente }}</h4>
                                    <h2 class="mb-5">{{ $site->nome_mstk }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    
    <!--foreach($sites as $site)
        <site-card 
            :nome='@json($site->cliente)'
            :codigo='@json($site->codigo)'
            :estacao='123'
            :sitename='@json($site->nome_mstk)'
            :ultenvio='2020-01-01'
            :path='/images/camera-off.png'
            :route='@json(route("site.index", $site->codigo))'
        ></site-card>
        <hr>
    endforeach-->
@endsection