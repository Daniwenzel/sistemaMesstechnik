@extends('layouts.app')

@section('content')
    <div data-barba-namespace="user-config">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Cadastro de {{ $user->name }}</h4>
                        <form method="POST" action="{{ route('edit.config', $user->id) }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Nome</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="name" value="{{ $user->name }}"/>
                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Sobrenome</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="last_name" value="{{ $user->last_name }}" />
                                            @if ($errors->has('last_name'))
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('last_name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Gênero</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="genero" value="{{ $user->genero }}">
                                                <option {{ $user->genero == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                                <option {{ $user->genero == 'Feminino' ? 'selected' : '' }}>Feminino</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Data de Nascimento</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" placeholder="dd/mm/yyyy" name="aniversario" value="{{ $user->aniversario }}"/>
                                            @if ($errors->has('aniversario'))
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('aniversario') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <button type="submit" class="btn btn-outline-success btn-fw">
                                    <i class="mdi mdi-file-document m-2"></i>Submit</button>
                            </div>
                        </form>
                        <form class="mt-5" method="POST" action="{{ route('edit.avatar', $user->id) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="avatar" class="col-sm-3 col-form-label">{{ __('Avatar (opcional)') }}</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control file-upload-info file-upload-browse btn btn-outline-success" name="avatar" />
                                            @if ($errors->has('avatar'))
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('avatar') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-outline-info btn-fw">
                                        <i class="mdi mdi-upload"></i>Upload</button>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="form-group row justify-content-md-center">
                                        @if($user->getFirstMedia('profile'))
                                            <img src="{{ $user->getFirstMedia('profile')->getUrl('preview') }}" alt="profile_image_preview"/>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection