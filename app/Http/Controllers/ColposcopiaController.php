<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\Colposcopia;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\PacienteFormRequest;
use sisVentasWeb\Http\Requests\ColposcopiaFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class ColposcopiaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $idpaciente=trim($request->get('searchidpaciente'));
        $colposcopias=DB::table('colposcopia as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idcolposcopia','c.fecha','c.iddoctor','d.name as Doctor','d.especialidad','c.idpaciente','p.nombre as Paciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('c.idpaciente','=',$idpaciente) 
        ->orderby('c.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.colposcopias.index",["paciente"=>Paciente::findOrFail($idpaciente),"colposcopias"=>$colposcopias]);
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

    	return view("pacientes.historiales.colposcopias.create",["doctor"=>$doctor,"paciente"=>$paciente]);
    }

    public function store (ColposcopiaFormRequest $request)
    {
    	try
    	{ 
            $fechacolposcopia=trim($request->get('fecha'));
            $fecha = date("Y-m-d", strtotime($fechacolposcopia));
            $iddoctor=$request->get('iddoctor');
            $idpaciente=$request->get('idpaciente');
            $idusuario=$request->get('idusuario');

            //valores check
            $hd_eap=$request->get('hd_eap');
            $hd_eam=$request->get('hd_eam');
            $hd_leucoplasia=$request->get('hd_leucoplasia');
            $hd_punteando=$request->get('hd_punteando');
            $hd_mosaico=$request->get('hd_mosaico');
            $hd_vasos=$request->get('hd_vasos');
            $hd_area=$request->get('hd_area');
            $hd_otros=$request->get('hd_otros');

            $dcn_insatisfactoria=$request->get('dcn_insatisfactoria');
            $hallazgos_nomales=$request->get('hallazgos_nomales');
            $inflamacion_infeccion=$request->get('inflamacion_infeccion');
            $biopsia=$request->get('biopsia');
            $legrado=$request->get('legrado');

            //verificando valores de checks
            if($hd_eap == null){$hd_eap = 0;}
            if($hd_eam == null){$hd_eam = 0;}
            if($hd_leucoplasia == null){$hd_leucoplasia = 0;}
            if($hd_punteando == null){$hd_punteando = 0;}
            if($hd_mosaico == null){$hd_mosaico = 0;}
            if($hd_vasos == null){$hd_vasos = 0;}
            if($hd_area == null){$hd_area = 0;}
            if($hd_otros == null){$hd_otros = 0;}
            if($dcn_insatisfactoria == null){$dcn_insatisfactoria = 0;}
            if($hallazgos_nomales == null){$hallazgos_nomales = 0;}
            if($inflamacion_infeccion== null){$inflamacion_infeccion = 0;}
            if($biopsia == null){$biopsia = 0;}
            if($legrado == null){$legrado = 0;}
            

    		DB::beginTransaction();

            $colposcopia=new Colposcopia;
            $colposcopia->fecha=$fecha;
            $colposcopia->iddoctor=$iddoctor;
            $colposcopia->idpaciente=$idpaciente;
            $colposcopia->idusuario=$idusuario;
            
            $colposcopia->union_escamoso_cilindrica=$request->get('union_escamoso_cilindrica');
            $colposcopia->legrado_endocervical=$request->get('legrado_endocervical');
            $colposcopia->colposcopia_insatisfactoria=$request->get('colposcopia_insatisfactoria');
            $colposcopia->hd_eap=$hd_eap;
            $colposcopia->hd_eam=$hd_eam;
            $colposcopia->hd_leucoplasia=$hd_leucoplasia;
            $colposcopia->hd_punteando=$hd_punteando;
            $colposcopia->hd_mosaico=$hd_mosaico;
            $colposcopia->hd_vasos=$hd_vasos;
            $colposcopia->hd_area=$hd_area;
            $colposcopia->hd_otros=$hd_otros;
            $colposcopia->hd_otros_especificar=$request->get('hd_otros_especificar');
            $colposcopia->hallazgos_fuera=$request->get('hallazgos_fuera');
            $colposcopia->carcinoma_invasor=$request->get('carcinoma_invasor');
            $colposcopia->otros_hallazgos=$request->get('otros_hallazgos');
            $colposcopia->dcn_insatisfactoria=$dcn_insatisfactoria;
            $colposcopia->dcn_insatisfactoria_especifique=$request->get('dcn_insatisfactoria_especifique');
            $colposcopia->hallazgos_nomales=$hallazgos_nomales;
            $colposcopia->inflamacion_infeccion=$inflamacion_infeccion;
            $colposcopia->inflamacion_infeccion_especifique=$request->get('inflamacion_infeccion_especifique');
            $colposcopia->biopsia=$biopsia;
            $colposcopia->numero_localizacion=$request->get('numero_localizacion');
            $colposcopia->legrado=$legrado;
            $colposcopia->otros_hallazgos_colposcopicos=$request->get('otros_hallazgos_colposcopicos');
    		$colposcopia->save();

            $cli=DB::table('paciente')->where('idpaciente','=',$colposcopia->idpaciente)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se creo una colposcopia para el paciente:".$cli->nombre.", Fecha: ".$fechacolposcopia;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}


    	$idpaciente=$idpaciente;
        $colposcopias=DB::table('colposcopia as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idcolposcopia','c.fecha','c.iddoctor','d.name as Doctor','d.especialidad','c.idpaciente','p.nombre as Paciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('c.idpaciente','=',$idpaciente) 
        ->orderby('c.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.colposcopias.index",["paciente"=>Paciente::findOrFail($idpaciente),"colposcopias"=>$colposcopias]);
    }

    public function show($id)
    {
        $colposcopia=DB::table('colposcopia as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idcolposcopia','c.fecha','c.iddoctor','d.name as Doctor','d.especialidad','c.idpaciente','p.nombre as Paciente','c.idusuario','u.name as Usuario','u.tipo_usuario','c.union_escamoso_cilindrica','c.legrado_endocervical','colposcopia_insatisfactoria','hd_eap','hd_eam','hd_leucoplasia','hd_punteando','hd_mosaico','hd_vasos','hd_area','hd_otros','hd_otros_especificar','hallazgos_fuera','carcinoma_invasor','otros_hallazgos','dcn_insatisfactoria','dcn_insatisfactoria_especifique','hallazgos_nomales','inflamacion_infeccion','inflamacion_infeccion_especifique','biopsia','numero_localizacion','legrado','otros_hallazgos_colposcopicos')
        ->where('c.idcolposcopia','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$colposcopia->idpaciente)
        ->first();
        
        $colposcopiaimgs=DB::table('colposcopia_img')
        ->where('idcolposcopia','=',$id) 
        ->get();

        return view("pacientes.historiales.colposcopias.show",["colposcopia"=>$colposcopia,"paciente"=>$paciente,"colposcopiaimgs"=>$colposcopiaimgs]);
    }

    public function edit($id)
    {
        $colposcopia=DB::table('colposcopia as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idcolposcopia','c.fecha','c.iddoctor','d.name as Doctor','d.especialidad','c.idpaciente','p.nombre as Paciente','c.idusuario','u.name as Usuario','u.tipo_usuario','c.union_escamoso_cilindrica','c.legrado_endocervical','colposcopia_insatisfactoria','hd_eap','hd_eam','hd_leucoplasia','hd_punteando','hd_mosaico','hd_vasos','hd_area','hd_otros','hd_otros_especificar','hallazgos_fuera','carcinoma_invasor','otros_hallazgos','dcn_insatisfactoria','dcn_insatisfactoria_especifique','hallazgos_nomales','inflamacion_infeccion','inflamacion_infeccion_especifique','biopsia','numero_localizacion','legrado','otros_hallazgos_colposcopicos')
        ->where('c.idcolposcopia','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$colposcopia->idpaciente)
        ->first();
        
       
        return view("pacientes.historiales.colposcopias.edit",["colposcopia"=>$colposcopia,"paciente"=>$paciente]);
    }


    public function update(ColposcopiaFormRequest $request,$id)
    {
        $fechacolposcopia=trim($request->get('fecha'));
        $fecha = date("Y-m-d", strtotime($fechacolposcopia));
        $iddoctor=$request->get('iddoctor');
        $idpaciente=$request->get('idpaciente');
        $idusuario=$request->get('idusuario');

        //valores check
        $hd_eap=$request->get('hd_eap');
        $hd_eam=$request->get('hd_eam');
        $hd_leucoplasia=$request->get('hd_leucoplasia');
        $hd_punteando=$request->get('hd_punteando');
        $hd_mosaico=$request->get('hd_mosaico');
        $hd_vasos=$request->get('hd_vasos');
        $hd_area=$request->get('hd_area');
        $hd_otros=$request->get('hd_otros');

        $dcn_insatisfactoria=$request->get('dcn_insatisfactoria');
        $hallazgos_nomales=$request->get('hallazgos_nomales');
        $inflamacion_infeccion=$request->get('inflamacion_infeccion');
        $biopsia=$request->get('biopsia');
        $legrado=$request->get('legrado');

        //verificando valores de checks
        if($hd_eap == null){$hd_eap = 0;}
        if($hd_eam == null){$hd_eam = 0;}
        if($hd_leucoplasia == null){$hd_leucoplasia = 0;}
        if($hd_punteando == null){$hd_punteando = 0;}
        if($hd_mosaico == null){$hd_mosaico = 0;}
        if($hd_vasos == null){$hd_vasos = 0;}
        if($hd_area == null){$hd_area = 0;}
        if($hd_otros == null){$hd_otros = 0;}
        if($dcn_insatisfactoria == null){$dcn_insatisfactoria = 0;}
        if($hallazgos_nomales == null){$hallazgos_nomales = 0;}
        if($inflamacion_infeccion== null){$inflamacion_infeccion = 0;}
        if($biopsia == null){$biopsia = 0;}
        if($legrado == null){$legrado = 0;}

        
        $colposcopia=Colposcopia::findOrFail($id);
        $colposcopia->fecha=$fecha;
        $colposcopia->iddoctor=$iddoctor;
        $colposcopia->idpaciente=$idpaciente;
        $colposcopia->idusuario=$idusuario;
            
        $colposcopia->union_escamoso_cilindrica=$request->get('union_escamoso_cilindrica');
        $colposcopia->legrado_endocervical=$request->get('legrado_endocervical');
        $colposcopia->colposcopia_insatisfactoria=$request->get('colposcopia_insatisfactoria');
        $colposcopia->hd_eap=$hd_eap;
        $colposcopia->hd_eam=$hd_eam;
        $colposcopia->hd_leucoplasia=$hd_leucoplasia;
        $colposcopia->hd_punteando=$hd_punteando;
        $colposcopia->hd_mosaico=$hd_mosaico;
        $colposcopia->hd_vasos=$hd_vasos;
        $colposcopia->hd_area=$hd_area;
        $colposcopia->hd_otros=$hd_otros;
        $colposcopia->hd_otros_especificar=$request->get('hd_otros_especificar');
        $colposcopia->hallazgos_fuera=$request->get('hallazgos_fuera');
        $colposcopia->carcinoma_invasor=$request->get('carcinoma_invasor');
        $colposcopia->otros_hallazgos=$request->get('otros_hallazgos');
        $colposcopia->dcn_insatisfactoria=$dcn_insatisfactoria;
        $colposcopia->dcn_insatisfactoria_especifique=$request->get('dcn_insatisfactoria_especifique');
        $colposcopia->hallazgos_nomales=$hallazgos_nomales;
        $colposcopia->inflamacion_infeccion=$inflamacion_infeccion;
        $colposcopia->inflamacion_infeccion_especifique=$request->get('inflamacion_infeccion_especifique');
        $colposcopia->biopsia=$biopsia;
        $colposcopia->numero_localizacion=$request->get('numero_localizacion');
        $colposcopia->legrado=$legrado;
        $colposcopia->otros_hallazgos_colposcopicos=$request->get('otros_hallazgos_colposcopicos');
        $colposcopia->update();

        $request->session()->flash('alert-success', 'Se edito correctamente una colposcopia.');

        $cli=DB::table('paciente')->where('idpaciente','=',$idpaciente)->first();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Paciente";
        $bitacora->descripcion="Se edito una colposcopia del paciente:".$cli->nombre.", Fecha: ".$fechacolposcopia;
        $bitacora->save();

        $idpaciente=$idpaciente;

        $colposcopia=DB::table('colposcopia as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idcolposcopia','c.fecha','c.iddoctor','d.name as Doctor','d.especialidad','c.idpaciente','p.nombre as Paciente','c.idusuario','u.name as Usuario','u.tipo_usuario','c.union_escamoso_cilindrica','c.legrado_endocervical','colposcopia_insatisfactoria','hd_eap','hd_eam','hd_leucoplasia','hd_punteando','hd_mosaico','hd_vasos','hd_area','hd_otros','hd_otros_especificar','hallazgos_fuera','carcinoma_invasor','otros_hallazgos','dcn_insatisfactoria','dcn_insatisfactoria_especifique','hallazgos_nomales','inflamacion_infeccion','inflamacion_infeccion_especifique','biopsia','numero_localizacion','legrado','otros_hallazgos_colposcopicos')
        ->where('c.idcolposcopia','=',$id) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$idpaciente)
        ->first();

        $colposcopiaimgs=DB::table('colposcopia_img')
        ->where('idcolposcopia','=',$id) 
        ->get();

        return view("pacientes.historiales.colposcopias.show",["colposcopia"=>$colposcopia,"paciente"=>$paciente,"colposcopiaimgs"=>$colposcopiaimgs]);
    }

    public function eliminarcolposcopia(Request $request)
    {
        $idcolposcopia = $request->get('idcolposcopia');
        $idpaciente = $request->get('idpaciente');
        
        $eliminarcolposcopia=Colposcopia::findOrFail($idcolposcopia);
        $eliminarcolposcopia->delete();

        $request->session()->flash('alert-success', 'Se elimino la colposcopia.');  
        
        $idpaciente=$idpaciente;
        $colposcopias=DB::table('colposcopia as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idcolposcopia','c.fecha','c.iddoctor','d.name as Doctor','d.especialidad','c.idpaciente','p.nombre as Paciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('c.idpaciente','=',$idpaciente) 
        ->orderby('c.fecha','desc')
        ->paginate(20);

        return view("pacientes.historiales.colposcopias.index",["paciente"=>Paciente::findOrFail($idpaciente),"colposcopias"=>$colposcopias]);
    }
}
