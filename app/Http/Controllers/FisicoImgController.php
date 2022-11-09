<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\FisicoImg;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\PacienteFormRequest;
use sisVentasWeb\Http\Requests\FisicoImgFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class FisicoImgController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $idfisico=trim($request->get('searchidfisico'));
        $fisico=DB::table('fisico as f')
        ->join('paciente as p','f.idpaciente','=','p.idpaciente')
        ->join('users as d','f.iddoctor','=','d.id')
        ->join('users as u','f.idusuario','=','u.id')
        ->select('f.idfisico','f.fecha','f.iddoctor','d.name as Doctor','d.especialidad','f.idpaciente','p.nombre as Paciente','f.idusuario','u.name as Usuario','u.tipo_usuario','f.motivo_consulta','f.peso','f.talla','f.perimetro_abdominal','f.presion_arterial','f.frecuencia_cardiaca','f.frecuencia_respiratoria','f.temperatura','f.saturacion_oxigeno','f.impresion_clinica','f.plan_diagnostico','f.plan_tratamiento','f.recomendaciones_generales','f.recomendaciones_especificas','f.cabeza_cuello','f.tiroides','f.mamas_axilas','f.cardiopulmonar','f.abdomen','f.genitales_externos','f.especuloscopia','f.tacto_bimanual','f.miembros_inferiores')
        ->where('f.idfisico','=',$idfisico) 
        ->first();

        $fisicoimgs=DB::table('fisico_img')
        ->where('idfisico','=',$idfisico) 
        ->get();

        return view("pacientes.historiales.fisicos.imagenes.index",["paciente"=>Paciente::findOrFail($fisico->idpaciente),"fisico"=>$fisico,"fisicoimgs"=>$fisicoimgs]);
    }

    public function store (FisicoImgFormRequest $request)
    {
        //obtenemos id de fisico imagen
        $idfisico = $request->get('idfisico');

        $fisico=DB::table('fisico as f')
            ->join('paciente as p','f.idpaciente','=','p.idpaciente')
            ->join('users as d','f.iddoctor','=','d.id')
            ->join('users as u','f.idusuario','=','u.id')
            ->select('f.idfisico','f.fecha','f.iddoctor','d.name as Doctor','d.especialidad','f.idpaciente','p.nombre as Paciente','f.idusuario','u.name as Usuario','u.tipo_usuario','f.motivo_consulta','f.peso','f.talla','f.perimetro_abdominal','f.presion_arterial','f.frecuencia_cardiaca','f.frecuencia_respiratoria','f.temperatura','f.saturacion_oxigeno','f.impresion_clinica','f.plan_diagnostico','f.plan_tratamiento','f.recomendaciones_generales','f.recomendaciones_especificas','f.cabeza_cuello','f.tiroides','f.mamas_axilas','f.cardiopulmonar','f.abdomen','f.genitales_externos','f.especuloscopia','f.tacto_bimanual','f.miembros_inferiores')
            ->where('f.idfisico','=',$idfisico) 
            ->first();

        if (input::hasfile('imagen'))
        {
            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $fechaFisicoImg = date("d-m-Y", strtotime($hoy));

            //Guardar archivo de imagen y obtener nombre unico
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);
            $file=Input::file('imagen');
            $file->move(public_path().'/imagenes/fisicos/',$generar_codigo_imagen.$file->getClientOriginalName());

            //Guardamos imagen en base de datos
            $fisicoImg=new FisicoImg;
            $fisicoImg->idfisico=$request->get('idfisico');
            $fisicoImg->imagen=$generar_codigo_imagen.$file->getClientOriginalName();
            $fisicoImg->descripcion=$request->get('descripcion');
            $fisicoImg->fecha=$hoy;
            $fisicoImg->save();

            $cli=DB::table('paciente')->where('idpaciente','=',$fisico->idpaciente)->first();
        
            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se agrego una imagen de un examen fisico para el paciente:".$cli->nombre.", Fecha: ".$fechaFisicoImg;
            $bitacora->save();

            $request->session()->flash('alert-success', 'Se agrego correctamente la imagen del examen fisico.');
        }else
        {
            $request->session()->flash('alert-danger', 'No agrego correctamente la imagen del examen fisico, seleccione una e intente de nuevo.');
        }

        $fisicoimgs=DB::table('fisico_img')
        ->where('idfisico','=',$idfisico) 
        ->get();

        return view("pacientes.historiales.fisicos.imagenes.index",["paciente"=>Paciente::findOrFail($fisico->idpaciente),"fisico"=>$fisico,"fisicoimgs"=>$fisicoimgs]);
    }

    public function edit($id)
    {
        $fisicoimg=DB::table('fisico_img')
        ->where('idfisico_img','=',$id) 
        ->first();

        $fisico=DB::table('fisico as f')
        ->join('paciente as p','f.idpaciente','=','p.idpaciente')
        ->join('users as d','f.iddoctor','=','d.id')
        ->join('users as u','f.idusuario','=','u.id')
        ->select('f.idfisico','f.fecha','f.iddoctor','d.name as Doctor','d.especialidad','f.idpaciente','p.nombre as Paciente','f.idusuario','u.name as Usuario','u.tipo_usuario','f.motivo_consulta','f.peso','f.talla','f.perimetro_abdominal','f.presion_arterial','f.frecuencia_cardiaca','f.frecuencia_respiratoria','f.temperatura','f.saturacion_oxigeno','f.impresion_clinica','f.plan_diagnostico','f.plan_tratamiento','f.recomendaciones_generales','f.recomendaciones_especificas','f.cabeza_cuello','f.tiroides','f.mamas_axilas','f.cardiopulmonar','f.abdomen','f.genitales_externos','f.especuloscopia','f.tacto_bimanual','f.miembros_inferiores')
        ->where('f.idfisico','=',$fisicoimg->idfisico) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$fisico->idpaciente)
        ->first();
        
       
        return view("pacientes.historiales.fisicos.imagenes.edit",["fisico"=>$fisico,"paciente"=>$paciente,"fisicoimg"=>$fisicoimg]);
    }


    public function update(FisicoImgFormRequest $request,$id)
    {
        //obtenemos id de fisico
        $idfisico = $request->get('idfisico');

        $fisico=DB::table('fisico as f')
            ->join('paciente as p','f.idpaciente','=','p.idpaciente')
            ->join('users as d','f.iddoctor','=','d.id')
            ->join('users as u','f.idusuario','=','u.id')
            ->select('f.idfisico','f.fecha','f.iddoctor','d.name as Doctor','d.especialidad','f.idpaciente','p.nombre as Paciente','f.idusuario','u.name as Usuario','u.tipo_usuario','f.motivo_consulta','f.peso','f.talla','f.perimetro_abdominal','f.presion_arterial','f.frecuencia_cardiaca','f.frecuencia_respiratoria','f.temperatura','f.saturacion_oxigeno','f.impresion_clinica','f.plan_diagnostico','f.plan_tratamiento','f.recomendaciones_generales','f.recomendaciones_especificas','f.cabeza_cuello','f.tiroides','f.mamas_axilas','f.cardiopulmonar','f.abdomen','f.genitales_externos','f.especuloscopia','f.tacto_bimanual','f.miembros_inferiores')
            ->where('f.idfisico','=',$idfisico) 
            ->first();

        if (input::hasfile('imagen')){
            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $fechaFisicoImg = date("d-m-Y", strtotime($hoy));

            //guardamos y obtenemos nombre unico de imagen
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);
            $file=Input::file('imagen');
            $file->move(public_path().'/imagenes/fisicos/',$generar_codigo_imagen.$file->getClientOriginalName());
            //Guardamos nombre de imagen en base de datos
            $fisicoImg=FisicoImg::findOrFail($id);
            $fisicoImg->imagen=$generar_codigo_imagen.$file->getClientOriginalName();
            $fisicoImg->descripcion=$request->get('descripcion');
            $fisicoImg->update();

            $cli=DB::table('paciente')->where('idpaciente','=',$fisico->idpaciente)->first();
        
            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se edito una imagen de un examen fisico para el paciente:".$cli->nombre.", Fecha: ".$fechaFisicoImg;
            $bitacora->save();

            $request->session()->flash('alert-success', 'Se edito correctamente una imagen del examen fisico.');
        }else
        {
            $request->session()->flash('alert-success', 'No se pudo editar la imagen Intentelo de nuevo.');
        }
        

        $fisicoimgs=DB::table('fisico_img')
        ->where('idfisico','=',$idfisico) 
        ->get();

        return view("pacientes.historiales.fisicos.imagenes.index",["paciente"=>Paciente::findOrFail($fisico->idpaciente),"fisico"=>$fisico,"fisicoimgs"=>$fisicoimgs]);
    }

    public function eliminarimagen(Request $request)
    {
        $idfisico = $request->get('idfisico');
        $idpaciente = $request->get('idpaciente');
        $idfisicoimg = $request->get('idfisicoimg');
        
        $eliminarimagen=FisicoImg::findOrFail($idfisicoimg);
        $eliminarimagen->delete();

        $request->session()->flash('alert-success', 'Se elimino la imagen de examen fisico.');  
        
        $fisico=DB::table('fisico as f')
        ->join('paciente as p','f.idpaciente','=','p.idpaciente')
        ->join('users as d','f.iddoctor','=','d.id')
        ->join('users as u','f.idusuario','=','u.id')
        ->select('f.idfisico','f.fecha','f.iddoctor','d.name as Doctor','d.especialidad','f.idpaciente','p.nombre as Paciente','f.idusuario','u.name as Usuario','u.tipo_usuario','f.motivo_consulta','f.peso','f.talla','f.perimetro_abdominal','f.presion_arterial','f.frecuencia_cardiaca','f.frecuencia_respiratoria','f.temperatura','f.saturacion_oxigeno','f.impresion_clinica','f.plan_diagnostico','f.plan_tratamiento','f.recomendaciones_generales','f.recomendaciones_especificas','f.cabeza_cuello','f.tiroides','f.mamas_axilas','f.cardiopulmonar','f.abdomen','f.genitales_externos','f.especuloscopia','f.tacto_bimanual','f.miembros_inferiores')
        ->where('f.idfisico','=',$idfisico) 
        ->first();

        $fisicoimgs=DB::table('fisico_img')
        ->where('idfisico','=',$idfisico) 
        ->get();

        return view("pacientes.historiales.fisicos.imagenes.index",["paciente"=>Paciente::findOrFail($fisico->idpaciente),"fisico"=>$fisico,"fisicoimgs"=>$fisicoimgs]);
    }
}
