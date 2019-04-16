@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h2 class="text-md-center">{{ $torre->cod_cliente }}</h2>
            <h4 class="card-title m-3 text-md-center">Última leitura realizada em {{ $created_at }}</h4>

            @foreach($sensores->chunk(2) as $chunk)
                <div class="row m-5">
                    @foreach($chunk as $sensor)
                        <div class="col-md-6 d-md-block">
                            <div class="card card-img-holder">
                                <div class="card-body">
                                    {{ $sensor->barometro->where('created_at', app\Models\Barometro::where('sensor_id', $sensor->id)->latest()->value('created_at')) }}
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