@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Cargos</h4>
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
                                        <button type="button" class="btn btn-icons btn-rounded btn-outline-primary">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-icons btn-rounded btn-outline-danger">
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
                                        <button type="button" class="btn btn-icons btn-rounded btn-outline-danger">
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
@endsection