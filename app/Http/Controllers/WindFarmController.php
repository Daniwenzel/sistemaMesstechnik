<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\WindFarm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WindFarmController extends Controller
{
    public function showWindfarmList(Request $request) {
        $filtro= $request['search'];
        if(Auth::user()->hasRole('Admin')) {
            $windfarms = WindFarm::where('nome', 'ilike', '%'.$filtro.'%')
                ->orderBy('nome', 'asc')
                ->paginate(5);
        }
        else {
            $windfarms = WindFarm::where('empresa_id', Auth::user()->empresa_id)
                ->where('nome', 'like', '%'.$filtro.'%')
                ->orderBy('nome', 'asc')
                ->paginate(5);
        }
        return view('windfarms', compact(['windfarms', 'filtro']));
    }

    public function showRegisterWindfarm() {
        $empresas = Company::all();

        return view('windfarmregister', compact('empresas'));
    }

    public function registerWindfarm(Request $request) {
        $parque = new WindFarm();
        $empresa = Company::where('nome', $request['empresa'])->first();

        $parque->nome = $request['nome'];
        $parque->cod_EPE = $request['cod_EPE'];
        $parque->empresa_id = $empresa->id;

        Session::flash('message', 'Parque cadastrado com sucesso!');

        $parque->save();

        return redirect()->back();
    }
}
