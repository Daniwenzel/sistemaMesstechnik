@extends('layouts.app')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
    	<div class="card">
    		<div class="card-body">
                <form method="POST" action="{{ route('site.update', $torre->codigo) }}" id="editarSite">
                    @csrf
                    <div class="row form-group">
                        <label for="nomeMstk" class="control-label">Nome MSTK</label>
                        <input class="form-control" name="nomeMstk" value="{{ $torre->nome_mstk }}">
                    </div>
                    <div class="row form-group">
                        <label for="latsite" class="control-label">Latitude</label>
                        <input class="form-control" name="latsite" value="{{ $torre->lat_decimal }}">
                    </div>
                    <div class="row form-group">
                        <label for="lngsite" class="control-label">Longitude</label>
                        <input class="form-control" name="lngsite" value="{{ $torre->long_decimal }}">
                    </div>
                    <div class="row form-group">
                        <button class="btn btn-outline-success" type="submit" form="editarSite" value="Submit">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection