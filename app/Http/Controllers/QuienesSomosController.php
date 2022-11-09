<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

class QuienesSomosController extends Controller
{
    public function index(Request $request)
    {
        if ($request)
        {   
            return view('vistas.vquienessomos.index'); 
        }
    }
}
