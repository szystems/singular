<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\UltrasonidoObstetricoImg;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\PacienteFormRequest;
use sisVentasWeb\Http\Requests\UltrasonidoObstetricoImgFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class UltrasonidoObstetricoImgController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $idultrasonido_obstetrico=trim($request->get('searchidultrasonido'));
        $ultrasonido=DB::table('ultrasonido_obstetrico as uo')
        ->join('paciente as p','uo.idpaciente','=','p.idpaciente')
        ->join('users as d','uo.iddoctor','=','d.id')
        ->join('users as u','uo.idusuario','=','u.id')
        ->select('uo.idultrasonido_obstetrico','uo.fecha','uo.iddoctor','d.name as Doctor','d.especialidad','uo.idpaciente','p.nombre as Paciente','uo.idusuario','u.name as Usuario','u.tipo_usuario','uo.spp','uo.fcardiaca_fetal','pubicacion','liquido_amniotico','utero_anexos','cervix','diametro_biparietal_medida','diametro_biparietal_semanas','circunferencia_cefalica_medida','circunferencia_cefalica_semanas','circunferencia_abdominal_medida','circunferencia_abdominal_semanas','longitud_femoral_medida','longitud_femoral_semanas','fetometria','peso_estimado','percentilo','comentarios','interpretacion','recomendaciones','observaciones','embarazo_unico','embarazo_unico_comentar','alteraciones_crecimiento','alteraciones_crecimiento_comentar','alteraciones_frecuencia','alteraciones_frecuencia_comentar','placenta','placenta_comentar','liquido','liquido_comentar','prematuro','prematuro_comentar')
        ->where('uo.idultrasonido_obstetrico','=',$idultrasonido_obstetrico) 
        ->first();

        $ultrasonidoimgs=DB::table('ultrasonido_obstetrico_img')
        ->where('idultrasonido_obstetrico','=',$idultrasonido_obstetrico) 
        ->get();

        return view("pacientes.historiales.ultrasonidos.imagenes.index",["paciente"=>Paciente::findOrFail($ultrasonido->idpaciente),"ultrasonido"=>$ultrasonido,"ultrasonidoimgs"=>$ultrasonidoimgs]);
    }

    public function store (UltrasonidoObstetricoImgFormRequest $request)
    {
        //obtenemos id de ultrasonido imagen
        $idultrasonido_obstetrico = $request->get('idultrasonido_obstetrico');

        $ultrasonido=DB::table('ultrasonido_obstetrico as uo')
        ->join('paciente as p','uo.idpaciente','=','p.idpaciente')
        ->join('users as d','uo.iddoctor','=','d.id')
        ->join('users as u','uo.idusuario','=','u.id')
        ->select('uo.idultrasonido_obstetrico','uo.fecha','uo.iddoctor','d.name as Doctor','d.especialidad','uo.idpaciente','p.nombre as Paciente','uo.idusuario','u.name as Usuario','u.tipo_usuario','uo.spp','uo.fcardiaca_fetal','pubicacion','liquido_amniotico','utero_anexos','cervix','diametro_biparietal_medida','diametro_biparietal_semanas','circunferencia_cefalica_medida','circunferencia_cefalica_semanas','circunferencia_abdominal_medida','circunferencia_abdominal_semanas','longitud_femoral_medida','longitud_femoral_semanas','fetometria','peso_estimado','percentilo','comentarios','interpretacion','recomendaciones','observaciones','embarazo_unico','embarazo_unico_comentar','alteraciones_crecimiento','alteraciones_crecimiento_comentar','alteraciones_frecuencia','alteraciones_frecuencia_comentar','placenta','placenta_comentar','liquido','liquido_comentar','prematuro','prematuro_comentar')
        ->where('uo.idultrasonido_obstetrico','=',$idultrasonido_obstetrico) 
        ->first();

        if (input::hasfile('imagen'))
        {
            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $fechaultrasonidoImg = date("d-m-Y", strtotime($hoy));

            //Guardar archivo de imagen y obtener nombre unico
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);
            $file=Input::file('imagen');
            $file->move(public_path().'/imagenes/ultrasonidos/',$generar_codigo_imagen.$file->getClientOriginalName());

            //Guardamos imagen en base de datos
            $ultrasonidoImg=new UltrasonidoObstetricoImg;
            $ultrasonidoImg->idultrasonido_obstetrico=$request->get('idultrasonido_obstetrico');
            $ultrasonidoImg->imagen=$generar_codigo_imagen.$file->getClientOriginalName();
            $ultrasonidoImg->descripcion=$request->get('descripcion');
            $ultrasonidoImg->fecha=$hoy;
            $ultrasonidoImg->save();

            $cli=DB::table('paciente')->where('idpaciente','=',$ultrasonido->idpaciente)->first();
        
            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se agrego una imagen de un ultrasonido obstetrico para el paciente:".$cli->nombre.", Fecha: ".$fechaultrasonidoImg;
            $bitacora->save();

            $request->session()->flash('alert-success', 'Se agrego correctamente la imagen de ultrasonido obstetrico.');
        }else
        {
            $request->session()->flash('alert-danger', 'No agrego correctamente la imagen de ultrasonido obstetrico, seleccione una e intente de nuevo.');
        }

        $ultrasonidoimgs=DB::table('ultrasonido_obstetrico_img')
        ->where('idultrasonido_obstetrico','=',$idultrasonido_obstetrico) 
        ->get();

        return view("pacientes.historiales.ultrasonidos.imagenes.index",["paciente"=>Paciente::findOrFail($ultrasonido->idpaciente),"ultrasonido"=>$ultrasonido,"ultrasonidoimgs"=>$ultrasonidoimgs]);
    }

    public function edit($id)
    {
        $ultrasonidoimg=DB::table('ultrasonido_obstetrico_img')
        ->where('idultrasonido_obstetrico_img','=',$id) 
        ->first();

        $ultrasonido=DB::table('ultrasonido_obstetrico as uo')
        ->join('paciente as p','uo.idpaciente','=','p.idpaciente')
        ->join('users as d','uo.iddoctor','=','d.id')
        ->join('users as u','uo.idusuario','=','u.id')
        ->select('uo.idultrasonido_obstetrico','uo.fecha','uo.iddoctor','d.name as Doctor','d.especialidad','uo.idpaciente','p.nombre as Paciente','uo.idusuario','u.name as Usuario','u.tipo_usuario','uo.spp','uo.fcardiaca_fetal','pubicacion','liquido_amniotico','utero_anexos','cervix','diametro_biparietal_medida','diametro_biparietal_semanas','circunferencia_cefalica_medida','circunferencia_cefalica_semanas','circunferencia_abdominal_medida','circunferencia_abdominal_semanas','longitud_femoral_medida','longitud_femoral_semanas','fetometria','peso_estimado','percentilo','comentarios','interpretacion','recomendaciones','observaciones','embarazo_unico','embarazo_unico_comentar','alteraciones_crecimiento','alteraciones_crecimiento_comentar','alteraciones_frecuencia','alteraciones_frecuencia_comentar','placenta','placenta_comentar','liquido','liquido_comentar','prematuro','prematuro_comentar')
        ->where('uo.idultrasonido_obstetrico','=',$ultrasonidoimg->idultrasonido_obstetrico) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$ultrasonido->idpaciente)
        ->first();
        
       
        return view("pacientes.historiales.ultrasonidos.imagenes.edit",["ultrasonido"=>$ultrasonido,"paciente"=>$paciente,"ultrasonidoimg"=>$ultrasonidoimg]);
    }


    public function update(UltrasonidoObstetricoImgFormRequest $request,$id)
    {
        //obtenemos id de ultrasonido
        $idultrasonido_obstetrico = $request->get('idultrasonido_obstetrico');

        $ultrasonido=DB::table('ultrasonido_obstetrico as uo')
        ->join('paciente as p','uo.idpaciente','=','p.idpaciente')
        ->join('users as d','uo.iddoctor','=','d.id')
        ->join('users as u','uo.idusuario','=','u.id')
        ->select('uo.idultrasonido_obstetrico','uo.fecha','uo.iddoctor','d.name as Doctor','d.especialidad','uo.idpaciente','p.nombre as Paciente','uo.idusuario','u.name as Usuario','u.tipo_usuario','uo.spp','uo.fcardiaca_fetal','pubicacion','liquido_amniotico','utero_anexos','cervix','diametro_biparietal_medida','diametro_biparietal_semanas','circunferencia_cefalica_medida','circunferencia_cefalica_semanas','circunferencia_abdominal_medida','circunferencia_abdominal_semanas','longitud_femoral_medida','longitud_femoral_semanas','fetometria','peso_estimado','percentilo','comentarios','interpretacion','recomendaciones','observaciones','embarazo_unico','embarazo_unico_comentar','alteraciones_crecimiento','alteraciones_crecimiento_comentar','alteraciones_frecuencia','alteraciones_frecuencia_comentar','placenta','placenta_comentar','liquido','liquido_comentar','prematuro','prematuro_comentar')
        ->where('uo.idultrasonido_obstetrico','=',$idultrasonido_obstetrico) 
        ->first();

        if (input::hasfile('imagen')){
            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $fechaultrasonidoImg = date("d-m-Y", strtotime($hoy));

            //guardamos y obtenemos nombre unico de imagen
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);
            $file=Input::file('imagen');
            $file->move(public_path().'/imagenes/ultrasonidos/',$generar_codigo_imagen.$file->getClientOriginalName());
            //Guardamos nombre de imagen en base de datos
            $ultrasonidoImg=UltrasonidoObstetricoImg::findOrFail($id);
            $ultrasonidoImg->imagen=$generar_codigo_imagen.$file->getClientOriginalName();
            $ultrasonidoImg->descripcion=$request->get('descripcion');
            $ultrasonidoImg->update();

            $cli=DB::table('paciente')->where('idpaciente','=',$ultrasonido->idpaciente)->first();
        
            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se edito una imagen de una ultrasonido para el paciente:".$cli->nombre.", Fecha: ".$fechaultrasonidoImg;
            $bitacora->save();

            $request->session()->flash('alert-success', 'Se edito correctamente una imagen de ultrasonido.');
        }else
        {
            $request->session()->flash('alert-success', 'No se pudo editar la imagen Intentelo de nuevo.');
        }
        

        $ultrasonidoimgs=DB::table('ultrasonido_obstetrico_img')
        ->where('idultrasonido_obstetrico','=',$idultrasonido_obstetrico) 
        ->get();

        return view("pacientes.historiales.ultrasonidos.imagenes.index",["paciente"=>Paciente::findOrFail($ultrasonido->idpaciente),"ultrasonido"=>$ultrasonido,"ultrasonidoimgs"=>$ultrasonidoimgs]);
    }

    public function eliminarimagen(Request $request)
    {
        $idultrasonido_obstetrico = $request->get('idultrasonido_obstetrico');
        $idpaciente = $request->get('idpaciente');
        $idultrasonido_obstetricoimg = $request->get('idultrasonido_obstetrico_img');
        
        $eliminarimagen=UltrasonidoObstetricoImg::findOrFail($idultrasonido_obstetricoimg);
        $eliminarimagen->delete();

        $request->session()->flash('alert-success', 'Se elimino la imagen de ultrasonido.');  
        
        $ultrasonido=DB::table('ultrasonido_obstetrico as uo')
        ->join('paciente as p','uo.idpaciente','=','p.idpaciente')
        ->join('users as d','uo.iddoctor','=','d.id')
        ->join('users as u','uo.idusuario','=','u.id')
        ->select('uo.idultrasonido_obstetrico','uo.fecha','uo.iddoctor','d.name as Doctor','d.especialidad','uo.idpaciente','p.nombre as Paciente','uo.idusuario','u.name as Usuario','u.tipo_usuario','uo.spp','uo.fcardiaca_fetal','pubicacion','liquido_amniotico','utero_anexos','cervix','diametro_biparietal_medida','diametro_biparietal_semanas','circunferencia_cefalica_medida','circunferencia_cefalica_semanas','circunferencia_abdominal_medida','circunferencia_abdominal_semanas','longitud_femoral_medida','longitud_femoral_semanas','fetometria','peso_estimado','percentilo','comentarios','interpretacion','recomendaciones','observaciones')
        ->where('uo.idultrasonido_obstetrico','=',$idultrasonido_obstetrico) 
        ->first();

        $ultrasonidoimgs=DB::table('ultrasonido_obstetrico_img')
        ->where('idultrasonido_obstetrico','=',$idultrasonido_obstetrico) 
        ->get();

        return view("pacientes.historiales.ultrasonidos.imagenes.index",["paciente"=>Paciente::findOrFail($ultrasonido->idpaciente),"ultrasonido"=>$ultrasonido,"ultrasonidoimgs"=>$ultrasonidoimgs]);
    }
}
