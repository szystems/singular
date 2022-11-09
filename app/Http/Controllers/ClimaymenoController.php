<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\Climaymeno;
use sisVentasWeb\ClimaymenoControl;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\ClimaymenoFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class ClimaymenoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $idpaciente=trim($request->get('searchidpaciente'));
        $climaymenos=DB::table('climaymeno as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idclimaymeno','c.fecha','c.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','c.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('c.idpaciente','=',$idpaciente) 
        ->orderby('c.fecha','desc')
        ->paginate(20);
        
        

        return view("pacientes.historiales.climaymenos.index",["paciente"=>Paciente::findOrFail($idpaciente),"climaymenos"=>$climaymenos]);
    }

    public function store (ClimaymenoFormRequest $request)
    {
    	try
    	{ 
            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $hoy = $hoy->format('d-m-Y');
            
            $fechaClimaymeno=trim($hoy);
            $fecha = date("Y-m-d", strtotime($fechaClimaymeno));

            $iddoctor=$request->get('iddoctor');
            $idpaciente=$request->get('idpaciente');
            $idusuario=$request->get('idusuario');

    		DB::beginTransaction();

            $climaymeno=new Climaymeno;
            $climaymeno->fecha=$fecha;
            $climaymeno->iddoctor=$iddoctor;
            $climaymeno->idpaciente=$idpaciente;
            $climaymeno->idusuario=$idusuario;
    		$climaymeno->save();
            
            $cli=DB::table('paciente')->where('idpaciente','=',$climaymeno->idpaciente)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se creo un nuevo estudio de climaterio y menopausea para el paciente:".$cli->nombre.", Fecha: ".$fechaClimaymeno;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}

    	
        $climaymenos=DB::table('climaymeno as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idclimaymeno','c.fecha','c.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','c.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('c.idpaciente','=',$idpaciente) 
        ->orderby('c.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.climaymenos.index",["paciente"=>Paciente::findOrFail($idpaciente),"climaymenos"=>$climaymenos]);
    }

    public function show($id)
    {
        $climaymeno=DB::table('climaymeno as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idclimaymeno','c.fecha','c.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','c.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('c.idclimaymeno','=',$id) 
        ->orderby('c.fecha','desc')
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$climaymeno->idpaciente)
        ->first();

        $controles=DB::table('climaymeno_control')
        ->where('idclimaymeno','=',$climaymeno->idclimaymeno)
        ->get();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$climaymeno->idpaciente)
        ->first();

        $climaymenoimgs=DB::table('climaymeno_img')
        ->where('idclimaymeno','=',$climaymeno->idclimaymeno) 
        ->get();

        return view("pacientes.historiales.climaymenos.show",["climaymeno"=>$climaymeno,"paciente"=>$paciente,"controles"=>$controles, "historia"=>$historia,"climaymenoimgs"=>$climaymenoimgs]);
    }

    public function eliminarclimaymeno(Request $request)
    {
        $idclimaymeno = $request->get('idclimaymeno');
        $idpaciente = $request->get('idpaciente');

        $eliminarcontroles=ClimaymenoControl::where('idclimaymeno',$idclimaymeno)->delete();
        
        $eliminarclimaymeno=Climaymeno::findOrFail($idclimaymeno);
        $eliminarclimaymeno->delete();

        $request->session()->flash('alert-success', 'Se elimino el estudio de climaterio y embarazo.');  
        
        
        $climaymenos=DB::table('climaymeno as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idclimaymeno','c.fecha','c.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','c.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('c.idpaciente','=',$idpaciente) 
        ->orderby('c.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.climaymenos.index",["paciente"=>Paciente::findOrFail($idpaciente),"climaymenos"=>$climaymenos]);
    }
}
