@extends('layouts.app')

@section('content')
    <div data-barba-namespace="tower">
        @if (Session::has('message'))
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-primary text-md-center">{{ Session::get('message') }}</div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <h2 class="text-md-center">{{ $torre->cod_cliente }}</h2>
                <h4 class="card-title m-3 text-md-center">{{ __('labels.data_recorded', ['attribute' => $torre->updated_at]) }}</h4>
                <div class="row">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card bg-gradient-light cursor-pointer">
                            <div class="card-body card-button-toggle">
                                <h4 class="card-title">{{ __('labels.anemometer') }}
                                    <span class="mdi mdi-arrow-down-bold float-md-right mdi-36px"></span>
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
                                <h4 class="card-title">{{ __('labels.windvane') }}
                                    <span class="mdi mdi-arrow-down-bold float-md-right mdi-36px"></span>
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
                                <h4 class="card-title">{{ __('labels.barometer') }}
                                    <span class="mdi mdi-arrow-down-bold float-md-right mdi-36px"></span>
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
                                <h4 class="card-title">{{ __('labels.temperature') }}
                                    <span class="mdi mdi-arrow-down-bold float-md-right mdi-36px"></span>
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
                                <h4 class="card-title">{{ __('labels.humidity') }}
                                    <span class="mdi mdi-arrow-down-bold float-md-right mdi-36px"></span>
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
                                <h4 class="card-title">{{ __('labels.batery') }}
                                    <span class="mdi mdi-arrow-down-bold float-md-right mdi-36px"></span>
                                </h4>
                                <canvas id="bateriaChart" style="height:250px; display:none;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/chart.js') }}" type="text/javascript" defer></script>
    <script type="text/javascript" defer>
        $(function() {
            @foreach($anemometros as $key => $anemometro)
                var titulo = @json($key);
                var data = @json($anemometro);
                addChartData(anChart, titulo, data);
            @endforeach
            @foreach($windvanes as $key => $windvane)
                var titulo = @json($key);
                var data = @json($windvane);
                addChartData(wvChart, titulo, data);
            @endforeach
            @foreach($barometros as $key => $barometro)
                var titulo = @json($key);
                var data = @json($barometro);
                addChartData(baChart, titulo, data);
            @endforeach
            @foreach($temperaturas as $key => $temperatura)
                var titulo = @json($key);
                var data = @json($temperatura);
                addChartData(tempChart, titulo, data);
            @endforeach
            @foreach($umidades as $key => $umidade)
                var titulo = @json($key);
                var data = @json($umidade);
                addChartData(umiChart, titulo, data);
            @endforeach
            @foreach($baterias as $key => $bateria)
                var titulo = @json($key);
                var data = @json($bateria);
                addChartData(batChart, titulo, data);
            @endforeach
        });
    </script>
@endpush