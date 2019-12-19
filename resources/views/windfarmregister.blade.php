@extends('layouts.app')

@section('content')

    @if (Session::has('message'))
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success text-md-center">{{ Session::get('message') }}</div>
            </div>
        </div>
    @endif
    <div class="row grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Cadastrar Parque Eólico</h4>
                <form class="forms-sample mt-5" method="post" action="{{ route('windfarms.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input name="nome" type="text" class="form-control" id="nome">
                    </div>
                    <div class="form-group">
                        <label for="cod_EPE">Código EPE</label>
                        <input name="cod_EPE" type="text" class="form-control" id="cod_EPE">
                    </div>
                    <div class="form-group">
                        <label for="empresa">Cliente</label>
                        <select class="form-control" id="empresa" name="empresa">
                            @foreach($clientes as $cliente)
                                <option>{{ $cliente->razaosocial }}</option>
                            @endforeach
                        </select>
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