@extends('layouts.app')

@section('content')
    <div data-barba-namespace="user">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('labels.users') }}</h4>
                    @role('Admin')
                    <button type="button" class="btn btn-outline-primary btn-md btn-block" onclick="window.location='{{ route("users.create") }}'">
                        <i class="mdi mdi-account-plus mdi-24px"></i>{{ __('buttons.register_user') }}
                    </button>
                    @endrole
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
                                    {{ __('labels.name') }}
                                </th>
                                <th>
                                    {{ __('labels.email') }}
                                </th>
                                <th>
                                    {{ __('labels.company') }}
                                </th>
                                <th>
                                    {{ __('labels.type') }}
                                </th>
                                <th>
                                    {{ __('labels.actions') }}
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
                                        {{ $usuario->empresa->razaosocial }}
                                    </td>
                                    <td>
                                        {{ $usuario->getRoleNames()->first() }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-icons btn-rounded btn-outline-success">
                                            <i class="mdi mdi-email"></i>
                                        </button>
                                        @if($usuario->codigo === Auth::user()->codigo or Auth::user()->hasRole('Admin'))
                                            <button type="button" class="btn btn-icons btn-rounded btn-outline-primary" 
                                            onclick="window.location='{{ route('users.edit', $usuario->codigo) }}'">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                        @endif
                                        @role('Admin')
                                        <button type="button" class="btn btn-icons btn-rounded btn-outline-danger btn-user-delete 
                                        {{ Auth::user()->codigo === $usuario->codigo ? 'hidden' : '' }}" usuario="{{ $usuario->codigo }}" data-toggle="modal">
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
    </div>

    <div class="row justify-content-md-center">
        {{ $usuarios->links() }}
    </div>

@endsection