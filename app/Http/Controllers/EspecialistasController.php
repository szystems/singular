<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

class EspecialistasController extends Controller
{
    public function index(Request $request)
    {
        if ($request)
        {   
            return view('vistas.vespecialistas.index'); 
        }
    }
}
