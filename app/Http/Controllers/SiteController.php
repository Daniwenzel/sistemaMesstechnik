<?php

namespace Messtechnik\Http\Controllers;

use Illuminate\Http\Request;
use Messtechnik\Models\Cliente;
use Messtechnik\Models\Site;
use Messtechnik\Models\ImagemSite;
use Messtechnik\Models\Atendimento;
use Messtechnik\Models\Pendencia;
use Illuminate\Support\Facades\Auth;
use Messtechnik\Traits\Logs;
use Messtechnik\Models\Arquivo;
use DB;

class SiteController extends Controller
{
	use Logs;

	public function index(Request $request) {		
		$projetos = Auth::user()->hasRole('Admin') ? Site::select('cliente')->distinct() : Site::where('clicodigo', Auth::user()->clicodigo);
		$projetos = $projetos->get();

		return view('site.list', compact('projetos'));
	}

	public function mostrarSitesCliente($client) {
		$sites = Site::where('cliente', $client)->get();
		$cliente = Cliente::where('razaosocial', $client)->get();

		if(Auth::user()->hasRole('Admin') || Auth::user()->clicodigo == $cliente->codigo) {
			return view('company.info', compact('sites'));
		}
		else {
			abort(403);
		}
	}

	public function mostrarSite($sitcodigo) {
		$site = Site::where('codigo', $sitcodigo)->first();
		
		if(Auth::user()->hasRole('Admin') || Auth::user()->clicodigo === $torre->clicodigo) {
			$pendencias = Pendencia::where('sitcodigo', $site->codigo)->orderBy('gravidade', 'desc')->orderBy('created_at', 'desc')->get();
			$atendimentos = Atendimento::where('sitcodigo', $site->codigo)->orderBy('dataInicio', 'desc')->get();

			return view('site.index', compact('site', 'pendencias', 'atendimentos'));
		} 
		else {
			abort(403);
		}

		// Desenvolver o controle de acesso, admins podem requisitar qualquer codigo de torre, enquanto que o cliente pode apenas as suas próprias torres
	}

	public function editarSite($sitcodigo) {
		$torre = Site::find($sitcodigo);
		
		return view('site.edit', compact('torre'));
	}


	public function adicionarArquivoTorre(Request $request) {

		dd($request);
		
		$arquivo = $request->file('arquivo');
		$tipo = $request->tipoArquivo;
		$tipoNum = $tipo == 'atendimento' ? 1 : ($tipo == 'pendencia' ? 2 : ($tipo == 'imagem' ? 3 : 0));

		if(($arquivo->extension() === 'pdf' || getimagesize($arquivo)) && $arquivo->isValid()) {
			$upload = $arquivo->storeAs($tipo, $arquivo->getClientOriginalName(), 'public');

			if($upload) {
				Arquivo::create([
					'path' => '/storage/'.$tipo.'/'.$arquivo->getClientOriginalName(),
					'sitcodigo' => $request->codigoSite,
					'tipo' => $tipoNum
					]);
				
				$this->createLog($tipo, 'success', $arquivo->getClientOriginalName().' armazenado com sucesso');
				$response = 'Arquivo armazenado com sucesso.';
			}
			else {
				$this->createLog($tipo, 'error', 'Erro ao tentar salvar arquivo');
				$response = 'Não foi possível armazenar o arquivo.';
			}
		}
		else {
			$this->createLog('/'.$tipo, 'error', 'Formato do arquivo inválido');
			$response = 'Formato do arquivo incorreto ou inválido.';
		}

		$request->session()->flash('message', $response);

	    return;
	}

	public function adicionarAtendimentoTorre(Request $request) {
		$atendimento = Atendimento::create([
			'sitcodigo' => $request->codigoSite,
			'descricao' => $request->descricaoAtendimento,
			'tipo' => $request->tipoAtendimento,
			'dataInicio' => $request->dataInicio,
			'dataFim' => $request->dataFim
		]);

		$this->createLog('-', 'success', 'Atendimento '.$atendimento->codigo.' criado com sucesso: '.Site::find($atendimento->sitcodigo)->nomemms.' - '.$atendimento->tipo);

		return;
	}

	public function adicionarPendenciaTorre(Request $request) {
		$pendencia = Pendencia::create([
			'sitcodigo' => $request->codigoSite,
			'descricao' => $request->descricaoPendencia,
			'gravidade' => $request->gravidadePendencia,
			'status' => 'Pendente'
		]);

		$this->createLog('-', 'success', 'Pendencia '.$pendencia->codigo.' criada com sucesso: '.Site::find($pendencia->sitcodigo)->nomemms);

		return;
	}

	public function mostrarAtendimentosTorre($sitcodigo) {
		return response()->json(Atendimento::where('sitcodigo',$sitcodigo)->get(['codigo', 'descricao'])->all());
	}

	public function mostrarPendenciasTorre($sitcodigo) {
		return response()->json(Pendencia::where('sitcodigo',$sitcodigo)->get(['codigo', 'descricao', 'gravidade'])->all());
	}

	public function update(Request $request, $sitcodigo) {
		Site::find($sitcodigo)->update([
			'nomemstk' => $request['nomeMstk'],
			'latsite' => $request['latsite'],
			'lngsite' => $request['lngsite']
			]);
		return $this->mostrarSite($sitcodigo);
	}
}