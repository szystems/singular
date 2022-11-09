<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\Embarazo;
use sisVentasWeb\Control;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\EmbarazoFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class EmbarazoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $idpaciente=trim($request->get('searchidpaciente'));
        $embarazos=DB::table('embarazo as e')
        ->join('paciente as p','e.idpaciente','=','p.idpaciente')
        ->join('users as d','e.iddoctor','=','d.id')
        ->join('users as u','e.idusuario','=','u.id')
        ->select('e.idembarazo','e.fecha','e.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','e.idpaciente','p.nombre as Paciente','p.foto as Imgdpaciente','e.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('e.idpaciente','=',$idpaciente) 
        ->orderby('e.fecha','desc')
        ->paginate(20);
        
        

        return view("pacientes.historiales.embarazos.index",["paciente"=>Paciente::findOrFail($idpaciente),"embarazos"=>$embarazos]);
    }

    public function store (EmbarazoFormRequest $request)
    {
    	try
    	{ 
            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $hoy = $hoy->format('d-m-Y');
            
            $fechaEmbarazo=trim($hoy);
            $fecha = date("Y-m-d", strtotime($fechaEmbarazo));

            $iddoctor=$request->get('iddoctor');
            $idpaciente=$request->get('idpaciente');
            $idusuario=$request->get('idusuario');
            $fur = date("Y-m-d", strtotime($request->get('fur')));

    		DB::beginTransaction();

            $embarazo=new Embarazo;
            $embarazo->fecha=$fecha;
            $embarazo->iddoctor=$iddoctor;
            $embarazo->idpaciente=$idpaciente;
            $embarazo->idusuario=$idusuario;
            $embarazo->fur=$fur;
    		$embarazo->save();
            
            $cli=DB::table('paciente')->where('idpaciente','=',$embarazo->idpaciente)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se creo un nuevo embarazo para el paciente:".$cli->nombre.", Fecha: ".$fechaEmbarazo;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}

    	
        $embarazos=DB::table('embarazo as e')
        ->join('paciente as p','e.idpaciente','=','p.idpaciente')
        ->join('users as d','e.iddoctor','=','d.id')
        ->join('users as u','e.idusuario','=','u.id')
        ->select('e.idembarazo','e.fecha','e.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','e.idpaciente','p.nombre as Paciente','p.foto as Imgdpaciente','e.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('e.idpaciente','=',$idpaciente) 
        ->orderby('e.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.embarazos.index",["paciente"=>Paciente::findOrFail($idpaciente),"embarazos"=>$embarazos]);
    }

    public function show($id)
    {
        $embarazo=DB::table('embarazo as e')
        ->join('paciente as p','e.idpaciente','=','p.idpaciente')
        ->join('users as d','e.iddoctor','=','d.id')
        ->join('users as u','e.idusuario','=','u.id')
        ->select('e.idembarazo','e.fecha','e.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','e.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','e.idusuario','u.name as Usuario','u.tipo_usuario','e.fur','e.trimestre1','e.trimestre2','e.trimestre3')
        ->where('e.idembarazo','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$embarazo->idpaciente)
        ->first();

        $controles=DB::table('control')
        ->where('idembarazo','=',$embarazo->idembarazo)
        ->get();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$embarazo->idpaciente)
        ->first();

        $embarazoimgs=DB::table('embarazo_img')
        ->where('idembarazo','=',$id) 
        ->get();

        return view("pacientes.historiales.embarazos.show",["embarazo"=>$embarazo,"paciente"=>$paciente,"controles"=>$controles,"historia"=>$historia,"embarazoimgs"=>$embarazoimgs]);
    }

    public function edit($id)
    {
        $embarazo=DB::table('embarazo as e')
        ->join('paciente as p','e.idpaciente','=','p.idpaciente')
        ->join('users as d','e.iddoctor','=','d.id')
        ->join('users as u','e.idusuario','=','u.id')
        ->select('e.idembarazo','e.fecha','e.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','e.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','e.idusuario','u.name as Usuario','u.tipo_usuario','e.fur','e.trimestre1','e.trimestre2','e.trimestre3')
        ->where('e.idembarazo','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$embarazo->idpaciente)
        ->first();
        
       
        return view("pacientes.historiales.embarazos.edit",["embarazo"=>$embarazo,"paciente"=>$paciente]);
    }

    public function update(EmbarazoFormRequest $request,$id)
    {
        $idpaciente=$request->get('idpaciente');
        $id = $request->get('idembarazo');
        

        //$zona_horaria = Auth::user()->zona_horaria;
        $fechaEmbarazo = $request->get('fecha');
        $fecha = date("Y-m-d", strtotime($fechaEmbarazo));
        $fur = date("Y-m-d", strtotime($request->get('fur')));
        

        $embarazo=Embarazo::findOrFail($id);
        $embarazo->fur=$fur;
        $embarazo->trimestre1=$request->get('trimestre1');
        $embarazo->trimestre2=$request->get('trimestre2');
        $embarazo->trimestre3=$request->get('trimestre3');
        $embarazo->save();

        $cli=DB::table('paciente')->where('idpaciente','=',$embarazo->idpaciente)->first();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Pacientes";
        $bitacora->descripcion="Se edito la cabecera de embarazo del paciente: ".$cli->nombre.", Fecha: ".$fechaEmbarazo;
        $bitacora->save();

        $request->session()->flash('alert-success', "Se edito la cabecera de embarazo del paciente: ".$cli->nombre.", Fecha: ".$fechaEmbarazo);

        $embarazo=DB::table('embarazo as e')
        ->join('paciente as p','e.idpaciente','=','p.idpaciente')
        ->join('users as d','e.iddoctor','=','d.id')
        ->join('users as u','e.idusuario','=','u.id')
        ->select('e.idembarazo','e.fecha','e.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','e.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','e.idusuario','u.name as Usuario','u.tipo_usuario','e.fur','e.trimestre1','e.trimestre2','e.trimestre3')
        ->where('e.idembarazo','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$embarazo->idpaciente)
        ->first();

        $controles=DB::table('control')
        ->where('idembarazo','=',$embarazo->idembarazo)
        ->get();

        $embarazoimgs=DB::table('embarazo_img')
        ->where('idembarazo','=',$embarazo->idembarazo) 
        ->get();

        return view("pacientes.historiales.embarazos.show",["embarazo"=>$embarazo,"paciente"=>$paciente,"controles"=>$controles,"embarazoimgs"=>$embarazoimgs]);
    }

    public function eliminarembarazo(Request $request)
    {
        $idembarazo = $request->get('idembarazo');
        $idpaciente = $request->get('idpaciente');

        $eliminarcontroles=Control::where('idembarazo',$idembarazo)->delete();
        
        $eliminarembarazo=Embarazo::findOrFail($idembarazo);
        $eliminarembarazo->delete();

        $request->session()->flash('alert-success', 'Se elimino el embarazo.');  
        
        
        $embarazos=DB::table('embarazo as e')
        ->join('paciente as p','e.idpaciente','=','p.idpaciente')
        ->join('users as d','e.iddoctor','=','d.id')
        ->join('users as u','e.idusuario','=','u.id')
        ->select('e.idembarazo','e.fecha','e.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','e.idpaciente','p.nombre as Paciente','p.foto as Imgdpaciente','e.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('e.idpaciente','=',$idpaciente) 
        ->orderby('e.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.embarazos.index",["paciente"=>Paciente::findOrFail($idpaciente),"embarazos"=>$embarazos]);
    }
}
