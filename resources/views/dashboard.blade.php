@extends('layouts.app')

@section('content')
    <div data-barba-namespace="home">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <button id="btnvrau" onclick="teste()" value="5">testeteste</button>
                        <div id="tester" style="width:90%;height:250px;"></div>
                        <div class="card-header">Dashboard</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            You are logged in!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
