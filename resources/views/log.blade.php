@extends('layouts.app')

@section('content')
    <div data-barba-namespace="log">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width: 50%">
                                    Descrição
                                </th>
                                <th style="width: 10%">
                                    Status
                                </th>
                                <th style="width: 10%">
                                    Usuário
                                </th>
                                <th style="width: 10%">
                                    Diretorio
                                </th>
                                <th style="width: 20%">
                                    Data
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($logs as $log)
                                <tr class="alert-{{ $log->status }} card-button btn-log-show" diretorio="{{ $log->diretorio }}" mensagem="{{ $log->mensagem }}" status="{{ $log->status }}">
                                    <td>
                                        {{ $log->mensagem }}
                                    </td>
                                    <td>
                                        {{ $log->status }}
                                    </td>
                                    <td>
                                        {{ $log->usuario }}
                                    </td>
                                    <td>
                                        {{ $log->diretorio }}
                                    </td>
                                    <td>
                                        {{ $log->created_at }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-sm-center">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection