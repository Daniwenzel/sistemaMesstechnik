<?php

namespace Messtechnik\Http\Controllers;

use Illuminate\Http\Request;
use Messtechnik\Models\Log;

class LogController extends Controller
{
    public function showLog() {
    	$logs = Log::orderBy('created_at','DESC')->get();

    	return view('log', compact('logs'));
    }
}
