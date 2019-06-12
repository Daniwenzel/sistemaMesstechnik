@extends('layouts.app')

@section('content')
    <div data-barba-namespace="company">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('labels.company') }}</h4>
                    <button type="button" class="btn btn-outline-success btn-md btn-block" onclick="window.location='{{ route("show.register.company") }}'">
                        <i class="mdi mdi-briefcase mdi-24px"></i>{{ __('buttons.register_company') }}
                    </button>
                    <form method="get" action="{{ route('company') }}" class="navbar-form navbar-left mt-4">
                        <div class="form-group">
                            <div class="input-group">
                                <input name="search" type="text" class="form-control" placeholder="{{ __('labels.search') }}" aria-label="Company" aria-describedby="colored-addon3">
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
                                    {{ __('labels.name') }}
                                </th>
                                <th>
                                    {{ __('labels.cnpj') }}
                                </th>
                                <th>
                                    {{ __('labels.phone') }}
                                </th>
                                <th>
                                    {{ __('labels.email') }}
                                </th>
                                <th>
                                    {{ __('labels.actions') }}
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
                                        <button type="button" class="btn btn-icons btn-rounded btn-outline-danger" onclick="swalDeletarEmpresa({{ $empresa->id }})" id="{{$empresa->id}}-btn" data-toggle="modal">
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
