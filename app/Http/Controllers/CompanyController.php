<?php

namespace Messtechnik\Http\Controllers;

use Messtechnik\Http\Requests\CompanyStoreRequest;
use Messtechnik\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
{

    /**
     * Mostra a view da lista de empresas cadastradas
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {
        $empresas = Company::where('RAZAOSOCIAL','like','%'.$request['search'].'%')->get();

        return view('company.list', compact('empresas'));
    }

    /**
     * Mostra a view para cadastrar uma empresa
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {
        return view('company.register');
    }

    /**
     * Cadastra uma nova empresa
     *
     * @param CompanyStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CompanyStoreRequest $request) {
        Company::create($request->validated())->save();

        Session::flash('message', 'Empresa cadastrada com sucesso!');
        return redirect()->back();
    }

    /**
     * Deleta uma empresa
     *
     * @param $company_id
     */
    public function delete($company_id) {
        $empresa = Company::find($company_id);
        $empresa->delete();
    }
}
