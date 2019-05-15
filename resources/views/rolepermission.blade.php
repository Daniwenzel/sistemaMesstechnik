@extends('layouts.app')

@section('content')
    <div data-barba-namespace="roles">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Cargos</h4>
                        <button type="button" class="btn btn-outline-info btn-md btn-block" data-toggle="modal" data-target=".modal-role-md">
                            <i class="mdi mdi-worker mdi-24px"></i>Registrar Cargo
                        </button>
                        <div class="modal fade modal-role-md badge-inverse-success" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modal-role-label">Registrar Cargo</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form class="forms-sample" method="POST" action="{{ route('create.role') }}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="role">Cargo</label>
                                                <input type="text" class="form-control" id="role" name="role">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success mr-2"> {{ __('Salvar alterações') }}</button>
                                            <button class="btn btn-light" data-dismiss="modal">{{ __('Cancelar') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>
                                        Nome
                                    </th>
                                    <th>
                                        Alterado em
                                    </th>
                                    <th>
                                        Ações
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cargos as $cargo)
                                    <tr>
                                        <td>
                                            {{ $cargo->name }}
                                        </td>
                                        <td>
                                            {{ $cargo->updated_at }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-icons btn-rounded btn-outline-danger" onclick="swalDeletarCargo({{ $cargo->id }})" id="{{$cargo->id}}-btn" data-toggle="modal">
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
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Permissões</h4>
                        <button type="button" class="btn btn-outline-warning btn-md btn-block" data-toggle="modal" data-target=".modal-permission-md">
                            <i class="mdi mdi-account-star mdi-24px"></i>Registrar Permissão
                        </button>
                        <div class="modal fade modal-permission-md badge-inverse-success" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modal-role-label">Registrar Permissão</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form class="forms-sample" method="POST" action="{{ route('create.permission') }}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="permission">Permissão</label>
                                                <input type="text" class="form-control" id="permission" name="permission">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success mr-2"> {{ __('Salvar alterações') }}</button>
                                            <button class="btn btn-light" data-dismiss="modal">{{ __('Cancelar') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>
                                        Nome
                                    </th>
                                    <th>
                                        Alterado em
                                    </th>
                                    <th>
                                        Ações
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($permissoes as $permissao)
                                    <tr>
                                        <td>
                                            {{ $permissao->name }}
                                        </td>
                                        <td>
                                            {{ $permissao->updated_at }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-icons btn-rounded btn-outline-primary">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-icons btn-rounded btn-outline-danger" onclick="swalDeletarPermissao({{ $permissao->id }})" id="{{$permissao->id}}-btn" data-toggle="modal">
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
    </div>
@endsection