@extends('layouts.app')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
    	<div class="card">
    		<div class="card-body">
                <!-- Se o usuário autenticado for ADMIN, mostra uma lista com todos os Clientes. Ao clicar em um cliente, mostra a lista de estações deste cliente. Caso o usuário autenticado não for ADMIN, mostra uma lista das estações deste usuário -->

                @role('Admin')
                    @foreach($projetos as $projeto)
                        <div class="row text-md-center">
                            <div class="col-md-12 stretch-card grid-margin" onclick="window.location='{{ url('stations', $projeto->cliente) }}'">
                                <div class="card bg-gradient-info card-img-holder text-white card-button">
                                    <div class="card-body btn">
                                        <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                                        <img src="{{ asset('images/wind-turbine.svg') }}" class="card-img-absolute" alt="wind-turbine"/>
                                        <h4 class="font-weight-normal mb-3">Projeto</h4>
                                        <h2 class="mb-5">{{ $projeto->cliente }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    @foreach($projetos as $projeto)
                        <div class="row text-md-center">
                            <div class="col-md-12 stretch-card grid-margin" onclick="window.location='{{ url('station', $projeto->codigo) }}'">
                                <div class="card bg-gradient-info card-img-holder text-white card-button">
                                    <div class="card-body btn">
                                        <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                                        <img src="{{ asset('images/wind-turbine.svg') }}" class="card-img-absolute" alt="wind-turbine"/>
                                        <h4 class="font-weight-normal mb-3">{{ $projeto->cliente }}</h4>
                                        <h2 class="mb-5">{{ $projeto->nome_mstk }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endrole
            </div>
        </div>
    </div>
@endsection