<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\User;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\DoctorFormRequest;
use sisVentasWeb\Http\Requests\DoctorEditFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use Illuminate\Support\Facades\Input;

class DoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index (Request $request)
	{
		if ($request)
		{
            $idempresa = Auth::user()->idempresa;
			$query=trim($request->get('searchText'));
			$doctores=DB::table('users')
            ->where('name','LIKE','%'.$query.'%')
            ->where('idempresa','=',$idempresa)
            ->where('tipo_usuario','=','Doctor')
            ->where('email','!=','Eliminado')
            ->orderBy('principal','desc')
			->orderBy('name','asc')
			->paginate(20);

            $doctor = DB::table('users')
            ->where('name','=',$query)
            ->first();

            $doctoresFiltro = DB::table('users')
            ->where('tipo_usuario','=',"Doctor")
            ->get();

			return view('seguridad.doctor.index',["doctores"=>$doctores,"searchText"=>$query,"doctor"=>$doctor,"doctoresFiltro"=>$doctoresFiltro]);
		}
	}

	public function create()
	{
		return view("seguridad.doctor.create");
	}
    
    public function store(DoctorFormRequest $request)
    {
        $idempresa = Auth::user()->idempresa;

        $doctor=new User;
    	$doctor->name=$request->get('name');
    	$doctor->email=$request->get('email');
    	$doctor->password=bcrypt($request->get('password'));
    	$doctor->tipo_usuario=$request->get('tipo_usuario');
        $doctor->especialidad=$request->get('especialidad');
        $doctor->no_colegiado=$request->get('no_colegiado');
    	$doctor->telefono=$request->get('telefono');
    	$doctor->direccion=$request->get('direccion');
        $fecha_nacimiento = date("Y-m-d", strtotime($request->get('fecha_nacimiento')));
        $doctor->fecha_nacimiento=trim($fecha_nacimiento);
    	$doctor->contacto_emergencia=$request->get('contacto_emergencia');
    	$doctor->telefono_emergencia=$request->get('telefono_emergencia');
        $doctor->empresa=Auth::user()->empresa;
        $doctor->idempresa=Auth::user()->idempresa;
        $doctor->zona_horaria=Auth::user()->zona_horaria;
        $doctor->moneda=Auth::user()->moneda;
        $doctor->max_descuento=$request->get('max_descuento');
        $doctor->logo=Auth::user()->logo;
        $doctor->principal='NO';
        if (input::hasfile('foto')){
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);

            $file=Input::file('foto');
            $file->move(public_path().'/imagenes/usuarios/',$generar_codigo_imagen.$file->getClientOriginalName());
            $doctor->foto=$generar_codigo_imagen.$file->getClientOriginalName();
        }
    	$doctor->save();


        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Seguridad";
        $bitacora->descripcion="Se creo un usuario de Doctor, Nombre: ".$doctor->name.", Email: ".$doctor->email.", Dirección: ".$doctor->direccion.", Teléfono: ".$doctor->telefono.", tipo: ".$doctor->tipo_usuario.", Especialidad: ".$doctor->especialidad.", Fecha Nacimiento: ".$fecha_nacimiento.", Contacto Emergencia: ".$doctor->contacto_emergencia.", Telefono Emergencia: ".$doctor->telefono_emergencia.", Descuento maximo: ".$doctor->max_descuento;
        $bitacora->save();

        $request->session()->flash('alert-success', 'El usuario de doctor Nombre: '.$request->get('name').' ,email: '.$request->get('email').' Se creo correctamente.');

    	return Redirect::to('seguridad/doctor');
    }

    public function edit($id)
    {
    	return view("seguridad.doctor.edit",["doctor"=>User::findOrFail($id)]);
    }

    public function update(DoctorEditFormRequest $request,$id)
    {
        //buscar si otro usuario usa el mismo email 
        $emailrepetido=DB::table('users')
        ->where('id','!=',$id)
        ->where('email','=',$request->get('email'))
        ->count();

        if($emailrepetido > 0)
        {
            $request->session()->flash('alert-danger', 'El email '.$request->get('email').' ya esta siendo usado por otro usuario, por favor intente con otro email.');
            return view("seguridad.doctor.edit",["usuario"=>User::findOrFail($id)]);
        }else
        {
            $doctor=User::findOrFail($id);
            $doctor->name=$request->get('name');
            $doctor->email=$request->get('email');
            $doctor->telefono=$request->get('telefono');
            $doctor->direccion=$request->get('direccion');
            $doctor->especialidad=$request->get('especialidad');
            $doctor->no_colegiado=$request->get('no_colegiado');
            $fecha_nacimiento = date("Y-m-d", strtotime($request->get('fecha_nacimiento')));
            $doctor->fecha_nacimiento=trim($fecha_nacimiento);
            $doctor->contacto_emergencia=$request->get('contacto_emergencia');
            $doctor->telefono_emergencia=$request->get('telefono_emergencia');
            $doctor->max_descuento=$request->get('max_descuento');
            if (input::hasfile('foto')){
                $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);
    
                $file=Input::file('foto');
                $file->move(public_path().'/imagenes/usuarios/',$generar_codigo_imagen.$file->getClientOriginalName());
                $doctor->foto=$generar_codigo_imagen.$file->getClientOriginalName();
            }
            $doctor->update();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);

            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Seguridad";
            $bitacora->descripcion="Se edito un usuario doctor Nombre: ".$doctor->name.", Email: ".$doctor->email.", Dirección: ".$doctor->direccion.", Teléfono: ".$doctor->telefono.", tipo: ".$doctor->tipo_usuario.", Especialidad: ".$doctor->especialidad.", Fecha Nacimiento: ".$fecha_nacimiento.", Contacto Emergencia: ".$doctor->contacto_emergencia.", Telefono Emergencia: ".$doctor->telefono_emergencia.", Descuento maximo: ".$doctor->max_descuento;
            $bitacora->save();

            $request->session()->flash('alert-success', 'El usuario de doctor '.$doctor->name.' se edito correctamente.');

            return Redirect::to('seguridad/doctor');
        }


    }

    public function show($id)
    {
        $zona_horaria = Auth::user()->zona_horaria;
        $hoy = Carbon::now($zona_horaria);
        $hoy = date("Y-m-d", strtotime ($hoy."- 1 days"));

        $dias=DB::table('dias')
            ->where('iddoctor','=',$id)
            ->where('fecha','>=',$hoy)
            ->orderBy('fecha','asc')
			->paginate(10);

        return view("seguridad.doctor.show",["doctor"=>User::findOrFail($id),"dias"=>$dias]);
    }

    public function destroy($id)
    {
        $usu=DB::table('users')->where('id','=',$id)->first();

        $doctor=User::findOrFail($id);
        $doctor->email="Eliminado";
        $doctor->update();

            $zonahoraria = Auth::user()->zona_horaria;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Seguridad";
            $bitacora->descripcion="Se elimino un doctor, Nombre: ".$usu->name;
            $bitacora->save();

        return Redirect::to('seguridad/doctor');
    	
    }
}
