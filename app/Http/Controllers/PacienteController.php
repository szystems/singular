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


class PacienteController extends Controller
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
			return view('pacientes.paciente.index',["pacientes"=>$pacientes,"searchText"=>$query]);
		}
	}

	public function create()
	{
		return view("pacientes.paciente.create");
	}
    
    public function store(PacienteFormRequest $request)
    {

        $paciente=new Paciente;
    	$paciente->nombre=$request->get('nombre');
    	$paciente->sexo=$request->get('sexo');
    	$paciente->correo=$request->get('correo');
    	$paciente->telefono=$request->get('telefono');
    	$paciente->direccion=$request->get('direccion');
        $fecha_nacimiento = date("Y-m-d", strtotime($request->get('fecha_nacimiento')));
        $paciente->fecha_nacimiento=trim($fecha_nacimiento);
        $paciente->dpi=$request->get('dpi');
    	$paciente->nit=$request->get('nit');
        $paciente->foto=$request->get('foto');
        if (input::hasfile('foto')){
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);

            $file=Input::file('foto');
            $file->move(public_path().'/imagenes/pacientes/',$generar_codigo_imagen.$file->getClientOriginalName());
            $paciente->foto=$generar_codigo_imagen.$file->getClientOriginalName();
        }
        $paciente->estado='Habilitado';
    	$paciente->save();


        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Paciente";
        $bitacora->descripcion="Se creo un paciente, Nombre: ".$paciente->nombre.", Sexo: ".$paciente->sexo.", Teléfono: ".$paciente->telefono.", Email: ".$paciente->correo.", Dirección: ".$paciente->direccion.", Fecha Nacimiento: ".$paciente->fecha_nacimiento.", Nit: ".$paciente->nit.", Estado: ".$paciente->estado.", DPI: ".$paciente->dpi;
        $bitacora->save();

        $request->session()->flash('alert-success', 'Paciente Nombre: '.$request->get('nombre').' Se creo correctamente.');

    	return Redirect::to('pacientes/paciente');
    }

    public function edit($id)
    {
    	return view("pacientes.paciente.edit",["paciente"=>Paciente::findOrFail($id)]);
    }

    public function update(PacienteFormRequest $request,$id)
    {
        //buscar si otro usuario usa el mismo email 
        $emailrepetido=DB::table('paciente')
        ->where('idpaciente','!=',$id)
        ->where('correo','=',$request->get('correo'))
        ->count();

        if($emailrepetido > 0)
        {
            $request->session()->flash('alert-danger', 'El email '.$request->get('correo').' ya esta siendo usado por otro paciente, por favor intente con otro email.');
            return view("pacientes.paciente.edit",["paciente"=>Paciente::findOrFail($id)]);
        }else
        {
            $paciente=Paciente::findOrFail($id);
            $paciente->nombre=$request->get('nombre');
            $paciente->sexo=$request->get('sexo');
            $paciente->correo=$request->get('correo');
            $paciente->telefono=$request->get('telefono');
            $paciente->direccion=$request->get('direccion');
            $fecha_nacimiento = date("Y-m-d", strtotime($request->get('fecha_nacimiento')));
            $paciente->fecha_nacimiento=trim($fecha_nacimiento);
            $paciente->dpi=$request->get('dpi');
            $paciente->nit=$request->get('nit');
                if (input::hasfile('foto')){
                    $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);
        
                    $file=Input::file('foto');
                    $file->move(public_path().'/imagenes/pacientes/',$generar_codigo_imagen.$file->getClientOriginalName());
                    $paciente->foto=$generar_codigo_imagen.$file->getClientOriginalName();
                }
            $paciente->update();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);

            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se edito un paciente, Nombre: ".$paciente->nombre.", Sexo: ".$paciente->sexo.", Teléfono: ".$paciente->telefono.", Email: ".$paciente->correo.", Dirección: ".$paciente->direccion.", Fecha Nacimiento: ".$paciente->fecha_nacimiento.", Nit: ".$paciente->nit.", Estado: ".$paciente->estado.", DPI: ".$paciente->dpi;
            $bitacora->save();

            $request->session()->flash('alert-success', 'El paciente '.$paciente->nombre.' se edito correctamente.');

            return Redirect::to('pacientes/paciente');
        }

    }

    public function show($id)
    {
        return view("pacientes.paciente.show",["paciente"=>Paciente::findOrFail($id)]);
    }

    public function destroy($id)
    {
        $persona=DB::table('paciente')->where('idpaciente','=',$id)->first();

        $paciente=Paciente::findOrFail($id);
        $paciente->estado="Eliminado";
        $paciente->update();

            $zonahoraria = Auth::user()->zona_horaria;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se elimino un huesped, Nombre: ".$persona->nombre;
            $bitacora->save();

        return Redirect::to('pacientes/paciente');
    	
    }
}
