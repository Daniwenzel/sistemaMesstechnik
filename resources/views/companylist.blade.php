@extends('layouts.app')

@section('content')
    <div data-barba-namespace="company">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Empresas</h4>
                    <button type="button" class="btn btn-outline-success btn-md btn-block" onclick="window.location='{{ route("show.register.company") }}'">
                        <i class="mdi mdi-briefcase mdi-24px"></i>Registrar Empresa
                    </button>
                    <form method="get" action="{{ route('company') }}" class="navbar-form navbar-left mt-4">
                        <div class="form-group">
                            <div class="input-group">
                                <input name="search" type="text" class="form-control" placeholder="Procurar..." aria-label="Company" aria-describedby="colored-addon3">
                                <div class="input-group-append bg-success border-primary">
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
                                    CNPJ
                                </th>
                                <th>
                                    Telefone
                                </th>
                                <th>
                                    E-mail
                                </th>
                                <th>
                                    Ações
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($empresas as $empresa)
                                <tr>
                                    <td>
                                        {{ $empresa->nome }}
                                    </td>
                                    <td>
                                        {{ $empresa->cnpj }}
                                    </td>
                                    <td>
                                        {{ $empresa->telefone }}
                                    </td>
                                    <td>
                                        {{ $empresa->email }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-icons btn-rounded btn-outline-danger btn-delete-company" id="{{$empresa->id}}-btn" data-toggle="modal" data-id="{{ $empresa->id }}">
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
