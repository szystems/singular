<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\PacienteFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class HistorialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index (Request $request)
	{
		if ($request)
		{
			$query=trim($request->get('searchText'));
			$pacientes=DB::table('paciente')
            ->where('nombre','LIKE','%'.$query.'%')
            ->where('estado','!=','Eliminado')
            ->orwhere('nit','LIKE','%'.$query.'%')
            ->where('estado','!=','Eliminado')
            ->orwhere('telefono','LIKE','%'.$query.'%')
            ->where('estado','!=','Eliminado')
            ->orwhere('dpi','LIKE','%'.$query.'%')
            ->where('estado','!=','Eliminado')
            ->orderBy('nombre','asc')
			->paginate(20);
			return view('pacientes.historiales.index',["pacientes"=>$pacientes,"searchText"=>$query]);
		}
	}

    public function show($id)
    {
        return view("pacientes.historiales.show",["paciente"=>Paciente::findOrFail($id)]);
    }

    
}
