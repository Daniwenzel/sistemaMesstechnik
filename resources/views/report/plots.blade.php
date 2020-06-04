@extends('layouts.app')

@section('content')
    <div>
        @if(Session::has('message'))
            <div class="alert alert-success">
                @foreach (Session::get('message') as $message)
                    <h4>{{ $message }}</h4>
                @endforeach
            </div>
        @endif
    </div> 

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                <h1>{{ $titulo }}</h1>
                    @foreach($fullPlotsPath as $plot)
                        <img src="{{ asset($plot) }}" height="90%" width="90%" class="m-5">
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection