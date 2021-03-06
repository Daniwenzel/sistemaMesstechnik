@extends('layouts.app')

@section('content')
    <div data-barba-namespace="register-company">
        @if (session('message'))
            <div class="alert alert-success text-center" role="alert">
                {{ session('message') }}
            </div>
        @endif
        <div class="content-wrapper d-flex align-items-center auth register-bg-1 theme-one">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card-body">
                        <h2 class="text-center mb-4">{{ __('Cadastrar Empresa') }}</h2>
                        <form method="POST" action="{{ route('companies.store') }}">
                            @csrf
                            <div class="form-group row">
                                <div class="input-group">
                                    <span class="input-group-text btn-inverse-primary">
                                        <label for="nome">
                                            <i class="mdi mdi-account"></i>
                                        </label>
                                    </span>
                                    <input id="nome" type="text" placeholder="Nome" name="nome" class="form-control{{ $errors->has('nome') ? ' is-invalid' : '' }}" required autofocus>

                                    @if ($errors->has('nome'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('nome') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="input-group">
                                    <span class="input-group-text btn-inverse-warning">
                                        <label for="cnpj">
                                            <i class="mdi mdi-barcode-scan"></i>
                                        </label>
                                    </span>
                                    <input id="cnpj" type="text" placeholder="CNPJ" name="cnpj" class="form-control{{ $errors->has('cnpj') ? ' is-invalid' : '' }}">

                                    @if ($errors->has('cnpj'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('cnpj') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="input-group">
                                    <span class="input-group-text btn-inverse-info">
                                        <label for="phone">
                                            <i class="mdi mdi-phone"></i>
                                        </label>
                                    </span>
                                    <input id="phone" type="text" placeholder="Telefone" name="phone" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}">

                                    @if ($errors->has('phone'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="input-group">
                                    <span class="input-group-text btn-inverse-success">
                                        <label for="email">
                                            <i class="mdi mdi-at"></i>
                                        </label>
                                    </span>
                                    <input id="email" type="email" placeholder="E-mail" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}">

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-10 offset-md-2">
                                    <button type="submit" class="btn btn-success mr-2">{{ __('Confirmar') }}</button>
                                    <button class="btn btn-light" onclick="window.history.go(-1); return false;">{{ __('Cancelar') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
