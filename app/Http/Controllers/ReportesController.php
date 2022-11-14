<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisVentasWeb\Http\Requests\ReportesFormRequest;
use DB;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

use Auth;
use sisVentasWeb\User;

class ReportesController extends Controller
{
    

    

    public function reporteusuarios(ReportesFormRequest $rrequest)
    {  
            if ($rrequest)
            {
                
                $idempresa = Auth::user()->idempresa;
                $nombreusu = Auth::user()->name;
                $empresa = Auth::user()->empresa;
                if (Auth::user()->logo == null)
                {
                    $logo = null;
                    $imagen = null;
                }
                else
                {
                     $logo = Auth::user()->logo;
                     $imagen = public_path('imagenes/logos/'.$logo);
                }

                $verpdf=trim($rrequest->get('pdf'));
                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                


                $usuarios=DB::table('users')
                ->where('idempresa','=',$idempresa)
                ->where('tipo_usuario','!=','Doctor')
                ->where('email','!=','Eliminado')
                ->orderBy('name','asc')
                ->get();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.usuarios.reporteusuarios', compact('usuarios','hoy','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                     $pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('ListadoUsuarios'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.usuarios.reporteusuarios', compact('usuarios','hoy','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                     $pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('ListadoUsuarios'.$nompdf.'.pdf');
                }
            }
        
    }

    

    public function vistausuario(ReportesFormRequest $rrequest)
    {  
            if ($rrequest)
            {
                
                $idempresa = Auth::user()->idempresa;
                $nombreusu = Auth::user()->name;
                $empresa = Auth::user()->empresa;
                $moneda = Auth::user()->moneda;
                if (Auth::user()->logo == null)
                {
                    $logo = null;
                    $imagen = null;
                }
                else
                {
                     $logo = Auth::user()->logo;
                     $imagen = public_path('imagenes/logos/'.$logo);
                     
                }
                $path = public_path('imagenes/articulos/');
                $verpdf=trim($rrequest->get('pdf'));
                $idusuario=trim($rrequest->get('rid'));
                $nombreusuario=trim($rrequest->get('rnombre'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                


                $usuario=DB::table('users')
                ->where('id','=',$idusuario)
                ->first();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.usuarios.vista.vistausuario', compact('usuario','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('VistaUsuario'.'-'.$nombreusuario.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.usuarios.vista.vistausuario', compact('usuario','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('VistaUsuario'.'-'.$nombreusuario.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    

    public function reportebitacora(ReportesFormRequest $rrequest)
    {  
            if ($rrequest)
            {
                
                $idempresa = Auth::user()->idempresa;
                $nombreusu = Auth::user()->name;
                $empresa = Auth::user()->empresa;
                if (Auth::user()->logo == null)
                {
                    $logo = null;
                    $imagen = null;
                }
                else
                {
                     $logo = Auth::user()->logo;
                     $imagen = public_path('imagenes/logos/'.$logo);
                }

                
                $verpdf=trim($rrequest->get('pdf'));
                $fecha=trim($rrequest->get('rfecha'));
                $usuario=trim($rrequest->get('rusuario'));
                $tipo=trim($rrequest->get('rtipo'));

                $usuarios=DB::table('users')
                ->where('idempresa','=',$idempresa)
                ->get();

                $usufiltro=DB::table('users')
					->select('name')
                	->where('id','=',$usuario)
                    ->get();

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                if($fecha != '1970-01-01')
                {
                    $bitacora=DB::table('bitacora as b')
                    ->join('users as u','b.idusuario','=','u.id')
                    ->select('b.idbitacora','b.idempresa','u.name','b.fecha','b.tipo','b.descripcion')
                    ->whereDate('b.fecha',$fecha)
                    ->where('u.id','LIKE','%'.$usuario.'%')
                    ->where('b.idempresa','=',$idempresa)
                    ->where('b.tipo','LIKE','%'.$tipo.'%')
                    ->orderBy('b.fecha','desc')
                    ->groupBy('b.idbitacora','b.idempresa','u.name','b.fecha','b.tipo','b.descripcion')
                    ->paginate(20);
                }
                else
                {
                    $bitacora=DB::table('bitacora as b')
                    ->join('users as u','b.idusuario','=','u.id')
                    ->select('b.idbitacora','b.idempresa','u.name','b.fecha','b.tipo','b.descripcion')
                    ->where('u.id','LIKE','%'.$usuario.'%')
                    ->where('b.idempresa','=',$idempresa)
                    ->where('b.tipo','LIKE','%'.$tipo.'%')
                    ->orderBy('b.fecha','desc')
                    ->groupBy('b.idbitacora','b.idempresa','u.name','b.fecha','b.tipo','b.descripcion')
                    ->paginate(50);
                }  
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.bitacora.reportebitacora', compact('bitacora','usuarios','fecha','tipo','usuario','hoy','usufiltro','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    return $pdf->download ('ReporteBitacora'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.bitacora.reportebitacora', compact('bitacora','usuarios','fecha','tipo','usuario','hoy','usufiltro','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    return $pdf->stream ('ReporteBitacora'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistabitacorareporte(ReportesFormRequest $rrequest)
    {  
            if ($rrequest)
            {
                
                $idempresa = Auth::user()->idempresa;
                $nombreusu = Auth::user()->name;
                $empresa = Auth::user()->empresa;
                $moneda = Auth::user()->moneda;
                if (Auth::user()->logo == null)
                {
                    $logo = null;
                    $imagen = null;
                }
                else
                {
                     $logo = Auth::user()->logo;
                     $imagen = public_path('imagenes/logos/'.$logo);
                     
                }
                $path = public_path('imagenes/articulos/');
                $verpdf=trim($rrequest->get('pdf'));
                $idbitacora=trim($rrequest->get('rid'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                $bitacora=DB::table('bitacora as b')
                    ->join('users as u','b.idusuario','=','u.id')
                    ->select('b.idbitacora','b.idempresa','u.name','b.fecha','b.tipo','b.descripcion')
                    ->where('b.idbitacora','=',$idbitacora)
                    ->where('b.idempresa','=',$idempresa)
                    ->first();

                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('reportes.bitacora.vista.vistabitacorareporte', compact('bitacora','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('Vistabitacorareporte'.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('reportes.bitacora.vista.vistabitacorareporte', compact('bitacora','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('Vistabitacorareporte'.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    
}
