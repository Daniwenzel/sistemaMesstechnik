<?php

namespace Messtechnik\Http\Controllers;

use Illuminate\Http\Request;
use Messtechnik\Models\Cliente;
use Messtechnik\Models\Site;

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
		}

		return view('company.info', compact('cliente', 'sites'));
	}

	public function showSite($sitcodigo) {
		return view('tower.index', compact('sitcodigo'));
	}

	public function salvarImagemTorre($sitcodigo, Request $request) {
		$nomeTorreEstacao = Site::find($sitcodigo)->estacao.".png";
	    $request->file('imagem')->storeAs('site', $nomeTorreEstacao, 'public');

	    return;
	}
}