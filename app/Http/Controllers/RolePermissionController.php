<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function insertRole(Request $request) {

        Role::create([
            'name' => $request->role
        ])->save();

        return redirect()->back();
    }

    public function insertPermission(Request $request) {

        Permission::create([
            'name' => $request->permission
        ])->save();

        return redirect()->back();
    }

    public function deleteRole($role_id) {
        DB::table('roles')->where('id', $role_id)->delete();
    }

    public function deletePerm($perm_id) {
        DB::table('permissions')->where('id', $perm_id)->delete();
    }
}
