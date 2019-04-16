@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-6 d-flex align-items-stretch grid-margin">
            <div class="row flex-grow">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Usuário '{{ $user->name }}'</h4>
                            <form class="forms-sample">
                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input type="email" class="form-control" id="email" placeholder="E-mail" value="{{ $user->email }}">
                                </div>
                                <div class="form-group">
                                    <label for="name">Nome</label>
                                    <input type="text" class="form-control" id="name" placeholder="Nome" value="{{ $user->name }}">
                                </div>

                                <select class="custom-select" multiple>
                                @foreach($allroles as $role)
                                    <!--<option selected>Open this select menu</option>-->
                                        <option>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <select class="custom-select" multiple>
                                @foreach($allpermissions as $permission)
                                    <!--<option selected>Open this select menu</option>-->
                                        <option>{{ $permission->name }}</option>
                                    @endforeach
                                </select>
                                <select class="custom-select" multiple>
                                @foreach($allpermissions as $permission)
                                    <!--<option selected>Open this select menu</option>-->
                                        <option>{{ $permission->name }}</option>
                                    @endforeach
                                </select>




                                <!-- <button type="submit" class="btn btn-success mr-2">Submit</button>
                                 <button class="btn btn-light">Cancel</button>-->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection