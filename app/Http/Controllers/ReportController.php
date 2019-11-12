<?php

namespace Messtechnik\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index() {


        return view('reportlist');
    }
}
