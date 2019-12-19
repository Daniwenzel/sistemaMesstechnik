<?php

namespace Messtechnik\Http\Controllers;

use Illuminate\Http\Request;
use Messtechnik\Models\Client;
use Messtechnik\Models\Site;

class ReportController extends Controller
{
    public function index() {
        $clientes = Client::all();
        $sites = Site::all();

        return view('reportlist', compact(['clientes', 'sites']));
    }
}
