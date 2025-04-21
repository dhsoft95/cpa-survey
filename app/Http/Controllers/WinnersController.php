<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WinnersController extends Controller
{
    public function index()
    {
        return view('winners');
    }
}
