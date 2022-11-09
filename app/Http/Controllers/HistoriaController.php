<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\Historia;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\PacienteFormRequest;
use sisVentasWeb\Http\Requests\HistoriaFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class HistoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $idpaciente=trim($request->get('searchidpaciente'));
        $historia = DB::table('historia')
        ->where('idpaciente','=',$idpaciente)
        ->first();

        return view("pacientes.historiales.historias.index",["paciente"=>Paciente::findOrFail($idpaciente),"historia"=>$historia]);
    }

    public function create(Request $request)
    {
        $idpaciente = $request->idpaciente;

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$idpaciente)
        ->first();

    	return view("pacientes.historiales.historias.create",["paciente"=>$paciente]);
    }

    public function store (HistoriaFormRequest $request)
    {
    	$fechaHistoria=trim($request->get('fecha'));
        $fecha = date("Y-m-d", strtotime($fechaHistoria));
        $fur = date("Y-m-d", strtotime($request->get('fur')));
        $ultimo = date("Y-m-d", strtotime($request->get('ultimo')));

        $idpaciente=$request->get('idpaciente');

        $existehistoria=DB::table('historia')->where('idpaciente','=',$idpaciente)->get();

        if($existehistoria->count() != 1)
        {
            //try
            //{ 
                //DB::beginTransaction();

                $historia=new Historia;
                //info general
                $historia->idpaciente=$idpaciente;
                $historia->fecha=$fecha;
                $historia->estado_civil=$request->get('estado_civil');
                $historia->procedencia=$request->get('procedencia');
                $historia->escolaridad=$request->get('escolaridad');
                $historia->tel_emergencia=$request->get('tel_emergencia');
                $historia->profesion=$request->get('profesion');
                $historia->motivo=$request->get('motivo');
                $historia->historia=$request->get('historia');
                //antecedentes personales
                $historia->ciclos_regulares=$request->get('ciclos_regulares');
                $historia->histerectomia=$request->get('histerectomia');
                $historia->mastopatia=$request->get('mastopatia');
                $historia->cardiopatias=$request->get('cardiopatias');
                $historia->cafelea_vascular=$request->get('cafelea_vascular');
                $historia->tabaquismo=$request->get('tabaquismo');
                $historia->tratamiento_quimioradiacion=$request->get('tratamiento_quimioradiacion');
                $historia->ejercicio=$request->get('ejercicio');
                //personales
                $historia->affecciones_ginecologicas=$request->get('affecciones_ginecologicas');
                $historia->cancer=$request->get('cancer');
                $historia->varices_trombosis=$request->get('varices_trombosis');
                $historia->enfermedades_hepaticas=$request->get('enfermedades_hepaticas');
                $historia->alcoholismo=$request->get('alcoholismo');
                $historia->cafeista=$request->get('cafeista');
                $historia->trh=$request->get('trh');
                $historia->otros=$request->get('otros');
                $historia->otros_texto=$request->get('otros_texto');
                $historia->observaciones=$request->get('observaciones');
                //Antecedentes Familiarea
                $historia->cardiopatias_50anos=$request->get('cardiopatias_50anos');
                $historia->cardiopatias_50anos_quien=$request->get('cardiopatias_50anos_quien');
                $historia->osteoporosis=$request->get('osteoporosis');
                $historia->osteoporosis_quien=$request->get('osteoporosis_quien');
                $historia->cancer_mama=$request->get('cancer_mama');
                $historia->cancer_mama_quien=$request->get('cancer_mama_quien');
                $historia->cancer_ovario=$request->get('cancer_ovario');
                $historia->cancer_ovario_quien=$request->get('cancer_ovario_quien');
                $historia->diabetes=$request->get('diabetes');
                $historia->diabetes_quien=$request->get('diabetes_quien');
                $historia->hiperlipidemias=$request->get('hiperlipidemias');
                $historia->hiperlipidemias_quien=$request->get('hiperlipidemias_quien');
                $historia->cancer_endometrial=$request->get('cancer_endometrial');
                $historia->cancer_endometrial_quien=$request->get('cancer_endometrial_quien');
                $historia->familiares_otros=$request->get('familiares_otros');
                //Antecedentes Obstetricos
                $historia->gestas=$request->get('gestas');
                $historia->vias_resolucion=$request->get('vias_resolucion');
                $historia->hijos_vivos=$request->get('hijos_vivos');
                $historia->hijos_muertos=$request->get('hijos_muertos');
                $historia->complicaciones_neonatales=$request->get('complicaciones_neonatales');
                $historia->complicaciones_obstetricos=$request->get('complicaciones_obstetricos');
                $historia->abortos=$request->get('abortos');
                $historia->causa=$request->get('causa');
                //Antecedentes Ginecologicos
                $historia->fur=$fur;
                $historia->ciclos_cada=$request->get('ciclos_cada');
                $historia->ciclos_por=$request->get('ciclos_por');
                $historia->cantidad_hemorragia=$request->get('cantidad_hemorragia');
                $historia->frecuencia=$request->get('frecuencia');
                $historia->dismenorrea=$request->get('dismenorrea');
                //Vida Sexual
                $historia->activa=$request->get('activa');
                $historia->edad=$request->get('edad');
                $historia->parejas=$request->get('parejas');
                $historia->metodo_anticonceptivo=$request->get('metodo_anticonceptivo');
                $historia->metodo_si=$request->get('metodo_si');
                $historia->tiempo_mes=$request->get('tiempo_mes');
                $historia->tiempo_ano=$request->get('tiempo_ano');
                $historia->efectos_secundarios=$request->get('efectos_secundarios');
                //Historia Papanicolau
                $historia->ultimo=$ultimo;
                $historia->resultado=$request->get('resultado');
                $historia->colonoscopia=$request->get('colonoscopia');
                $historia->colonoscopia_si=$request->get('colonoscopia_si');
                $historia->procedimientos=$request->get('procedimientos');
                $historia->rendiciones=$request->get('rendiciones');
                //Revision por sistemas
                $historia->revision=$request->get('revision');
                
                $historia->save();

                $cli=DB::table('paciente')->where('idpaciente','=',$historia->idpaciente)->first();

                $zonahoraria = Auth::user()->zona_horaria;
                $moneda = Auth::user()->moneda;
                $fechahora= Carbon::now($zonahoraria);
                $bitacora=new Bitacora;
                $bitacora->idempresa=Auth::user()->idempresa;
                $bitacora->idusuario=Auth::user()->id;
                $bitacora->fecha=$fechahora;
                $bitacora->tipo="Paciente";
                $bitacora->descripcion="Se creo la historia de:".$cli->nombre.", Fecha: ".$fechaHistoria;
                $bitacora->save();

                $request->session()->flash('alert-success', "Se creo la historia de: ".$cli->nombre.", Fecha: ".$fechaHistoria);

                //DB::commit();

            //}catch(\Exception $e)
            //{
                //DB::rollback();
            //}
        }else
        {
            $cli=DB::table('paciente')->where('idpaciente','=',$idpaciente)->first();
            $historia=DB::table('historia')->where('idpaciente','=',$idpaciente)->first();
            $request->session()->flash('alert-danger', "Ya existe una historia del paciente: ".$cli->nombre);
        }

    	$idpaciente=$idpaciente;

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$idpaciente)
        ->first();

        return view("pacientes.historiales.historias.index",["paciente"=>Paciente::findOrFail($idpaciente),"historia"=>$historia]);
    }

    public function edit($id)
    {
        $idpaciente=$id;
        $historia = DB::table('historia')
        ->where('idpaciente','=',$idpaciente)
        ->first();

    	return view("pacientes.historiales.historias.edit",["paciente"=>Paciente::findOrFail($idpaciente),"historia"=>$historia]);
    }

    public function update(HistoriaFormRequest $request,$id)
    {
        $idpaciente=$request->get('idpaciente');
        $idhistoria = $request->get('idhistoria');
        

        //$zona_horaria = Auth::user()->zona_horaria;
        $fechaHistoria = $request->get('fecha');
        $fecha = date("Y-m-d", strtotime($fechaHistoria));
        $fur = date("Y-m-d", strtotime($request->get('fur')));
        $ultimo = date("Y-m-d", strtotime($request->get('ultimo')));
        

        $historia=Historia::findOrFail($id);
        //info general
        $historia->fecha=$fecha;
        $historia->estado_civil=$request->get('estado_civil');
        $historia->procedencia=$request->get('procedencia');
        $historia->escolaridad=$request->get('escolaridad');
        $historia->tel_emergencia=$request->get('tel_emergencia');
        $historia->profesion=$request->get('profesion');
        $historia->motivo=$request->get('motivo');
        $historia->historia=$request->get('historia');
        //antecedentes personales
        $historia->ciclos_regulares=$request->get('ciclos_regulares');
        $historia->histerectomia=$request->get('histerectomia');
        $historia->mastopatia=$request->get('mastopatia');
        $historia->cardiopatias=$request->get('cardiopatias');
        $historia->cafelea_vascular=$request->get('cafelea_vascular');
        $historia->tabaquismo=$request->get('tabaquismo');
        $historia->tratamiento_quimioradiacion=$request->get('tratamiento_quimioradiacion');
        $historia->ejercicio=$request->get('ejercicio');
        //personales
        $historia->affecciones_ginecologicas=$request->get('affecciones_ginecologicas');
        $historia->cancer=$request->get('cancer');
        $historia->varices_trombosis=$request->get('varices_trombosis');
        $historia->enfermedades_hepaticas=$request->get('enfermedades_hepaticas');
        $historia->alcoholismo=$request->get('alcoholismo');
        $historia->cafeista=$request->get('cafeista');
        $historia->trh=$request->get('trh');
        $historia->otros=$request->get('otros');
        $historia->otros_texto=$request->get('otros_texto');
        $historia->observaciones=$request->get('observaciones');
        //Antecedentes Familiares
        $historia->cardiopatias_50anos=$request->get('cardiopatias_50anos');
        $historia->cardiopatias_50anos_quien=$request->get('cardiopatias_50anos_quien');
        $historia->osteoporosis=$request->get('osteoporosis');
        $historia->osteoporosis_quien=$request->get('osteoporosis_quien');
        $historia->cancer_mama=$request->get('cancer_mama');
        $historia->cancer_mama_quien=$request->get('cancer_mama_quien');
        $historia->cancer_ovario=$request->get('cancer_ovario');
        $historia->cancer_ovario_quien=$request->get('cancer_ovario_quien');
        $historia->diabetes=$request->get('diabetes');
        $historia->diabetes_quien=$request->get('diabetes_quien');
        $historia->hiperlipidemias=$request->get('hiperlipidemias');
        $historia->hiperlipidemias_quien=$request->get('hiperlipidemias_quien');
        $historia->cancer_endometrial=$request->get('cancer_endometrial');
        $historia->cancer_endometrial_quien=$request->get('cancer_endometrial_quien');
        $historia->familiares_otros=$request->get('familiares_otros');
        //Antecedentes Obstetricos
        $historia->gestas=$request->get('gestas');
        $historia->vias_resolucion=$request->get('vias_resolucion');
        $historia->hijos_vivos=$request->get('hijos_vivos');
        $historia->hijos_muertos=$request->get('hijos_muertos');
        $historia->complicaciones_neonatales=$request->get('complicaciones_neonatales');
        $historia->complicaciones_obstetricos=$request->get('complicaciones_obstetricos');
        $historia->abortos=$request->get('abortos');
        $historia->causa=$request->get('causa');
        //Antecedentes Ginecologicos
        $historia->fur=$fur;
        $historia->ciclos_cada=$request->get('ciclos_cada');
        $historia->ciclos_por=$request->get('ciclos_por');
        $historia->cantidad_hemorragia=$request->get('cantidad_hemorragia');
        $historia->frecuencia=$request->get('frecuencia');
        $historia->dismenorrea=$request->get('dismenorrea');
        //Vida Sexual
        $historia->activa=$request->get('activa');
        $historia->edad=$request->get('edad');
        $historia->parejas=$request->get('parejas');
        $historia->metodo_anticonceptivo=$request->get('metodo_anticonceptivo');
        $historia->metodo_si=$request->get('metodo_si');
        $historia->tiempo_mes=$request->get('tiempo_mes');
        $historia->tiempo_ano=$request->get('tiempo_ano');
        $historia->efectos_secundarios=$request->get('efectos_secundarios');
        //Historia Papanicolau
        $historia->ultimo=$ultimo;
        $historia->resultado=$request->get('resultado');
        $historia->colonoscopia=$request->get('colonoscopia');
        $historia->colonoscopia_si=$request->get('colonoscopia_si');
        $historia->procedimientos=$request->get('procedimientos');
        $historia->rendiciones=$request->get('rendiciones');
        //Revision por sistemas
        $historia->revision=$request->get('revision');

        $historia->save();

        $cli=DB::table('paciente')->where('idpaciente','=',$historia->idpaciente)->first();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Pacientes";
        $bitacora->descripcion="Se edito la historia de: ".$cli->nombre.", Fecha: ".$fechaHistoria;
        $bitacora->save();

        $request->session()->flash('alert-success', "Se edito la historia de: ".$cli->nombre.", Fecha: ".$fechaHistoria);

        $idpaciente=$cli->idpaciente;

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$idpaciente)
        ->first();

        return view("pacientes.historiales.historias.index",["paciente"=>Paciente::findOrFail($idpaciente),"historia"=>$historia]);
    }

}
