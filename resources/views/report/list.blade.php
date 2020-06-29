@extends('layouts.app')

@section('content')

     <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form method="get" action="{{ route('reports.list') }}" class="navbar-form navbar-left mt-4">
                    <div class="form-group">
                        <div class="input-group">
                            <input name="search" type="text" class="form-control" placeholder="{{ __('labels.search_station_by_id') }}">
                            <div class="input-group-append bg-primary border-primary">
                                <button type="submit" class="btn input-group-text bg-transparent card-button">
                                    <i class="mdi mdi-magnify text-white"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($nomes as $key => $nome)
        <div class="row text-md-center">
            <div class="col-md-12 stretch-card grid-margin" onclick="window.location='{{ route("reports.plots",['folder' => $key]) }}'">
                <div class="card bg-gradient-info card-img-holder text-white card-button">
                    <div class="card-body btn">
                        <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image"/>
                        <img src="{{ asset('images/wind-turbine.svg') }}" class="card-img-absolute-left" alt="wind-turbine"/>
                        <img src="{{ asset('images/wind-turbine.svg') }}" class="card-img-absolute" alt="wind-turbine"/>
                        <h4 class="font-weight-normal mb-3">{{ $key }}</h4>
                        <h2 class="mb-5">{{ $nome }}</h2>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="container">
        <div class="row justify-content-sm-center">
            {{ $nomes->links() }}
        </div>
    </div>
@endsection