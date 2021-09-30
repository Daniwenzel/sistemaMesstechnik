<?php

namespace Messtechnik\Http\Controllers;

use Illuminate\Http\Request;
use Messtechnik\Models\Cliente;
use Messtechnik\Models\Oem;
use Messtechnik\Models\Atendimento;
use Messtechnik\Models\Pendencia;
use Messtechnik\Models\ImagemSite;
use Messtechnik\Models\Equipamento;
use Illuminate\Support\Facades\Auth;
use Messtechnik\Traits\Logs;
use Messtechnik\Traits\Pendencias;
use Messtechnik\Models\Arquivo;
use DB;

class SiteController extends Controller
{
	use Logs, Pendencias;

	public function index(Request $request) {		
		$projetos = (Auth::user()->hasRole('Admin')) ? Oem::select('cliente')->distinct()->get() : Oem::where('clicodigo', Auth::user()->clicodigo)->get();

		return view('site.list', compact('projetos'));
	}

	public function mostrarSitesCliente($client) {
		$cliente = Cliente::where('razaosocial', $client)->get();

		if(Auth::user()->hasRole('Admin') || Auth::user()->clicodigo == $cliente->codigo) {
			$sites = Oem::where('cliente', $client)->get();

			return view('company.info', compact('sites'));
		}
		else {
			abort(403);
		}
	}

	public function mostrarSite($oemcodigo) {
		$site = Oem::where('codigo', $oemcodigo)->first();
		
		if(Auth::user()->hasRole('Admin') || Auth::user()->clicodigo === $site->clicodigo) {
			$pendencias = Pendencia::where('oemcodigo', $oemcodigo)->orderBy('status', 'asc')->orderBy('gravidade', 'desc')->orderBy('created_at', 'desc')->get();
			$atendimentos = Atendimento::where('oemcodigo', $oemcodigo)->orderBy('dataInicio', 'desc')->get();
			$equipamentos = Equipamento::where('oemcodigo', $oemcodigo)->orderBy('estado', 'asc')->orderBy('created_at', 'asc')->get();

			$imagem = ImagemSite::where('oemcodigo', $site->codigo)->first()->path ?? '/images/No-Image-Placeholder.png';

			return view('site.index', compact('site', 'pendencias', 'atendimentos', 'equipamentos', 'imagem'));
		} 
		else {
			$this->createLog('-', 'error', 'Usuário não-autenticado tentou visualizar estação com código = '.$oemcodigo);
			abort(403);
		}

		// Desenvolver o controle de acesso, admins podem requisitar qualquer codigo de torre, enquanto que o cliente pode apenas as suas próprias torres
	}

	public function editarSite($oemcodigo) {
		$torre = Oem::find($oemcodigo);
		
		return view('site.edit', compact('torre'));
	}


	public function adicionarArquivoTorre(Request $request) {
		try {
			$arquivo = $request->file('arquivo');
			$site = Oem::find($request->codigoSite);
			$tipo = $request->tipoArquivo;
			$nomeArquivo = $arquivo->getClientOriginalName();
			$pathArquivo = '/storage/'.$tipo.'/'.$nomeArquivo;

			/* Se o tipo do arquivo for pdf ou imagem, e se for um arquivo válido, salva arquivo no storage do laravel e armazena um registro do arquivo na tabela arquivos.
			Armazena um log do resultado e retorna ao método javascript com mensagem */
			if(($arquivo->extension() === 'pdf' || getimagesize($arquivo)) && $arquivo->isValid()) {
				$upload = $arquivo->storeAs($tipo, $nomeArquivo, 'public');

				if($upload) {
					switch($tipo) {
						case 'atendimento':
							Atendimento::find($request->tipoCodigo)->update(['arquivo' => $pathArquivo]);
							break;
						case 'pendencia':
							Pendencia::find($request->tipoCodigo)->update(['arquivo' => $pathArquivo]);
							break;
						case 'imagem':
							ImagemSite::updateOrCreate(['oemcodigo' => $site->codigo], ['path' => $pathArquivo]);
							break;
					}

					$this->createLog($tipo, 'success', $site->nome_mstk.': Arquivo '.$nomeArquivo.', tipo "'.$tipo.'", armazenado com sucesso.');
					$response = 'Arquivo armazenado com sucesso.';
				}
				else {
					$this->createLog($tipo, 'error', $site->nome_mstk.': Erro ao tentar salvar arquivo '.$nomeArquivo);
					$response = 'Não foi possível armazenar o arquivo.';
				}
			}
			else {
				$this->createLog('/'.$tipo, 'error', $site->nome_mstk.': Formato do arquivo '.$nomeArquivo.' inválido.');
				$response = 'Formato do arquivo incorreto ou inválido.';
			}

			$request->session()->flash('message', $response);
		} catch(\Exception $e) {
			$this->createLog('-', 'error', 'Falha ao tentar adicionar arquivo: '.$e->getMessage());
		}
	    return;
	}

	public function adicionarAtendimentoTorre(Request $request) {
		try {
			$atendimento = Atendimento::create([
				'oemcodigo' => $request->codigoSite,
				'descricao' => $request->descricaoAtendimento,
				'tipo' => $request->tipoAtendimento,
				'dataInicio' => $request->dataInicio,
				'dataFim' => $request->dataFim
			]);
			$this->createLog('-', 'success', Oem::find($atendimento->oemcodigo)->nome_mstk.': Atendimento cód. '.$atendimento->codigo.' do tipo '.$atendimento->tipo.' criado com sucesso.');
		} catch(\Exception $e) {
			$this->createLog('-', 'error', 'Falha ao tentar adicionar atendimento: '.$e->getMessage());

			abort(500);
		}

		return;
	}

	public function adicionarPendenciaTorre(Request $request) {
		try {
			$pendencia = $this->adicionarPendencia($request->codigoSite, null, $request->descricaoPendencia, $request->gravidadePendencia, 'Pendente');

			$this->createLog('-', 'success', 'Pendencia cód. número '.$pendencia->codigo.' criada com sucesso: '.Oem::find($pendencia->oemcodigo)->nomemms);
		} catch(\Exception $e) {
			$this->createLog('-', 'error', 'Falha ao tentar adicionar pendencia: '.$e->getMessage());

			abort(500);
		}

		return;
	}

	public function adicionarEquipamentoTorre(Request $request) {
		try {
			// Se o request possuir uma data de substituição e a data de substituição expirou, atribui o estado "Irregular" ao equipamento, caso contrário, atribui o estado do request e armazena registro do equipamento
			if($request->dataSubstituicao && date_diff(date_create(date('Y-m-d')), date_create($request->dataSubstituicao))->format('%R%a') <= 0) {
				$estado = "Irregular";
			} else {
				$estado = $request->estadoEquipamento;
			}
			$equipamento = Equipamento::create([
				'sitcodigo' => $request->codigoSite,
				'descricao' => $request->descricaoEquipamento,
				'numero_serie' => $request->nroSerieEquipamento,
				'data_instalacao' => $request->dataInstalacao,
				'tempo_operacao' => $request->tempoOperacao,
				'data_substituicao' => $request->dataSubstituicao,
				'estado' => $estado
			]);

			// Se o estado do novo equipamento for igual a "Irregular", cria pendência Urgente
			if($equipamento->estado === 'Irregular') {
				$descricaoPendencia = 'Pendência gerada pois equipamento "'.$equipamento->descricao.'" está com seu estado igual a "Irregular".';
				$this->adicionarPendencia($equipamento->oemcodigo, $equipamento->codigo, $descricaoPendencia, 'Urgente', 'Pendente');
			}
			// Se o equipamento possuir uma data de substituição e ela estiver próxima de 90 dias, cria uma pendência Urgente. Caso estiver próxima de 1 ano, cria uma pendência Normal
			elseif($equipamento->data_substituicao) {
				$diasParaSubstituicao = date_diff(date_create(date('Y-m-d')), date_create($equipamento->data_substituicao))->format('%R%a');

				if($diasParaSubstituicao < 90) {
					$descricaoPendencia = 'Pendência gerada pois equipamento "'.$equipamento->descricao.'" está com a data de substituição atrasada ou próxima de 90 dias.';
					$this->adicionarPendencia($equipamento->oemcodigo, $equipamento->codigo, $descricaoPendencia, 'Urgente', 'Pendente');
				} 
				
				elseif($diasParaSubstituicao < 365) {
					$descricaoPendencia = 'Pendência gerada pois equipamento "'.$equipamento->descricao.'" está com a data de substituição próxima de 1 ano.';
					$this->adicionarPendencia($equipamento->oemcodigo, $equipamento->codigo, $descricaoPendencia, 'Normal', 'Pendente');
				}
			}
			
			$this->createLog('-', 'success', Oem::find($equipamento->oemcodigo)->nome_mstk.': Equipamento cód. '.$equipamento->codigo.' criado com sucesso');
		} catch(\Exception $e) {
			$this->createLog('-', 'error', 'Falha ao tentar adicionar equipamento: '.$e->getMessage());

			//abort(500);
		}

		return;
	}

	public function mostrarAtendimentosTorre($sitcodigo) {
		return response()->json(Atendimento::where('oemcodigo',$sitcodigo)->get(['codigo', 'descricao'])->all());
	}

	public function mostrarPendenciasTorre($sitcodigo) {
		return response()->json(Pendencia::where('oemcodigo',$sitcodigo)->get(['codigo', 'equipcodigo', 'descricao', 'gravidade', 'status', 'arquivo'])->all());
	}

	public function update(Request $request, $sitcodigo) {
		Oem::find($sitcodigo)->update([
			'nome_mstk' => $request['nomeMstk'],
			'lat_decimal' => $request['latsite'],
			'long_decimal' => $request['lngsite']
			]);
		return $this->mostrarSite($sitcodigo);
	}
}