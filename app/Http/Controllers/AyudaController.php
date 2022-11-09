<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

class AyudaController extends Controller
{
    public function index(Request $request)
    {
        return view('ayuda.index');
    }
}
