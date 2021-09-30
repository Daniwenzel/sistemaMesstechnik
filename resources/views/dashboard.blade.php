@extends('layouts.app')

@section('content')
    <div data-barba-namespace="home">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        teste na branch teste2
                        @foreach($atendimentos as $atendimento)
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div class="card border border-danger rounded mb-3">
                                        <div class="card-header text-center card-button">{{ $atendimento->torre->cliente }}</div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $atendimento->torre->nome_mstk }}: {{ $atendimento->dataInicio }} ~ {{ $atendimento->dataFim }}</h5>
                                            <p>{{ $atendimento->descricao }}</p>
                                            <hr />
                                            <h5>Pendências:</h5>
                                            @foreach($atendimento->torre->pendencias as $pendencia)
                                                <p class="card-text">{{ $pendencia->descricao }}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
