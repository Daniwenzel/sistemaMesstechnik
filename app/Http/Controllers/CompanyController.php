<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyStoreRequest;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
{
    public function showRegisterCompany()
    {
        if (Auth::user()->hasPermissionTo('teste')) {
            return view('register-company');
        } else {
            return view('errors/unallowed');
        }
    }

    public function registerCompany(CompanyStoreRequest $request) {
        $validated = $request->validated();

        $empresa = new Company([
            'nome' => $request['name'],
            'cnpj' => $request['cnpj'],
            'telefone' => $request['phone'],
            'email' => $request['email']
        ]);
        $empresa->save();

        Session::flash('message', 'Empresa cadastrada com sucesso!');
        return redirect()->back();
    }

    public function showCompanyList() {
        $empresas = Company::all();

        return view('companylist', compact('empresas'));
    }
}