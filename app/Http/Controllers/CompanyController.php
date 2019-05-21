<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyStoreRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
{

    // Mostrar tela de registro de empresa
    public function showRegisterCompany()
    {
        if (Auth::user()->hasRole('Admin')) {
            return view('companyregister');
        } else {
            return view('errors/unallowed');
        }
    }

    // Registrar empresa
    public function registerCompany(CompanyStoreRequest $request) {
        $validated = $request->validated();

        $empresa = new Company([
            'nome' => $request['nome'],
            'cnpj' => $request['cnpj'],
            'telefone' => $request['phone'],
            'email' => $request['email']
        ]);
        $empresa->save();

        Session::flash('message', 'Empresa cadastrada com sucesso!');
        return redirect()->back();
    }

    // Mostrar lista de empresas
    public function showCompanyList(Request $request) {
        $search = $request['search'];

        $empresas = Company::where('nome','like','%'.$search.'%')->get();

        return view('companylist', compact('empresas'));
    }

    // Deletar empresa
    public function deleteCompany($company_id) {
        Company::find($company_id)->delete();
    }

}