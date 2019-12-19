@extends('layouts.app')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ __('Relatório Semanal') }}</h4>
                <form method="get" action="#" onsubmit="console.log('vrau')">
                    <select class="form-control" id="sites" name="site">
                        @foreach($sites as $site)
                            <option>{{ $site->sitename }}</option>
                        @endforeach
                    </select>
                    <button type="submit">enviar request para rapache</button>
                </form>
            </div>
        </div>
    </div>
@endsection