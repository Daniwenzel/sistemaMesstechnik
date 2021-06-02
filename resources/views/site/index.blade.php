@extends('layouts.app')

@section('content')
	<div class="col-lg-12 grid-margin stretch-card">
    	<div class="card">
    		<div class="card-body">
				<div class="row">
					<div class="table-responsive col-md-12 my-5" id="site-mapa" style="height: 500px;"></div>
				</div>
				<div class="row">
					<div class="table-responsive col-md-6 my-5">
						<table class="table table-striped">
							<tbody>
								<tr><td>Nome Mstk: {{ $site->nome_mstk }}</td></tr>
								<tr><td>Nome Cliente: {{ $site->cliente }}</td></tr>
								<tr><td>Cidade: {{ $site->cidade }}</td></tr>
								<tr><td>Latitude: {{ $site->lat_decimal }}</td><tr>
								<tr><td>Longitude: {{ $site->long_decimal }}</td></tr>


								<!--<h6 class="my-4"></h6>
								<h6 class="my-4">Nome Cliente:  $site->nomecliente }}</h6>
								<h6 class="my-4">Estação EPE:  $site->estacao }}</h6>
								<h6 class="my-4">Último Envio EPE:  $site->ultenvio }}</h6>-->
							</tbody>
						</table>
					</div>
					<div class="col-md-6 my-5">
						<img src="#">
					</div>
				</div>
				@role('Admin')
				<div class="col-md-12 text-center">
					<button class="btn btn-inverse-dark" type="button" data-sitcodigo="{{ $site->codigo }}" id="btn-atendimento-add">Adicionar Atendimento</button>
					<button class="btn btn-inverse-dark" type="button" data-sitcodigo="{{ $site->codigo }}" id="btn-pendencia-add">Adicionar Pendência</button>
					<button class="btn btn-inverse-dark" type="button" data-sitcodigo="{{ $site->codigo }}" id="btn-arquivo-add">Adicionar Arquivo</button>
					<a href="{{ route('site.edit', $site->codigo) }}"><button class="btn btn-inverse-dark" type="button">Editar Estação</button></a>
				</div>
				@endrole
				<div class="row">
					<div class="table-responsive col-md-12 my-5">
						<table class="table" id="tabelaAtendimentos">
							<thead>
								<tr>
									<th style="width: 50%">Descrição</th>
									<th style="width: 20%">Tipo</th>
									<th style="width: 15%">Inicio</th>
									<th style="width: 15%">Fim</th>
								</tr>
							</thead>
							<tbody>
							@foreach($atendimentos as $atendimento)
								<tr class="card-button">
									<td>{{ $atendimento->descricao }}</td>
									<td>{{ $atendimento->tipo }}</td>
									<td>{{ $atendimento->dataInicio }}</td>
									<td>{{ $atendimento->dataFim }}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="table-responsive col-md-12 my-5">
						<table class="table" id="tabelaPendencias">
							<thead>
								<tr>
									<th style="width: 80%">Descrição</th>
									<th class="width: 20%">Criada em</th>
								</tr>
							</thead>
							<tbody>
							@foreach($pendencias as $pendencia)
								<tr class="card-button {{ $pendencia->alerta }}">
									<td style="style= word-wrap: break-word">{{ $pendencia->descricao }}</td>
									<td>{{ $pendencia->created_at }}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script defer>
		var latitude = {!! json_encode($site->lat_decimal) !!};
		var longitude = {!! json_encode($site->long_decimal) !!};
		var nomeSite = {!! json_encode($site->nome_mstk) !!};

		$(document).ready(function() {
			var siteMapa = L.map('site-mapa', {
    			center: [latitude, longitude],
    			zoom: 15
			});
			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'}).addTo(siteMapa);
			// var siteIcon = L.Icon.Default;
			L.marker([latitude, longitude]).addTo(siteMapa)
			.bindPopup(nomeSite)
			.openPopup();
		})
	</script>
@endpush