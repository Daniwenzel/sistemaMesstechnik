@extends('layouts.app')

@section('content')

    <div data-barba-namespace="login" class="login-form-padding">
        <div id="login-bg"></div>

        <form class="login-form" method="POST" action="{{ route('login') }}">
            <img src="{{ asset('images/logo.png') }}" alt="logo">
            @csrf
            <div class="form-group">
                <label for="email" class="label">{{ __('E-Mail Address') }}</label>
                <div class="input-group">
                    <input id="email" type="email" class="login-input {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="label">{{ __('Password') }}</label>
                <div class="input-group">
                    <input id="password" type="password" class="login-input {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary submit-btn btn-block">
                    {{ __('Login') }}
                </button>
            </div>
            <div class="form-group d-flex justify-content-between">
               <!-- <div class="form-check form-check-flat mt-0">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Keep me signed in') }}
                    </label>
                </div>-->
                @if (Route::has('password.request'))
                    <a class="text-small forgot-password text-white" href="{{ route('password.request') }}">
                        {{ __('Forgot Password') }}
                    </a>
                @endif
            </div>
        </form>
        <p class="footer-text text-center">Copyright © 2019 Messtechnik Comércio e Instrumentações LTDA. All rights reserved.</p>
    </div>

@endsection
