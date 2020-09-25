@extends('layouts.app')

@section('content')
	<div class="col-lg-12 grid-margin stretch-card">
    	<div class="card">
    		<div class="card-body">
				<h1 class="my-4">{{ $torre->nomemms }}</h1>
				<div class="col-md-12 text-center">
					<button class="btn btn-inverse-dark" type="button" data-sitcodigo="{{ $torre->codigo }}" id="btn-atendimento-add">Adicionar Atendimento</button>
					<button class="btn btn-inverse-dark" type="button" data-sitcodigo="{{ $torre->codigo }}" id="btn-pendencia-add">Adicionar Pendência</button>
					<button class="btn btn-inverse-dark" type="button" data-sitcodigo="{{ $torre->codigo }}" id="btn-arquivo-add">Adicionar Arquivo</button>
				</div>
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