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

	public function showSites(int $codigo) {
		$cliente = Cliente::find($codigo);

		$sites = Site::where('clicodigo', $codigo)->get();

		foreach ($sites as $site) {
			preg_match('#\((.*?)\)#', $site->sitename, $match);
			$site->nome = $match[1] ?? $site->sitename;
		}

		return view('company.info', compact('cliente', 'sites'));
	}
}