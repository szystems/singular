<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

class ServiciosController extends Controller
{
    public function index(Request $request)
    {
        if ($request)
        {   
            return view('vistas.vservicios.index'); 
        }
    }
}
