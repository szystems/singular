<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\Embarazo;
use sisVentasWeb\Control;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\EmbarazoFormRequest;
use sisVentasWeb\Http\Requests\ControlFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class ControlController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        $idembarazo = $request->idembarazo;

        $embarazo=DB::table('embarazo as e')
        ->join('paciente as p','e.idpaciente','=','p.idpaciente')
        ->join('users as d','e.iddoctor','=','d.id')
        ->join('users as u','e.idusuario','=','u.id')
        ->select('e.idembarazo','e.fecha','e.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','e.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','e.idusuario','u.name as Usuario','u.tipo_usuario','e.fur','e.trimestre1','e.trimestre2','e.trimestre3')
        ->where('e.idembarazo','=',$idembarazo) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$embarazo->idpaciente)
        ->first(); 

        $historia = DB::table('historia')
        ->where('idpaciente','=',$embarazo->idpaciente)
        ->first();

    	return view("pacientes.historiales.embarazos.controles.create",["paciente"=>$paciente,"embarazo"=>$embarazo, "historia"=>$historia]);
    }

    public function store (ControlFormRequest $request)
    {
    	try
    	{ 
            
            $fechaControl=trim($request->get('fecha'));
            $fecha = date("Y-m-d", strtotime($fechaControl));

            $idpaciente=$request->get('idpaciente');
            $idembarazo=$request->get('idembarazo');
            $proxima_cita = date("Y-m-d", strtotime($request->get('proxima_cita')));
            $presion_arterial1=$request->get('presion_arterial1');
            $presion_arterial2=$request->get('presion_arterial2');
            $presion_arterial=$presion_arterial1."/".$presion_arterial2;

            //numero_control
            $ultimoControl = DB::table('control')
            ->where('idembarazo','=',$idembarazo)
            ->max('numero_control');
            if(isset($ultimoControl))
            {
                $numero_control = $ultimoControl + 1;
            }else
            {
                $numero_control = 1;
            }

            $embarazo=DB::table('embarazo as e')
            ->join('paciente as p','e.idpaciente','=','p.idpaciente')
            ->join('users as d','e.iddoctor','=','d.id')
            ->join('users as u','e.idusuario','=','u.id')
            ->select('e.idembarazo','e.fecha','e.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','e.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','e.idusuario','u.name as Usuario','u.tipo_usuario','e.fur','e.trimestre1','e.trimestre2','e.trimestre3')
            ->where('e.idembarazo','=',$idembarazo) 
            ->first();

            //calcular semanas de embarazo
            $fur = new DateTime($embarazo->fur);
            $hoy = new DateTime("$fecha");
            $interval = $fur->diff($hoy);
            $semanas = floor(($interval->format('%a') / 7)) . ' semanas con ' . ($interval->format('%a') % 7) . ' días';

    		DB::beginTransaction();

            $control=new Control;
            $control->idembarazo=$request->get('idembarazo');
            $control->numero_control=$numero_control;
            $control->fecha=$fecha;
            $control->semanas=$semanas;

            $control->sueno=$request->get('sueno');
            $control->apetito=$request->get('apetito');
            $control->estrenimiento=$request->get('estrenimiento');
            $control->disuria=$request->get('disuria');
            $control->nauseas_vomitos=$request->get('nauseas_vomitos');
            $control->flujo_vaginal=$request->get('flujo_vaginal');
            $control->dolor=$request->get('dolor');
            $control->otros=$request->get('otros');

            $control->peso=$request->get('peso');
            $control->talla=$request->get('talla');
            $control->presion_arterial=$presion_arterial;
            $control->temperatura=$request->get('temperatura');
            $control->frecuencia_cardiaca_materna=$request->get('frecuencia_cardiaca_materna');
            $control->altura_uterina=$request->get('altura_uterina');
            $control->frecuencia_cardiaca_fetal=$request->get('frecuencia_cardiaca_fetal');
            $control->presentacion_fetal=$request->get('presentacion_fetal');
            $control->movimientos_fetales=$request->get('movimientos_fetales');
            $control->edema_mi=$request->get('edema_mi');
            $control->varices=$request->get('varices');
            $control->flujo_vaginal_ph=$request->get('flujo_vaginal_ph');

            $control->medicamentos=$request->get('medicamentos');
            $control->especiales=$request->get('especiales');
            $control->proxima_cita=$proxima_cita;
            $control->nota=$request->get('nota');

    		$control->save();
            
            $cli=DB::table('paciente')->where('idpaciente','=',$embarazo->idpaciente)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se creo un nuevo control de embarazo para el paciente:".$cli->nombre.", Fecha: ".$fechaControl;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}

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
        ->where('idembarazo','=',$embarazo->idembarazo) 
        ->get();

        $request->session()->flash('alert-success', "Se creo un control de embarazo del paciente: ".$cli->nombre.", Fecha: ".$fechaControl);

        return view("pacientes.historiales.embarazos.show",["embarazo"=>$embarazo,"paciente"=>$paciente,"controles"=>$controles, "historia"=>$historia,"embarazoimgs"=>$embarazoimgs]);
    }

    public function edit($id)
    {
        $control=DB::table('control')
        ->where('idcontrol','=',$id)
        ->first();

        $embarazo=DB::table('embarazo as e')
        ->join('paciente as p','e.idpaciente','=','p.idpaciente')
        ->join('users as d','e.iddoctor','=','d.id')
        ->join('users as u','e.idusuario','=','u.id')
        ->select('e.idembarazo','e.fecha','e.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','e.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','e.idusuario','u.name as Usuario','u.tipo_usuario','e.fur','e.trimestre1','e.trimestre2','e.trimestre3')
        ->where('e.idembarazo','=',$control->idembarazo) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$embarazo->idpaciente)
        ->first();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$embarazo->idpaciente)
        ->first();
        
       
        return view("pacientes.historiales.embarazos.controles.edit",["control"=>$control,"embarazo"=>$embarazo,"paciente"=>$paciente, "historia"=>$historia]);
    }

    public function update(ControlFormRequest $request,$id)
    {
        $fechaControl=trim($request->get('fecha'));
        $fecha = date("Y-m-d", strtotime($fechaControl));

        $idpaciente=$request->get('idpaciente');
        $idembarazo=$request->get('idembarazo');
        $proxima_cita = date("Y-m-d", strtotime($request->get('proxima_cita')));

        //numero_control
        $ultimoControl = DB::table('control')
        ->where('idembarazo','=',$idembarazo)
        ->max('numero_control');
        if(isset($ultimoControl))
        {
            $numero_control = $ultimoControl + 1;
        }else
        {
            $numero_control = 1;
        }

        $embarazo=DB::table('embarazo as e')
        ->join('paciente as p','e.idpaciente','=','p.idpaciente')
        ->join('users as d','e.iddoctor','=','d.id')
        ->join('users as u','e.idusuario','=','u.id')
        ->select('e.idembarazo','e.fecha','e.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','e.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','e.idusuario','u.name as Usuario','u.tipo_usuario','e.fur','e.trimestre1','e.trimestre2','e.trimestre3')
        ->where('e.idembarazo','=',$idembarazo) 
        ->first();

        //calcular semanas de embarazo
        $fur = new DateTime($embarazo->fur);
        $hoy = new DateTime("$fecha");
        $interval = $fur->diff($hoy);
        $semanas = floor(($interval->format('%a') / 7)) . ' semanas con ' . ($interval->format('%a') % 7) . ' días';
        

        $control=Control::findOrFail($id);
        $control->sueno=$request->get('sueno');
        $control->apetito=$request->get('apetito');
        $control->estrenimiento=$request->get('estrenimiento');
        $control->disuria=$request->get('disuria');
        $control->nauseas_vomitos=$request->get('nauseas_vomitos');
        $control->flujo_vaginal=$request->get('flujo_vaginal');
        $control->dolor=$request->get('dolor');
        $control->otros=$request->get('otros');

        $control->peso=$request->get('peso');
        $control->talla=$request->get('talla');
        $control->presion_arterial=$request->get('presion_arterial');
        $control->temperatura=$request->get('temperatura');
        $control->frecuencia_cardiaca_materna=$request->get('frecuencia_cardiaca_materna');
        $control->altura_uterina=$request->get('altura_uterina');
        $control->frecuencia_cardiaca_fetal=$request->get('frecuencia_cardiaca_fetal');
        $control->presentacion_fetal=$request->get('presentacion_fetal');
        $control->movimientos_fetales=$request->get('movimientos_fetales');
        $control->edema_mi=$request->get('edema_mi');
        $control->varices=$request->get('varices');
        $control->flujo_vaginal_ph=$request->get('flujo_vaginal_ph');

        $control->medicamentos=$request->get('medicamentos');
        $control->especiales=$request->get('especiales');
        $control->proxima_cita=$proxima_cita;
        $control->nota=$request->get('nota');

        $control->save();

        $cli=DB::table('paciente')->where('idpaciente','=',$embarazo->idpaciente)->first();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Paciente";
        $bitacora->descripcion="Se creo un nuevo control de embarazo para el paciente:".$cli->nombre.", Fecha: ".$fechaControl;
        $bitacora->save();

        $request->session()->flash('alert-success', "Se edito un control de embarazo del paciente: ".$cli->nombre.", Fecha: ".$fechaControl);

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
        ->where('idembarazo','=',$embarazo->idembarazo) 
        ->get();

        $request->session()->flash('alert-success', "Se edito un control de embarazo del paciente: ".$cli->nombre.", Fecha: ".$fechaControl);

        return view("pacientes.historiales.embarazos.show",["embarazo"=>$embarazo,"paciente"=>$paciente,"controles"=>$controles, "historia"=>$historia,"embarazoimgs"=>$embarazoimgs]);
    }

    public function eliminarcontrol(Request $request)
    {
        $idembarazo = $request->get('idembarazo');
        $idpaciente = $request->get('idpaciente');
        $idcontrol = $request->get('idcontrol');
        
        $eliminarcontrol=Control::findOrFail($idcontrol);
        $eliminarcontrol->delete();

        $request->session()->flash('alert-success', 'Se elimino el control.');  
        
        
        $embarazo=DB::table('embarazo as e')
        ->join('paciente as p','e.idpaciente','=','p.idpaciente')
        ->join('users as d','e.iddoctor','=','d.id')
        ->join('users as u','e.idusuario','=','u.id')
        ->select('e.idembarazo','e.fecha','e.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','e.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','e.idusuario','u.name as Usuario','u.tipo_usuario','e.fur','e.trimestre1','e.trimestre2','e.trimestre3')
        ->where('e.idembarazo','=',$idembarazo) 
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
        ->where('idembarazo','=',$embarazo->idembarazo) 
        ->get();

        return view("pacientes.historiales.embarazos.show",["embarazo"=>$embarazo,"paciente"=>$paciente,"controles"=>$controles, "historia"=>$historia,"embarazoimgs"=>$embarazoimgs]);
    }
}
