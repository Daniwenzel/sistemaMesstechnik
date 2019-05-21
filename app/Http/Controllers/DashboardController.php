<?php

namespace App\Http\Controllers;


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
