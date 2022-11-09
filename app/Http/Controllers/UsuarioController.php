<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\User;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\UsuarioFormRequest;
use sisVentasWeb\Http\Requests\UsuarioEditFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use Illuminate\Support\Facades\Input;

class UsuarioController extends Controller
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
			$usuarios=DB::table('users')
            ->where('name','LIKE','%'.$query.'%')
            ->where('idempresa','=',$idempresa)
            ->where('tipo_usuario','!=','Doctor')
            ->where('email','!=','Eliminado')
            ->orderBy('principal','desc')
			->orderBy('name','asc')
			->paginate(20);
			return view('seguridad.usuario.index',["usuarios"=>$usuarios,"searchText"=>$query]);
		}
	}

	public function create()
	{
		return view("seguridad.usuario.create");
	}
    
    public function store(UsuarioFormRequest $request)
    {
        $idempresa = Auth::user()->idempresa;

        $usuario=new User;
    	$usuario->name=$request->get('name');
    	$usuario->email=$request->get('email');
    	$usuario->password=bcrypt($request->get('password'));
    	$usuario->tipo_usuario=$request->get('tipo_usuario');
    	$usuario->telefono=$request->get('telefono');
    	$usuario->direccion=$request->get('direccion');
        $fecha_nacimiento = date("Y-m-d", strtotime($request->get('fecha_nacimiento')));
        $usuario->fecha_nacimiento=trim($fecha_nacimiento);
    	$usuario->contacto_emergencia=$request->get('contacto_emergencia');
    	$usuario->telefono_emergencia=$request->get('telefono_emergencia');
        $usuario->empresa=Auth::user()->empresa;
        $usuario->idempresa=Auth::user()->idempresa;
        $usuario->zona_horaria=Auth::user()->zona_horaria;
        $usuario->moneda=Auth::user()->moneda;
        $usuario->max_descuento=$request->get('max_descuento');
        $usuario->logo=Auth::user()->logo;
        $usuario->principal='NO';
        if (input::hasfile('foto')){
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);

            $file=Input::file('foto');
            $file->move(public_path().'/imagenes/usuarios/',$generar_codigo_imagen.$file->getClientOriginalName());
            $usuario->foto=$generar_codigo_imagen.$file->getClientOriginalName();
        }
    	$usuario->save();


        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Seguridad";
        $bitacora->descripcion="Se creo un usuario , Nombre: ".$usuario->name.", Email: ".$usuario->email.", Dirección: ".$usuario->direccion.", Teléfono: ".$usuario->telefono.", Tipo: ".$usuario->tipo_usuario.", Fecha Nacimiento: ".$fecha_nacimiento.", Contacto Emergencia: ".$usuario->contacto_emergencia.", Telefono Emergencia: ".$usuario->telefono_emergencia.", Descuento maximo: ".$usuario->max_descuento;
        $bitacora->save();

        $request->session()->flash('alert-success', 'El usuario Nombre: '.$request->get('name').' ,email: '.$request->get('email').' Se creo correctamente.');

    	return Redirect::to('seguridad/usuario');
    }

    public function edit($id)
    {
    	return view("seguridad.usuario.edit",["usuario"=>User::findOrFail($id)]);
    }

    public function update(UsuarioEditFormRequest $request,$id)
    {
        //buscar si otro usuario usa el mismo email 
        $emailrepetido=DB::table('users')
        ->where('id','!=',$id)
        ->where('email','=',$request->get('email'))
        ->count();

        if($emailrepetido > 0)
        {
            $request->session()->flash('alert-danger', 'El email '.$request->get('email').' ya esta siendo usado por otro usuario, por favor intente con otro email.');
            return view("seguridad.usuario.edit",["usuario"=>User::findOrFail($id)]);
        }else
        {
            $usuario=User::findOrFail($id);
            $usuario->name=$request->get('name');
            $usuario->email=$request->get('email');
            $usuario->telefono=$request->get('telefono');
            $usuario->direccion=$request->get('direccion');
            $fecha_nacimiento = date("Y-m-d", strtotime($request->get('fecha_nacimiento')));
            $usuario->fecha_nacimiento=trim($fecha_nacimiento);
            $usuario->contacto_emergencia=$request->get('contacto_emergencia');
            $usuario->telefono_emergencia=$request->get('telefono_emergencia');
            $usuario->tipo_usuario=$request->get('tipo_usuario');
            $usuario->max_descuento=$request->get('max_descuento');
            if (input::hasfile('foto')){
                $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);
    
                $file=Input::file('foto');
                $file->move(public_path().'/imagenes/usuarios/',$generar_codigo_imagen.$file->getClientOriginalName());
                $usuario->foto=$generar_codigo_imagen.$file->getClientOriginalName();
            }
            
            $usuario->update();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);

            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Seguridad";
            $bitacora->descripcion="Se edito un usuario: Nombre: ".$usuario->name.", Email: ".$usuario->email.", Dirección: ".$usuario->direccion.", Teléfono: ".$usuario->telefono.", Tipo: ".$usuario->tipo_usuario.", Fecha Nacimiento: ".$fecha_nacimiento.", Contacto Emergencia: ".$usuario->contacto_emergencia.", Telefono Emergencia: ".$usuario->telefono_emergencia.", Descuento maximo: ".$usuario->max_descuento;
            $bitacora->save();

            $request->session()->flash('alert-success', 'El usuario '.$usuario->name.' se edito correctamente.');

            return Redirect::to('seguridad/usuario');
        }


    }

    public function show($id)
    {
        return view("seguridad.usuario.show",["usuario"=>User::findOrFail($id)]);
    }

    public function destroy($id)
    {
        $usu=DB::table('users')->where('id','=',$id)->first();

        $usuario=User::findOrFail($id);
        $usuario->email="Eliminado";
        $usuario->update();

            $zonahoraria = Auth::user()->zona_horaria;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Seguridad";
            $bitacora->descripcion="Se elimino un Usuario, Nombre: ".$usu->name;
            $bitacora->save();

        return Redirect::to('seguridad/usuario');
    	
    }
}
