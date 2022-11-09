<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\Receta;
use sisVentasWeb\DetalleReceta;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\PacienteFormRequest;
use sisVentasWeb\Http\Requests\RecetaFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class RecetaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $idpaciente=trim($request->get('searchidpaciente'));
        $recetas=DB::table('receta as r')
        ->join('paciente as p','r.idpaciente','=','p.idpaciente')
        ->join('users as d','r.iddoctor','=','d.id')
        ->join('users as u','r.idusuario','=','u.id')
        ->select('r.idreceta','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgdpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('r.idpaciente','=',$idpaciente) 
        ->orderby('r.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.recetas.index",["paciente"=>Paciente::findOrFail($idpaciente),"recetas"=>$recetas]);
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

        $presentaciones=DB::table('presentacion')
            ->where('estado','=','Habilitado')
            ->orderBy('nombre','asc')
            ->get();

    	return view("pacientes.historiales.recetas.create",["presentaciones"=>$presentaciones,"doctor"=>$doctor,"paciente"=>$paciente]);
    }

    public function store (RecetaFormRequest $request)
    {
    	try
    	{ 
            $fechaReceta=trim($request->get('fecha'));
            $fecha = date("Y-m-d", strtotime($fechaReceta));
            $iddoctor=$request->get('iddoctor');
            $idpaciente=$request->get('idpaciente');
            $idusuario=$request->get('idusuario');

    		DB::beginTransaction();

            $receta=new Receta;
            $receta->fecha=$fecha;
            $receta->iddoctor=$iddoctor;
            $receta->idpaciente=$idpaciente;
            $receta->idusuario=$idusuario;
    		$receta->save();


    		$cantidad = $request->get('cantidad');
            $idpresentacion = $request->get('presentacion');
    		$medicamento = $request->get('medicamento');
    		$indicaciones = $request->get('indicaciones');

    		$cont = 0;

    		while ($cont < count($medicamento)) 
    		{
    			$detalle = new DetalleReceta();
    			$detalle->idreceta=$receta->idreceta;
    			$detalle->cantidad=$cantidad[$cont];
                $detalle->presentacion=$idpresentacion[$cont];
                $detalle->medicamento=$medicamento[$cont];
    			$detalle->indicaciones=$indicaciones[$cont];
    			$detalle->save();

    			$cont=$cont+1;	
            }
            
            $cli=DB::table('paciente')->where('idpaciente','=',$receta->idpaciente)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se creo una nueva receta para el paciente:".$cli->nombre.", Fecha: ".$fechaReceta;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}

    	$idpaciente=$cli->idpaciente;
        $recetas=DB::table('receta as r')
        ->join('paciente as p','r.idpaciente','=','p.idpaciente')
        ->join('users as d','r.iddoctor','=','d.id')
        ->join('users as u','r.idusuario','=','u.id')
        ->select('r.idreceta','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('r.idpaciente','=',$idpaciente) 
        ->orderby('r.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.recetas.index",["paciente"=>Paciente::findOrFail($idpaciente),"recetas"=>$recetas]);
    }

    public function show($id)
    {
        $receta=DB::table('receta as r')
        ->join('paciente as p','r.idpaciente','=','p.idpaciente')
        ->join('users as d','r.iddoctor','=','d.id')
        ->join('users as u','r.idusuario','=','u.id')
        ->select('r.idreceta','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('r.idreceta','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$receta->idpaciente)
        ->first();

        return view("pacientes.historiales.recetas.show",["receta"=>$receta,"paciente"=>$paciente]);
    }

    public function quitar(Request $request)
    {
        $idreceta_medicamento = $request->get('idreceta_medicamento');
        $idreceta = $request->get('idreceta');
        $idpaciente = $request->get('idpaciente');

        $medicamento=DetalleReceta::findOrFail($idreceta_medicamento);
        $medicamento->delete();

        $request->session()->flash('alert-success', 'Se a quitado el medicamento.');  
        
        $receta=DB::table('receta as r')
        ->join('paciente as p','r.idpaciente','=','p.idpaciente')
        ->join('users as d','r.iddoctor','=','d.id')
        ->join('users as u','r.idusuario','=','u.id')
        ->select('r.idreceta','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('r.idreceta','=',$idreceta) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$idpaciente)
        ->first();

        return view("pacientes.historiales.recetas.show",["receta"=>$receta,"paciente"=>$paciente]);
    }

    public function editarmedicamento(Request $request)
    {
        $idreceta_medicamento = $request->get('idreceta_medicamento');
        $idreceta = $request->get('idreceta');
        $idpaciente = $request->get('idpaciente');
        $cantidad = $request->get('cantidad');
        $presentacion = $request->get('presentacion');
        $medicamento = $request->get('medicamento');
        $indicaciones = $request->get('indicaciones');

        $editarmedicamento=DetalleReceta::findOrFail($idreceta_medicamento);
        $editarmedicamento->cantidad=$cantidad;
        $editarmedicamento->presentacion=$presentacion;
        $editarmedicamento->medicamento=$medicamento;
        $editarmedicamento->indicaciones=$indicaciones;
        $editarmedicamento->update();

        $request->session()->flash('alert-success', 'Se a editado el medicamento.');  
        
        $receta=DB::table('receta as r')
        ->join('paciente as p','r.idpaciente','=','p.idpaciente')
        ->join('users as d','r.iddoctor','=','d.id')
        ->join('users as u','r.idusuario','=','u.id')
        ->select('r.idreceta','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('r.idreceta','=',$idreceta) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$idpaciente)
        ->first();

        return view("pacientes.historiales.recetas.show",["receta"=>$receta,"paciente"=>$paciente]);
    }

    public function agregar(Request $request)
    {
        $idreceta = $request->get('idreceta');
        $idpaciente = $request->get('idpaciente');
        $cantidad = $request->get('cantidad');
        $presentacion = $request->get('presentacion');
        $medicamento = $request->get('medicamento');
        $indicaciones = $request->get('indicaciones');

        $detalle = new DetalleReceta();
    	$detalle->idreceta=$idreceta;
    	$detalle->cantidad=$cantidad;
        $detalle->presentacion=$presentacion;
        $detalle->medicamento=$medicamento;
    	$detalle->indicaciones=$indicaciones;
    	$detalle->save();

        $request->session()->flash('alert-success', 'Se agrego el medicamento.');  
        
        $receta=DB::table('receta as r')
        ->join('paciente as p','r.idpaciente','=','p.idpaciente')
        ->join('users as d','r.iddoctor','=','d.id')
        ->join('users as u','r.idusuario','=','u.id')
        ->select('r.idreceta','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('r.idreceta','=',$idreceta) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$idpaciente)
        ->first();

        return view("pacientes.historiales.recetas.show",["receta"=>$receta,"paciente"=>$paciente]);
    }

    public function eliminarreceta(Request $request)
    {
        $idreceta = $request->get('idreceta');
        $idpaciente = $request->get('idpaciente');

        $eliminarmedicamentos=DetalleReceta::where('idreceta',$idreceta)->delete();
        
        $eliminarreceta=Receta::findOrFail($idreceta);
        $eliminarreceta->delete();

        $request->session()->flash('alert-success', 'Se elimino la receta.');  
        
        

        return view("pacientes.historiales.show",["paciente"=>Paciente::findOrFail($idpaciente)]);
    }
}
