<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Climaymeno;
use sisVentasWeb\Paciente;
use sisVentasWeb\ClimaymenoImg;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\PacienteFormRequest;
use sisVentasWeb\Http\Requests\ClimaymenoImgFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class ClimaymenoImgController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $idclimaymeno=trim($request->get('searchidclimaymeno'));
        $climaymeno=DB::table('climaymeno as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idclimaymeno','c.fecha','c.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','c.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('c.idclimaymeno','=',$idclimaymeno) 
        ->orderby('c.fecha','desc')
        ->first();

        $climaymenoimgs=DB::table('climaymeno_img')
        ->where('idclimaymeno','=',$idclimaymeno) 
        ->get();

        return view("pacientes.historiales.climaymenos.imagenes.index",["paciente"=>Paciente::findOrFail($climaymeno->idpaciente),"climaymeno"=>$climaymeno,"climaymenoimgs"=>$climaymenoimgs]);
    }

    public function store (ClimaymenoImgFormRequest $request)
    {
        //obtenemos id de fisico imagen
        $idclimaymeno = $request->get('idclimaymeno');

        $climaymeno=DB::table('climaymeno as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idclimaymeno','c.fecha','c.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','c.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('c.idclimaymeno','=',$idclimaymeno) 
        ->orderby('c.fecha','desc')
        ->first();

        if (input::hasfile('imagen'))
        {
            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $fechaClimaymenoImg = date("d-m-Y", strtotime($hoy));

            //Guardar archivo de imagen y obtener nombre unico
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);
            $file=Input::file('imagen');
            $file->move(public_path().'/imagenes/climaymenos/',$generar_codigo_imagen.$file->getClientOriginalName());

            //Guardamos imagen en base de datos
            $climaymenoImg=new ClimaymenoImg;
            $climaymenoImg->idclimaymeno=$request->get('idclimaymeno');
            $climaymenoImg->imagen=$generar_codigo_imagen.$file->getClientOriginalName();
            $climaymenoImg->descripcion=$request->get('descripcion');
            $climaymenoImg->fecha=$hoy;
            $climaymenoImg->save();

            $cli=DB::table('paciente')->where('idpaciente','=',$climaymeno->idpaciente)->first();
        
            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se agrego una imagen de climaterio y menopausea para el paciente:".$cli->nombre.", Fecha: ".$fechaClimaymenoImg;
            $bitacora->save();

            $request->session()->flash('alert-success', 'Se agrego correctamente la imagen de climaterio y menopausea.');
        }else
        {
            $request->session()->flash('alert-danger', 'No agrego correctamente la imagen de climaterio y menopausea, seleccione una e intente de nuevo.');
        }

        $climaymenoimgs=DB::table('climaymeno_img')
        ->where('idclimaymeno','=',$idclimaymeno) 
        ->get();

        return view("pacientes.historiales.climaymenos.imagenes.index",["paciente"=>Paciente::findOrFail($climaymeno->idpaciente),"climaymeno"=>$climaymeno,"climaymenoimgs"=>$climaymenoimgs]);
    }

    public function edit($id)
    {
        $climaymenoimg=DB::table('climaymeno_img')
        ->where('idclimaymeno_img','=',$id) 
        ->first();

        $climaymeno=DB::table('climaymeno as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idclimaymeno','c.fecha','c.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','c.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('c.idclimaymeno','=',$climaymenoimg->idclimaymeno) 
        ->orderby('c.fecha','desc')
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$climaymeno->idpaciente)
        ->first();
        
       
        return view("pacientes.historiales.climaymenos.imagenes.edit",["climaymeno"=>$climaymeno,"paciente"=>$paciente,"climaymenoimg"=>$climaymenoimg]);
    }


    public function update(ClimaymenoImgFormRequest $request,$id)
    {
        //obtenemos id de fisico
        $idclimaymeno = $request->get('idclimaymeno');

        $climaymeno=DB::table('climaymeno as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idclimaymeno','c.fecha','c.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','c.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('c.idclimaymeno','=',$idclimaymeno) 
        ->orderby('c.fecha','desc')
        ->first();

        if (input::hasfile('imagen')){
            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $fechaClimaymenoImg = date("d-m-Y", strtotime($hoy));

            //guardamos y obtenemos nombre unico de imagen
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);
            $file=Input::file('imagen');
            $file->move(public_path().'/imagenes/climaymenos/',$generar_codigo_imagen.$file->getClientOriginalName());
            //Guardamos nombre de imagen en base de datos
            $climaymenoImg=ClimaymenoImg::findOrFail($id);
            $climaymenoImg->imagen=$generar_codigo_imagen.$file->getClientOriginalName();
            $climaymenoImg->descripcion=$request->get('descripcion');
            $climaymenoImg->update();

            $cli=DB::table('paciente')->where('idpaciente','=',$climaymeno->idpaciente)->first();
        
            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se edito una imagen de un climaymeno para el paciente:".$cli->nombre.", Fecha: ".$fechaClimaymenoImg;
            $bitacora->save();

            $request->session()->flash('alert-success', 'Se edito correctamente una imagen de climaterio y menopausea.');
        }else
        {
            $request->session()->flash('alert-success', 'No se pudo editar la imagen Intentelo de nuevo.');
        }
        

        $climaymenoimgs=DB::table('climaymeno_img')
        ->where('idclimaymeno','=',$idclimaymeno) 
        ->get();

        return view("pacientes.historiales.climaymenos.imagenes.index",["paciente"=>Paciente::findOrFail($climaymeno->idpaciente),"climaymeno"=>$climaymeno,"climaymenoimgs"=>$climaymenoimgs]);
    }

    public function eliminarimagen(Request $request)
    {
        $idclimaymeno = $request->get('idclimaymeno');
        $idpaciente = $request->get('idpaciente');
        $idclimaymenoimg = $request->get('idclimaymenoimg');
        
        $eliminarimagen=ClimaymenoImg::findOrFail($idclimaymenoimg);
        $eliminarimagen->delete();

        $request->session()->flash('alert-success', 'Se elimino la imagen de climaymeno.');  
        
        $climaymeno=DB::table('climaymeno as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idclimaymeno','c.fecha','c.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','c.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('c.idclimaymeno','=',$idclimaymeno) 
        ->orderby('c.fecha','desc')
        ->first();

        $climaymenoimgs=DB::table('climaymeno_img')
        ->where('idclimaymeno','=',$idclimaymeno) 
        ->get();

        return view("pacientes.historiales.climaymenos.imagenes.index",["paciente"=>Paciente::findOrFail($climaymeno->idpaciente),"climaymeno"=>$climaymeno,"climaymenoimgs"=>$climaymenoimgs]);
    }
}
