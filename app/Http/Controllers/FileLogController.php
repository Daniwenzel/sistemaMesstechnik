<?php

namespace App\Http\Controllers;

use App\Models\ArquivoLog;
use Illuminate\Http\Request;

class FileLogController extends Controller
{
    public function showFileLog() {
        $logs = ArquivoLog::orderBy('id', 'desc')
            ->paginate(40);

        return view('filelog', compact('logs'));
    }
}
