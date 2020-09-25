<?php

namespace Messtechnik\Http\Controllers;

use Illuminate\Http\Request;
use Messtechnik\Models\Cliente;
use Messtechnik\Models\Site;
use Messtechnik\Models\ImagemSite;
use Messtechnik\Models\Atendimento;
use Messtechnik\Models\Pendencia;
use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{
	// public function index(Request $request) {
	// 	$clientes = Cliente::where('razaosocial', 'like', '%'.$request->search.'%')->get();

	// 	return view('company.list', compact('clientes'));
	// }

	public function index(Request $request) {
		$torres = Auth::user()->hasRole('Admin') ? Site::all() : Site::where('clicodigo', Auth::user()->clicodigo)->get();

		return view('site.list', compact('torres'));
	}

	public function mostrarClienteSites($clicodigo) {
		$cliente = Cliente::find($clicodigo);

		$sites = $cliente->torres()->get();

		foreach ($sites as $site) {
			preg_match('#\((.*?)\)#', $site->sitename, $match);
			$site->nome = $match[1] ?? $site->sitename;
			$site->imagemPath = $site->imagem->last()->path ?? '/images/camera-off.png';
		}

		return view('company.info', compact('cliente', 'sites'));
	}

	public function showSite($sitcodigo) {
		$torre = Site::find($sitcodigo);
		if(Auth::user()->hasRole('Admin') || Auth::user()->clicodigo === $torre->clicodigo) {
			// $pendencias = $torre->pendencias;
			$pendencias = Pendencia::where('sitcodigo', $torre->codigo)->orderBy('gravidade', 'desc')->orderBy('created_at', 'desc')->get();
			$atendimentos = Atendimento::where('sitcodigo', $torre->codigo)->orderBy('dataInicio', 'desc')->get();

			return view('site.index', compact('torre', 'pendencias', 'atendimentos'));
		} 
		else {
			abort(403);
		}

		// Fazer algum tipo de controle de acesso, admins podem requisitar qualquer codigo de torre, mas o cliente pode apenas as suas próprias torres
		// return view('site.index', compact('sitcodigo'));
	}

	public function adicionarArquivoTorre(Request $request) {
		switch($request->tipoArquivo) {
			case 'atendimento':
				$arquivo = $request->file('arquivoAtendimento');
				if($arquivo->extension() === 'pdf' && $arquivo->isValid()) {
					dd("atendimento pdf lul");
				}
				break;
			case 'pendencia':
				$arquivo = $request->file('arquivoPendencia');
				if(getimagesize($arquivo) && $arquivo->isValid()) {
					dd("pendencia lul");
				}
				break;
			case 'imagem':
				$arquivo = $request->file('imagemTorre');
				if(getimagesize($arquivo) && $arquivo->isValid()) {
					dd("imagem torre lul");
				}
				break;
			default:
				abort(404);
				break;
		}
		
	    // $imagem = $request->file('imagem');
	    // $imagem->storeAs('site', $imagem->getClientOriginalName(), 'public');

	    // ImagemSite::create([
	    // 	'path' => '/storage/site/'.$imagem->getClientOriginalName(),
	    // 	'sitcodigo' => $request->sitcodigo
	    // ]);

	    return;
	}

	public function adicionarAtendimentoTorre(Request $request) {
		Atendimento::create([
			'sitcodigo' => $request->codigoSite,
			'descricao' => $request->descricaoAtendimento,
			'tipo' => $request->tipoAtendimento,
			'dataInicio' => $request->dataInicio,
			'dataFim' => $request->dataFim
		]);

		return;
	}

	public function adicionarPendenciaTorre(Request $request) {
		$pendencia = Pendencia::create([
			'sitcodigo' => $request->codigoSite,
			'descricao' => $request->descricaoPendencia,
			'gravidade' => $request->gravidadePendencia,
			'status' => 'Pendente'
		]);

		return;
	}

	public function mostrarAtendimentosTorre($sitcodigo) {
		return response()->json(Atendimento::where('sitcodigo',$sitcodigo)->get(['codigo', 'descricao'])->all());
	}

	public function mostrarPendenciasTorre($sitcodigo) {
		return response()->json(Pendencia::where('sitcodigo',$sitcodigo)->get(['codigo', 'descricao', 'gravidade'])->all());
	}
}