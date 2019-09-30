<?php

namespace Messtechnik\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    /**
     * Mostra a view das permissoes e cargos
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        if (Auth::user()->hasRole('Admin')) {
            $permissoes = Permission::all();
            $cargos = Role::all();

            return view('rolepermission', compact(['permissoes', 'cargos']));
        }
    }

    /**
     * Registra um cargo
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function roleStore(Request $request) {
        Role::create(['name' => $request->role])->save();

        return redirect()->back();
    }

    /**
     * Registra uma permissão
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function permissionStore(Request $request) {
        Permission::create(['name' => $request->permission])->save();

        return redirect()->back();
    }

    /**
     * Deleta um cargo
     *
     * @param $role_id
     */
    public function roleDelete($role_id) {
        DB::table('roles')->where('id', $role_id)->delete();
    }

    /**
     * Deleta uma permissão
     *
     * @param $perm_id
     */
    public function permissionDelete($perm_id) {
        DB::table('permissions')->where('id', $perm_id)->delete();
    }
}
