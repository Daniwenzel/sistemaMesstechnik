@extends('layouts.app')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
    	<div class="card">
    		<div class="card-body">
                <!-- Se o usuário autenticado for ADMIN, mostra uma lista com todos os Clientes. Ao clicar em um cliente, mostra a lista de estações deste cliente. Caso o usuário autenticado não for ADMIN, mostra uma lista das estações deste usuário -->

                <!--<clients-list v-bind:clientsList="{{ json_encode($projetos) }}"></clients-list>-->

                <h4 class="card-title">Estações</h4>

                @role('Admin')
                    <button type="button" class="btn btn-outline-primary btn-md btn-block" onclick="window.location='{{ route("users.create") }}'">
                        <i class="mdi mdi-account-plus mdi-24px"></i>Registrar Estação
                    </button>
                @endrole
                <!-- Editar formulário para buscar estação -->
                <form method="get" action="{{ route('users.index') }}" class="navbar-form navbar-left mt-4">
                    <div class="form-group">
                        <div class="input-group">
                            <input name="search" type="text" class="form-control" placeholder="{{ __('labels.search') }}" aria-label="Username" aria-describedby="colored-addon3">
                            <div class="input-group-append bg-primary border-primary">
                                <button type="submit" class="btn input-group-text bg-transparent card-button">
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
                                Cidade
                            </th>
                            <th>
                                Coordenadas
                            </th>
                            <th>
                                {{ __('labels.actions') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($projetos as $projeto)
                            <tr>
                                <td>
                                    {{ $projeto->nome_mstk }}
                                </td>
                                <td>
                                    {{ $projeto->cidade }}
                                </td>
                                <td>
                                    {{ $projeto->lat.' '.$projeto->long }}
                                </td>
                                <td>
                                    <a href="{{ route('site.index', $projeto->codigo) }}"><button type="button" class="btn btn-icons btn-rounded btn-outline-success">
                                        <i class="mdi mdi-information"></i>
                                    </button></a>
                                    <button type="button" class="btn btn-icons btn-rounded btn-outline-primary" onclick="window.location='#'">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                    @role('Admin')
                                    <button type="button" class="btn btn-icons btn-rounded btn-outline-danger btn-user-delete" data-toggle="modal">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                    @endrole
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection