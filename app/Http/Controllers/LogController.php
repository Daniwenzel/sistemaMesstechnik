<?php

namespace Messtechnik\Http\Controllers;

use Illuminate\Http\Request;
use Messtechnik\Models\ArquivoLog;

class LogController extends Controller
{
    public function showFileLog() {
        $logs = ArquivoLog::orderBy('id', 'desc')
            ->paginate(40);

        return view('filelog', compact('logs'));
    }
}