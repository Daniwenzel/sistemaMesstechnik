@extends('layouts.app')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
    	<div class="card">
    		<div class="card-body">
                <form method="POST" action="{{ route('site.update', $torre->codigo) }}" id="editarSite">
                    @csrf
                    <div class="form-group">
                        <label for="nomeMstk" class="control-label">Nome MSTK</label>
                        <input name="nomeMstk" value="{{ $torre->nomemstk }}">
                    </div>
                    <div class="form-group">
                        <label for="latsite" class="control-label">Latitude</label>
                        <input name="latsite" value="{{ $torre->latsite }}">
                    </div>
                    <div class="form-group">
                        <label for="lngsite" class="control-label">Longitude</label>
                        <input name="lngsite" value="{{ $torre->lngsite }}">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-outline-success" type="submit" form="editarSite" value="Submit">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection