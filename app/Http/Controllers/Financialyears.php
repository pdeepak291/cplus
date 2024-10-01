<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Financialyears extends Controller
{
    public function index(){
        return view('financialyear');
    }
}
