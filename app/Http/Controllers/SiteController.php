<?php

namespace Messtechnik\Http\Controllers;

use Messtechnik\Models\Client;
use Messtechnik\Models\Tower;
use Messtechnik\Models\Site;
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
        $clientes = Client::all();
        $cliente_selecionado = $request['empresa'] ? $request['empresa'] : null;

        if(Auth::user()->hasRole('Admin')) {
            $torres = Site::where('sitename', 'ilike', '%'.$filtro.'%')
                ->orderBy('sitename', 'asc');
            $torres = is_numeric($cliente_selecionado) ?
                $torres->where('clicodigo', $cliente_selecionado)->paginate(5)
                : $torres->paginate(5);
        }
        else {
            $torres = Site::where('clicodigo', Auth::user()->cliente_codigo)
                ->where('sitename', 'ilike', '%'.$filtro.'%')
                ->orderBy('sitename', 'asc')
                ->paginate(5);
        }
        return view('windfarms', compact(['torres', 'filtro', 'clientes', 'cliente_selecionado']));
    }

    /**
     * Mostra a view para cadastrar um parque eólico
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {
        $empresas = Client::all();

        return view('windfarmregister', compact('empresas'));
    }

    /**
     * Cadastra um parque eólico
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $empresa = Client::where('nome', $request['empresa'])->first();

        $parque = new Site([
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
        if ((Site::find($farm_id)->empresa_id !== Auth::user()->empresa_id) && (!(Auth::user()->hasRole('Admin')))) {
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
