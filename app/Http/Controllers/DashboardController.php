<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Mostrar tela inicial (dashboard) após o login efetuado
    public function showDashboard()
    {
        return view('dashboard');
    }
}
