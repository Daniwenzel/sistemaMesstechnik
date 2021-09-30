<?php

namespace Messtechnik\Http\Controllers;

use Messtechnik\Models\Atendimento;

class DashboardController extends Controller
{
    /*
        Busca pelas informações necessárias e mostra a tela inicial
    */
    public function index() {
        $atendimentos = Atendimento::where('dataInicio', '>=', now()->subDays(5)->setTime(0, 0, 0)->toDateString())->get();


        return view('dashboard', compact('atendimentos'));
    }
}
