@extends('layouts.app')

@section('content')
	<div class="col-lg-12 grid-margin stretch-card">
    	<div class="card">
    		<div class="card-body">		
    			<button class="btn btn-inverse-success" type="button" name="teste" onclick="swalMostrarFormImagemTorre({{$sitcodigo}})">Abrir form imagem torre
    			</button>
			</div>
		</div>
	</div>
@endsection