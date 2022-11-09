<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\ColposcopiaImg;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\PacienteFormRequest;
use sisVentasWeb\Http\Requests\ColposcopiaImgFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class ColposcopiaImgController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $idcolposcopia=trim($request->get('searchidcolposcopia'));
        $colposcopia=DB::table('colposcopia as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idcolposcopia','c.fecha','c.iddoctor','d.name as Doctor','d.especialidad','c.idpaciente','p.nombre as Paciente','c.idusuario','u.name as Usuario','u.tipo_usuario','c.union_escamoso_cilindrica','c.legrado_endocervical','colposcopia_insatisfactoria','hd_eap','hd_eam','hd_leucoplasia','hd_punteando','hd_mosaico','hd_vasos','hd_area','hd_otros','hd_otros_especificar','hallazgos_fuera','carcinoma_invasor','otros_hallazgos','dcn_insatisfactoria','dcn_insatisfactoria_especifique','hallazgos_nomales','inflamacion_infeccion','inflamacion_infeccion_especifique','biopsia','numero_localizacion','legrado','otros_hallazgos_colposcopicos')
        ->where('c.idcolposcopia','=',$idcolposcopia) 
        ->first();

        $colposcopiaimgs=DB::table('colposcopia_img')
        ->where('idcolposcopia','=',$idcolposcopia) 
        ->get();

        return view("pacientes.historiales.colposcopias.imagenes.index",["paciente"=>Paciente::findOrFail($colposcopia->idpaciente),"colposcopia"=>$colposcopia,"colposcopiaimgs"=>$colposcopiaimgs]);
    }

    public function store (ColposcopiaImgFormRequest $request)
    {
        //obtenemos id de colposcopia imagen
        $idcolposcopia = $request->get('idcolposcopia');

        $colposcopia=DB::table('colposcopia as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idcolposcopia','c.fecha','c.iddoctor','d.name as Doctor','d.especialidad','c.idpaciente','p.nombre as Paciente','c.idusuario','u.name as Usuario','u.tipo_usuario','c.union_escamoso_cilindrica','c.legrado_endocervical','colposcopia_insatisfactoria','hd_eap','hd_eam','hd_leucoplasia','hd_punteando','hd_mosaico','hd_vasos','hd_area','hd_otros','hd_otros_especificar','hallazgos_fuera','carcinoma_invasor','otros_hallazgos','dcn_insatisfactoria','dcn_insatisfactoria_especifique','hallazgos_nomales','inflamacion_infeccion','inflamacion_infeccion_especifique','biopsia','numero_localizacion','legrado','otros_hallazgos_colposcopicos')
        ->where('c.idcolposcopia','=',$idcolposcopia) 
        ->first();

        if (input::hasfile('imagen'))
        {
            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $fechacolposcopiaImg = date("d-m-Y", strtotime($hoy));

            //Guardar archivo de imagen y obtener nombre unico
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);
            $file=Input::file('imagen');
            $file->move(public_path().'/imagenes/colposcopias/',$generar_codigo_imagen.$file->getClientOriginalName());

            //Guardamos imagen en base de datos
            $colposcopiaImg=new ColposcopiaImg;
            $colposcopiaImg->idcolposcopia=$request->get('idcolposcopia');
            $colposcopiaImg->imagen=$generar_codigo_imagen.$file->getClientOriginalName();
            $colposcopiaImg->descripcion=$request->get('descripcion');
            $colposcopiaImg->fecha=$hoy;
            $colposcopiaImg->save();

            $cli=DB::table('paciente')->where('idpaciente','=',$colposcopia->idpaciente)->first();
        
            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se agrego una imagen de una colposcopia para el paciente:".$cli->nombre.", Fecha: ".$fechacolposcopiaImg;
            $bitacora->save();

            $request->session()->flash('alert-success', 'Se agrego correctamente la imagen de colposcopia.');
        }else
        {
            $request->session()->flash('alert-danger', 'No agrego correctamente la imagen de colposcopia, seleccione una e intente de nuevo.');
        }

        $colposcopiaimgs=DB::table('colposcopia_img')
        ->where('idcolposcopia','=',$idcolposcopia) 
        ->get();

        return view("pacientes.historiales.colposcopias.imagenes.index",["paciente"=>Paciente::findOrFail($colposcopia->idpaciente),"colposcopia"=>$colposcopia,"colposcopiaimgs"=>$colposcopiaimgs]);
    }

    public function edit($id)
    {
        $colposcopiaimg=DB::table('colposcopia_img')
        ->where('idcolposcopia_img','=',$id) 
        ->first();

        $colposcopia=DB::table('colposcopia as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idcolposcopia','c.fecha','c.iddoctor','d.name as Doctor','d.especialidad','c.idpaciente','p.nombre as Paciente','c.idusuario','u.name as Usuario','u.tipo_usuario','c.union_escamoso_cilindrica','c.legrado_endocervical','colposcopia_insatisfactoria','hd_eap','hd_eam','hd_leucoplasia','hd_punteando','hd_mosaico','hd_vasos','hd_area','hd_otros','hd_otros_especificar','hallazgos_fuera','carcinoma_invasor','otros_hallazgos','dcn_insatisfactoria','dcn_insatisfactoria_especifique','hallazgos_nomales','inflamacion_infeccion','inflamacion_infeccion_especifique','biopsia','numero_localizacion','legrado','otros_hallazgos_colposcopicos')
        ->where('c.idcolposcopia','=',$colposcopiaimg->idcolposcopia) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$colposcopia->idpaciente)
        ->first();
        
       
        return view("pacientes.historiales.colposcopias.imagenes.edit",["colposcopia"=>$colposcopia,"paciente"=>$paciente,"colposcopiaimg"=>$colposcopiaimg]);
    }


    public function update(ColposcopiaImgFormRequest $request,$id)
    {
        //obtenemos id de colposcopia
        $idcolposcopia = $request->get('idcolposcopia');

        $colposcopia=DB::table('colposcopia as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idcolposcopia','c.fecha','c.iddoctor','d.name as Doctor','d.especialidad','c.idpaciente','p.nombre as Paciente','c.idusuario','u.name as Usuario','u.tipo_usuario','c.union_escamoso_cilindrica','c.legrado_endocervical','colposcopia_insatisfactoria','hd_eap','hd_eam','hd_leucoplasia','hd_punteando','hd_mosaico','hd_vasos','hd_area','hd_otros','hd_otros_especificar','hallazgos_fuera','carcinoma_invasor','otros_hallazgos','dcn_insatisfactoria','dcn_insatisfactoria_especifique','hallazgos_nomales','inflamacion_infeccion','inflamacion_infeccion_especifique','biopsia','numero_localizacion','legrado','otros_hallazgos_colposcopicos')
        ->where('c.idcolposcopia','=',$idcolposcopia) 
        ->first();

        if (input::hasfile('imagen')){
            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $fechacolposcopiaImg = date("d-m-Y", strtotime($hoy));

            //guardamos y obtenemos nombre unico de imagen
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);
            $file=Input::file('imagen');
            $file->move(public_path().'/imagenes/colposcopias/',$generar_codigo_imagen.$file->getClientOriginalName());
            //Guardamos nombre de imagen en base de datos
            $colposcopiaImg=ColposcopiaImg::findOrFail($id);
            $colposcopiaImg->imagen=$generar_codigo_imagen.$file->getClientOriginalName();
            $colposcopiaImg->descripcion=$request->get('descripcion');
            $colposcopiaImg->update();

            $cli=DB::table('paciente')->where('idpaciente','=',$colposcopia->idpaciente)->first();
        
            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se edito una imagen de una colposcopia para el paciente:".$cli->nombre.", Fecha: ".$fechacolposcopiaImg;
            $bitacora->save();

            $request->session()->flash('alert-success', 'Se edito correctamente una imagen de colposcopia.');
        }else
        {
            $request->session()->flash('alert-success', 'No se pudo editar la imagen Intentelo de nuevo.');
        }
        

        $colposcopiaimgs=DB::table('colposcopia_img')
        ->where('idcolposcopia','=',$idcolposcopia) 
        ->get();

        return view("pacientes.historiales.colposcopias.imagenes.index",["paciente"=>Paciente::findOrFail($colposcopia->idpaciente),"colposcopia"=>$colposcopia,"colposcopiaimgs"=>$colposcopiaimgs]);
    }

    public function eliminarimagen(Request $request)
    {
        $idcolposcopia = $request->get('idcolposcopia');
        $idpaciente = $request->get('idpaciente');
        $idcolposcopiaimg = $request->get('idcolposcopiaimg');
        
        $eliminarimagen=ColposcopiaImg::findOrFail($idcolposcopiaimg);
        $eliminarimagen->delete();

        $request->session()->flash('alert-success', 'Se elimino la imagen de colposcopia.');  
        
        $colposcopia=DB::table('colposcopia as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idcolposcopia','c.fecha','c.iddoctor','d.name as Doctor','d.especialidad','c.idpaciente','p.nombre as Paciente','c.idusuario','u.name as Usuario','u.tipo_usuario','c.union_escamoso_cilindrica','c.legrado_endocervical','colposcopia_insatisfactoria','hd_eap','hd_eam','hd_leucoplasia','hd_punteando','hd_mosaico','hd_vasos','hd_area','hd_otros','hd_otros_especificar','hallazgos_fuera','carcinoma_invasor','otros_hallazgos','dcn_insatisfactoria','dcn_insatisfactoria_especifique','hallazgos_nomales','inflamacion_infeccion','inflamacion_infeccion_especifique','biopsia','numero_localizacion','legrado','otros_hallazgos_colposcopicos')
        ->where('c.idcolposcopia','=',$idcolposcopia) 
        ->first();

        $colposcopiaimgs=DB::table('colposcopia_img')
        ->where('idcolposcopia','=',$idcolposcopia) 
        ->get();

        return view("pacientes.historiales.colposcopias.imagenes.index",["paciente"=>Paciente::findOrFail($colposcopia->idpaciente),"colposcopia"=>$colposcopia,"colposcopiaimgs"=>$colposcopiaimgs]);
    }
}
