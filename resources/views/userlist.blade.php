@extends('layouts.app')

@section('content')
    <div data-barba-namespace="user">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Usuários</h4>
                    <button type="button" class="btn btn-outline-primary btn-md btn-block" onclick="window.location='{{ route("show.register") }}'">
                        <i class="mdi mdi-account-plus mdi-24px"></i>Registrar Usuário
                    </button>
                    <form method="get" action="{{ route('user') }}" class="navbar-form navbar-left mt-4">
                        <div class="form-group">
                            <div class="input-group">
                                <input name="search" type="text" class="form-control" placeholder="Procurar..." aria-label="Username" aria-describedby="colored-addon3">
                                <div class="input-group-append bg-primary border-primary">
                                    <button type="submit" class="btn input-group-text bg-transparent">
                                        <i class="mdi mdi-account-search text-white"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
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
                            @foreach($usuarios as $usuario)
                                <tr>
                                    <td>
                                        {{ $usuario->name }}
                                    </td>
                                    <td>
                                        {{ $usuario->email }}
                                    </td>
                                    <td>
                                        {{ $usuario->empresa->nome }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-icons btn-rounded btn-outline-success">
                                            <i class="mdi mdi-email"></i>
                                        </button>
                                        <button type="button" class="btn btn-icons btn-rounded btn-outline-primary" onclick="window.location='{{ route('show.config', $usuario->id) }}'">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-icons btn-rounded btn-outline-danger btn-delete-user" id="{{$usuario->id}}-btn" data-toggle="modal" data-id="{{ $usuario->id }}">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-md-center">
        {{ $usuarios->links() }}
    </div>

@endsection