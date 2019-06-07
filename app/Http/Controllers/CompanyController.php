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
        return view('companyregister');
    }

    // Registrar empresa
    public function registerCompany(CompanyStoreRequest $request)
    {
        if ($request->isMethod('POST')) {
            $validated = $request->validated();

            $empresa = new Company([
                'nome' => $validated['nome'],
                'cnpj' => $validated['cnpj'],
                'telefone' => $validated['phone'],
                'email' => $validated['email']
            ]);
            $empresa->save();

            Session::flash('message', 'Empresa cadastrada com sucesso!');
            return redirect()->back();
        } else {
            return view('companyregister');
        }
    }

    // Mostrar lista de empresas
    public function showCompanyList(Request $request) {
        if (Auth::user()->hasRole('Admin')) {

            $search = $request['search'] ? $request['search'] : '';

            $empresas = Company::where('nome','like','%'.$search.'%')->get();

            return view('companylist', compact('empresas'));
        }
        else {
            return view('errors.403');
        }
    }

    // Deletar empresa
    public function deleteCompany($company_id) {
        Company::find($company_id)->delete();
    }

}