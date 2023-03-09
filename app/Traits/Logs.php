<?php

namespace Messtechnik\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Messtechnik\Models\Log;

trait Logs
{
    private function createLog($diretorio, $status, $mensagem) {
        Log::create([
            'usuario' => Auth::user()->name,
            'diretorio' => $diretorio,
            'status' => $status,
            'mensagem' => $mensagem
        ])->save();
    }
}
