<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function showRegisterUser() {
        $empresas = Empresa::all('id', 'nome');

        return view('auth/register')->with('empresas', $empresas);
    }

    public function registerUser(Request $request) {
        $nome = $request->input('name');
        $email = $request->input('email');
        $senha = Hash::make($request->input('password'));
        $empresa_id = Empresa::where('nome', $request->input('empresa'))->first();


        $user = new User([
            'name' => $nome,
            'email' => $email,
            'password' => $senha,
            'empresa_id' => $empresa_id->id
        ]);
        $user->assignRole('Engenheiro');
        $user->save();
    }

}
