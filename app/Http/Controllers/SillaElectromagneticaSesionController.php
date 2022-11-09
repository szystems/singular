<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\SillaElectromagnetica;
use sisVentasWeb\SillaElectromagneticaSesion;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\SillaElectromagneticaSesionFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class SillaElectromagneticaSesionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        $idSillaCiclo = $request->get('idsillae_ciclo');
        $idpaciente = $request->get('idpaciente');

        $sillaCiclo=DB::table('sillae_ciclo as s')
        ->join('paciente as p','s.idpaciente','=','p.idpaciente')
        ->join('users as d','s.iddoctor','=','d.id')
        ->join('users as u','s.idusuario','=','u.id')
        ->select('s.idsillae_ciclo','s.fecha','s.ciclo_numero','s.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','s.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','s.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('s.idsillae_ciclo','=',$idSillaCiclo) 
        ->first();

        

        $historia = DB::table('historia')
        ->where('idpaciente','=',$sillaCiclo->idpaciente)
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$sillaCiclo->idpaciente)
        ->first(); 

    	return view("pacientes.historiales.sillas.sesiones.create",["paciente"=>$paciente,"sillaCiclo"=>$sillaCiclo, "historia"=>$historia]);
    }

    public function store (SillaElectromagneticaSesionFormRequest $request)
    {
    	try
    	{ 
            
            $fechaSesion=trim($request->get('fecha'));
            $fecha = date("Y-m-d", strtotime($fechaSesion));

            $idpaciente=$request->get('idpaciente');
            $idSillaCiclo=$request->get('idsillae_ciclo');

            $sillaCiclo=DB::table('sillae_ciclo as s')
            ->join('paciente as p','s.idpaciente','=','p.idpaciente')
            ->join('users as d','s.iddoctor','=','d.id')
            ->join('users as u','s.idusuario','=','u.id')
            ->select('s.idsillae_ciclo','s.fecha','s.ciclo_numero','s.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','s.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','s.idusuario','u.name as Usuario','u.tipo_usuario')
            ->where('s.idsillae_ciclo','=',$idSillaCiclo) 
            ->first();

            //numero_sesion
            $ultimaSesion = DB::table('sillae_ciclo_sesion')
            ->where('idsillae_ciclo','=',$idSillaCiclo)
            ->max('numero_sesion');
            if(isset($ultimaSesion))
            {
                $numero_sesion = $ultimaSesion + 1;
            }else
            {
                $numero_sesion = 1;
            }

    		DB::beginTransaction();

            $sesion=new SillaElectromagneticaSesion;
            $sesion->idsillae_ciclo=$request->get('idsillae_ciclo');
            $sesion->numero_sesion=$numero_sesion;
            $sesion->fecha=$fecha;
            $sesion->tesla=$request->get('tesla');
            $sesion->minutos=$request->get('minutos');
            $sesion->observaciones=$request->get('observaciones');
    		$sesion->save();
            
            $cli=DB::table('paciente')->where('idpaciente','=',$idpaciente)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se creo un nueva sesion de silla electromagnetica para el paciente:".$cli->nombre.", Fecha: ".$fechaSesion;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$idpaciente)
        ->first();

        $sesiones=DB::table('sillae_ciclo_sesion')
        ->where('idsillae_ciclo','=',$sillaCiclo->idsillae_ciclo)
        ->get();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$idpaciente)
        ->first();

        $request->session()->flash('alert-success', "Se creo una sesion de silla electromagnetica del paciente: ".$cli->nombre.", Fecha: ".$fechaSesion);

        return view("pacientes.historiales.sillas.show",["sillaCiclo"=>$sillaCiclo,"paciente"=>$paciente,"sesiones"=>$sesiones,"historia"=>$historia]);
    }

    public function edit($id)
    {
        $sesion=DB::table('sillae_ciclo_sesion')
        ->where('idsillae_ciclo_sesion','=',$id)
        ->first();

        $sillaCiclo=DB::table('sillae_ciclo as s')
        ->join('paciente as p','s.idpaciente','=','p.idpaciente')
        ->join('users as d','s.iddoctor','=','d.id')
        ->join('users as u','s.idusuario','=','u.id')
        ->select('s.idsillae_ciclo','s.fecha','s.ciclo_numero','s.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','s.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','s.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('s.idsillae_ciclo','=',$sesion->idsillae_ciclo) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$sillaCiclo->idpaciente)
        ->first();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$sillaCiclo->idpaciente)
        ->first();
        
       
        return view("pacientes.historiales.sillas.sesiones.edit",["sesion"=>$sesion,"sillaCiclo"=>$sillaCiclo,"paciente"=>$paciente, "historia"=>$historia]);
    }

    public function update(SillaElectromagneticaSesionFormRequest $request,$id)
    {
        $fechaSesion=trim($request->get('fecha'));
        $fecha = date("Y-m-d", strtotime($fechaSesion));

        $idpaciente=$request->get('idpaciente');
        $idSillaCiclo=$request->get('idsillae_ciclo');
        $numeroSesion=$request->get('numero_sesion');

        $sillaCiclo=DB::table('sillae_ciclo as s')
            ->join('paciente as p','s.idpaciente','=','p.idpaciente')
            ->join('users as d','s.iddoctor','=','d.id')
            ->join('users as u','s.idusuario','=','u.id')
            ->select('s.idsillae_ciclo','s.fecha','s.ciclo_numero','s.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','s.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','s.idusuario','u.name as Usuario','u.tipo_usuario')
            ->where('s.idsillae_ciclo','=',$idSillaCiclo) 
            ->first();

        $sesion=SillaElectromagneticaSesion::findOrFail($id);
        $sesion->idsillae_ciclo=$idSillaCiclo;
        $sesion->numero_sesion=$numeroSesion;
        $sesion->tesla=$request->get('tesla');
        $sesion->minutos=$request->get('minutos');
        $sesion->observaciones=$request->get('observaciones');
    	$sesion->update();

        $cli=DB::table('paciente')->where('idpaciente','=',$sillaCiclo->idpaciente)->first();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Paciente";
        $bitacora->descripcion="Se edito una sesion de silla electromagnetica para el paciente:".$cli->nombre.", Fecha: ".$fechaSesion;
        $bitacora->save();

        $request->session()->flash('alert-success', "Se edito una sesion de silla electromagnetica del paciente: ".$cli->nombre.", Fecha: ".$fechaSesion);

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$idpaciente)
        ->first();

        $sesiones=DB::table('sillae_ciclo_sesion')
        ->where('idsillae_ciclo','=',$sillaCiclo->idsillae_ciclo)
        ->get();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$idpaciente)
        ->first();

        $request->session()->flash('alert-success', "Se edito una sesion de silla electromagnetica del paciente: ".$cli->nombre.", Fecha: ".$fechaSesion);

        return view("pacientes.historiales.sillas.show",["sillaCiclo"=>$sillaCiclo,"paciente"=>$paciente,"sesiones"=>$sesiones,"historia"=>$historia]);
    }

    public function eliminarsesion(Request $request)
    {
        $idSillaCiclo = $request->get('idsillae_ciclo');
        $idpaciente = $request->get('idpaciente');
        $idSillaSesion = $request->get('idsillae_ciclo_sesion');
        
        $eliminarsesion=SillaElectromagneticaSesion::findOrFail($idSillaSesion);
        $eliminarsesion->delete();

        $request->session()->flash('alert-success', 'Se elimino la sesion del ciclo de silla electromagnetica.');  
        
        
        $sillaCiclo=DB::table('sillae_ciclo as s')
            ->join('paciente as p','s.idpaciente','=','p.idpaciente')
            ->join('users as d','s.iddoctor','=','d.id')
            ->join('users as u','s.idusuario','=','u.id')
            ->select('s.idsillae_ciclo','s.fecha','s.ciclo_numero','s.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','s.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','s.idusuario','u.name as Usuario','u.tipo_usuario')
            ->where('s.idsillae_ciclo','=',$idSillaCiclo) 
            ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$sillaCiclo->idpaciente)
        ->first();

        $sesiones=DB::table('sillae_ciclo_sesion')
        ->where('idsillae_ciclo','=',$sillaCiclo->idsillae_ciclo)
        ->get();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$sillaCiclo->idpaciente)
        ->first();

        return view("pacientes.historiales.sillas.show",["sillaCiclo"=>$sillaCiclo,"paciente"=>$paciente,"sesiones"=>$sesiones,"historia"=>$historia]);
    }
}
