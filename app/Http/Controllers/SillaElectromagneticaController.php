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
use sisVentasWeb\Http\Requests\SillaElectromagneticaFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class SillaElectromagneticaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $idpaciente=trim($request->get('searchidpaciente'));
        $sillaCiclos=DB::table('sillae_ciclo as s')
        ->join('paciente as p','s.idpaciente','=','p.idpaciente')
        ->join('users as d','s.iddoctor','=','d.id')
        ->join('users as u','s.idusuario','=','u.id')
        ->select('s.idsillae_ciclo','s.fecha','s.ciclo_numero','s.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','s.idpaciente','p.nombre as Paciente','p.foto as Imgdpaciente','s.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('s.idpaciente','=',$idpaciente) 
        ->orderby('s.fecha','desc')
        ->paginate(20);
        
        

        return view("pacientes.historiales.sillas.index",["paciente"=>Paciente::findOrFail($idpaciente),"sillaCiclos"=>$sillaCiclos]);
    }

    public function store (SillaElectromagneticaFormRequest $request)
    {
    	try
    	{ 
            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $hoy = $hoy->format('Y-m-d');
            $fecha = Carbon::now($zona_horaria);
            $fecha = $fecha->format('d-m-Y');

            $iddoctor=$request->get('iddoctor');
            $idpaciente=$request->get('idpaciente');
            $idusuario=$request->get('idusuario');
            $ciclo_numero=$request->get('ciclo_numero');

            //ciclo_numero
            //$ultimoCiclo = DB::table('sillae_ciclo')
            //->where('idpaciente','=',$idpaciente)
            //->max('ciclo_numero');
            //if(isset($ultimoCiclo))
            //{
                //if($ultimoCiclo < 3)
                //{
                    //$numero_ciclo = $ultimoCiclo + 1;
                //}if($ultimoCiclo = 3)
                //{
                    //$numero_ciclo = 1;
                //}
                
            //}else
            //{
                //$numero_ciclo = 1;
            //}

    		DB::beginTransaction();

            $sillaCiclo=new SillaElectromagnetica;
            $sillaCiclo->fecha=$hoy;
            $sillaCiclo->iddoctor=$iddoctor;
            $sillaCiclo->idpaciente=$idpaciente;
            $sillaCiclo->idusuario=$idusuario;
            $sillaCiclo->ciclo_numero=$ciclo_numero;
    		$sillaCiclo->save();
            
            $cli=DB::table('paciente')->where('idpaciente','=',$sillaCiclo->idpaciente)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se creo una nuevo ciclo para silla electromagnetica para el paciente:".$cli->nombre.", Fecha: ".$fecha;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}

    	
        $sillaCiclos=DB::table('sillae_ciclo as s')
        ->join('paciente as p','s.idpaciente','=','p.idpaciente')
        ->join('users as d','s.iddoctor','=','d.id')
        ->join('users as u','s.idusuario','=','u.id')
        ->select('s.idsillae_ciclo','s.fecha','s.ciclo_numero','s.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','s.idpaciente','p.nombre as Paciente','p.foto as Imgdpaciente','s.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('s.idpaciente','=',$idpaciente) 
        ->orderby('s.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.sillas.index",["paciente"=>Paciente::findOrFail($idpaciente),"sillaCiclos"=>$sillaCiclos]);
    }

    public function show($id)
    {
        $sillaCiclo=DB::table('sillae_ciclo as s')
        ->join('paciente as p','s.idpaciente','=','p.idpaciente')
        ->join('users as d','s.iddoctor','=','d.id')
        ->join('users as u','s.idusuario','=','u.id')
        ->select('s.idsillae_ciclo','s.fecha','s.ciclo_numero','s.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','s.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','s.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('s.idsillae_ciclo','=',$id) 
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

    public function edit($id)
    {
        $sillaCiclo=DB::table('sillae_ciclo as s')
        ->join('paciente as p','s.idpaciente','=','p.idpaciente')
        ->join('users as d','s.iddoctor','=','d.id')
        ->join('users as u','s.idusuario','=','u.id')
        ->select('s.idsillae_ciclo','s.fecha','s.ciclo_numero','s.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','s.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','s.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('s.idsillae_ciclo','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$sillaCiclo->idpaciente)
        ->first();
        
       
        return view("pacientes.historiales.sillas.edit",["sillaCiclo"=>$sillaCiclo,"paciente"=>$paciente]);
    }

    public function update(SillaElectromagneticaFormRequest $request,$id)
    {
        $idpaciente=$request->get('idpaciente');
        $id = $request->get('idsillaciclo');
        

        //$zona_horaria = Auth::user()->zona_horaria;
        $fechaSilla = $request->get('fecha');
        $fecha = date("Y-m-d", strtotime($fechaSilla));
        

        $sillaCiclo=SillaElectromagnetica::findOrFail($id);
        $sillaCiclo->ciclo_numero=$request->get('ciclo_numero');
        $sillaCiclo->save();

        $cli=DB::table('paciente')->where('idpaciente','=',$idpaciente)->first();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Pacientes";
        $bitacora->descripcion="Se edito la cabecera de ciclo del ciclo de silla electromagnetica del paciente: ".$cli->nombre.", Fecha: ".$fechaSilla;
        $bitacora->save();

        $request->session()->flash('alert-success', "Se edito la cabecera del ciclo de silla electromagnetica del paciente: ".$cli->nombre.", Fecha: ".$fechaSilla);

        $sillaCiclo=DB::table('sillae_ciclo as s')
        ->join('paciente as p','s.idpaciente','=','p.idpaciente')
        ->join('users as d','s.iddoctor','=','d.id')
        ->join('users as u','s.idusuario','=','u.id')
        ->select('s.idsillae_ciclo','s.fecha','s.ciclo_numero','s.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','s.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','s.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('s.idsillae_ciclo','=',$id) 
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

    public function eliminarsillaciclo(Request $request)
    {
        $idsillaCiclo = $request->get('idsillaCiclo');
        $idpaciente = $request->get('idpaciente');

        $eliminarsesionessilla=SillaElectromagneticaSesion::where('idsillae_ciclo',$idsillaCiclo)->delete();
        
        $eliminarsilla=SillaElectromagnetica::findOrFail($idsillaCiclo);
        $eliminarsilla->delete();

        $request->session()->flash('alert-success', 'Se elimino el ciclo de silla electromagnetica.');  
        
        
        $sillaCiclos=DB::table('sillae_ciclo as s')
        ->join('paciente as p','s.idpaciente','=','p.idpaciente')
        ->join('users as d','s.iddoctor','=','d.id')
        ->join('users as u','s.idusuario','=','u.id')
        ->select('s.idsillae_ciclo','s.fecha','s.ciclo_numero','s.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','s.idpaciente','p.nombre as Paciente','p.foto as Imgdpaciente','s.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('s.idpaciente','=',$idpaciente) 
        ->orderby('s.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.sillas.index",["paciente"=>Paciente::findOrFail($idpaciente),"sillaCiclos"=>$sillaCiclos]);
    }
}
