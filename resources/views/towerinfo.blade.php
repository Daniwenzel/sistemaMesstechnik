@extends('layouts.app')

@section('content')
    <div data-barba-namespace="tower">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="text-md-center">{{ $torre->cod_cliente }}</h2>
                <h4 class="card-title m-3 text-md-center">Dados do dia {{ $yesterday }}</h4>
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card bg-gradient-light cursor-pointer">
                            <div class="card-body card-button-toggle">
                                <h4 class="card-title">Anemômetros
                                    <span class="mdi mdi-arrow-down-bold pull-right mdi-36px"></span>
                                </h4>
                                <canvas id="anemometroChart" style="height:250px; display:none;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card bg-gradient-light cursor-pointer">
                            <div class="card-body card-button-toggle">
                                <h4 class="card-title">Windvanes
                                    <span class="mdi mdi-arrow-down-bold pull-right mdi-36px"></span>
                                </h4>
                                <canvas id="windvaneChart" style="height:250px; display:none;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card bg-gradient-light cursor-pointer">
                            <div class="card-body card-button-toggle">
                                <h4 class="card-title">Barômetros
                                    <span class="mdi mdi-arrow-down-bold pull-right mdi-36px"></span>
                                </h4>
                                <canvas id="barometroChart" style="height:250px; display:none;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card bg-gradient-light cursor-pointer">
                            <div class="card-body card-button-toggle">
                                <h4 class="card-title">Temperaturas
                                    <span class="mdi mdi-arrow-down-bold pull-right mdi-36px"></span>
                                </h4>
                                <canvas id="temperaturaChart" style="height:250px; display:none;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card bg-gradient-light cursor-pointer">
                            <div class="card-body card-button-toggle">
                                <h4 class="card-title">Umidades
                                    <span class="mdi mdi-arrow-down-bold pull-right mdi-36px"></span>
                                </h4>
                                <canvas id="umidadeChart" style="height:250px; display:none;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card bg-gradient-light cursor-pointer">
                            <div class="card-body card-button-toggle">
                                <h4 class="card-title">Baterias
                                    <span class="mdi mdi-arrow-down-bold pull-right mdi-36px"></span>
                                </h4>
                                <canvas id="bateriaChart" style="height:250px; display:none;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script type="text/javascript" defer>

        $(function() {
            @foreach($anemometros as $key => $anemometro)
                var titulo = {!! json_encode($key) !!};
                var data = {!! json_encode($anemometro) !!};

                addChartData(anChart, titulo, data);
            @endforeach
            @foreach($windvanes as $key => $windvane)
                var titulo = {!! json_encode($key) !!};
                var data = {!! json_encode($windvane) !!};

                addChartData(wvChart, titulo, data);
            @endforeach
            @foreach($barometros as $key => $barometro)
                var titulo = {!! json_encode($key) !!};
                var data = {!! json_encode($barometro) !!};

                addChartData(baChart, titulo, data);
            @endforeach
            @foreach($temperaturas as $key => $temperatura)
                var titulo = {!! json_encode($key) !!};
                var data = {!! json_encode($temperatura) !!};

                addChartData(tempChart, titulo, data);
            @endforeach
            @foreach($umidades as $key => $umidade)
                var titulo = {!! json_encode($key) !!};
                var data = {!! json_encode($umidade) !!};

                addChartData(umiChart, titulo, data);
            @endforeach
            @foreach($baterias as $key => $bateria)
                var titulo = {!! json_encode($key) !!};
                var data = {!! json_encode($bateria) !!};

                addChartData(batChart, titulo, data);
            @endforeach
        });
        </script>
    @endpush
@endsection