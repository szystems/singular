<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\Climaymeno;
use sisVentasWeb\ClimaymenoControl;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\ClimaymenoFormRequest;
use sisVentasWeb\Http\Requests\ClimaymenoControlFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class ClimaymenoControlController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        $idclimaymeno = $request->idclimaymeno;

        $climaymeno=DB::table('climaymeno as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idclimaymeno','c.fecha','c.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','c.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('c.idclimaymeno','=',$idclimaymeno) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$climaymeno->idpaciente)
        ->first(); 

        $historia = DB::table('historia')
        ->where('idpaciente','=',$climaymeno->idpaciente)
        ->first();

    	return view("pacientes.historiales.climaymenos.controles.create",["paciente"=>$paciente,"climaymeno"=>$climaymeno, "historia"=>$historia]);
    }

    public function store (ClimaymenoControlFormRequest $request)
    {
    	try
    	{ 
            
            $fechaControl=trim($request->get('fecha'));
            $fecha = date("Y-m-d", strtotime($fechaControl));

            $fechalaboratorios=trim($request->get('fecha_laboratorios'));
            $fechalaboratorios = date("Y-m-d", strtotime($fechalaboratorios));

            $idpaciente=$request->get('idpaciente');
            $idclimaymeno=$request->get('idclimaymeno');

            //numero_control
            $ultimoControl = DB::table('climaymeno_control')
            ->where('idclimaymeno','=',$idclimaymeno)
            ->max('numero_control');
            if(isset($ultimoControl))
            {
                $numero_control = $ultimoControl + 1;
            }else
            {
                $numero_control = 1;
            }

            $climaymeno=DB::table('climaymeno as c')
            ->join('paciente as p','c.idpaciente','=','p.idpaciente')
            ->join('users as d','c.iddoctor','=','d.id')
            ->join('users as u','c.idusuario','=','u.id')
            ->select('c.idclimaymeno','c.fecha','c.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','c.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
            ->where('c.idclimaymeno','=',$idclimaymeno) 
            ->first();

            
    		DB::beginTransaction();

            $control=new ClimaymenoControl;
            $control->idclimaymeno=$request->get('idclimaymeno');
            $control->numero_control=$numero_control;
            $control->fecha=$fecha;

            $control->bochornos=$request->get('bochornos');
            $control->bochornos_escala=$request->get('bochornos_escala');
            $control->depresion=$request->get('depresion');
            $control->depresion_escala=$request->get('depresion_escala');
            $control->irritabilidad=$request->get('irritabilidad');
            $control->irritabilidad_escala=$request->get('irritabilidad_escala');
            $control->perdida_libido=$request->get('perdida_libido');
            $control->perdida_libido_escala=$request->get('perdida_libido_escala');
            $control->sequedad_vaginal=$request->get('sequedad_vaginal');
            $control->sequedad_vaginal_escala=$request->get('sequedad_vaginal_escala');
            $control->insomnio=$request->get('insomnio');
            $control->insomnio_escala=$request->get('insomnio_escala');
            $control->cefalea=$request->get('cefalea');
            $control->cefalea_escala=$request->get('cefalea_escala');
            $control->fatiga=$request->get('fatiga');
            $control->fatiga_escala=$request->get('fatiga_escala');
            $control->artralgias_mialgias=$request->get('artralgias_mialgias');
            $control->artralgias_mialgias_escala=$request->get('artralgias_mialgias_escala');
            $control->trastornos_miccionales=$request->get('trastornos_miccionales');
            $control->trastornos_miccionales_escala=$request->get('trastornos_miccionales_escala');
            $control->otros=$request->get('otros');
            $control->otros_si=$request->get('otros_si');

            $control->peso=$request->get('peso');
            $control->talla=$request->get('talla');
            $control->presion_arterial=$request->get('presion_arterial');
            $control->temperatura=$request->get('temperatura');
            $control->frecuencia_cardiaca=$request->get('frecuencia_cardiaca');
            $control->cara=$request->get('cara');
            $control->mamas=$request->get('mamas');
            $control->torax=$request->get('torax');
            $control->abdomen=$request->get('abdomen');
            $control->vulva=$request->get('vulva');
            $control->utero_anexos=$request->get('utero_anexos');
            $control->varices=$request->get('varices');
            $control->flujo_vaginal_ph=$request->get('flujo_vaginal_ph');
            $control->hallazgos=$request->get('hallazgos');

            $control->fecha_laboratorios=$fechalaboratorios;
            $control->hemograma=$request->get('hemograma');
            $control->examen_orina=$request->get('examen_orina');
            $control->glicemia_curva_glicemica=$request->get('glicemia_curva_glicemica');
            $control->insulina=$request->get('insulina');
            $control->panel_lipidos=$request->get('panel_lipidos');
            $control->transaminasas=$request->get('transaminasas');
            $control->citologia_cervicovaginal=$request->get('citologia_cervicovaginal');
            $control->mamografia=$request->get('mamografia');
            $control->fsh=$request->get('fsh');
            $control->lh=$request->get('lh');
            $control->pruebas_tiroideas=$request->get('pruebas_tiroideas');
            $control->prolactina=$request->get('prolactina');
            $control->densitometria_osea=$request->get('densitometria_osea');
            $control->ultrasonografia_pelvica=$request->get('ultrasonografia_pelvica');
            $control->escala_homa=$request->get('escala_homa');
            $control->otros_laboratorio=$request->get('otros_laboratorio');

            $control->acos=$request->get('acos');
            $control->tratamiento_infecciones=$request->get('tratamiento_infecciones');
            $control->trh_tipo_dosis=$request->get('trh_tipo_dosis');
            $control->tratamiento_osteoporosis=$request->get('tratamiento_osteoporosis');
            $control->calcio=$request->get('calcio');
            $control->vitamina_d=$request->get('vitamina_d');
            $control->aspirina=$request->get('aspirina');
            $control->tratamiento_hta=$request->get('tratamiento_hta');
            $control->tratamiento_diabetes=$request->get('tratamiento_diabetes');
            $control->jabones_intimos=$request->get('jabones_intimos');
            $control->nota_adicionales=$request->get('nota_adicionales');

    		$control->save();
            
            $cli=DB::table('paciente')->where('idpaciente','=',$climaymeno->idpaciente)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se creo un nuevo control climaterio y menopausea del paciente:".$cli->nombre.", Fecha: ".$fechaControl;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}

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
        ->where('idclimaymeno','=',$idclimaymeno) 
        ->get();

        $request->session()->flash('alert-success', "Se creo un control de climaterio y menopausea del paciente: ".$cli->nombre.", Fecha: ".$fechaControl);

        return view("pacientes.historiales.climaymenos.show",["climaymeno"=>$climaymeno,"paciente"=>$paciente,"controles"=>$controles, "historia"=>$historia,"climaymenoimgs"=>$climaymenoimgs]);
    }

    public function edit($id)
    {
        $control=DB::table('climaymeno_control')
        ->where('idclimaymeno_control','=',$id)
        ->first();

        $climaymeno=DB::table('climaymeno as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idclimaymeno','c.fecha','c.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','c.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('c.idclimaymeno','=',$control->idclimaymeno) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$climaymeno->idpaciente)
        ->first(); 

        $historia = DB::table('historia')
        ->where('idpaciente','=',$climaymeno->idpaciente)
        ->first();
        
       
        return view("pacientes.historiales.climaymenos.controles.edit",["control"=>$control,"climaymeno"=>$climaymeno,"paciente"=>$paciente, "historia"=>$historia]);
    }

    public function update(ClimaymenoControlFormRequest $request,$id)
    {
        $fechaControl=trim($request->get('fecha'));
        $fecha = date("Y-m-d", strtotime($fechaControl));

        $fechalaboratorios=trim($request->get('fecha_laboratorios'));
        $fechalaboratorios = date("Y-m-d", strtotime($fechalaboratorios));

        $idpaciente=$request->get('idpaciente');
        $idclimaymeno=$request->get('idclimaymeno');

        //numero_control
        $ultimoControl = DB::table('climaymeno_control')
        ->where('idclimaymeno','=',$idclimaymeno)
        ->max('numero_control');
        if(isset($ultimoControl))
        {
            $numero_control = $ultimoControl + 1;
        }else
        {
            $numero_control = 1;
        }

        $climaymeno=DB::table('climaymeno as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idclimaymeno','c.fecha','c.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','c.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('c.idclimaymeno','=',$idclimaymeno) 
        ->first();
        

        $control=ClimaymenoControl::findOrFail($id);
            $control->bochornos=$request->get('bochornos');
            $control->bochornos_escala=$request->get('bochornos_escala');
            $control->depresion=$request->get('depresion');
            $control->depresion_escala=$request->get('depresion_escala');
            $control->irritabilidad=$request->get('irritabilidad');
            $control->irritabilidad_escala=$request->get('irritabilidad_escala');
            $control->perdida_libido=$request->get('perdida_libido');
            $control->perdida_libido_escala=$request->get('perdida_libido_escala');
            $control->sequedad_vaginal=$request->get('sequedad_vaginal');
            $control->sequedad_vaginal_escala=$request->get('sequedad_vaginal_escala');
            $control->insomnio=$request->get('insomnio');
            $control->insomnio_escala=$request->get('insomnio_escala');
            $control->cefalea=$request->get('cefalea');
            $control->cefalea_escala=$request->get('cefalea_escala');
            $control->fatiga=$request->get('fatiga');
            $control->fatiga_escala=$request->get('fatiga_escala');
            $control->artralgias_mialgias=$request->get('artralgias_mialgias');
            $control->artralgias_mialgias_escala=$request->get('artralgias_mialgias_escala');
            $control->trastornos_miccionales=$request->get('trastornos_miccionales');
            $control->trastornos_miccionales_escala=$request->get('trastornos_miccionales_escala');
            $control->otros=$request->get('otros');
            $control->otros_si=$request->get('otros_si');

            $control->peso=$request->get('peso');
            $control->talla=$request->get('talla');
            $control->presion_arterial=$request->get('presion_arterial');
            $control->temperatura=$request->get('temperatura');
            $control->frecuencia_cardiaca=$request->get('frecuencia_cardiaca');
            $control->cara=$request->get('cara');
            $control->mamas=$request->get('mamas');
            $control->torax=$request->get('torax');
            $control->abdomen=$request->get('abdomen');
            $control->vulva=$request->get('vulva');
            $control->utero_anexos=$request->get('utero_anexos');
            $control->varices=$request->get('varices');
            $control->flujo_vaginal_ph=$request->get('flujo_vaginal_ph');
            $control->hallazgos=$request->get('hallazgos');

            $control->fecha_laboratorios=$fechalaboratorios;
            $control->hemograma=$request->get('hemograma');
            $control->examen_orina=$request->get('examen_orina');
            $control->glicemia_curva_glicemica=$request->get('glicemia_curva_glicemica');
            $control->insulina=$request->get('insulina');
            $control->panel_lipidos=$request->get('panel_lipidos');
            $control->transaminasas=$request->get('transaminasas');
            $control->citologia_cervicovaginal=$request->get('citologia_cervicovaginal');
            $control->mamografia=$request->get('mamografia');
            $control->fsh=$request->get('fsh');
            $control->lh=$request->get('lh');
            $control->pruebas_tiroideas=$request->get('pruebas_tiroideas');
            $control->prolactina=$request->get('prolactina');
            $control->densitometria_osea=$request->get('densitometria_osea');
            $control->ultrasonografia_pelvica=$request->get('ultrasonografia_pelvica');
            $control->escala_homa=$request->get('escala_homa');
            $control->otros_laboratorio=$request->get('otros_laboratorio');

            $control->acos=$request->get('acos');
            $control->tratamiento_infecciones=$request->get('tratamiento_infecciones');
            $control->trh_tipo_dosis=$request->get('trh_tipo_dosis');
            $control->tratamiento_osteoporosis=$request->get('tratamiento_osteoporosis');
            $control->calcio=$request->get('calcio');
            $control->vitamina_d=$request->get('vitamina_d');
            $control->aspirina=$request->get('aspirina');
            $control->tratamiento_hta=$request->get('tratamiento_hta');
            $control->tratamiento_diabetes=$request->get('tratamiento_diabetes');
            $control->jabones_intimos=$request->get('jabones_intimos');
            $control->nota_adicionales=$request->get('nota_adicionales');

        $control->save();

        $cli=DB::table('paciente')->where('idpaciente','=',$climaymeno->idpaciente)->first();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Paciente";
        $bitacora->descripcion="Se edito un control de climaterio y menopausea para el paciente:".$cli->nombre.", Fecha: ".$fechaControl;
        $bitacora->save();

        $request->session()->flash('alert-success', "Se edito un control de climaterio y menopausea del paciente: ".$cli->nombre.", Fecha: ".$fechaControl);

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
        ->where('idclimaymeno','=',$idclimaymeno) 
        ->get();

        $request->session()->flash('alert-success', "Se edito un control de climaterio y menopausea del paciente: ".$cli->nombre.", Fecha: ".$fechaControl);

        return view("pacientes.historiales.climaymenos.show",["climaymeno"=>$climaymeno,"paciente"=>$paciente,"controles"=>$controles, "historia"=>$historia,"climaymenoimgs"=>$climaymenoimgs]);
    }

    public function eliminarcontrol(Request $request)
    {
        $idclimaymeno = $request->get('idclimaymeno');
        $idpaciente = $request->get('idpaciente');
        $idcontrol = $request->get('idcontrol');
        
        $eliminarcontrol=ClimaymenoControl::findOrFail($idcontrol);
        $eliminarcontrol->delete();

        $request->session()->flash('alert-success', 'Se elimino el control.');  
        
        
        $climaymeno=DB::table('climaymeno as c')
        ->join('paciente as p','c.idpaciente','=','p.idpaciente')
        ->join('users as d','c.iddoctor','=','d.id')
        ->join('users as u','c.idusuario','=','u.id')
        ->select('c.idclimaymeno','c.fecha','c.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','c.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
        ->where('c.idclimaymeno','=',$idclimaymeno) 
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
        ->where('idclimaymeno','=',$idclimaymeno) 
        ->get();

        return view("pacientes.historiales.climaymenos.show",["climaymeno"=>$climaymeno,"paciente"=>$paciente,"controles"=>$controles, "historia"=>$historia,"climaymenoimgs"=>$climaymenoimgs]);
    }
}
