<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\Radiofrecuencia;
use sisVentasWeb\RadiofrecuenciaLaser;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\RadiofrecuenciaFormRequest;
use sisVentasWeb\Http\Requests\RadiofrecuenciaLaserFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class RadiofrecuenciaLaserController extends Controller
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

    	return view("pacientes.historiales.radiofrecuencias.lasers.create",["paciente"=>$paciente,"radiofrecuencia"=>$radiofrecuencia, "historia"=>$historia]);
    }

    public function store (RadiofrecuenciaLaserFormRequest $request)
    {
    	try
    	{ 
            
            $fechaLaser=trim($request->get('fecha'));
            $fecha = date("Y-m-d", strtotime($fechaLaser));

            $idpaciente=$request->get('idpaciente');
            $idradiofrecuencia=$request->get('idradiofrecuencia');

            //numero_sesion
            $ultimaLaser = DB::table('radiofrecuencia_laser')
            ->where('idradiofrecuencia','=',$idradiofrecuencia)
            ->max('numero_sesion');
            if(isset($ultimaLaser))
            {
                $numero_sesion = $ultimaLaser + 1;
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

            $sesion=new RadiofrecuenciaLaser;
            $sesion->idradiofrecuencia=$request->get('idradiofrecuencia');
            $sesion->numero_sesion=$numero_sesion;
            $sesion->fecha=$fecha;

            $sesion->tipo=$request->get('tipo');
            $sesion->area=$request->get('area');
            $sesion->zonas_a_tratar=$request->get('zonas_a_tratar');
            $sesion->parametros=$request->get('parametros');
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
            $bitacora->descripcion="Se creo un nueva sesion de laser para el paciente:".$cli->nombre.", Fecha: ".$fechaLaser;
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

        $request->session()->flash('alert-success', "Se creo una sesion de radiofrecuencia del paciente: ".$cli->nombre.", Fecha: ".$fechaLaser);

        return view("pacientes.historiales.radiofrecuencias.show",["radiofrecuencia"=>$radiofrecuencia,"paciente"=>$paciente,"sesiones"=>$sesiones,"sesionesFotomodulacion"=>$sesionesFotomodulacion,"sesionesLaser"=>$sesionesLaser, "historia"=>$historia]);
    }

    public function edit($id)
    {
        $sesion=DB::table('radiofrecuencia_laser')
        ->where('idradiofrecuencia_laser','=',$id)
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
        
       
        return view("pacientes.historiales.radiofrecuencias.lasers.edit",["sesion"=>$sesion,"radiofrecuencia"=>$radiofrecuencia,"paciente"=>$paciente, "historia"=>$historia]);
    }

    public function update(RadiofrecuenciaLaserFormRequest $request,$id)
    {
        $fechaLaser=trim($request->get('fecha'));
        $fecha = date("Y-m-d", strtotime($fechaLaser));

        $idpaciente=$request->get('idpaciente');
        $idradiofrecuencia=$request->get('idradiofrecuencia');

        $radiofrecuencia=DB::table('radiofrecuencia as r')
            ->join('paciente as p','r.idpaciente','=','p.idpaciente')
            ->join('users as d','r.iddoctor','=','d.id')
            ->join('users as u','r.idusuario','=','u.id')
            ->select('r.idradiofrecuencia','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario','r.fototipo_piel','r.implantes','r.implantes_tipo','r.marcapasos','r.periodo_gestacion','r.glaucoma','r.neoplasias_procesos_tumorales','r.portador_epilepsia','r.antecedentes_fotosensibilidad','r.tratamientos_acidos','r.medicamentos_fotosensibles','r.resumen')
            ->where('r.idradiofrecuencia','=',$idradiofrecuencia) 
            ->first();

        $sesion=RadiofrecuenciaLaser::findOrFail($id);
        
        $sesion->tipo=$request->get('tipo');
        $sesion->area=$request->get('area');
        $sesion->zonas_a_tratar=$request->get('zonas_a_tratar');
        $sesion->parametros=$request->get('parametros');
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
        $bitacora->descripcion="Se edito una sesion de laser para el paciente:".$cli->nombre.", Fecha: ".$fechaLaser;
        $bitacora->save();

        $request->session()->flash('alert-success', "Se edito una sesion de laser del paciente: ".$cli->nombre.", Fecha: ".$fechaLaser);

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

        $request->session()->flash('alert-success', "Se edito una sesion de radiofrecuencia del paciente: ".$cli->nombre.", Fecha: ".$fechaLaser);

        return view("pacientes.historiales.radiofrecuencias.show",["radiofrecuencia"=>$radiofrecuencia,"paciente"=>$paciente,"sesiones"=>$sesiones,"sesionesFotomodulacion"=>$sesionesFotomodulacion,"sesionesLaser"=>$sesionesLaser, "historia"=>$historia]);
    }

    public function eliminarsesion(Request $request)
    {
        $idradiofrecuencia = $request->get('idradiofrecuencia');
        $idpaciente = $request->get('idpaciente');
        $idradiofrecuencia_laser = $request->get('idradiofrecuencia_laser');
        
        $eliminarsesion=RadiofrecuenciaLaser::findOrFail($idradiofrecuencia_laser);
        $eliminarsesion->delete();

        $request->session()->flash('alert-success', 'Se elimino la sesion de laser.');  
        
        
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
