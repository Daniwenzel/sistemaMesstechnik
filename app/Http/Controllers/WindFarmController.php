<?php

namespace Messtechnik\Http\Controllers;

use Messtechnik\Models\Company;
use Messtechnik\Models\Tower;
use Messtechnik\Models\WindFarm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WindFarmController extends Controller
{

    /**
     * Mostra a view com todos os parques eólicos cadastrados para uma conta admin, ou
     * mostra apenas os parques eólicos do próprio cliente caso a conta seja de um cliente
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {
        $filtro = $request['search'];
        $empresas = Company::all();
        $empresa_selecionada = $request['empresa'] ? $request['empresa'] : null;

        if(Auth::user()->hasRole('Admin')) {
            $windfarms = WindFarm::where('nome', 'ilike', '%'.$filtro.'%')
                ->orderBy('nome', 'asc');
            $windfarms = is_numeric($empresa_selecionada) ?
                $windfarms->where('empresa_id', $empresa_selecionada)->paginate(5)
                : $windfarms->paginate(5);
        }
        else {
            $windfarms = WindFarm::where('empresa_id', Auth::user()->empresa_id)
                ->where('nome', 'ilike', '%'.$filtro.'%')
                ->orderBy('nome', 'asc')
                ->paginate(5);
        }
        return view('windfarms', compact(['windfarms', 'filtro', 'empresas', 'empresa_selecionada']));
    }

    /**
     * Mostra a view para cadastrar um parque eólico
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {
        $empresas = Company::all();

        return view('windfarmregister', compact('empresas'));
    }

    /**
     * Cadastra um parque eólico
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $empresa = Company::where('nome', $request['empresa'])->first();

        $parque = new WindFarm([
            'nome' => $request['nome'],
            'cod_EPE' => $request['cod_EPE'],
            'empresa_id' => $empresa->id
        ]);
        $parque->save();

        Session::flash('message', 'Parque cadastrado com sucesso!');
        return redirect()->back();
    }

    /**
     * Mostra as torres do parque eólico selecionado
     *
     * @param Request $request
     * @param $farm_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $farm_id)
    {
        // Se o usuário tentar entrar nesse escopo através do URL digitando um id que não seja o do próprio parque
        // eólico e ele não seja um ADMIN, redireciona para a view 'sem permissão'
        if ((WindFarm::find($farm_id)->empresa_id !== Auth::user()->empresa_id) && (!(Auth::user()->hasRole('Admin')))) {
            return view('errors.500');
        } else {
            $torres = Tower::where('parque_id', $farm_id)
                ->where('cod_cliente', 'like', '%'.$request['search'].'%')
                ->orderBy('cod_MSTK', 'asc')
                ->paginate(3);
            // Caso a busca não encontre resultados ou o parque selecionado não possui torres cadastradas
            if ($torres->isEmpty()) {
                Session::flash('message', 'Não foi possível encontrar torres com este nome, ou o parque ainda não possui torres cadastradas.');
            }
            return view('towers', compact(['torres', 'farm_id']));
        }
    }
}
