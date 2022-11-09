<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Dias;
use sisVentasWeb\User;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;

use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\DiasFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use Illuminate\Support\Facades\Input;

class DiasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(DiasFormRequest $request)
    {
        $iddoctor = $request->get('iddoctor');
        $fecha = date("Y-m-d", strtotime($request->get('fecha')));
        $apuntes = $request->get('apuntes');

        $doctor=DB::table('users')->where('id','=',$iddoctor)->first();

        $comprobarDias=DB::table('dias')
            ->where('iddoctor','=',$iddoctor)
            ->where('fecha','=',$fecha)
			->get();
        
        if($comprobarDias->count() >= 1)
        {
            $request->session()->flash('alert-danger', "Esta fecha ya esta bloqueada, Doctor: ".$doctor->name.", Fecha: ".$request->get('fecha'));
            
            //return Redirect::to('seguridad/doctor');

            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $diasfecha = date("Y-m-d", strtotime ($hoy."- 1 days"));

            $dias=DB::table('dias')
            ->where('iddoctor','=',$iddoctor)
            ->where('fecha','>=',$diasfecha)
            ->orderBy('fecha','asc')
			->get();
            
            return view("seguridad.doctor.show",["doctor"=>User::findOrFail($iddoctor),"dias"=>$dias]);
        }
        else
        {
            $dia=new Dias;
            $dia->iddoctor = $request->get('iddoctor');
            $dia->fecha = $fecha;
            $dia->apuntes=$apuntes;
            $dia->save();

            

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Citas";
            $bitacora->descripcion="Se a Bloqueado una fecha para citas con el Doctor, Nombre: ".$doctor->name.", Fecha: ".$request->get('fecha').", Apuntes: ".$dia->apuntes;
            $bitacora->save();

            $request->session()->flash('alert-success', "Se a Bloqueado una fecha para citas con el Doctor, Nombre: ".$doctor->name.", Fecha: ".$request->get('fecha'));

            //return Redirect::to('seguridad/doctor');
            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $diasfecha = date("Y-m-d", strtotime ($hoy."- 1 days"));

            $dias=DB::table('dias')
            ->where('iddoctor','=',$iddoctor)
            ->where('fecha','>=',$diasfecha)
            ->orderBy('fecha','asc')
			->get();

            return view("seguridad.doctor.show",["doctor"=>User::findOrFail($iddoctor),"dias"=>$dias]);
        }
    }

    public function destroy($id)
    {
        $dia=DB::table('dias')->where('iddias','=',$id)->first();
        $fecha = date("d-m-Y", strtotime($dia->fecha));
        $Desbloquear=Dias::where('iddias',$id)->delete();

            $zonahoraria = Auth::user()->zona_horaria;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Citas";
            $bitacora->descripcion="Se desbloqueo la fecha: ".$fecha;
            $bitacora->save();

            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $diasfecha = date("Y-m-d", strtotime ($hoy."- 1 days"));

            $dias=DB::table('dias')
                ->where('iddoctor','=',$id)
                ->where('fecha','>=',$diasfecha)
                ->orderBy('fecha','asc')
                ->get();
            
            return Redirect::to('seguridad/doctor');
    	
    }
}
