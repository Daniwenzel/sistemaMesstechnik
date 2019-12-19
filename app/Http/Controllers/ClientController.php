<?php

namespace Messtechnik\Http\Controllers;

use Messtechnik\Http\Requests\CompanyStoreRequest;
use Messtechnik\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/*
    Todos os métodos que realizam a manutenção da informação das empresas estão protegidos
    contra o acesso inesperado de um usuário comum do sistema através da middleware 'admin' na
    definição das rotas
*/

class ClientController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Mostra a view da lista de empresas cadastradas
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {
        $clientes = Client::where('razaosocial','ilike','%'.$request['search'].'%')->get();

        return view('companylist', compact('clientes'));
    }

    /**
     * Mostra a view para cadastrar uma empresa
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {
        return view('companyregister');
    }

    /**
     * Cadastra uma nova empresa
     *
     * @param CompanyStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CompanyStoreRequest $request) {
        Client::create($request->validated())->save();

        Session::flash('message', 'Empresa cadastrada com sucesso!');
        return redirect()->back();
    }

    /**
     * Deleta uma empresa
     *
     * @param $company_id
     */
    public function delete($company_id) {
        $empresa = Client::find($company_id);
        $empresa->delete();
    }
}