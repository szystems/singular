<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Embarazo;
use sisVentasWeb\Paciente;
use sisVentasWeb\EmbarazoImg;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\PacienteFormRequest;
use sisVentasWeb\Http\Requests\EmbarazoImgFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class EmbarazoImgController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $idembarazo=trim($request->get('searchidembarazo'));
        $embarazo=DB::table('embarazo as e')
        ->join('paciente as p','e.idpaciente','=','p.idpaciente')
        ->join('users as d','e.iddoctor','=','d.id')
        ->join('users as u','e.idusuario','=','u.id')
        ->select('e.idembarazo','e.fecha','e.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','e.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','e.idusuario','u.name as Usuario','u.tipo_usuario','e.fur','e.trimestre1','e.trimestre2','e.trimestre3')
        ->where('e.idembarazo','=',$idembarazo) 
        ->first();

        $embarazoimgs=DB::table('embarazo_img')
        ->where('idembarazo','=',$idembarazo) 
        ->get();

        return view("pacientes.historiales.embarazos.imagenes.index",["paciente"=>Paciente::findOrFail($embarazo->idpaciente),"embarazo"=>$embarazo,"embarazoimgs"=>$embarazoimgs]);
    }

    public function store (EmbarazoImgFormRequest $request)
    {
        //obtenemos id de fisico imagen
        $idembarazo = $request->get('idembarazo');

        $embarazo=DB::table('embarazo as e')
        ->join('paciente as p','e.idpaciente','=','p.idpaciente')
        ->join('users as d','e.iddoctor','=','d.id')
        ->join('users as u','e.idusuario','=','u.id')
        ->select('e.idembarazo','e.fecha','e.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','e.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','e.idusuario','u.name as Usuario','u.tipo_usuario','e.fur','e.trimestre1','e.trimestre2','e.trimestre3')
        ->where('e.idembarazo','=',$idembarazo) 
        ->first();

        if (input::hasfile('imagen'))
        {
            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $fechaEmbarazoImg = date("d-m-Y", strtotime($hoy));

            //Guardar archivo de imagen y obtener nombre unico
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);
            $file=Input::file('imagen');
            $file->move(public_path().'/imagenes/embarazos/',$generar_codigo_imagen.$file->getClientOriginalName());

            //Guardamos imagen en base de datos
            $embarazoImg=new EmbarazoImg;
            $embarazoImg->idembarazo=$request->get('idembarazo');
            $embarazoImg->imagen=$generar_codigo_imagen.$file->getClientOriginalName();
            $embarazoImg->descripcion=$request->get('descripcion');
            $embarazoImg->fecha=$hoy;
            $embarazoImg->save();

            $cli=DB::table('paciente')->where('idpaciente','=',$embarazo->idpaciente)->first();
        
            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se agrego una imagen de un embarazo para el paciente:".$cli->nombre.", Fecha: ".$fechaEmbarazoImg;
            $bitacora->save();

            $request->session()->flash('alert-success', 'Se agrego correctamente la imagen del embarazo.');
        }else
        {
            $request->session()->flash('alert-danger', 'No agrego correctamente la imagen del embarazo, seleccione una e intente de nuevo.');
        }

        $embarazoimgs=DB::table('embarazo_img')
        ->where('idembarazo','=',$idembarazo) 
        ->get();

        return view("pacientes.historiales.embarazos.imagenes.index",["paciente"=>Paciente::findOrFail($embarazo->idpaciente),"embarazo"=>$embarazo,"embarazoimgs"=>$embarazoimgs]);
    }

    public function edit($id)
    {
        $embarazoimg=DB::table('embarazo_img')
        ->where('idembarazo_img','=',$id) 
        ->first();

        $embarazo=DB::table('embarazo as e')
        ->join('paciente as p','e.idpaciente','=','p.idpaciente')
        ->join('users as d','e.iddoctor','=','d.id')
        ->join('users as u','e.idusuario','=','u.id')
        ->select('e.idembarazo','e.fecha','e.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','e.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','e.idusuario','u.name as Usuario','u.tipo_usuario','e.fur','e.trimestre1','e.trimestre2','e.trimestre3')
        ->where('e.idembarazo','=',$embarazoimg->idembarazo) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$embarazo->idpaciente)
        ->first();
        
       
        return view("pacientes.historiales.embarazos.imagenes.edit",["embarazo"=>$embarazo,"paciente"=>$paciente,"embarazoimg"=>$embarazoimg]);
    }


    public function update(EmbarazoImgFormRequest $request,$id)
    {
        //obtenemos id de fisico
        $idembarazo = $request->get('idembarazo');

        $embarazo=DB::table('embarazo as e')
        ->join('paciente as p','e.idpaciente','=','p.idpaciente')
        ->join('users as d','e.iddoctor','=','d.id')
        ->join('users as u','e.idusuario','=','u.id')
        ->select('e.idembarazo','e.fecha','e.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','e.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','e.idusuario','u.name as Usuario','u.tipo_usuario','e.fur','e.trimestre1','e.trimestre2','e.trimestre3')
        ->where('e.idembarazo','=',$idembarazo) 
        ->first();

        if (input::hasfile('imagen')){
            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $fechaEmbarazoImg = date("d-m-Y", strtotime($hoy));

            //guardamos y obtenemos nombre unico de imagen
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);
            $file=Input::file('imagen');
            $file->move(public_path().'/imagenes/embarazos/',$generar_codigo_imagen.$file->getClientOriginalName());
            //Guardamos nombre de imagen en base de datos
            $embarazoImg=EmbarazoImg::findOrFail($id);
            $embarazoImg->imagen=$generar_codigo_imagen.$file->getClientOriginalName();
            $embarazoImg->descripcion=$request->get('descripcion');
            $embarazoImg->update();

            $cli=DB::table('paciente')->where('idpaciente','=',$embarazo->idpaciente)->first();
        
            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se edito una imagen de un embarazo para el paciente:".$cli->nombre.", Fecha: ".$fechaEmbarazoImg;
            $bitacora->save();

            $request->session()->flash('alert-success', 'Se edito correctamente una imagen del embarazo.');
        }else
        {
            $request->session()->flash('alert-success', 'No se pudo editar la imagen Intentelo de nuevo.');
        }
        

        $embarazoimgs=DB::table('embarazo_img')
        ->where('idembarazo','=',$idembarazo) 
        ->get();

        return view("pacientes.historiales.embarazos.imagenes.index",["paciente"=>Paciente::findOrFail($embarazo->idpaciente),"embarazo"=>$embarazo,"embarazoimgs"=>$embarazoimgs]);
    }

    public function eliminarimagen(Request $request)
    {
        $idembarazo = $request->get('idembarazo');
        $idpaciente = $request->get('idpaciente');
        $idembarazoimg = $request->get('idembarazoimg');
        
        $eliminarimagen=EmbarazoImg::findOrFail($idembarazoimg);
        $eliminarimagen->delete();

        $request->session()->flash('alert-success', 'Se elimino la imagen de embarazo.');  
        
        $embarazo=DB::table('embarazo as e')
        ->join('paciente as p','e.idpaciente','=','p.idpaciente')
        ->join('users as d','e.iddoctor','=','d.id')
        ->join('users as u','e.idusuario','=','u.id')
        ->select('e.idembarazo','e.fecha','e.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','e.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','e.idusuario','u.name as Usuario','u.tipo_usuario','e.fur','e.trimestre1','e.trimestre2','e.trimestre3')
        ->where('e.idembarazo','=',$idembarazo) 
        ->first();

        $embarazoimgs=DB::table('embarazo_img')
        ->where('idembarazo','=',$idembarazo) 
        ->get();

        return view("pacientes.historiales.embarazos.imagenes.index",["paciente"=>Paciente::findOrFail($embarazo->idpaciente),"embarazo"=>$embarazo,"embarazoimgs"=>$embarazoimgs]);
    }
}
