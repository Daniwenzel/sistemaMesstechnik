<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\Company;
use App\User;
use DebugBar\DebugBar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use UxWeb\SweetAlert\SweetAlert;

class UserController extends Controller
{

    // Registrar usuário
    public function registerUser(UserStoreRequest $request) {
        $empresa = Company::where('nome', $request['empresa'])->first();

        $user = new User($request->validated());
        $user->password = Hash::make($user->password);
        $user->empresa_id = $empresa->id;

        //$user->assignRole('Engenheiro');
        $user->save();

        alert()->message('Message', 'Optional Title');

        Session::flash('message', 'Usuário cadastrado com sucesso!');
        return redirect()->back();
    }

    // Mostrar tela de cadastro de usuário
    public function showRegisterUser() {
        if (Auth::user()->hasRole('Admin')) {
            $empresas = Company::all('id', 'nome');
        }
        else {
            $empresas = Company::all('id', 'nome')
                ->where('id', Auth::user()->empresa_id);
        }
        return view('auth/register', compact('empresas'));
    }

    // Mostrar tela de usuários cadastrados
    public function showUserList(Request $request) {
        $search = $request['search'];

        if (Auth::user()->hasRole('Admin')) {
            $usuarios = User::where('name','like','%'.$search.'%')
                ->with('empresa')
                ->orWhereHas('empresa', function($query) use ($search) {
                    $query->where('nome', 'like','%'.$search.'%');
                })
                ->paginate(10);
        }
        else {
            $usuarios = User::with('empresa')
                ->where('empresa_id', Auth::user()->empresa_id)
                ->where('name','like','%'.$search.'%')
                ->paginate(10);
        }

        return view('userlist', compact('usuarios'));
    }

    // Mostrar tela lista de cargos e permissoes
    public function showRolesPermissions() {
        if (Auth::user()->hasRole('Admin')) {
            $permissoes = Permission::all();
            $cargos = Role::all();

            return view('rolepermission', compact(['permissoes', 'cargos']));
        }
    }

    // Deletar usuario
    public function deleteUser($user_id) {
        User::find($user_id)->delete();
    }

    // Mostrar tela e configuracoes de usuario
    public static function showUserConfig($user_id) {
        $user = User::find($user_id);
        $allpermissions = Permission::all();
        $permission = $user->getAllPermissions();
        $allroles = Role::all();
        $role = $user->getRoleNames();

        return view('userconfig', compact(['user', 'permission', 'role', 'allpermissions', 'allroles']));
    }

    public function editUserConfig($user_id, Request $request) {
        $user = User::find($user_id);

        $user->name = $request->input('name');
        $user->last_name = $request->input('last_name');
        $user->genero = $request->input('genero');
        $user->aniversario = $request->input('aniversario');

        if($request->hasFile('avatar')) {
            if ($request->file('avatar')->isValid()) {
                $user
                    ->addMedia($request->file('avatar'))
                    ->toMediaCollection('profile');
            }
        }

        $user->save();
        return view('userconfig', compact('user'));
    }

    public function editUserAvatar($user_id, Request $request) {
        $user = User::find($user_id);

        if($request->hasFile('avatar')) {
            if ($request->file('avatar')->isValid()) {
                $user
                    ->addMedia($request->file('avatar'))
                    ->toMediaCollection('profile');
            }
        }

        return view('userconfig', compact('user'));
    }

}
