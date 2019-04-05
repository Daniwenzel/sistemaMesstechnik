@extends('layouts.app')

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success">
            <ul>
                <li>{!! Session::get('message') !!}</li>
            </ul>
        </div>
    @endif

    <div class="content-wrapper d-flex align-items-center auth register-bg-1 theme-one">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card-body">
                    <h2 class="text-center mb-4">{{ __('Cadastrar Empresa') }}</h2>
                    <form method="POST" action="{{ route('create.company') }}">
                        @csrf
                        <div class="form-group row">
                            <div class="input-group">
                            <span class="input-group-text btn-inverse-primary">
                              <i class="mdi mdi-account"></i>
                            </span>
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Nome" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="input-group">
                            <span class="input-group-text btn-inverse-warning">
                              <i class="mdi mdi-barcode-scan"></i>
                            </span>
                                <input id="cnpj" type="text" class="cnpj form-control{{ $errors->has('cnpj') ? ' is-invalid' : '' }}" placeholder="CNPJ" name="cnpj" value="{{ old('cnpj') }}" required>

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
                              <i class="mdi mdi-phone"></i>
                            </span>
                                <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" placeholder="Telefone" name="phone" value="{{ old('phone') }}" required>

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
                              <i class="mdi mdi-at"></i>
                            </span>
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="E-mail" name="email" value="{{ old('email') }}" required>

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
@endsection
