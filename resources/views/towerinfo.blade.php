@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h2 class="text-md-center">{{ $torre->cod_cliente }}</h2>
            <h4 class="card-title m-3 text-md-center">Última atualização realizada em {{ $created_at }}</h4>

            @foreach(array_chunk($leituras,2) as $chunk)
                <div class="row">
                    @foreach($chunk as $sensor)
                        <div class="col-md-6 d-md-block mt-5">
                            <div class="card card-img-holder bg-gradient-green-white">
                                <div class="card-body">
                                    <?php $sensorArray = $sensor->toArray(); ?>

                                    @foreach(array_slice($sensorArray, 0, count($sensorArray) - 1) as $dataSensor)
                                        <h5>{{ $dataSensor['nome'] }}: {{ $dataSensor['leitura'] }}</h5>
                                    @endforeach

                                    <img src="{{ asset(last($sensorArray)['marca']) }}" class="card-img-absolute"/>
                                    <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

@endsection




<!--
 foreach($chunked_sensores as $sensores)
    <div class="row m-5">
foreach($sensores as $nome => $sensor)
        <div class="col-md-6 d-md-block">
            <div class="card card-img-holder">
                <div class="card-body">
                    <h2> $nome }}</h2>
                                    foreach($sensor as $statistic_sensor)
            <p> $statistic_sensor->nome }} :   $statistic_sensor->leitura }}</p>
                                    endforeach
                <img src=" asset($sensor_vrau) }}" class="card-img-absolute"/>
                                </div>
                            </div>
                        </div>
                    endforeach
            </div>
endforeach
-->