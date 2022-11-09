<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\Incontinenciau;
use sisVentasWeb\Incontinenciau_cuestionario;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\IncontinenciauFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class IncontinenciauController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $idpaciente=trim($request->get('searchidpaciente'));
        $incontinencias=DB::table('incontinenciau as i')
        ->join('paciente as p','i.idpaciente','=','p.idpaciente')
        ->join('users as d','i.iddoctor','=','d.id')
        ->join('users as u','i.idusuario','=','u.id')
        ->select('i.idincontinenciau','i.fecha','i.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','i.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','i.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('i.idpaciente','=',$idpaciente) 
        ->orderby('i.fecha','desc')
        ->paginate(20);
        
        

        return view("pacientes.historiales.incontinencias.index",["paciente"=>Paciente::findOrFail($idpaciente),"incontinencias"=>$incontinencias]);
    }

    public function store (IncontinenciauFormRequest $request)
    {
    	try
    	{ 
            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $hoy = $hoy->format('d-m-Y');
            
            $fechaIncontinenciau=trim($hoy);
            $fecha = date("Y-m-d", strtotime($fechaIncontinenciau));

            $iddoctor=$request->get('iddoctor');
            $idpaciente=$request->get('idpaciente');
            $idusuario=$request->get('idusuario');

    		DB::beginTransaction();

            $incontinencia=new Incontinenciau;
            $incontinencia->fecha=$fecha;
            $incontinencia->iddoctor=$iddoctor;
            $incontinencia->idpaciente=$idpaciente;
            $incontinencia->idusuario=$idusuario;
    		$incontinencia->save();
            
            $cli=DB::table('paciente')->where('idpaciente','=',$incontinencia->idpaciente)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se creo un nuevo estudio de incontinencia urinaria para el paciente:".$cli->nombre.", Fecha: ".$fechaIncontinenciau;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}

    	
        $incontinencias=DB::table('incontinenciau as i')
        ->join('paciente as p','i.idpaciente','=','p.idpaciente')
        ->join('users as d','i.iddoctor','=','d.id')
        ->join('users as u','i.idusuario','=','u.id')
        ->select('i.idincontinenciau','i.fecha','i.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','i.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','i.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('i.idpaciente','=',$idpaciente) 
        ->orderby('i.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.incontinencias.index",["paciente"=>Paciente::findOrFail($idpaciente),"incontinencias"=>$incontinencias]);
    }

    public function show($id)
    {
        $incontinencia=DB::table('incontinenciau as i')
        ->join('paciente as p','i.idpaciente','=','p.idpaciente')
        ->join('users as d','i.iddoctor','=','d.id')
        ->join('users as u','i.idusuario','=','u.id')
        ->select('i.idincontinenciau','i.fecha','i.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','i.idpaciente','p.sexo','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','i.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('i.idincontinenciau','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$incontinencia->idpaciente)
        ->first();

        $cuestionarios=DB::table('incontinenciau_cuestionario')
        ->where('idincontinenciau','=',$incontinencia->idincontinenciau)
        ->get();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$incontinencia->idpaciente)
        ->first();

        return view("pacientes.historiales.incontinencias.show",["incontinencia"=>$incontinencia,"paciente"=>$paciente,"cuestionarios"=>$cuestionarios, "historia"=>$historia]);
    }

    public function eliminarincontinencia(Request $request)
    {
        $idincontinencia = $request->get('idincontinenciau');
        $idpaciente = $request->get('idpaciente');

        $eliminarcuestionarios=Incontinenciau_cuestionario::where('idincontinenciau',$idincontinencia)->delete();
        
        $eliminarincontinencia=Incontinenciau::findOrFail($idincontinencia);
        $eliminarincontinencia->delete();

        $request->session()->flash('alert-success', 'Se elimino el estudio de incontinencia urinaria.');  
        
        
        $incontinencias=DB::table('incontinenciau as i')
        ->join('paciente as p','i.idpaciente','=','p.idpaciente')
        ->join('users as d','i.iddoctor','=','d.id')
        ->join('users as u','i.idusuario','=','u.id')
        ->select('i.idincontinenciau','i.fecha','i.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','i.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','i.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('i.idpaciente','=',$idpaciente) 
        ->orderby('i.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.incontinencias.index",["paciente"=>Paciente::findOrFail($idpaciente),"incontinencias"=>$incontinencias]);
    }
}
