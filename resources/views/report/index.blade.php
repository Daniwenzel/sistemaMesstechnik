@extends('layouts.app')

@section('content')
    <div data-barba-namespace="reports">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row text-md-center">
                        <div class="col-md-6 stretch-card grid-margin" onclick="window.location='{{ url('reports/compare') }}'">
                            <div class="card bg-gradient-warning card-img-holder text-white card-button">
                                <div class="card-body btn">
                                    <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                                    <img src="{{ asset('images/wind-turbine.svg') }}" class="card-img-absolute-left" alt="wind-turbine"/>
                                    <!-- <h4 class="font-weight-normal mb-3">{{ 'Comparar' }}</h4> -->
                                    <h2 class="mb-5">{{ 'Comparar' }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 stretch-card grid-margin" onclick="window.location='{{ url('reports/generate') }}'">
                            <div class="card bg-gradient-danger card-img-holder text-white card-button">
                                <div class="card-body btn">
                                    <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                                    <img src="{{ asset('images/wind-turbine.svg') }}" class="card-img-absolute" alt="wind-turbine"/>
                                    <!-- <h4 class="font-weight-normal mb-3">{{ 'Gerar' }}</h4> -->
                                    <h2 class="mb-5">{{ 'Gerar' }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row text-md-center">
                        <div class="col-md-6 stretch-card grid-margin" onclick="window.location='{{ url('reports/list') }}'">
                            <div class="card bg-gradient-success card-img-holder text-white card-button">
                                <div class="card-body btn">
                                    <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                                    <img src="{{ asset('images/wind-turbine.svg') }}" class="card-img-absolute-left" alt="wind-turbine"/>
                                    <!-- <h4 class="font-weight-normal mb-3">{{ 'Listar' }}</h4> -->
                                    <h2 class="mb-5">{{ 'Listar' }}</h2>
                                </div>
                            </div>
                        </div>
                        <!--<div class="col-md-6 stretch-card grid-margin">
                            <div class="card bg-gradient-secondary card-img-holder text-white card-button">
                                <div class="card-body btn">
                                    <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                                    <img src="{{ asset('images/wind-turbine.svg') }}" class="card-img-absolute" alt="wind-turbine"/>
                                    <!-- <h4 class="font-weight-normal mb-3">{{ 'Gerar' }}</h4> 
                                    <h2 class="mb-5">{{ 'Teste' }}</h2>
                                </div>
                            </div>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection