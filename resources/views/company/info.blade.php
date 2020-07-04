@extends('layouts.app')

@section('content')
    @foreach($sites as $site)
        <site-card 
            :nome='@json($site->nome)'
            :codigo='@json($site->codigo)'
            :estacao='@json($site->estacao)'
            :sitename='@json($site->sitename)'
            :ultenvio='@json($site->ultenvio)'
            :path='@json($site->imagemPath)'
            :route='@json(route("site.index", $site->codigo))'
        ></site-card>
    <hr>
    @endforeach
@endsection