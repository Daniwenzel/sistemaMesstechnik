@extends('layouts.app')

@section('content')

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Usuários</h4>
                <button type="button" class="btn btn-outline-success btn-md btn-block" onclick="window.location='{{ route("show.register.company") }}'">
                    <i class="mdi mdi-briefcase mdi-24px"></i>Registrar Empresa
                </button>
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
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
