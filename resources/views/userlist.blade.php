@extends('layouts.app')

@section('content')

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Usuários</h4>
                <button type="button" class="btn btn-outline-primary btn-md btn-block" onclick="window.location='{{ route("show.register") }}'">
                    <i class="mdi mdi-account-plus mdi-24px"></i>Registrar Usuário
                </button>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>
                                Nome
                            </th>
                            <th>
                                E-mail
                            </th>
                            <th>
                                Empresa
                            </th>
                            <th>
                                Ações
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($empresas as $empresa)
                            @foreach($empresa->users as $user)
                                <tr>
                                    <td>
                                        {{ $user->name }}
                                    </td>
                                    <td>
                                        {{ $user->email }}
                                    </td>
                                    <td>
                                        {{ $empresa->nome }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-icons btn-rounded btn-outline-success">
                                            <i class="mdi mdi-email"></i>
                                        </button>
                                        <button type="button" class="btn btn-icons btn-rounded btn-outline-primary">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-icons btn-rounded btn-outline-danger btn-delete-user" id="{{$user->id}}-btn" data-toggle="modal" data-id="{{ $user->id }}">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection