<?php

namespace Messtechnik\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class FileController extends Controller
{
    public function index() {
        return view('files/index');
    }

    public function upload() {
        $input = Input::all();
        $directory = storage_path('dados');
        $filename = $input['file']->getClientOriginalName();

        $input['file']->storeAs('dados', $filename, 'public');

        
    }
}
