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
                                <th>
                                    Data
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Diretorio
                                </th>
                                <th>
                                    Arquivo
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($logs as $log)
                                @switch($log->status)
                                    @case('success')
                                    <tr class="alert-success card-button" onclick="swalMostrarMensagemLog('{{ $log->diretorio }}', '{{ $log->mensagem }}', 'success')">
                                    @break
                                    @case('warning')
                                    <tr class="alert-warning card-button" onclick="swalMostrarMensagemLog('{{ $log->diretorio }}', '{{ $log->mensagem }}', 'warning')">
                                    @break
                                    @case('error')
                                    <tr class="alert-danger card-button" onclick="swalMostrarMensagemLog('{{ $log->diretorio }}', '{{ $log->mensagem }}', 'error')">
                                        @break
                                        @endswitch
                                        <td>
                                            {{ $log->created_at }}
                                        </td>
                                        <td>
                                            {{ $log->status }}
                                        </td>
                                        <td>
                                            {{ $log->diretorio }}
                                        </td>
                                        <td>
                                            {{ $log->nome }}
                                        </td>
                                    </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-md-center">
            {{ $logs->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        window.swalMostrarMensagemLog = function(diretorio, mensagem, tipo) {
            Swal.fire({
                type: tipo,
                title: diretorio,
                text: mensagem,
            })
        };
    </script>
@endpush