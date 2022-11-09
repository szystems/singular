<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\Fisico;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\PacienteFormRequest;
use sisVentasWeb\Http\Requests\FisicoFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class FisicoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $idpaciente=trim($request->get('searchidpaciente'));
        $fisicos=DB::table('fisico as f')
        ->join('paciente as p','f.idpaciente','=','p.idpaciente')
        ->join('users as d','f.iddoctor','=','d.id')
        ->join('users as u','f.idusuario','=','u.id')
        ->select('f.idfisico','f.fecha','f.iddoctor','d.name as Doctor','d.especialidad','f.idpaciente','p.nombre as Paciente','f.idusuario','u.name as Usuario','u.tipo_usuario','f.motivo_consulta')
        ->where('f.idpaciente','=',$idpaciente) 
        ->orderby('f.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.fisicos.index",["paciente"=>Paciente::findOrFail($idpaciente),"fisicos"=>$fisicos]);
    }

    public function create(Request $request)
    {
        $iddoctor=Auth::user()->id;
    	$doctor=DB::table('users')
        ->where('id','=',$iddoctor)
        ->first();

        $idpaciente = $request->idpaciente;
        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$idpaciente)
        ->first();

    	return view("pacientes.historiales.fisicos.create",["doctor"=>$doctor,"paciente"=>$paciente]);
    }

    public function store (FisicoFormRequest $request)
    {
    	try
    	{ 
            $fechaFisico=trim($request->get('fecha'));
            $fecha = date("Y-m-d", strtotime($fechaFisico));
            $iddoctor=$request->get('iddoctor');
            $idpaciente=$request->get('idpaciente');
            $idusuario=$request->get('idusuario');

            $presion_arterial1=$request->get('presion_arterial1');
            $presion_arterial2=$request->get('presion_arterial2');
            $presion_arterial=$presion_arterial1."/".$presion_arterial2;

    		DB::beginTransaction();

            $fisico=new Fisico;
            $fisico->fecha=$fecha;
            $fisico->iddoctor=$iddoctor;
            $fisico->idpaciente=$idpaciente;
            $fisico->idusuario=$idusuario;
            
            $fisico->motivo_consulta=$request->get('motivo_consulta');
            $fisico->peso=$request->get('peso');
            $fisico->talla=$request->get('talla');
            $fisico->perimetro_abdominal=$request->get('perimetro_abdominal');
            $fisico->presion_arterial=$presion_arterial;
            $fisico->frecuencia_cardiaca=$request->get('frecuencia_cardiaca');
            $fisico->frecuencia_respiratoria=$request->get('frecuencia_respiratoria');
            $fisico->temperatura=$request->get('temperatura');
            $fisico->saturacion_oxigeno=$request->get('saturacion_oxigeno');
            $fisico->impresion_clinica=$request->get('impresion_clinica');
            $fisico->plan_diagnostico=$request->get('plan_diagnostico');
            $fisico->plan_tratamiento=$request->get('plan_tratamiento');
            $fisico->recomendaciones_generales=$request->get('recomendaciones_generales');
            $fisico->recomendaciones_especificas=$request->get('recomendaciones_especificas');

            $fisico->cabeza_cuello=$request->get('cabeza_cuello');
            $fisico->tiroides=$request->get('tiroides');
            $fisico->mamas_axilas=$request->get('mamas_axilas');
            $fisico->cardiopulmonar=$request->get('cardiopulmonar');
            $fisico->abdomen=$request->get('abdomen');
            $fisico->genitales_externos=$request->get('genitales_externos');
            $fisico->especuloscopia =$request->get('especuloscopia');
            $fisico->tacto_bimanual=$request->get('tacto_bimanual');
            $fisico->miembros_inferiores=$request->get('miembros_inferiores');


    		$fisico->save();


    		
            
            $cli=DB::table('paciente')->where('idpaciente','=',$fisico->idpaciente)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se creo un examen fisico para el paciente:".$cli->nombre.", Fecha: ".$fechaFisico;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}


    	$idpaciente=$idpaciente;
        $fisicos=DB::table('fisico as f')
        ->join('paciente as p','f.idpaciente','=','p.idpaciente')
        ->join('users as d','f.iddoctor','=','d.id')
        ->join('users as u','f.idusuario','=','u.id')
        ->select('f.idfisico','f.fecha','f.iddoctor','d.name as Doctor','d.especialidad','f.idpaciente','p.nombre as Paciente','f.idusuario','u.name as Usuario','u.tipo_usuario','f.motivo_consulta')
        ->where('f.idpaciente','=',$idpaciente) 
        ->orderby('f.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.fisicos.index",["paciente"=>Paciente::findOrFail($idpaciente),"fisicos"=>$fisicos]);
    }

    public function show($id)
    {
        $fisico=DB::table('fisico as f')
        ->join('paciente as p','f.idpaciente','=','p.idpaciente')
        ->join('users as d','f.iddoctor','=','d.id')
        ->join('users as u','f.idusuario','=','u.id')
        ->select('f.idfisico','f.fecha','f.iddoctor','d.name as Doctor','d.especialidad','f.idpaciente','p.nombre as Paciente','f.idusuario','u.name as Usuario','u.tipo_usuario','f.motivo_consulta','f.peso','f.talla','f.perimetro_abdominal','f.presion_arterial','f.frecuencia_cardiaca','f.frecuencia_respiratoria','f.temperatura','f.saturacion_oxigeno','f.impresion_clinica','f.plan_diagnostico','f.plan_tratamiento','f.recomendaciones_generales','f.recomendaciones_especificas','f.cabeza_cuello','f.tiroides','f.mamas_axilas','f.cardiopulmonar','f.abdomen','f.genitales_externos','f.especuloscopia','f.tacto_bimanual','f.miembros_inferiores')
        ->where('f.idfisico','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$fisico->idpaciente)
        ->first();
        
        $fisicoimgs=DB::table('fisico_img')
        ->where('idfisico','=',$id) 
        ->get();

        return view("pacientes.historiales.fisicos.show",["fisico"=>$fisico,"paciente"=>$paciente,"fisicoimgs"=>$fisicoimgs]);
    }

    public function edit($id)
    {
        $fisico=DB::table('fisico as f')
        ->join('paciente as p','f.idpaciente','=','p.idpaciente')
        ->join('users as d','f.iddoctor','=','d.id')
        ->join('users as u','f.idusuario','=','u.id')
        ->select('f.idfisico','f.fecha','f.iddoctor','d.name as Doctor','d.especialidad','f.idpaciente','p.nombre as Paciente','f.idusuario','u.name as Usuario','u.tipo_usuario','f.motivo_consulta','f.peso','f.talla','f.perimetro_abdominal','f.presion_arterial','f.frecuencia_cardiaca','f.frecuencia_respiratoria','f.temperatura','f.saturacion_oxigeno','f.impresion_clinica','f.plan_diagnostico','f.plan_tratamiento','f.recomendaciones_generales','f.recomendaciones_especificas','f.cabeza_cuello','f.tiroides','f.mamas_axilas','f.cardiopulmonar','f.abdomen','f.genitales_externos','f.especuloscopia','f.tacto_bimanual','f.miembros_inferiores')
        ->where('f.idfisico','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$fisico->idpaciente)
        ->first();
        
       
        return view("pacientes.historiales.fisicos.edit",["fisico"=>$fisico,"paciente"=>$paciente]);
    }


    public function update(FisicoFormRequest $request,$id)
    {
        $fechaFisico=trim($request->get('fecha'));
        $fecha = date("Y-m-d", strtotime($fechaFisico));
        $iddoctor=$request->get('iddoctor');
        $idpaciente=$request->get('idpaciente');
        $idusuario=$request->get('idusuario');

        
        $fisico=Fisico::findOrFail($id);
        $fisico->fecha=$fecha;
        $fisico->motivo_consulta=$request->get('motivo_consulta');
        $fisico->peso=$request->get('peso');
        $fisico->talla=$request->get('talla');
        $fisico->perimetro_abdominal=$request->get('perimetro_abdominal');
        $fisico->presion_arterial=$request->get('presion_arterial');
        $fisico->frecuencia_cardiaca=$request->get('frecuencia_cardiaca');
        $fisico->frecuencia_respiratoria=$request->get('frecuencia_respiratoria');
        $fisico->temperatura=$request->get('temperatura');
        $fisico->saturacion_oxigeno=$request->get('saturacion_oxigeno');
        $fisico->impresion_clinica=$request->get('impresion_clinica');
        $fisico->plan_diagnostico=$request->get('plan_diagnostico');
        $fisico->plan_tratamiento=$request->get('plan_tratamiento');
        $fisico->recomendaciones_generales=$request->get('recomendaciones_generales');
        $fisico->recomendaciones_especificas=$request->get('recomendaciones_especificas');

        $fisico->cabeza_cuello=$request->get('cabeza_cuello');
        $fisico->tiroides=$request->get('tiroides');
        $fisico->mamas_axilas=$request->get('mamas_axilas');
        $fisico->cardiopulmonar=$request->get('cardiopulmonar');
        $fisico->abdomen=$request->get('abdomen');
        $fisico->genitales_externos=$request->get('genitales_externos');
        $fisico->especuloscopia =$request->get('especuloscopia');
        $fisico->tacto_bimanual=$request->get('tacto_bimanual');
        $fisico->miembros_inferiores=$request->get('miembros_inferiores');
        $fisico->update();

        $request->session()->flash('alert-success', 'Se edito correctamente un examen fisico.');

        $cli=DB::table('paciente')->where('idpaciente','=',$idpaciente)->first();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Paciente";
        $bitacora->descripcion="Se edito un examen fisico del paciente:".$cli->nombre.", Fecha: ".$fechaFisico;
        $bitacora->save();

        $idpaciente=$idpaciente;

        $fisico=DB::table('fisico as f')
        ->join('paciente as p','f.idpaciente','=','p.idpaciente')
        ->join('users as d','f.iddoctor','=','d.id')
        ->join('users as u','f.idusuario','=','u.id')
        ->select('f.idfisico','f.fecha','f.iddoctor','d.name as Doctor','d.especialidad','f.idpaciente','p.nombre as Paciente','f.idusuario','u.name as Usuario','u.tipo_usuario','f.motivo_consulta','f.peso','f.talla','f.perimetro_abdominal','f.presion_arterial','f.frecuencia_cardiaca','f.frecuencia_respiratoria','f.temperatura','f.saturacion_oxigeno','f.impresion_clinica','f.plan_diagnostico','f.plan_tratamiento','f.recomendaciones_generales','f.recomendaciones_especificas','f.cabeza_cuello','f.tiroides','f.mamas_axilas','f.cardiopulmonar','f.abdomen','f.genitales_externos','f.especuloscopia','f.tacto_bimanual','f.miembros_inferiores')
        ->where('f.idfisico','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$idpaciente)
        ->first();

        $fisicoimgs=DB::table('fisico_img')
        ->where('idfisico','=',$id) 
        ->get();

        return view("pacientes.historiales.fisicos.show",["fisico"=>$fisico,"paciente"=>$paciente,"fisicoimgs"=>$fisicoimgs]);
    }

    public function eliminarfisico(Request $request)
    {
        $idfisico = $request->get('idfisico');
        $idpaciente = $request->get('idpaciente');
        
        $eliminarfisico=Fisico::findOrFail($idfisico);
        $eliminarfisico->delete();

        $request->session()->flash('alert-success', 'Se elimino el examen fisico.');  
        
        $idpaciente=$idpaciente;
        $fisicos=DB::table('fisico as f')
        ->join('paciente as p','f.idpaciente','=','p.idpaciente')
        ->join('users as d','f.iddoctor','=','d.id')
        ->join('users as u','f.idusuario','=','u.id')
        ->select('f.idfisico','f.fecha','f.iddoctor','d.name as Doctor','d.especialidad','f.idpaciente','p.nombre as Paciente','f.idusuario','u.name as Usuario','u.tipo_usuario','f.motivo_consulta')
        ->where('f.idpaciente','=',$idpaciente) 
        ->orderby('f.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.fisicos.index",["paciente"=>Paciente::findOrFail($idpaciente),"fisicos"=>$fisicos]);
    }

    
}
