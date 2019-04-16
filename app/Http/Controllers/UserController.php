<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\Company;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function registerUser(UserStoreRequest $request) {

        $validated = $request->validated();

        $empresa = Company::where('nome', $request['empresa'])->first();

        $user = new User([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'empresa_id' => $empresa->id
        ]);
        //$user->assignRole('Engenheiro');
        $user->save();

        Session::flash('message', 'Usuário criado com sucesso!');
        return redirect()->back();
    }

    public function showRegisterUser() {
        if (Auth::user()->hasPermissionTo('teste')) {
            $empresas = Company::all('id', 'nome');

            return view('auth/register')->with('empresas', $empresas);
        }
        else {
            return view('errors/unallowed');
        }
    }

    public function showUserList() {
        if (Auth::user()->hasPermissionTo('teste')) {
            $empresas = Company::with('users')->get();
        }
        else {
            $empresas = Company::with('users')->where('id', Auth::user()->empresa_id)->get();
        }
        return view('userlist', compact('empresas'));
    }

    public function showRolesPermissions() {
        if (Auth::user()->hasRole('Engenheiro')) {
            $permissoes = Permission::all();
            $cargos = Role::all();

            return view('rolepermission', compact(['permissoes', 'cargos']));
        }
    }

    public function deleteUser($user_id) {
        User::find($user_id)->delete();

    }

    public static function showUserConfig($user_id) {
        $user = User::find($user_id);
        $allpermissions = Permission::all();
        $permission = $user->getAllPermissions();
        $allroles = Role::all();
        $role =$user->getRoleNames();

        return view('userconfig', compact(['user', 'permission', 'role', 'allpermissions', 'allroles']));
    }

}
