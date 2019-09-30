<?php

namespace Messtechnik\Http\Controllers;


class DashboardController extends Controller
{

    /**
     * Mostra a view do painel principal
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view('dashboard');
    }
}
