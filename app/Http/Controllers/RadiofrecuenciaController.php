<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\Radiofrecuencia;
use sisVentasWeb\RadiofrecuenciaSesion;
use sisVentasWeb\RadiofrecuenciaFotomodulacion;
use sisVentasWeb\control;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\RadiofrecuenciaFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class RadiofrecuenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $idpaciente=trim($request->get('searchidpaciente'));
        $radiofrecuencias=DB::table('radiofrecuencia as r')
        ->join('paciente as p','r.idpaciente','=','p.idpaciente')
        ->join('users as d','r.iddoctor','=','d.id')
        ->join('users as u','r.idusuario','=','u.id')
        ->select('r.idradiofrecuencia','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgdpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('r.idpaciente','=',$idpaciente) 
        ->orderby('r.fecha','desc')
        ->paginate(20);
        
        

        return view("pacientes.historiales.radiofrecuencias.index",["paciente"=>Paciente::findOrFail($idpaciente),"radiofrecuencias"=>$radiofrecuencias]);
    }

    public function store (RadiofrecuenciaFormRequest $request)
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

    		DB::beginTransaction();

            $radiofrecuencia=new Radiofrecuencia;
            $radiofrecuencia->fecha=$hoy;
            $radiofrecuencia->iddoctor=$iddoctor;
            $radiofrecuencia->idpaciente=$idpaciente;
            $radiofrecuencia->idusuario=$idusuario;
    		$radiofrecuencia->save();
            
            $cli=DB::table('paciente')->where('idpaciente','=',$radiofrecuencia->idpaciente)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se creo una nueva radiofrecuencia para el paciente:".$cli->nombre.", Fecha: ".$fecha;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}

    	
        $radiofrecuencias=DB::table('radiofrecuencia as r')
        ->join('paciente as p','r.idpaciente','=','p.idpaciente')
        ->join('users as d','r.iddoctor','=','d.id')
        ->join('users as u','r.idusuario','=','u.id')
        ->select('r.idradiofrecuencia','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgdpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('r.idpaciente','=',$idpaciente) 
        ->orderby('r.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.radiofrecuencias.index",["paciente"=>Paciente::findOrFail($idpaciente),"radiofrecuencias"=>$radiofrecuencias]);
    }

    public function show($id)
    {
        $radiofrecuencia=DB::table('radiofrecuencia as r')
        ->join('paciente as p','r.idpaciente','=','p.idpaciente')
        ->join('users as d','r.iddoctor','=','d.id')
        ->join('users as u','r.idusuario','=','u.id')
        ->select('r.idradiofrecuencia','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario','r.fototipo_piel','r.implantes','r.implantes_tipo','r.marcapasos','r.periodo_gestacion','r.glaucoma','r.neoplasias_procesos_tumorales','r.portador_epilepsia','r.antecedentes_fotosensibilidad','r.tratamientos_acidos','r.medicamentos_fotosensibles','r.resumen')
        ->where('r.idradiofrecuencia','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$radiofrecuencia->idpaciente)
        ->first();

        $sesiones=DB::table('radiofrecuencia_sesion')
        ->where('idradiofrecuencia','=',$radiofrecuencia->idradiofrecuencia)
        ->get();

        $sesionesFotomodulacion=DB::table('radiofrecuencia_fotomodulacion')
        ->where('idradiofrecuencia','=',$radiofrecuencia->idradiofrecuencia)
        ->get();

        $sesionesLaser=DB::table('radiofrecuencia_laser')
        ->where('idradiofrecuencia','=',$radiofrecuencia->idradiofrecuencia)
        ->get();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$radiofrecuencia->idpaciente)
        ->first();

        return view("pacientes.historiales.radiofrecuencias.show",["radiofrecuencia"=>$radiofrecuencia,"paciente"=>$paciente,"sesiones"=>$sesiones,"sesionesFotomodulacion"=>$sesionesFotomodulacion,"sesionesLaser"=>$sesionesLaser,"historia"=>$historia]);
    }

    public function edit($id)
    {
        $radiofrecuencia=DB::table('radiofrecuencia as r')
        ->join('paciente as p','r.idpaciente','=','p.idpaciente')
        ->join('users as d','r.iddoctor','=','d.id')
        ->join('users as u','r.idusuario','=','u.id')
        ->select('r.idradiofrecuencia','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario','r.fototipo_piel','r.implantes','r.implantes_tipo','r.marcapasos','r.periodo_gestacion','r.glaucoma','r.neoplasias_procesos_tumorales','r.portador_epilepsia','r.antecedentes_fotosensibilidad','r.tratamientos_acidos','r.medicamentos_fotosensibles','r.resumen')
        ->where('r.idradiofrecuencia','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$radiofrecuencia->idpaciente)
        ->first();
        
       
        return view("pacientes.historiales.radiofrecuencias.edit",["radiofrecuencia"=>$radiofrecuencia,"paciente"=>$paciente]);
    }

    public function update(RadiofrecuenciaFormRequest $request,$id)
    {
        $idpaciente=$request->get('idpaciente');
        $id = $request->get('idradiofrecuencia');
        

        //$zona_horaria = Auth::user()->zona_horaria;
        $fechaRadiofrecuencia = $request->get('fecha');
        $fecha = date("Y-m-d", strtotime($fechaRadiofrecuencia));
        

        $radiofrecuencia=Radiofrecuencia::findOrFail($id);
        $radiofrecuencia->fototipo_piel=$request->get('fototipo_piel');
        $radiofrecuencia->implantes=$request->get('implantes');
        $radiofrecuencia->implantes_tipo=$request->get('implantes_tipo');
        $radiofrecuencia->marcapasos=$request->get('marcapasos');
        $radiofrecuencia->periodo_gestacion=$request->get('periodo_gestacion');
        $radiofrecuencia->glaucoma=$request->get('glaucoma');
        $radiofrecuencia->neoplasias_procesos_tumorales=$request->get('neoplasias_procesos_tumorales');
        $radiofrecuencia->portador_epilepsia=$request->get('portador_epilepsia');
        $radiofrecuencia->antecedentes_fotosensibilidad=$request->get('antecedentes_fotosensibilidad');
        $radiofrecuencia->tratamientos_acidos=$request->get('tratamientos_acidos');
        $radiofrecuencia->medicamentos_fotosensibles=$request->get('medicamentos_fotosensibles');
        $radiofrecuencia->resumen=$request->get('resumen');
        $radiofrecuencia->save();

        $cli=DB::table('paciente')->where('idpaciente','=',$radiofrecuencia->idpaciente)->first();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Pacientes";
        $bitacora->descripcion="Se edito la cabecera de radiofrecuencia del paciente: ".$cli->nombre.", Fecha: ".$fechaRadiofrecuencia;
        $bitacora->save();

        $request->session()->flash('alert-success', "Se edito la cabecera de radiofrecuencia del paciente: ".$cli->nombre.", Fecha: ".$fechaRadiofrecuencia);

        $radiofrecuencia=DB::table('radiofrecuencia as r')
        ->join('paciente as p','r.idpaciente','=','p.idpaciente')
        ->join('users as d','r.iddoctor','=','d.id')
        ->join('users as u','r.idusuario','=','u.id')
        ->select('r.idradiofrecuencia','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario','r.fototipo_piel','r.implantes','r.implantes_tipo','r.marcapasos','r.periodo_gestacion','r.glaucoma','r.neoplasias_procesos_tumorales','r.portador_epilepsia','r.antecedentes_fotosensibilidad','r.tratamientos_acidos','r.medicamentos_fotosensibles','r.resumen')
        ->where('r.idradiofrecuencia','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$radiofrecuencia->idpaciente)
        ->first();

        $sesionesFotomodulacion=DB::table('radiofrecuencia_fotomodulacion')
        ->where('idradiofrecuencia','=',$radiofrecuencia->idradiofrecuencia)
        ->get();

        $sesionesLaser=DB::table('radiofrecuencia_laser')
        ->where('idradiofrecuencia','=',$radiofrecuencia->idradiofrecuencia)
        ->get();

        $sesiones=DB::table('radiofrecuencia_sesion')
        ->where('idradiofrecuencia','=',$radiofrecuencia->idradiofrecuencia)
        ->get();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$radiofrecuencia->idpaciente)
        ->first();

        return view("pacientes.historiales.radiofrecuencias.show",["radiofrecuencia"=>$radiofrecuencia,"paciente"=>$paciente,"sesiones"=>$sesiones,"sesionesFotomodulacion"=>$sesionesFotomodulacion,"sesionesLaser"=>$sesionesLaser,"historia"=>$historia]);
    }

    public function eliminarradiofrecuencia(Request $request)
    {
        $idradiofrecuencia = $request->get('idradiofrecuencia');
        $idpaciente = $request->get('idpaciente');

        $eliminarsesionesradiofrecuencia=RadiofrecuenciaSesion::where('idradiofrecuencia',$idradiofrecuencia)->delete();
        $eliminarsesionesradiofrecuencia=RadiofrecuenciaFotomodulacion::where('idradiofrecuencia',$idradiofrecuencia)->delete();
        
        $eliminarradiofrecuencia=Radiofrecuencia::findOrFail($idradiofrecuencia);
        $eliminarradiofrecuencia->delete();

        $request->session()->flash('alert-success', 'Se elimino la radiofrecuencia.');  
        
        
        $radiofrecuencias=DB::table('radiofrecuencia as r')
        ->join('paciente as p','r.idpaciente','=','p.idpaciente')
        ->join('users as d','r.iddoctor','=','d.id')
        ->join('users as u','r.idusuario','=','u.id')
        ->select('r.idradiofrecuencia','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgdpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('r.idpaciente','=',$idpaciente) 
        ->orderby('r.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.radiofrecuencias.index",["paciente"=>Paciente::findOrFail($idpaciente),"radiofrecuencias"=>$radiofrecuencias]);
    }
}
