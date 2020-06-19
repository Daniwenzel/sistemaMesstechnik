@extends('layouts.app')

@section('content')
    @foreach($sites as $site)
     <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $site->nome }}</h4>

                <p>{{ $site->codigo }}</p>
                <p>{{ $site->estacao }}</p>
                <p>{{ $site->sitename }}</p>
                <p>{{ $site->ultenvio }}</p>
            </div>
        </div>
    </div>
    <hr>
    @endforeach
@endsection