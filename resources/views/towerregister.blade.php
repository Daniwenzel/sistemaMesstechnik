@extends('layouts.app')

@section('content')
    @if (Session::has('message'))
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success text-md-center">{{ Session::get('message') }}</div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div class="clearfix">
                        <div class="float-right">
                            <p class="mb-0 text-right">Parque Eólico</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $parque->nome }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="float-right">
                        <i class="mdi mdi-wind-turbine text-facebook icon-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div class="clearfix">
                        <div class="float-right">
                            <p class="mb-0 text-right">Código EPE</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $parque->cod_EPE }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="float-right">
                        <i class="mdi mdi-barcode text-reddit icon-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div class="clearfix">
                        <div class="float-right">
                            <p class="mb-0 text-right">Empresa</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $parque->empresa->nome }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="float-right">
                        <i class="mdi mdi-factory text-twitter icon-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Cadastrar Torre</h4>
                <p class="card-description">
                    <b class="text-danger">ATENÇÃO!</b> Para que o sistema consiga encontrar os dados correspondentes ás leituras de sensores da torre,
                    o <i>Código Arquivo de Dados</i> deve ser IDÊNTICO ao encontrado no seu respectivo arquivo de dados/configuração.
                </p>
                <form class="forms-sample mt-5" method="post" action="{{ route('towers.store', $parque->id) }}">
                    @csrf
                    <div class="form-group">
                        <label for="cod_cliente">Código do Cliente</label>
                        <input name="cod_cliente" type="text" class="form-control" id="cod_cliente">
                    </div>
                    <div class="form-group">
                        <label for="cod_mstk">Código MSTK</label>
                        <input name="cod_mstk" type="text" class="form-control" id="cod_mstk">
                    </div>
                    <div class="form-group">
                        <label for="cod_arquivo_dados">Código do Arquivo de Dados</label>
                        <input name="cod_arquivo_dados" type="text" class="form-control" id="cod_arquivo_dados">
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success mr-2">{{ __('buttons.confirm') }}</button>
                            <button class="btn btn-light" onclick="window.history.go(-1); return false;">{{ __('buttons.cancel') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection