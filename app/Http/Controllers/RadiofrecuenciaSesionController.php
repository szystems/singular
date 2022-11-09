<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Paciente;
use sisVentasWeb\Radiofrecuencia;
use sisVentasWeb\RadiofrecuenciaSesion;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\RadiofrecuenciaFormRequest;
use sisVentasWeb\Http\Requests\RadiofrecuenciaSesionFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class RadiofrecuenciaSesionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        $idradiofrecuencia = $request->idradiofrecuencia;

        $radiofrecuencia=DB::table('radiofrecuencia as r')
        ->join('paciente as p','r.idpaciente','=','p.idpaciente')
        ->join('users as d','r.iddoctor','=','d.id')
        ->join('users as u','r.idusuario','=','u.id')
        ->select('r.idradiofrecuencia','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','p.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario','r.fototipo_piel','r.implantes','r.implantes_tipo','r.marcapasos','r.periodo_gestacion','r.glaucoma','r.neoplasias_procesos_tumorales','r.portador_epilepsia','r.antecedentes_fotosensibilidad','r.tratamientos_acidos','r.medicamentos_fotosensibles','r.resumen')
        ->where('r.idradiofrecuencia','=',$idradiofrecuencia) 
        ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$radiofrecuencia->idpaciente)
        ->first(); 

        $historia = DB::table('historia')
        ->where('idpaciente','=',$radiofrecuencia->idpaciente)
        ->first();

    	return view("pacientes.historiales.radiofrecuencias.sesiones.create",["paciente"=>$paciente,"radiofrecuencia"=>$radiofrecuencia, "historia"=>$historia]);
    }

    public function store (RadiofrecuenciaSesionFormRequest $request)
    {
    	try
    	{ 
            
            $fechaSesion=trim($request->get('fecha'));
            $fecha = date("Y-m-d", strtotime($fechaSesion));

            $idpaciente=$request->get('idpaciente');
            $idradiofrecuencia=$request->get('idradiofrecuencia');

            //numero_sesion
            $ultimaSesion = DB::table('radiofrecuencia_sesion')
            ->where('idradiofrecuencia','=',$idradiofrecuencia)
            ->max('numero_sesion');
            if(isset($ultimaSesion))
            {
                $numero_sesion = $ultimaSesion + 1;
            }else
            {
                $numero_sesion = 1;
            }

            $radiofrecuencia=DB::table('radiofrecuencia as r')
            ->join('paciente as p','r.idpaciente','=','p.idpaciente')
            ->join('users as d','r.iddoctor','=','d.id')
            ->join('users as u','r.idusuario','=','u.id')
            ->select('r.idradiofrecuencia','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','p.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario','r.fototipo_piel','r.implantes','r.implantes_tipo','r.marcapasos','r.periodo_gestacion','r.glaucoma','r.neoplasias_procesos_tumorales','r.portador_epilepsia','r.antecedentes_fotosensibilidad','r.tratamientos_acidos','r.medicamentos_fotosensibles','r.resumen')
            ->where('r.idradiofrecuencia','=',$idradiofrecuencia) 
            ->first();

    		DB::beginTransaction();

            $sesion=new RadiofrecuenciaSesion;
            $sesion->idradiofrecuencia=$request->get('idradiofrecuencia');
            $sesion->numero_sesion=$numero_sesion;
            $sesion->fecha=$fecha;

            $sesion->monopolar_areas=$request->get('monopolar_areas');
            $sesion->monopolar_indicacion=$request->get('monopolar_indicacion');
            $sesion->monopolar_temperatura=$request->get('monopolar_temperatura');
            $sesion->monopolar_tiempo=$request->get('monopolar_tiempo');
            $sesion->monopolar_zonas_tratadas=$request->get('monopolar_zonas_tratadas');
            
            $sesion->bipolar_areas=$request->get('bipolar_areas');
            $sesion->bipolar_indicacion=$request->get('bipolar_indicacion');
            $sesion->bipolar_temperatura=$request->get('bipolar_temperatura');
            $sesion->bipolar_tiempo=$request->get('bipolar_tiempo');
            $sesion->bipolar_zonas_tratadas=$request->get('bipolar_zonas_tratadas');

            $sesion->tetrapolar_areas=$request->get('tetrapolar_areas');
            $sesion->tetrapolar_indicacion=$request->get('tetrapolar_indicacion');
            $sesion->tetrapolar_temperatura=$request->get('tetrapolar_temperatura');
            $sesion->tetrapolar_tiempo=$request->get('tetrapolar_tiempo');
            $sesion->tetrapolar_zonas_tratadas=$request->get('tetrapolar_zonas_tratadas');

            $sesion->hexapolar_areas=$request->get('hexapolar_areas');
            $sesion->hexapolar_indicacion=$request->get('hexapolar_indicacion');
            $sesion->hexapolar_temperatura=$request->get('hexapolar_temperatura');
            $sesion->hexapolar_tiempo=$request->get('hexapolar_tiempo');
            $sesion->hexapolar_zonas_tratadas=$request->get('hexapolar_zonas_tratadas');

            $sesion->ginecologico_areas=$request->get('ginecologico_areas');
            $sesion->ginecologico_indicacion=$request->get('ginecologico_indicacion');
            $sesion->ginecologico_temperatura=$request->get('ginecologico_temperatura');
            $sesion->ginecologico_tiempo=$request->get('ginecologico_tiempo');
            $sesion->ginecologico_zonas_tratadas=$request->get('ginecologico_zonas_tratadas');

    		$sesion->save();
            
            $cli=DB::table('paciente')->where('idpaciente','=',$idpaciente)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Paciente";
            $bitacora->descripcion="Se creo un nueva sesion de radiofrecuencia para el paciente:".$cli->nombre.", Fecha: ".$fechaSesion;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$idpaciente)
        ->first();

        $sesiones=DB::table('radiofrecuencia_sesion')
        ->where('idradiofrecuencia','=',$radiofrecuencia->idradiofrecuencia)
        ->get();

        $sesionesFotomodulacion=DB::table('radiofrecuencia_fotomodulacion')
        ->where('idradiofrecuencia','=',$radiofrecuencia->idradiofrecuencia)
        ->get();

        $sesionesLaser=DB::table('radiofrecuencia_laser')
        ->where('idradiofrecuencia','=',$radiofrecuencia->idradiofrecuencia)
        ->get();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$idpaciente)
        ->first();

        $request->session()->flash('alert-success', "Se creo una sesion de radiofrecuencia del paciente: ".$cli->nombre.", Fecha: ".$fechaSesion);

        return view("pacientes.historiales.radiofrecuencias.show",["radiofrecuencia"=>$radiofrecuencia,"paciente"=>$paciente,"sesiones"=>$sesiones,"sesionesFotomodulacion"=>$sesionesFotomodulacion,"sesionesLaser"=>$sesionesLaser, "historia"=>$historia]);
    }

    public function edit($id)
    {
        $sesion=DB::table('radiofrecuencia_sesion')
        ->where('idradiofrecuencia_sesion','=',$id)
        ->first();

        $radiofrecuencia=DB::table('radiofrecuencia as r')
            ->join('paciente as p','r.idpaciente','=','p.idpaciente')
            ->join('users as d','r.iddoctor','=','d.id')
            ->join('users as u','r.idusuario','=','u.id')
            ->select('r.idradiofrecuencia','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario','r.fototipo_piel','r.implantes','r.implantes_tipo','r.marcapasos','r.periodo_gestacion','r.glaucoma','r.neoplasias_procesos_tumorales','r.portador_epilepsia','r.antecedentes_fotosensibilidad','r.tratamientos_acidos','r.medicamentos_fotosensibles','r.resumen')
            ->where('r.idradiofrecuencia','=',$sesion->idradiofrecuencia) 
            ->first();

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$radiofrecuencia->idpaciente)
        ->first();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$radiofrecuencia->idpaciente)
        ->first();
        
       
        return view("pacientes.historiales.radiofrecuencias.sesiones.edit",["sesion"=>$sesion,"radiofrecuencia"=>$radiofrecuencia,"paciente"=>$paciente, "historia"=>$historia]);
    }

    public function update(RadiofrecuenciaSesionFormRequest $request,$id)
    {
        $fechaSesion=trim($request->get('fecha'));
        $fecha = date("Y-m-d", strtotime($fechaSesion));

        $idpaciente=$request->get('idpaciente');
        $idradiofrecuencia=$request->get('idradiofrecuencia');

        $radiofrecuencia=DB::table('radiofrecuencia as r')
            ->join('paciente as p','r.idpaciente','=','p.idpaciente')
            ->join('users as d','r.iddoctor','=','d.id')
            ->join('users as u','r.idusuario','=','u.id')
            ->select('r.idradiofrecuencia','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario','r.fototipo_piel','r.implantes','r.implantes_tipo','r.marcapasos','r.periodo_gestacion','r.glaucoma','r.neoplasias_procesos_tumorales','r.portador_epilepsia','r.antecedentes_fotosensibilidad','r.tratamientos_acidos','r.medicamentos_fotosensibles','r.resumen')
            ->where('r.idradiofrecuencia','=',$idradiofrecuencia) 
            ->first();

        $sesion=RadiofrecuenciaSesion::findOrFail($id);
        
        $sesion->monopolar_areas=$request->get('monopolar_areas');
        $sesion->monopolar_indicacion=$request->get('monopolar_indicacion');
        $sesion->monopolar_temperatura=$request->get('monopolar_temperatura');
        $sesion->monopolar_tiempo=$request->get('monopolar_tiempo');
        $sesion->monopolar_zonas_tratadas=$request->get('monopolar_zonas_tratadas');
            
        $sesion->bipolar_areas=$request->get('bipolar_areas');
        $sesion->bipolar_indicacion=$request->get('bipolar_indicacion');
        $sesion->bipolar_temperatura=$request->get('bipolar_temperatura');
        $sesion->bipolar_tiempo=$request->get('bipolar_tiempo');
        $sesion->bipolar_zonas_tratadas=$request->get('bipolar_zonas_tratadas');

        $sesion->tetrapolar_areas=$request->get('tetrapolar_areas');
        $sesion->tetrapolar_indicacion=$request->get('tetrapolar_indicacion');
        $sesion->tetrapolar_temperatura=$request->get('tetrapolar_temperatura');
        $sesion->tetrapolar_tiempo=$request->get('tetrapolar_tiempo');
        $sesion->tetrapolar_zonas_tratadas=$request->get('tetrapolar_zonas_tratadas');

        $sesion->hexapolar_areas=$request->get('hexapolar_areas');
        $sesion->hexapolar_indicacion=$request->get('hexapolar_indicacion');
        $sesion->hexapolar_temperatura=$request->get('hexapolar_temperatura');
        $sesion->hexapolar_tiempo=$request->get('hexapolar_tiempo');
        $sesion->hexapolar_zonas_tratadas=$request->get('hexapolar_zonas_tratadas');

        $sesion->ginecologico_areas=$request->get('ginecologico_areas');
        $sesion->ginecologico_indicacion=$request->get('ginecologico_indicacion');
        $sesion->ginecologico_temperatura=$request->get('ginecologico_temperatura');
        $sesion->ginecologico_tiempo=$request->get('ginecologico_tiempo');
        $sesion->ginecologico_zonas_tratadas=$request->get('ginecologico_zonas_tratadas');

        $sesion->save();

        $cli=DB::table('paciente')->where('idpaciente','=',$radiofrecuencia->idpaciente)->first();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Paciente";
        $bitacora->descripcion="Se edito una sesion de radiofrecuencia para el paciente:".$cli->nombre.", Fecha: ".$fechaSesion;
        $bitacora->save();

        $request->session()->flash('alert-success', "Se edito una sesion de radiofrecuencia del paciente: ".$cli->nombre.", Fecha: ".$fechaSesion);

        $paciente=DB::table('paciente')
        ->where('idpaciente','=',$radiofrecuencia->idpaciente)
        ->first();

        $sesiones=DB::table('radiofrecuencia_sesion')
        ->where('idradiofrecuencia','=',$radiofrecuencia->idradiofrecuencia)
        ->get();

        $sesionesFotomodulacion=DB::table('radiofrecuencia_fotomodulacion')
        ->where('idradiofrecuencia','=',$radiofrecuencia->idradiofrecuencia)
        ->get();

        $sesionesLaser=DB::table('radiofrecuencia_laser')
        ->where('idradiofrecuencia','=',$radiofrecuencia->idradiofrecuencia)
        ->get();

        $historia = DB::table('historia')
        ->where('idpaciente','=',$radiofrecuencia->idpaciente)
        ->first();

        $request->session()->flash('alert-success', "Se edito una sesion de radiofrecuencia del paciente: ".$cli->nombre.", Fecha: ".$fechaSesion);

        return view("pacientes.historiales.radiofrecuencias.show",["radiofrecuencia"=>$radiofrecuencia,"paciente"=>$paciente,"sesiones"=>$sesiones,"sesionesFotomodulacion"=>$sesionesFotomodulacion,"sesionesLaser"=>$sesionesLaser, "historia"=>$historia]);
    }

    public function eliminarsesion(Request $request)
    {
        $idradiofrecuencia = $request->get('idradiofrecuencia');
        $idpaciente = $request->get('idpaciente');
        $idradiofrecuencia_sesion = $request->get('idradiofrecuencia_sesion');
        
        $eliminarsesion=RadiofrecuenciaSesion::findOrFail($idradiofrecuencia_sesion);
        $eliminarsesion->delete();

        $request->session()->flash('alert-success', 'Se elimino la sesion de radiofrecuencia.');  
        
        
        $radiofrecuencia=DB::table('radiofrecuencia as r')
            ->join('paciente as p','r.idpaciente','=','p.idpaciente')
            ->join('users as d','r.iddoctor','=','d.id')
            ->join('users as u','r.idusuario','=','u.id')
            ->select('r.idradiofrecuencia','r.fecha','r.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario','r.fototipo_piel','r.implantes','r.implantes_tipo','r.marcapasos','r.periodo_gestacion','r.glaucoma','r.neoplasias_procesos_tumorales','r.portador_epilepsia','r.antecedentes_fotosensibilidad','r.tratamientos_acidos','r.medicamentos_fotosensibles','r.resumen')
            ->where('r.idradiofrecuencia','=',$idradiofrecuencia) 
            ->first();

        $paciente=DB::table('paciente')
            ->where('idpaciente','=',$radiofrecuencia->idpaciente)
            ->first();
    
            $sesiones=DB::table('radiofrecuencia_sesion')
            ->where('idradiofrecuencia','=',$radiofrecuencia->idradiofrecuencia)
            ->get();
    
            $sesionesFotomodulacion=DB::table('radiofrecuencia_fotomodulacion')
            ->where('idradiofrecuencia','=',$radiofrecuencia->idradiofrecuencia)
            ->get();

        $sesionesLaser=DB::table('radiofrecuencia_laser')
        ->where('idradiofrecuencia','=',$radiofrecuencia->idradiofrecuencia)
        ->get();
    
        $historia = DB::table('historia')
            ->where('idpaciente','=',$radiofrecuencia->idpaciente)
            ->first();

        return view("pacientes.historiales.radiofrecuencias.show",["radiofrecuencia"=>$radiofrecuencia,"paciente"=>$paciente,"sesiones"=>$sesiones,"sesionesFotomodulacion"=>$sesionesFotomodulacion,"sesionesLaser"=>$sesionesLaser, "historia"=>$historia]);
    }
}
