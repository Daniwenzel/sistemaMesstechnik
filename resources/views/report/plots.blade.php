@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    @foreach($fullPlotsPath as $plot)
                        <img src="{{ asset($plot) }}" height="90%" width="90%" class="m-5">
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection