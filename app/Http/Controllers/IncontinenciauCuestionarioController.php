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
use sisVentasWeb\Http\Requests\IncontinenciauCuestionarioFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class IncontinenciauCuestionarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        $idincontinencia = $request->idincontinenciau;

        $incontinencia=DB::table('incontinenciau as i')
        ->join('paciente as p','i.idpaciente','=','p.idpaciente')
        ->join('users as d','i.iddoctor','=','d.id')
        ->join('users as u','i.idusuario','=','u.id')
        ->select('i.idincontinenciau','i.fecha','i.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','i.idpaciente','p.sexo','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','i.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('i.idincontinenciau','=',$idincontinencia) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$incontinencia->idpaciente)
        ->first(); 

        $historia = DB::table('historia')
        ->where('idpaciente','=',$incontinencia->idpaciente)
        ->first();

    	return view("pacientes.historiales.incontinencias.cuestionarios.create",["paciente"=>$paciente,"incontinencia"=>$incontinencia, "historia"=>$historia]);
    }

    public function store (IncontinenciauCuestionarioFormRequest $request)
    {
    	try
    	{ 
            
            $fechaCuestionario=trim($request->get('fecha'));
            $fecha = date("Y-m-d", strtotime($fechaCuestionario));

            $idpaciente=$request->get('idpaciente');
            $idincontinencia=$request->get('idincontinenciau');

            //numero_control
            $ultimoCuestionario = DB::table('incontinenciau_cuestionario')
            ->where('idincontinenciau','=',$idincontinencia)
            ->max('numero_cuestionario');
            if(isset($ultimoCuestionario))
            {
                $numero_cuestionario = $ultimoCuestionario + 1;
            }else
            {
                $numero_cuestionario = 1;
            }

            $incontinencia=DB::table('incontinenciau as i')
            ->join('paciente as p','i.idpaciente','=','p.idpaciente')
            ->join('users as d','i.iddoctor','=','d.id')
            ->join('users as u','i.idusuario','=','u.id')
            ->select('i.idincontinenciau','i.fecha','i.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','i.idpaciente','p.sexo','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','i.idusuario','u.name as Usuario','u.tipo_usuario')
            ->where('i.idincontinenciau','=',$idincontinencia) 
            ->first();

            //obtener valores para la sumatoria de puntuacion
            $frecuencia=$request->get('frecuencia');
            $cantidad=$request->get('cantidad');
            $medida=$request->get('medida');
            if($request->get('nunca'))
            {
                $nunca=$request->get('nunca');
            }
            else
            {
                $nunca=0;
            }

            if($request->get('antes_servicio'))
            {
                $antes_servicio=$request->get('antes_servicio');
            }
            else
            {
                $antes_servicio=0;
            }

            if($request->get('toser'))
            {
                $toser=$request->get('toser');
            }
            else
            {
                $toser=0;
            }

            if($request->get('duerme'))
            {
                $duerme=$request->get('duerme');
            }
            else
            {
                $duerme=0;
            }

            if($request->get('esfuerzos'))
            {
                $esfuerzos=$request->get('esfuerzos');
            }
            else
            {
                $esfuerzos=0;
            }

            if($request->get('termina'))
            {
                $termina=$request->get('termina');
            }
            else
            {
                $termina=0;
            }

            if($request->get('sinmotivo'))
            {
                $sinmotivo=$request->get('sinmotivo');
            }
            else
            {
                $sinmotivo=0;
            }

            if($request->get('continua'))
            {
                $continua=$request->get('continua');
            }
            else
            {
                $continua=0;
            }

            $puntuacion = $frecuencia + $cantidad + $medida + $nunca + $antes_servicio + $toser + $duerme + $esfuerzos + $termina + $sinmotivo + $continua;

            
    		DB::beginTransaction();

            $cuestionario=new Incontinenciau_cuestionario;
            $cuestionario->idincontinenciau=$request->get('idincontinenciau');
            $cuestionario->numero_cuestionario=$numero_cuestionario;
            $cuestionario->fecha=$fecha;

            $cuestionario->frecuencia=$frecuencia;
            $cuestionario->cantidad=$cantidad;
            $cuestionario->medida=$medida;
            $cuestionario->nunca=$nunca;
            $cuestionario->antes_servicio=$antes_servicio;
            $cuestionario->toser=$toser;
            $cuestionario->duerme=$duerme;
            $cuestionario->esfuerzos=$esfuerzos;
            $cuestionario->termina=$termina;
            $cuestionario->sinmotivo=$sinmotivo;
            $cuestionario->continua=$continua;
            $cuestionario->puntuacion=$puntuacion;
    		$cuestionario->save();
            
            $cli=DB::table('paciente')->where('idpaciente','=',$incontinencia->idpaciente)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se creo un nuevo cuestionario de incontinencia urinaria del paciente:".$cli->nombre.", Fecha: ".$fechaCuestionario;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$incontinencia->idpaciente)
        ->first();

        $cuestionarios=DB::table('incontinenciau_cuestionario')
        ->where('idincontinenciau','=',$incontinencia->idincontinenciau)
        ->get();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$incontinencia->idpaciente)
        ->first();

        $request->session()->flash('alert-success', "Se creo un cuestionario de incontinencia urinaria del paciente: ".$cli->nombre.", Fecha: ".$fechaCuestionario);

        return view("pacientes.historiales.incontinencias.show",["incontinencia"=>$incontinencia,"paciente"=>$paciente,"cuestionarios"=>$cuestionarios, "historia"=>$historia]);
    }

    public function edit($id)
    {
        $cuestionario=DB::table('incontinenciau_cuestionario')
        ->where('idincontinenciau_cuestionario','=',$id)
        ->first();

        $incontinencia=DB::table('incontinenciau as i')
        ->join('paciente as p','i.idpaciente','=','p.idpaciente')
        ->join('users as d','i.iddoctor','=','d.id')
        ->join('users as u','i.idusuario','=','u.id')
        ->select('i.idincontinenciau','i.fecha','i.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','i.idpaciente','p.sexo','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','i.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('i.idincontinenciau','=',$cuestionario->idincontinenciau) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$incontinencia->idpaciente)
        ->first(); 

        $historia = DB::table('historia')
        ->where('idpaciente','=',$incontinencia->idpaciente)
        ->first();
        
       
        return view("pacientes.historiales.incontinencias.cuestionarios.edit",["cuestionario"=>$cuestionario,"incontinencia"=>$incontinencia,"paciente"=>$paciente, "historia"=>$historia]);
    }

    public function update(IncontinenciauCuestionarioFormRequest $request,$id)
    {
        $fechaCuestionario=trim($request->get('fecha'));
        $fecha = date("Y-m-d", strtotime($fechaCuestionario));

        $idpaciente=$request->get('idpaciente');
        $idincontinencia=$request->get('idincontinenciau');

        //numero_control
        $ultimoCuestionario = DB::table('incontinenciau_cuestionario')
        ->where('idincontinenciau','=',$idincontinencia)
        ->max('numero_cuestionario');
        if(isset($ultimoCuestionario))
        {
            $numero_cuestionario = $ultimoCuestionario + 1;
        }else
        {
            $numero_cuestionario = 1;
        }

        $incontinencia=DB::table('incontinenciau as i')
        ->join('paciente as p','i.idpaciente','=','p.idpaciente')
        ->join('users as d','i.iddoctor','=','d.id')
        ->join('users as u','i.idusuario','=','u.id')
        ->select('i.idincontinenciau','i.fecha','i.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','i.idpaciente','p.sexo','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','i.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('i.idincontinenciau','=',$idincontinencia) 
        ->first();

        //obtener valores para la sumatoria de puntuacion
        $frecuencia=$request->get('frecuencia');
        $cantidad=$request->get('cantidad');
        $medida=$request->get('medida');
        if($request->get('nunca'))
        {
            $nunca=$request->get('nunca');
        }
        else
        {
            $nunca=0;
        }

        if($request->get('antes_servicio'))
        {
            $antes_servicio=$request->get('antes_servicio');
        }
        else
        {
            $antes_servicio=0;
        }

        if($request->get('toser'))
        {
            $toser=$request->get('toser');
        }
        else
        {
            $toser=0;
        }

        if($request->get('duerme'))
        {
            $duerme=$request->get('duerme');
        }
        else
        {
            $duerme=0;
        }

        if($request->get('esfuerzos'))
        {
            $esfuerzos=$request->get('esfuerzos');
        }
        else
        {
            $esfuerzos=0;
        }

        if($request->get('termina'))
        {
            $termina=$request->get('termina');
        }
        else
        {
            $termina=0;
        }

        if($request->get('sinmotivo'))
        {
            $sinmotivo=$request->get('sinmotivo');
        }
        else
        {
            $sinmotivo=0;
        }

        if($request->get('continua'))
        {
            $continua=$request->get('continua');
        }
        else
        {
            $continua=0;
        }

        $puntuacion = $frecuencia + $cantidad + $medida + $nunca + $antes_servicio + $toser + $duerme + $esfuerzos + $termina + $sinmotivo + $continua;
        

        $cuestionario=Incontinenciau_cuestionario::findOrFail($id);
        $cuestionario->frecuencia=$frecuencia;
        $cuestionario->cantidad=$cantidad;
        $cuestionario->medida=$medida;
        $cuestionario->nunca=$nunca;
        $cuestionario->antes_servicio=$antes_servicio;
        $cuestionario->toser=$toser;
        $cuestionario->duerme=$duerme;
        $cuestionario->esfuerzos=$esfuerzos;
        $cuestionario->termina=$termina;
        $cuestionario->sinmotivo=$sinmotivo;
        $cuestionario->continua=$continua;
        $cuestionario->puntuacion=$puntuacion;
    	$cuestionario->save();

        $cli=DB::table('paciente')->where('idpaciente','=',$incontinencia->idpaciente)->first();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Paciente";
        $bitacora->descripcion="Se edito un cuestionario de incontinencia urinaria para el paciente:".$cli->nombre.", Fecha: ".$fechaCuestionario;
        $bitacora->save();

        $request->session()->flash('alert-success', "Se edito un cuestionario de incontinencia urinaria del paciente: ".$cli->nombre.", Fecha: ".$fechaCuestionario);

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$incontinencia->idpaciente)
        ->first();

        $cuestionarios=DB::table('incontinenciau_cuestionario')
        ->where('idincontinenciau','=',$incontinencia->idincontinenciau)
        ->get();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$incontinencia->idpaciente)
        ->first();

        $request->session()->flash('alert-success', "Se edito un cuestionario de incontinencia urinaria del paciente: ".$cli->nombre.", Fecha: ".$fechaCuestionario);

        return view("pacientes.historiales.incontinencias.show",["incontinencia"=>$incontinencia,"paciente"=>$paciente,"cuestionarios"=>$cuestionarios, "historia"=>$historia]);
    }

    public function eliminarcuestionario(Request $request)
    {
        $idincontinencia = $request->get('idincontinenciau');
        $idpaciente = $request->get('idpaciente');
        $idcuestionario = $request->get('idcuestionario');
        
        $eliminarcuestionario=Incontinenciau_cuestionario::findOrFail($idcuestionario);
        $eliminarcuestionario->delete();

        $request->session()->flash('alert-success', 'Se elimino el cuestionario.');  
        
        
        $incontinencia=DB::table('incontinenciau as i')
        ->join('paciente as p','i.idpaciente','=','p.idpaciente')
        ->join('users as d','i.iddoctor','=','d.id')
        ->join('users as u','i.idusuario','=','u.id')
        ->select('i.idincontinenciau','i.fecha','i.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','i.idpaciente','p.sexo','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','i.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('i.idincontinenciau','=',$idincontinencia) 
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
}
