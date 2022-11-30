@extends('layouts.app')

@section('content')
	<div class="col-lg-12 grid-margin stretch-card">
    	<div class="card">
    		<div class="card-body">
			<h4 class="card-title">{{ $site->nome_mstk }}</h4>

				<div class="row">
					<div class="table-responsive col-md-12 my-5" id="site-mapa" style="height: 500px;"></div>
				</div>

				@role('Admin')
				<div class="col-md-12 text-center my-3">
					<button class="btn btn-inverse-dark" type="button" data-oemcodigo="{{ $site->codigo }}" id="btn-arquivo-add">Adicionar Arquivo</button>
					<a href="{{ route('site.edit', $site->codigo) }}"><button class="btn btn-inverse-dark" type="button">Editar Estação</button></a>
				</div>
				@endrole
				<div class="row menu-estacao">
					<div class="table-responsive"><!--col-md-6 my-5-->
						<h4 class="card-title my-3">Informações Gerais</h4>
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
					<!--<div class="col-md-6 my-5">-->
					<img src="{{ $imagem }}" alt="Imagem da estação" class="imagem-estacao">	
					<!--</div>-->
				</div>

				@role('Admin')
				<div class="col-md-12 text-center my-3">
					<button class="btn btn-inverse-dark" type="button" data-sitcodigo="{{ $site->codigo }}" id="btn-atendimento-add">Adicionar Atendimento</button>
				</div>
				@endrole
				<div class="row">
					<div class="table-responsive col-md-12 my-3">
						<h4 class="card-title my-3">Atendimentos</h4>
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
									<td style="word-wrap: break-word">{{ $atendimento->descricao }}</td>
									<td>{{ $atendimento->tipo }}</td>
									<td>{{ $atendimento->dataInicio }}</td>
									<td>{{ $atendimento->dataFim }}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>

				@role('Admin')
				<div class="col-md-12 text-center">
					<button class="btn btn-inverse-dark" type="button" data-sitcodigo="{{ $site->codigo }}" id="btn-pendencia-add">Adicionar Pendência</button>
				</div>
				@endrole
				<div class="row">
					<div class="table-responsive col-md-12 my-5">
						<h4 class="card-title my-3">Pendências</h4>
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
									<td style="word-wrap: break-word">{{ $pendencia->descricao }}</td>
									<td>{{ $pendencia->created_at }}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
				@role('Admin')
				<div class="col-md-12 text-center">
					<button class="btn btn-inverse-dark" type="button" data-sitcodigo="{{ $site->codigo }}" id="btn-equipamento-add">Adicionar Equipamento</button>
				</div>
				@endrole
				<div class="row">
					<div class="table-responsive col-md-12 my-5">
						<h4 class="card-title my-3">Equipamentos</h4>
						<table class="table" id="tabelaEquipamentos">
							<thead>
								<tr>
									<th style="width: 40%">Descrição</th>
									<th style="width: 10%">Nº de Série</th>
									<th style="width: 10%">Data Instalação</th>
									<th style="width: 10%">Tempo Operação</th>
									<th style="width: 10%">Data para Substituição</th>
									<th style="width: 10%">Estado</th>
									<th style="width: 10%">Ações</th>
								</tr>
							</thead>
							<tbody>
							@foreach($equipamentos as $equipamento)
								<tr class="card-button {{ $equipamento->alerta }}">
									<td style="word-wrap: break-word">{{ $equipamento->descricao }}</td>
									<td>{{ $equipamento->numero_serie }}</td>
									<td>{{ $equipamento->data_instalacao }}</td>
									<td>{{ $equipamento->tempo_operacao }}</td>
									<td>{{ $equipamento->data_substituicao }}</td>
									<td>{{ $equipamento->estado }}</td>
									<td>editar</td>
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