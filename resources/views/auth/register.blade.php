@extends('layouts.app')

@section('content')
    @if (session('message'))
        <div class="alert alert-success" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="content-wrapper d-flex align-items-center auth register-bg-1 theme-one">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card-body">
                    <h2 class="text-center mb-4">{{ __('labels.register_user') }}</h2>
                    <form method="POST" action="{{ route('create.register') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="input-group">
                            <span class="input-group-text btn-inverse-primary">
                              <i class="mdi mdi-account"></i>
                            </span>
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('labels.name') }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
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

                        <div class="form-group row">
                            <div class="input-group">
                            <span class="input-group-text btn-inverse-info">
                              <i class="mdi mdi-key"></i>
                            </span>
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('labels.password') }}" name="password" required>
                                <div class="input-group-append">
                                    <a style="cursor:pointer;" class="input-group-text" onclick="togglePasswordType()"><i id="passIcon" class="mdi mdi-lock"></i></a>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="input-group">
                                <input id="password-confirm" type="password" class="form-control" placeholder="{{__('labels.confirm')}}" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="input-group">
                                <span class="input-group-text btn-inverse-warning">
                                    <i class="mdi mdi-briefcase"></i>
                                </span>
                                <select class="form-control" id="empresa" name="empresa">
                                    @foreach($empresas as $empresa)
                                        <option>{{ $empresa->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @role('Admin')
                        <div class="form-group row">
                            <div class="col-sm-5">
                                <div class="form-radio">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="accountRole" value="Basica" checked> {{ __('labels.basic') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-radio">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="accountRole" value="Master"> {{ __('labels.master') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endrole
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
    </div>
@endsection
