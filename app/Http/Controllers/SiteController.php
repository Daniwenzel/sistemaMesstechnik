<?php

namespace Messtechnik\Http\Controllers;

use Illuminate\Http\Request;
use Messtechnik\Models\Cliente;
use Messtechnik\Models\Site;
use Messtechnik\Models\ImagemSite;

class SiteController extends Controller
{
	public function index(Request $request) {
		$clientes = Cliente::where('razaosocial', 'like', '%'.$request->search.'%')->get();

		return view('company.list', compact('clientes'));
	}

	public function showClientSites($clicodigo) {
		$cliente = Cliente::find($clicodigo);

		$sites = Site::where('clicodigo', $clicodigo)->get();

		foreach ($sites as $site) {
			preg_match('#\((.*?)\)#', $site->sitename, $match);
			$site->nome = $match[1] ?? $site->sitename;
			$site->imagemPath = $site->imagem->last()->path ?? '/images/camera-off.png';
		}

		return view('company.info', compact('cliente', 'sites'));
	}

	public function showSite($sitcodigo) {
		return view('tower.index', compact('sitcodigo'));
	}

	public function salvarImagemTorre($sitcodigo, Request $request) {
	    $imagem = $request->file('imagem');
	    $imagem->storeAs('site', $imagem->getClientOriginalName(), 'public');

	    ImagemSite::create([
	    	'path' => '/storage/site/'.$imagem->getClientOriginalName(),
	    	'sitcodigo' => $sitcodigo
	    ]);

	    return;
	}
}