<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\Radiofrecuencia;
use sisVentasWeb\RadiofrecuenciaFotomodulacion;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\RadiofrecuenciaFormRequest;
use sisVentasWeb\Http\Requests\RadiofrecuenciaFotomodulacionFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class RadiofrecuenciaFotomodulacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        $idradiofrecuencia = $request->idradiofrecuencia;

        $radiofrecuencia=DB::table('radiofrecuencia as r')
        ->join('paciente as p','r.idpaciente','=','p.idpaciente')
        ->join('users as d','r.iddoctor','=','d.id')
        ->join('users as u','r.idusuario','=','u.id')
        ->select('r.idradiofrecuencia','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','p.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario','r.fototipo_piel','r.implantes','r.implantes_tipo','r.marcapasos','r.periodo_gestacion','r.glaucoma','r.neoplasias_procesos_tumorales','r.portador_epilepsia','r.antecedentes_fotosensibilidad','r.tratamientos_acidos','r.medicamentos_fotosensibles','r.resumen')
        ->where('r.idradiofrecuencia','=',$idradiofrecuencia) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$radiofrecuencia->idpaciente)
        ->first(); 

        $historia = DB::table('historia')
        ->where('idpaciente','=',$radiofrecuencia->idpaciente)
        ->first();

    	return view("pacientes.historiales.radiofrecuencias.fotomodulaciones.create",["paciente"=>$paciente,"radiofrecuencia"=>$radiofrecuencia, "historia"=>$historia]);
    }

    public function store (RadiofrecuenciaFotomodulacionFormRequest $request)
    {
    	try
    	{ 
            
            $fechaSesion=trim($request->get('fecha'));
            $fecha = date("Y-m-d", strtotime($fechaSesion));

            $idpaciente=$request->get('idpaciente');
            $idradiofrecuencia=$request->get('idradiofrecuencia');

            //numero_sesion
            $ultimaSesion = DB::table('radiofrecuencia_fotomodulacion')
            ->where('idradiofrecuencia','=',$idradiofrecuencia)
            ->max('numero_sesion');
            if(isset($ultimaSesion))
            {
                $numero_sesion = $ultimaSesion + 1;
            }else
            {
                $numero_sesion = 1;
            }

            $radiofrecuencia=DB::table('radiofrecuencia as r')
            ->join('paciente as p','r.idpaciente','=','p.idpaciente')
            ->join('users as d','r.iddoctor','=','d.id')
            ->join('users as u','r.idusuario','=','u.id')
            ->select('r.idradiofrecuencia','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','p.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario','r.fototipo_piel','r.implantes','r.implantes_tipo','r.marcapasos','r.periodo_gestacion','r.glaucoma','r.neoplasias_procesos_tumorales','r.portador_epilepsia','r.antecedentes_fotosensibilidad','r.tratamientos_acidos','r.medicamentos_fotosensibles','r.resumen')
            ->where('r.idradiofrecuencia','=',$idradiofrecuencia) 
            ->first();

    		DB::beginTransaction();

            $sesion=new RadiofrecuenciaFotomodulacion;
            $sesion->idradiofrecuencia=$request->get('idradiofrecuencia');
            $sesion->numero_sesion=$numero_sesion;
            $sesion->fecha=$fecha;

            $sesion->azul_area=$request->get('azul_area');
            $sesion->azul_zona=$request->get('azul_zona');
            $sesion->azul_jm2=$request->get('azul_jm2');
            $sesion->azul_tiempo=$request->get('azul_tiempo');
            
            $sesion->infralight_area=$request->get('infralight_area');
            $sesion->infralight_zona=$request->get('infralight_zona');
            $sesion->infralight_jm2=$request->get('infralight_jm2');
            $sesion->infralight_tiempo=$request->get('infralight_tiempo');
            
            $sesion->ambar_area=$request->get('ambar_area');
            $sesion->ambar_zona=$request->get('ambar_zona');
            $sesion->ambar_jm2=$request->get('ambar_jm2');
            $sesion->ambar_tiempo=$request->get('ambar_tiempo');
            
            $sesion->rubylight_area=$request->get('rubylight_area');
            $sesion->rubylight_zona=$request->get('rubylight_zona');
            $sesion->rubylight_jm2=$request->get('rubylight_jm2');
            $sesion->rubylight_tiempo=$request->get('rubylight_tiempo');
            
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
            $bitacora->descripcion="Se creo un nueva nueva sesion de fotomodulacion para el paciente:".$cli->nombre.", Fecha: ".$fechaSesion;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$idpaciente)
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
        ->where('idpaciente','=',$idpaciente)
        ->first();

        $request->session()->flash('alert-success', "Se creo una sesion de fotomodulacion del paciente: ".$cli->nombre.", Fecha: ".$fechaSesion);

        return view("pacientes.historiales.radiofrecuencias.show",["radiofrecuencia"=>$radiofrecuencia,"paciente"=>$paciente,"sesiones"=>$sesiones,"sesionesFotomodulacion"=>$sesionesFotomodulacion,"sesionesLaser"=>$sesionesLaser, "historia"=>$historia]);
    }

    public function edit($id)
    {
        $sesion=DB::table('radiofrecuencia_fotomodulacion')
        ->where('idradiofrecuencia_fotomodulacion','=',$id)
        ->first();

        $radiofrecuencia=DB::table('radiofrecuencia as r')
            ->join('paciente as p','r.idpaciente','=','p.idpaciente')
            ->join('users as d','r.iddoctor','=','d.id')
            ->join('users as u','r.idusuario','=','u.id')
            ->select('r.idradiofrecuencia','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario','r.fototipo_piel','r.implantes','r.implantes_tipo','r.marcapasos','r.periodo_gestacion','r.glaucoma','r.neoplasias_procesos_tumorales','r.portador_epilepsia','r.antecedentes_fotosensibilidad','r.tratamientos_acidos','r.medicamentos_fotosensibles','r.resumen')
            ->where('r.idradiofrecuencia','=',$sesion->idradiofrecuencia) 
            ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$radiofrecuencia->idpaciente)
        ->first();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$radiofrecuencia->idpaciente)
        ->first();
        
       
        return view("pacientes.historiales.radiofrecuencias.fotomodulaciones.edit",["sesion"=>$sesion,"radiofrecuencia"=>$radiofrecuencia,"paciente"=>$paciente, "historia"=>$historia]);
    }

    public function update(RadiofrecuenciaFotomodulacionFormRequest $request,$id)
    {
        $fechaSesion=trim($request->get('fecha'));
        $fecha = date("Y-m-d", strtotime($fechaSesion));

        $idpaciente=$request->get('idpaciente');
        $idradiofrecuencia=$request->get('idradiofrecuencia');

        $radiofrecuencia=DB::table('radiofrecuencia as r')
            ->join('paciente as p','r.idpaciente','=','p.idpaciente')
            ->join('users as d','r.iddoctor','=','d.id')
            ->join('users as u','r.idusuario','=','u.id')
            ->select('r.idradiofrecuencia','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario','r.fototipo_piel','r.implantes','r.implantes_tipo','r.marcapasos','r.periodo_gestacion','r.glaucoma','r.neoplasias_procesos_tumorales','r.portador_epilepsia','r.antecedentes_fotosensibilidad','r.tratamientos_acidos','r.medicamentos_fotosensibles','r.resumen')
            ->where('r.idradiofrecuencia','=',$idradiofrecuencia) 
            ->first();

        $sesion=RadiofrecuenciaFotomodulacion::findOrFail($id);
        
        $sesion->azul_area=$request->get('azul_area');
        $sesion->azul_zona=$request->get('azul_zona');
        $sesion->azul_jm2=$request->get('azul_jm2');
        $sesion->azul_tiempo=$request->get('azul_tiempo');
                   
        $sesion->infralight_area=$request->get('infralight_area');
        $sesion->infralight_zona=$request->get('infralight_zona');
        $sesion->infralight_jm2=$request->get('infralight_jm2');
        $sesion->infralight_tiempo=$request->get('infralight_tiempo');
        
        $sesion->ambar_area=$request->get('ambar_area');
        $sesion->ambar_zona=$request->get('ambar_zona');
        $sesion->ambar_jm2=$request->get('ambar_jm2');
        $sesion->ambar_tiempo=$request->get('ambar_tiempo');
        
        $sesion->rubylight_area=$request->get('rubylight_area');
        $sesion->rubylight_zona=$request->get('rubylight_zona');
        $sesion->rubylight_jm2=$request->get('rubylight_jm2');
        $sesion->rubylight_tiempo=$request->get('rubylight_tiempo');
        
        $sesion->save();

        $cli=DB::table('paciente')->where('idpaciente','=',$radiofrecuencia->idpaciente)->first();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Paciente";
        $bitacora->descripcion="Se creo una nueva sesion de fotomodulacion para el paciente:".$cli->nombre.", Fecha: ".$fechaSesion;
        $bitacora->save();

        $request->session()->flash('alert-success', "Se edito una sesion de fotomodulacion del paciente: ".$cli->nombre.", Fecha: ".$fechaSesion);

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

        $request->session()->flash('alert-success', "Se edito una sesion de fotomodulacion del paciente: ".$cli->nombre.", Fecha: ".$fechaSesion);

        return view("pacientes.historiales.radiofrecuencias.show",["radiofrecuencia"=>$radiofrecuencia,"paciente"=>$paciente,"sesiones"=>$sesiones,"sesionesFotomodulacion"=>$sesionesFotomodulacion,"sesionesLaser"=>$sesionesLaser, "historia"=>$historia]);
    }

    public function eliminarsesion(Request $request)
    {
        $idradiofrecuencia = $request->get('idradiofrecuencia');
        $idpaciente = $request->get('idpaciente');
        $idradiofrecuencia_fotomodulacion = $request->get('idradiofrecuencia_fotomodulacion');
        
        $eliminarsesion=RadiofrecuenciaFotomodulacion::findOrFail($idradiofrecuencia_fotomodulacion);
        $eliminarsesion->delete();

        $request->session()->flash('alert-success', 'Se elimino la sesion de fotomodulacion.');  
        
        
        $radiofrecuencia=DB::table('radiofrecuencia as r')
            ->join('paciente as p','r.idpaciente','=','p.idpaciente')
            ->join('users as d','r.iddoctor','=','d.id')
            ->join('users as u','r.idusuario','=','u.id')
            ->select('r.idradiofrecuencia','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario','r.fototipo_piel','r.implantes','r.implantes_tipo','r.marcapasos','r.periodo_gestacion','r.glaucoma','r.neoplasias_procesos_tumorales','r.portador_epilepsia','r.antecedentes_fotosensibilidad','r.tratamientos_acidos','r.medicamentos_fotosensibles','r.resumen')
            ->where('r.idradiofrecuencia','=',$idradiofrecuencia) 
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

        return view("pacientes.historiales.radiofrecuencias.show",["radiofrecuencia"=>$radiofrecuencia,"paciente"=>$paciente,"sesiones"=>$sesiones,"sesionesFotomodulacion"=>$sesionesFotomodulacion,"sesionesLaser"=>$sesionesLaser, "historia"=>$historia]);
    }
}
