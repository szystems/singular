<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\User;
use sisVentasWeb\Cita;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\CitaFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use Illuminate\Support\Facades\Input;

class CitaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index (Request $request)
	{
		if ($request)
		{
            $iddoctorBuscar = $request->get('iddoctorBuscar');
            $fechaBuscar = trim($request->get('fechaBuscar'));

            $fechaBuscar = date("Y-m-d", strtotime($fechaBuscar));
            $mananaBuscar = date("Y-m-d", strtotime($fechaBuscar.'+ 1 days'));

            if($fechaBuscar != '1970-01-01')
            {
                
                $citas=DB::table('cita')
                ->where('iddoctor','LIKE', '%'.$iddoctorBuscar.'%')
                ->where('fecha_inicio','>=', $fechaBuscar)
                ->where('fecha_inicio','<', $mananaBuscar)
                ->orderBy('fecha_inicio','asc')
                ->paginate(20);

                $doctores=DB::table('users')
                ->where('tipo_usuario', '=', 'Doctor')
                ->where('email','!=','Eliminado')
                ->orderBy('especialidad','name','asc')
                ->get();

                $pacientes=DB::table('paciente')
                ->where('estado','=','Habilitado')
                ->orderBy('nombre','asc')
                ->get();

                return view('pacientes.cita.index',["citas"=>$citas,"doctores"=>$doctores,"pacientes"=>$pacientes,"iddoctorBuscar"=>$iddoctorBuscar,"fechaBuscar"=>$fechaBuscar]);
            }
            else
            {
                $zona_horaria = Auth::user()->zona_horaria;
                $fecha= Carbon::now($zona_horaria);
                $fecha=trim($fecha);
                $fecha = date("Y-m-d", strtotime($fecha));

                $manana = date("Y-m-d", strtotime($fecha.'+ 1 days'));

                $citas=DB::table('cita')
                ->where('fecha_inicio','>=',$fecha)
                ->where('fecha_inicio','<',$manana)
                ->orderBy('fecha_inicio','asc')
                ->paginate(20);

                $doctores=DB::table('users')
                ->where('tipo_usuario','=','Doctor')
                ->where('email','!=','Eliminado')
                ->orderBy('especialidad','name','asc')
                ->get();

                $pacientes=DB::table('paciente')
                ->where('estado','=','Habilitado')
                ->orderBy('nombre','asc')
                ->get();
                return view('pacientes.cita.index',["citas"=>$citas,"doctores"=>$doctores,"pacientes"=>$pacientes,"iddoctorBuscar"=>$iddoctorBuscar,"fechaBuscar"=>$fecha]);
            }
		}
	}

	public function create(Request $request)
	{
        
        
	}
    
    public function store(CitaFormRequest $request)
    {
        $iddoctor = $request->get('iddoctor');
        $idpaciente = $request->get('idpaciente');
        $fecha_inicio = $request->get('fecha');
        $fecha_fin = $request->get('fecha');
        $hora = $request->get('hora');
        $minuto_entrada = $request->get('minuto');
        $duracion = $request->get('duracion');
        $doctor=DB::table('users')->where('id','=',$iddoctor)->first();
        $paciente=DB::table('paciente')->where('idpaciente','=',$idpaciente)->first();

        //$zona_horaria = Auth::user()->zona_horaria;
        $fecha_inicio = date("Y-m-d", strtotime($fecha_inicio));
        $fecha_inicio = date($fecha_inicio." ".$hora.":".$minuto_entrada.":00");

        if($duracion == 59 and $minuto_entrada == 30)
        {
            $duracion = 29;
            $minuto_salida = $duracion;
            $hora = $hora + 1;
        }
        else
        {
            $minuto_salida = $minuto_entrada + $duracion;
        }
        

        $fecha_fin = date("Y-m-d", strtotime($fecha_fin));
        $fecha_fin = date($fecha_fin." ".$hora.":".$minuto_salida.":00");

        //comprobamos que la fecha no este bloqueada para este doctor
        $fechaComprobacion = date("Y-m-d", strtotime($fecha_inicio));
        $comprobarDias=DB::table('dias')
            ->where('iddoctor','=',$iddoctor)
            ->where('fecha','=',$fechaComprobacion)
			->get();
        
        if($comprobarDias->count() == 0)
        {
            //buscamos si alguna cita esta a la misma hora, fecha y doctor
            $citas=DB::table('cita')
            ->where('iddoctor', '=', $iddoctor)
            ->where('estado', '!=', "Cancelada")
            ->whereBetween('fecha_inicio', [$fecha_inicio, $fecha_fin])
            ->orWhereBetween('fecha_fin', [$fecha_inicio, $fecha_fin])
            ->where('iddoctor', '=', $iddoctor)
            ->where('estado', '!=', "Cancelada")
            ->get();
            $existeCita= $citas->count();
            if($existeCita == 0)
            {
                $cita=new Cita;
                $cita->idusuario=Auth::user()->id;
                $cita->iddoctor=$iddoctor;
                $cita->idpaciente=$idpaciente;
                $cita->fecha_inicio=$fecha_inicio;
                $cita->fecha_fin=$fecha_fin;
                $cita->estado_cita="Confirmada";
                //$cita->apuntes=$request->get('apuntes');
                $cita->estado="Habilitado";
                $cita->save();

                $zonahoraria = Auth::user()->zona_horaria;
                $moneda = Auth::user()->moneda;
                $fechahora= Carbon::now($zonahoraria);
                $bitacora=new Bitacora;
                $bitacora->idusuario=Auth::user()->id;
                $bitacora->idempresa=Auth::user()->idempresa;
                $bitacora->fecha=$fechahora;
                $bitacora->tipo="Citas";
                $bitacora->descripcion="Se creo una cita, Paciente: ".$paciente->nombre.", Doctor: ".$doctor->name.", Fecha y hora: ".$cita->fecha_inicio.", Finaliza: ".$cita->fecha_fin.", Estado: ".$cita->estado_cita;
                $bitacora->save();

                //$request->session()->flash('alert-success', 'La cita se creo correctamente: '.$paciente->nombre.", Doctor: ".$doctor->name.", Fecha y hora: ".$cita->fecha_inicio.", Finaliza: ".$cita->fecha_fin.", Estado: ".$cita->estado_cita);

                $cita=DB::table('cita')->where('idcita','=',$cita->idcita)->first();
                $request->session()->flash('alert-success', 'La Fecha y Hora con el doctor: '.$doctor->name.' esta disponible y se creo correctamente, a continuación  puede editar algunos datos y guarde la misma.');
                return view("pacientes.cita.edit",["cita"=>$cita,"doctor"=>$doctor,"paciente"=>$paciente,"fecha_inicio"=>$fecha_inicio,"fecha_fin"=>$fecha_fin]);
            }
            else
            {
                $request->session()->flash('alert-danger', 'La Fecha y Hora con el doctor: '.$doctor->name.' no esta disponible, porfavor consulte el listado.');
                return Redirect::to('pacientes/cita');
            }
        }else
        {
            $request->session()->flash('alert-danger', 'La Fecha'.$request->get('fecha').' esta bloqueada para el doctor: '.$doctor->name);
            return Redirect::to('pacientes/cita');
        }

        

        

        
    }

    public function edit($id)
    {
        $cita=DB::table('cita')->where('idcita','=',$id)->first();
        $doctor=DB::table('users')->where('id','=',$cita->iddoctor)->first();
        $paciente=DB::table('paciente')->where('idpaciente','=',$cita->idpaciente)->first();
    	return view("pacientes.cita.edit",["cita"=>$cita,"doctor"=>$doctor,"paciente"=>$paciente]);
    }

    public function update(citaFormRequest $request,$id)
    {
        $cita=DB::table('cita')->where('idcita','=',$id)->first();
        $iddoctor = $request->get('iddoctor');
        $idpaciente = $request->get('idpaciente');
        $fecha_inicio = $request->get('fecha');
        $fecha_fin = $request->get('fecha');
        $hora = $request->get('hora');
        $minuto_entrada = $request->get('minuto');
        $duracion = $request->get('duracion');
        $doctor=DB::table('users')->where('id','=',$cita->iddoctor)->first();
        $paciente=DB::table('paciente')->where('idpaciente','=',$cita->idpaciente)->first();
        $estado_cita=$request->get('estado_cita');
        $apuntes=$request->get('apuntes');

        //$zona_horaria = Auth::user()->zona_horaria;
        $fecha_inicio = date("Y-m-d", strtotime($fecha_inicio));
        $fecha_inicio = date($fecha_inicio." ".$hora.":".$minuto_entrada.":00");
        
        if($duracion == 59 and $minuto_entrada == 30)
        {
            $duracion = 29;
            $minuto_salida = $duracion;
            $hora = $hora + 1;
        }
        else
        {
            $minuto_salida = $minuto_entrada + $duracion;
        }

        $fecha_fin = date("Y-m-d", strtotime($fecha_fin));
        $fecha_fin = date($fecha_fin." ".$hora.":".$minuto_salida.":00");

        //comprobamos que la fecha no este bloqueada para este doctor
        $fechaComprobacion = date("Y-m-d", strtotime($fecha_inicio));
        $comprobarDias=DB::table('dias')
            ->where('iddoctor','=',$iddoctor)
            ->where('fecha','=',$fechaComprobacion)
			->get();
        
        if($comprobarDias->count() == 0)
        {
            //comprobar si la cita es editable
            $citaEditable=DB::table('cita')
            ->where('idcita','=',$id)
            ->where('iddoctor','=',$iddoctor)
            ->where('fecha_inicio','=',$fecha_inicio)
            ->where('fecha_fin','=',$fecha_fin)
            ->get();

            if($citaEditable->count() == 0)
            {
                //buscamos si alguna cita esta a la misma hora, fecha y doctor
                $citas=DB::table('cita')
                ->where('iddoctor', '=', $iddoctor)
                ->where('estado', '!=', "Cancelada")
                ->where('idcita', '!=', $id)
                ->whereBetween('fecha_inicio', [$fecha_inicio, $fecha_fin])
                ->orWhereBetween('fecha_fin', [$fecha_inicio, $fecha_fin])
                ->where('iddoctor', '=', $iddoctor)
                ->where('estado', '!=', "Cancelada")
                ->where('idcita', '!=', $id)
                ->get();
                $existeCita= $citas->count();
                if($existeCita == 0)
                {
                    if($estado_cita == "Espera")
                    {
                        $citaComprobar=Cita::findOrFail($id);
                        if($citaComprobar->turno == null)
                        {
                            $fechacomp = date("Y-m-d", strtotime($fecha_inicio));
                            $turno = DB::table('cita')
                            ->whereDate('fecha_inicio','=',$fechacomp)
                            ->where('iddoctor','=',$iddoctor)
                            ->max('turno');

                            $dbturno = $turno+1;
                        }else
                        {
                            $turno='/';
                            $dbturno=$citaComprobar->turno;
                        }
                        
                    }else
                    {
                        $citaComprobar=Cita::findOrFail($id);
                        $dbturno=$citaComprobar->turno;
                    }

                    $cita=Cita::findOrFail($id);
                    $cita->idusuario=Auth::user()->id;
                    $cita->iddoctor=$iddoctor;
                    $cita->idpaciente=$idpaciente;
                    $cita->fecha_inicio=$fecha_inicio;
                    $cita->fecha_fin=$fecha_fin;
                    $cita->estado_cita=$estado_cita;
                    $cita->apuntes=$apuntes;
                    $cita->turno=$dbturno;
                    $cita->idusuario=Auth::user()->id;
                    $cita->save();

                    $zonahoraria = Auth::user()->zona_horaria;
                    $moneda = Auth::user()->moneda;
                    $fechahora= Carbon::now($zonahoraria);
                    $bitacora=new Bitacora;
                    $bitacora->idusuario=Auth::user()->id;
                    $bitacora->idempresa=Auth::user()->idempresa;
                    $bitacora->fecha=$fechahora;
                    $bitacora->tipo="Citas";
                    $bitacora->descripcion="Se edito una cita, Paciente: ".$paciente->nombre.", Doctor: ".$doctor->name.", Fecha y hora: ".$cita->fecha_inicio.", Finaliza: ".$cita->fecha_fin.", Estado: ".$cita->estado_cita.", Apuntes: ".$cita->apuntes;
                    $bitacora->save();

                    $request->session()->flash('alert-success', 'La Fecha y Hora con el doctor: '.$doctor->name.' esta disponible y se edito correctamente. dbturno:'.$dbturno);
                    return Redirect::to('pacientes/cita');
                }
                else
                {
                    $doctorNoDisponible=DB::table('users')->where('id','=',$iddoctor)->first();
                    $request->session()->flash('alert-danger', 'La Fecha y Hora con el doctor: '.$doctorNoDisponible->name.' no esta disponible, porfavor consulte el listado.');
                    
                    $cita=DB::table('cita')->where('idcita','=',$id)->first();
                    return view("pacientes.cita.edit",["cita"=>$cita,"doctor"=>$doctor,"paciente"=>$paciente,"fecha_inicio"=>$fecha_inicio,"fecha_fin"=>$fecha_fin]);
                }

            }else
            {
                if($estado_cita == "Espera")
                {
                    $citaComprobar=Cita::findOrFail($id);
                    if($citaComprobar->turno == null)
                    {
                        $fechacomp = date("Y-m-d", strtotime($fecha_inicio));
                        $turno = DB::table('cita')
                        ->whereDate('fecha_inicio','=',$fechacomp)
                        ->where('iddoctor','=',$iddoctor)
                        ->max('turno');

                        $dbturno = $turno+1;
                    }else
                    {
                        $turno='/';
                        $dbturno=$citaComprobar->turno;
                    }
                        
                }else
                {
                    $citaComprobar=Cita::findOrFail($id);
                    $dbturno=$citaComprobar->turno;
                }
                

                $cita=Cita::findOrFail($id);
                $cita->idusuario=Auth::user()->id;
                $cita->iddoctor=$iddoctor;
                $cita->idpaciente=$idpaciente;
                $cita->fecha_inicio=$fecha_inicio;
                $cita->fecha_fin=$fecha_fin;
                $cita->estado_cita=$estado_cita;
                $cita->apuntes=$apuntes;
                $cita->turno=$dbturno;
                $cita->idusuario=Auth::user()->id;
                $cita->save();

                $zonahoraria = Auth::user()->zona_horaria;
                $moneda = Auth::user()->moneda;
                $fechahora= Carbon::now($zonahoraria);
                $bitacora=new Bitacora;
                $bitacora->idusuario=Auth::user()->id;
                $bitacora->idempresa=Auth::user()->idempresa;
                $bitacora->fecha=$fechahora;
                $bitacora->tipo="Citas";
                $bitacora->descripcion="Se edito una cita, Paciente: ".$paciente->nombre.", Doctor: ".$doctor->name.", Fecha y hora: ".$cita->fecha_inicio.", Finaliza: ".$cita->fecha_fin.", Estado: ".$cita->estado_cita.", Apuntes: ";
                $bitacora->save();
    
                $request->session()->flash('alert-success', 'La Fecha y Hora con el doctor: '.$doctor->name.' esta disponible y se edito correctamente. Turno:'.$dbturno);
                return Redirect::to('pacientes/cita');
            }
        }
        else
        {
            $doctorNoDisponible=DB::table('users')->where('id','=',$iddoctor)->first();
            $request->session()->flash('alert-danger', 'La Fecha'.$request->get('fecha').' esta bloqueada para el doctor: '.$doctorNoDisponible->name);
                    
            $cita=DB::table('cita')->where('idcita','=',$id)->first();
            return view("pacientes.cita.edit",["cita"=>$cita,"doctor"=>$doctor,"paciente"=>$paciente,"fecha_inicio"=>$fecha_inicio,"fecha_fin"=>$fecha_fin]);
        }
        
        


    }

    public function show($id)
    {
        $cita=DB::table('cita')->where('idcita','=',$id)->first();
        $doctor=DB::table('users')->where('id','=',$cita->iddoctor)->first();
        $paciente=DB::table('paciente')->where('idpaciente','=',$cita->idpaciente)->first();
        $usuario=DB::table('users')->where('id','=',$cita->idusuario)->first();
        return view("pacientes.cita.show",["cita"=>$cita,"doctor"=>$doctor,"paciente"=>$paciente,"usuario"=>$usuario]);
    }

    public function destroy($id)
    {
        $cita=DB::table('cita')->where('idcita','=',$id)->first();

        $cita=Cita::findOrFail($id);
        $cita->estado="Cancelada";
        $cita->estado_cita="Cancelada";
        $cita->idusuario=Auth::user()->id;;
        $cita->update();

        $fecha_inicio = date("d-m-Y H:i A", strtotime($cita->fecha_inicio));
		$fecha_fin = date("H:i A", strtotime($cita->fecha_fin));
        $fechaCita = date("d-m-Y", strtotime($cita->fecha_fin));
        $doctor=DB::table('users')->where('id','=',$cita->iddoctor)->first();
        $paciente=DB::table('paciente')->where('idpaciente','=',$cita->idpaciente)->first();
        $usuario=DB::table('users')->where('id','=',$cita->idusuario)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Citas";
            $bitacora->descripcion="Se Cancelo una cita, Fecha y Hora: ".$fecha_inicio." - ".$fecha_fin.", Doctor: ".$doctor->name.", Paciente: ".$paciente->nombre.", Usuario: ".$usuario->name;
            $bitacora->save();

            return view("pacientes.cita.show",["cita"=>$cita,"doctor"=>$doctor,"paciente"=>$paciente,"usuario"=>$usuario]);
    	
    }
}
