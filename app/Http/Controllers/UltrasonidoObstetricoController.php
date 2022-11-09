<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\UltrasonidoObstetrico;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\PacienteFormRequest;
use sisVentasWeb\Http\Requests\UltrasonidoObstetricoFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class UltrasonidoObstetricoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $idpaciente=trim($request->get('searchidpaciente'));
        $ultrasonidos=DB::table('ultrasonido_obstetrico as uo')
        ->join('paciente as p','uo.idpaciente','=','p.idpaciente')
        ->join('users as d','uo.iddoctor','=','d.id')
        ->join('users as u','uo.idusuario','=','u.id')
        ->select('uo.idultrasonido_obstetrico','uo.fecha','uo.iddoctor','d.name as Doctor','d.especialidad','uo.idpaciente','p.nombre as Paciente','uo.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('uo.idpaciente','=',$idpaciente) 
        ->orderby('uo.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.ultrasonidos.index",["paciente"=>Paciente::findOrFail($idpaciente),"ultrasonidos"=>$ultrasonidos]);
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

        $historia = DB::table('historia')
        ->where('idpaciente','=',$idpaciente)
        ->first();

    	return view("pacientes.historiales.ultrasonidos.create",["doctor"=>$doctor,"paciente"=>$paciente,"historia"=>$historia]);
    }

    public function store (UltrasonidoObstetricoFormRequest $request)
    {
    	try
    	{ 
            $fechaultrasonido=trim($request->get('fecha'));
            $fecha = date("Y-m-d", strtotime($fechaultrasonido));
            $iddoctor=$request->get('iddoctor');
            $idpaciente=$request->get('idpaciente');
            $idusuario=$request->get('idusuario');

            $embarazo_unico=$request->get('embarazo_unico');
            $alteraciones_crecimiento=$request->get('alteraciones_crecimiento');
            $alteraciones_frecuencia=$request->get('alteraciones_frecuencia');
            $placenta=$request->get('placenta');
            $liquido=$request->get('liquido');
            $prematuro=$request->get('prematuro');
            $observaciones=$request->get('observaciones');

            //verificando valores de checks
            if($embarazo_unico == null){$embarazo_unico = 0;}
            if($alteraciones_crecimiento == null){$alteraciones_crecimiento = 0;}
            if($alteraciones_frecuencia == null){$alteraciones_frecuencia = 0;}
            if($placenta == null){$placenta = 0;}
            if($liquido == null){$liquido = 0;}
            if($prematuro == null){$prematuro = 0;}
            if($observaciones == null){$observaciones = 0;}

            

    		DB::beginTransaction();

            $ultrasonido=new UltrasonidoObstetrico;
            $ultrasonido->fecha=$fecha;
            $ultrasonido->iddoctor=$iddoctor;
            $ultrasonido->idpaciente=$idpaciente;
            $ultrasonido->idusuario=$idusuario;
            
            $ultrasonido->spp=$request->get('spp');
            $ultrasonido->fcardiaca_fetal=$request->get('fcardiaca_fetal');
            $ultrasonido->pubicacion=$request->get('pubicacion');
            $ultrasonido->liquido_amniotico=$request->get('liquido_amniotico');
            $ultrasonido->utero_anexos=$request->get('utero_anexos');
            $ultrasonido->cervix=$request->get('cervix');
            $ultrasonido->diametro_biparietal_medida=$request->get('diametro_biparietal_medida');
            $ultrasonido->diametro_biparietal_semanas=$request->get('diametro_biparietal_semanas');
            $ultrasonido->circunferencia_cefalica_medida=$request->get('circunferencia_cefalica_medida');
            $ultrasonido->circunferencia_cefalica_semanas=$request->get('circunferencia_cefalica_semanas');
            $ultrasonido->circunferencia_abdominal_medida=$request->get('circunferencia_abdominal_medida');
            $ultrasonido->circunferencia_abdominal_semanas=$request->get('circunferencia_abdominal_semanas');
            $ultrasonido->longitud_femoral_medida=$request->get('longitud_femoral_medida');
            $ultrasonido->longitud_femoral_semanas=$request->get('longitud_femoral_semanas');
            $ultrasonido->fetometria=$request->get('fetometria');
            $ultrasonido->peso_estimado=$request->get('peso_estimado');
            $ultrasonido->percentilo=$request->get('percentilo');
            $ultrasonido->comentarios=$request->get('comentarios');
            $ultrasonido->interpretacion=$request->get('interpretacion');
            $ultrasonido->recomendaciones=$request->get('recomendaciones');
            $ultrasonido->observaciones=$observaciones;
            $ultrasonido->embarazo_unico=$embarazo_unico;
            $ultrasonido->embarazo_unico_comentar=$request->get('embarazo_unico_comentar');
            $ultrasonido->alteraciones_crecimiento=$alteraciones_crecimiento;
            $ultrasonido->alteraciones_crecimiento_comentar=$request->get('alteraciones_crecimiento_comentar');
            $ultrasonido->alteraciones_frecuencia=$alteraciones_frecuencia;
            $ultrasonido->alteraciones_frecuencia_comentar=$request->get('alteraciones_frecuencia_comentar');
            $ultrasonido->placenta=$placenta;
            $ultrasonido->placenta_comentar=$request->get('placenta_comentar');
            $ultrasonido->liquido=$liquido;
            $ultrasonido->liquido_comentar=$request->get('liquido_comentar');
            $ultrasonido->prematuro=$prematuro;
            $ultrasonido->prematuro_comentar=$request->get('prematuro_comentar');
            
    		$ultrasonido->save();

            $cli=DB::table('paciente')->where('idpaciente','=',$ultrasonido->idpaciente)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se creo un ultrasonido obstetrico para el paciente:".$cli->nombre.", Fecha: ".$fechaultrasonido;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}


    	$idpaciente=$idpaciente;
        $ultrasonidos=DB::table('ultrasonido_obstetrico as uo')
        ->join('paciente as p','uo.idpaciente','=','p.idpaciente')
        ->join('users as d','uo.iddoctor','=','d.id')
        ->join('users as u','uo.idusuario','=','u.id')
        ->select('uo.idultrasonido_obstetrico','uo.fecha','uo.iddoctor','d.name as Doctor','d.especialidad','uo.idpaciente','p.nombre as Paciente','uo.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('uo.idpaciente','=',$idpaciente) 
        ->orderby('uo.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.ultrasonidos.index",["paciente"=>Paciente::findOrFail($idpaciente),"ultrasonidos"=>$ultrasonidos]);
    }

    public function show($id)
    {
        $ultrasonido=DB::table('ultrasonido_obstetrico as uo')
        ->join('paciente as p','uo.idpaciente','=','p.idpaciente')
        ->join('users as d','uo.iddoctor','=','d.id')
        ->join('users as u','uo.idusuario','=','u.id')
        ->select('uo.idultrasonido_obstetrico','uo.fecha','uo.iddoctor','d.name as Doctor','d.especialidad','uo.idpaciente','p.nombre as Paciente','uo.idusuario','u.name as Usuario','u.tipo_usuario','uo.spp','uo.fcardiaca_fetal','pubicacion','liquido_amniotico','utero_anexos','cervix','diametro_biparietal_medida','diametro_biparietal_semanas','circunferencia_cefalica_medida','circunferencia_cefalica_semanas','circunferencia_abdominal_medida','circunferencia_abdominal_semanas','longitud_femoral_medida','longitud_femoral_semanas','fetometria','peso_estimado','percentilo','comentarios','interpretacion','recomendaciones','observaciones','embarazo_unico','embarazo_unico_comentar','alteraciones_crecimiento','alteraciones_crecimiento_comentar','alteraciones_frecuencia','alteraciones_frecuencia_comentar','placenta','placenta_comentar','liquido','liquido_comentar','prematuro','prematuro_comentar')
        ->where('uo.idultrasonido_obstetrico','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$ultrasonido->idpaciente)
        ->first();
        
        $ultrasonidoimgs=DB::table('ultrasonido_obstetrico_img')
        ->where('idultrasonido_obstetrico','=',$id) 
        ->get();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$ultrasonido->idpaciente)
        ->first();

        return view("pacientes.historiales.ultrasonidos.show",["ultrasonido"=>$ultrasonido,"paciente"=>$paciente,"ultrasonidoimgs"=>$ultrasonidoimgs,"historia"=>$historia]);
    }

    public function edit($id)
    {
        $ultrasonido=DB::table('ultrasonido_obstetrico as uo')
        ->join('paciente as p','uo.idpaciente','=','p.idpaciente')
        ->join('users as d','uo.iddoctor','=','d.id')
        ->join('users as u','uo.idusuario','=','u.id')
        ->select('uo.idultrasonido_obstetrico','uo.fecha','uo.iddoctor','d.name as Doctor','d.especialidad','uo.idpaciente','p.nombre as Paciente','uo.idusuario','u.name as Usuario','u.tipo_usuario','uo.spp','uo.fcardiaca_fetal','pubicacion','liquido_amniotico','utero_anexos','cervix','diametro_biparietal_medida','diametro_biparietal_semanas','circunferencia_cefalica_medida','circunferencia_cefalica_semanas','circunferencia_abdominal_medida','circunferencia_abdominal_semanas','longitud_femoral_medida','longitud_femoral_semanas','fetometria','peso_estimado','percentilo','comentarios','interpretacion','recomendaciones','observaciones','embarazo_unico','embarazo_unico_comentar','alteraciones_crecimiento','alteraciones_crecimiento_comentar','alteraciones_frecuencia','alteraciones_frecuencia_comentar','placenta','placenta_comentar','liquido','liquido_comentar','prematuro','prematuro_comentar')
        ->where('uo.idultrasonido_obstetrico','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$ultrasonido->idpaciente)
        ->first();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$ultrasonido->idpaciente)
        ->first();
        
       
        return view("pacientes.historiales.ultrasonidos.edit",["ultrasonido"=>$ultrasonido,"paciente"=>$paciente,"historia"=>$historia]);
    }


    public function update(UltrasonidoObstetricoFormRequest $request,$id)
    {
        $fechaultrasonido=trim($request->get('fecha'));
        $fecha = date("Y-m-d", strtotime($fechaultrasonido));
        $iddoctor=$request->get('iddoctor');
        $idpaciente=$request->get('idpaciente');
        $idusuario=$request->get('idusuario');

        $embarazo_unico=$request->get('embarazo_unico');
        $alteraciones_crecimiento=$request->get('alteraciones_crecimiento');
        $alteraciones_frecuencia=$request->get('alteraciones_frecuencia');
        $placenta=$request->get('placenta');
        $liquido=$request->get('liquido');
        $prematuro=$request->get('prematuro');
        $observaciones=$request->get('observaciones');

        //verificando valores de checks
        if($embarazo_unico == null){$embarazo_unico = 0;}
        if($alteraciones_crecimiento == null){$alteraciones_crecimiento = 0;}
        if($alteraciones_frecuencia == null){$alteraciones_frecuencia = 0;}
        if($placenta == null){$placenta = 0;}
        if($liquido == null){$liquido = 0;}
        if($prematuro == null){$prematuro = 0;}
        if($observaciones == null){$observaciones = 0;}

        
        $ultrasonido=UltrasonidoObstetrico::findOrFail($id);
        $ultrasonido->fecha=$fecha;
        $ultrasonido->iddoctor=$iddoctor;
        $ultrasonido->idpaciente=$idpaciente;
        $ultrasonido->idusuario=$idusuario;
            
        $ultrasonido->spp=$request->get('spp');
        $ultrasonido->fcardiaca_fetal=$request->get('fcardiaca_fetal');
        $ultrasonido->pubicacion=$request->get('pubicacion');
        $ultrasonido->liquido_amniotico=$request->get('liquido_amniotico');
        $ultrasonido->utero_anexos=$request->get('utero_anexos');
        $ultrasonido->cervix=$request->get('cervix');
        $ultrasonido->diametro_biparietal_medida=$request->get('diametro_biparietal_medida');
        $ultrasonido->diametro_biparietal_semanas=$request->get('diametro_biparietal_semanas');
        $ultrasonido->circunferencia_cefalica_medida=$request->get('circunferencia_cefalica_medida');
        $ultrasonido->circunferencia_cefalica_semanas=$request->get('circunferencia_cefalica_semanas');
        $ultrasonido->circunferencia_abdominal_medida=$request->get('circunferencia_abdominal_medida');
        $ultrasonido->circunferencia_abdominal_semanas=$request->get('circunferencia_abdominal_semanas');
        $ultrasonido->longitud_femoral_medida=$request->get('longitud_femoral_medida');
        $ultrasonido->longitud_femoral_semanas=$request->get('longitud_femoral_semanas');
        $ultrasonido->fetometria=$request->get('fetometria');
        $ultrasonido->peso_estimado=$request->get('peso_estimado');
        $ultrasonido->percentilo=$request->get('percentilo');
        $ultrasonido->comentarios=$request->get('comentarios');
        $ultrasonido->interpretacion=$request->get('interpretacion');
        $ultrasonido->recomendaciones=$request->get('recomendaciones');
        $ultrasonido->observaciones=$observaciones;
        $ultrasonido->embarazo_unico=$embarazo_unico;
        $ultrasonido->embarazo_unico_comentar=$request->get('embarazo_unico_comentar');
        $ultrasonido->alteraciones_crecimiento=$alteraciones_crecimiento;
        $ultrasonido->alteraciones_crecimiento_comentar=$request->get('alteraciones_crecimiento_comentar');
        $ultrasonido->alteraciones_frecuencia=$alteraciones_frecuencia;
        $ultrasonido->alteraciones_frecuencia_comentar=$request->get('alteraciones_frecuencia_comentar');
        $ultrasonido->placenta=$placenta;
        $ultrasonido->placenta_comentar=$request->get('placenta_comentar');
        $ultrasonido->liquido=$liquido;
        $ultrasonido->liquido_comentar=$request->get('liquido_comentar');
        $ultrasonido->prematuro=$prematuro;
        $ultrasonido->prematuro_comentar=$request->get('prematuro_comentar');
        $ultrasonido->update();

        $request->session()->flash('alert-success', 'Se edito correctamente un ultrasonido obstetrico.');

        $cli=DB::table('paciente')->where('idpaciente','=',$idpaciente)->first();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Paciente";
        $bitacora->descripcion="Se edito un ultrasonido obstetrico del paciente:".$cli->nombre.", Fecha: ".$fechaultrasonido;
        $bitacora->save();

        $idpaciente=$idpaciente;

        $ultrasonido=DB::table('ultrasonido_obstetrico as uo')
        ->join('paciente as p','uo.idpaciente','=','p.idpaciente')
        ->join('users as d','uo.iddoctor','=','d.id')
        ->join('users as u','uo.idusuario','=','u.id')
        ->select('uo.idultrasonido_obstetrico','uo.fecha','uo.iddoctor','d.name as Doctor','d.especialidad','uo.idpaciente','p.nombre as Paciente','uo.idusuario','u.name as Usuario','u.tipo_usuario','uo.spp','uo.fcardiaca_fetal','pubicacion','liquido_amniotico','utero_anexos','cervix','diametro_biparietal_medida','diametro_biparietal_semanas','circunferencia_cefalica_medida','circunferencia_cefalica_semanas','circunferencia_abdominal_medida','circunferencia_abdominal_semanas','longitud_femoral_medida','longitud_femoral_semanas','fetometria','peso_estimado','percentilo','comentarios','interpretacion','recomendaciones','observaciones','embarazo_unico','embarazo_unico_comentar','alteraciones_crecimiento','alteraciones_crecimiento_comentar','alteraciones_frecuencia','alteraciones_frecuencia_comentar','placenta','placenta_comentar','liquido','liquido_comentar','prematuro','prematuro_comentar')
        ->where('uo.idultrasonido_obstetrico','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$idpaciente)
        ->first();

        $ultrasonidoimgs=DB::table('ultrasonido_obstetrico_img')
        ->where('idultrasonido_obstetrico','=',$id) 
        ->get();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$ultrasonido->idpaciente)
        ->first();

        return view("pacientes.historiales.ultrasonidos.show",["ultrasonido"=>$ultrasonido,"paciente"=>$paciente,"ultrasonidoimgs"=>$ultrasonidoimgs,"historia"=>$historia]);
    }

    public function eliminarultrasonido(Request $request)
    {
        $idultrasonido_obstetrico = $request->get('idultrasonido_obstetrico');
        $idpaciente = $request->get('idpaciente');
        
        $eliminarultrasonido=UltrasonidoObstetrico::findOrFail($idultrasonido_obstetrico);
        $eliminarultrasonido->delete();

        $request->session()->flash('alert-success', 'Se elimino la ultrasonido.');  
        
        $idpaciente=$idpaciente;
        $ultrasonidos=DB::table('ultrasonido_obstetrico as uo')
        ->join('paciente as p','uo.idpaciente','=','p.idpaciente')
        ->join('users as d','uo.iddoctor','=','d.id')
        ->join('users as u','uo.idusuario','=','u.id')
        ->select('uo.idultrasonido_obstetrico','uo.fecha','uo.iddoctor','d.name as Doctor','d.especialidad','uo.idpaciente','p.nombre as Paciente','uo.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('uo.idpaciente','=',$idpaciente) 
        ->orderby('uo.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.ultrasonidos.index",["paciente"=>Paciente::findOrFail($idpaciente),"ultrasonidos"=>$ultrasonidos]);
    }
}
