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
    

    public function reporteventas(ReportesFormRequest $rrequest)
    {  
            if ($rrequest)
            {
                
                $idempresa = Auth::user()->idempresa;
                $nombreusu = Auth::user()->name;
                $empresa = Auth::user()->empresa;

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');
                
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
                $desde=trim($rrequest->get('searchDesde'));
                $hasta=trim($rrequest->get('searchHasta'));
                $cliente=trim($rrequest->get('searchCliente'));
                $usuario=trim($rrequest->get('searchUsuario'));
                $saldo=trim($rrequest->get('searchSaldo'));
                $estado=trim($rrequest->get('searchEstado'));
                $tipopago=trim($rrequest->get('searchTipopago'));

                $usufiltro=DB::table('users')
                	->where('id','=',$usuario)
                    ->first();
                    
                $clientefiltro=DB::table('paciente')
                ->where('idpaciente','=',$cliente)
                ->first();

                

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                
                if ( $saldo != null )
                {
                    $ventas=DB::table('venta as v')
                    ->join('paciente as p','v.idcliente','=','p.idpaciente')
                    ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                    ->join('users as u','v.idusuario','=','u.id')
                    ->select('v.idventa','p.nombre','u.name','u.tipo_usuario','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.fecha','v.impuesto','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estado','v.estadosaldo','v.estadoventa','v.tipopago','v.idorden')
                    ->whereBetween('fecha', [$desde, $hasta])
                    ->where('p.idpaciente','LIKE','%'.$cliente.'%')
                    ->where('u.id','LIKE','%'.$usuario.'%')
                    ->where('v.estado','=','A')
                    ->where('v.estadosaldo','=',$saldo)
                    ->where('v.estadoventa','LIKE','%'.$estado.'%')
                    ->where('v.tipopago','LIKE','%'.$tipopago.'%')
                    ->orderBy('v.idventa','desc')
                    ->groupBy('v.idventa','p.nombre','u.name','u.tipo_usuario','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.fecha','v.impuesto','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estado','v.estadosaldo','v.estadoventa','v.tipopago','v.idorden')
                    ->paginate(20);
                }
                else
                {
                    $ventas=DB::table('venta as v')
                    ->join('paciente as p','v.idcliente','=','p.idpaciente')
                    ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                    ->join('users as u','v.idusuario','=','u.id')
                    ->select('v.idventa','p.nombre','u.name','u.tipo_usuario','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.fecha','v.impuesto','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estado','v.estadosaldo','v.estadoventa','v.tipopago','v.idorden')
                    ->whereBetween('fecha', [$desde, $hasta])
                    ->where('p.idpaciente','LIKE','%'.$cliente.'%')
                    ->where('u.id','LIKE','%'.$usuario.'%')
                    ->where('v.estado','=','A')
                    ->where('v.estadoventa','LIKE','%'.$estado.'%')
                    ->where('v.tipopago','LIKE','%'.$tipopago.'%')
                    ->where('v.estadosaldo','!=',NULL)
                    ->orderBy('v.idventa','desc')
                    ->groupBy('v.idventa','p.nombre','u.name','u.tipo_usuario','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.fecha','v.impuesto','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estado','v.estadosaldo','v.estadoventa','v.tipopago','v.idorden')
                    ->paginate(20);
                        
                }
            

                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.ventas.reporteventas', compact('ventas','desde','hasta','cliente','usuario','saldo','estado','tipopago','hoy','usufiltro','clientefiltro','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    return $pdf->download ('ReporteVentas'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.ventas.reporteventas', compact('ventas','desde','hasta','cliente','usuario','saldo','estado','tipopago','hoy','usufiltro','clientefiltro','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    return $pdf->stream ('ReporteVentas'.$nompdf.'.pdf');
                }
            }
        
    }

    

    public function reportecompras(ReportesFormRequest $rrequest)
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
                $desde=trim($rrequest->get('searchDesde'));
                $hasta=trim($rrequest->get('searchHasta'));
                $proveedor=trim($rrequest->get('searchProveedor'));
                $usuario=trim($rrequest->get('searchUsuario'));
                $estado=trim($rrequest->get('searchEstado'));

                $personas=DB::table('persona')
                ->where('tipo','=','Cliente')
                ->where('idempresa','=',$idempresa)
                ->get();

                $usuarios=DB::table('users')
                ->where('idempresa','=',$idempresa)
                ->get();

                $usufiltro=DB::table('users')
					->select('name')
                	->where('id','=',$usuario)
                    ->get();
                    
                $provfiltro=DB::table('persona')
                ->select('nombre')
                ->where('idpersona','=',$proveedor)
                ->get();

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                if($desde != '1970-01-01' or $hasta != '1970-01-01')
                {
                    $compras=DB::table('ingreso as i')
                    ->join('persona as p','i.idproveedor','=','p.idpersona')
                    ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
                    ->join('users as u','i.idusuario','=','u.id')
                    ->select('i.idingreso','i.fecha','p.nombre','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.total_compra) as total'))
                    ->whereBetween('fecha', [$desde, $hasta])
                    ->where('p.idpersona','LIKE','%'.$proveedor.'%')
                    ->where('u.id','LIKE','%'.$usuario.'%')
                    ->where('i.idempresa','=',$idempresa)
                    ->where('i.estado','LIKE','%'.$estado.'%')
                    ->orderBy('i.fecha','asc')
                    ->groupBy('i.idingreso','i.fecha','p.nombre','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
                    ->paginate(20);
                    //return view('pdf.ventas.reporteventas',["ventas"=>$ventas,"personas"=>$personas,"usuarios"=>$usuarios,"desde"=>$desde,"hasta"=>$hasta,"cliente"=>$cliente,"usuario"=>$usuario,"estadosaldo"=>$estadosaldo,"hoy"=>$hoy,"usufiltro"=>$usufiltro,"clientefiltro"=>$clientefiltro]);
                }
                else
                {
                    $compras=DB::table('ingreso as i')
                    ->join('persona as p','i.idproveedor','=','p.idpersona')
                    ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
                    ->join('users as u','i.idusuario','=','u.id')
                    ->select('i.idingreso','i.fecha','p.nombre','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.total_compra) as total'))
                    ->where('i.idempresa','=',$idempresa)
                    ->orderBy('i.fecha','asc')
                    ->groupBy('i.idingreso','i.fecha','p.nombre','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
                    ->paginate(20);
                }
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.compras.reportecompras', compact('compras','personas','usuarios','desde','hasta','proveedor','usuario','estado','hoy','usufiltro','provfiltro','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    return $pdf->download ('ReporteCompras'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.compras.reportecompras', compact('compras','personas','usuarios','desde','hasta','proveedor','usuario','estado','hoy','usufiltro','provfiltro','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    return $pdf->stream ('ReporteCompras'.$nompdf.'.pdf');
                }
            }
        
    }

    public function reporteinventario(ReportesFormRequest $rrequest)
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

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $desde=trim($rrequest->get('searchDesde'));
                $hasta=trim($rrequest->get('searchHasta'));

                $desdef = date("Y-m-d", strtotime($desde));
                $hastaf = date("Y-m-d", strtotime($hasta));
                
                $verpdf=trim($rrequest->get('pdf'));
                $articulof=trim($rrequest->get('searchArticulo'));
                $proveedorf=trim($rrequest->get('searchProveedor'));
                $presentacionf=trim($rrequest->get('searchPresentacion'));
                $estadoOfertaf=trim($rrequest->get('searchOferta'));
                $estadof=trim($rrequest->get('searchEstado'));
                
                $stockf=trim($rrequest->get('searchStock'));
                if($stockf == "Stock")
                {
                    $stock=0;
                }else
                {
                    $stock=-1;
                }

                $vigenciaf=trim($rrequest->get('searchVigencia'));
                if(!isset($vigenciaf))
                {
                    $vigenciaf = "";
                }
                $today = Carbon::now($zona_horaria);
                $today = $today->format('Y-m-d');
                $signo = ">=";
                if($vigenciaf == "+30 dias")
                {
                    $vigencia = date("Y-m-d", strtotime($today.'+ 30 days'));
                    $signo = ">";
                }
                elseif($vigenciaf == "-30 dias")
                {
                    $vigenciaDesde = $today;
                    $vigenciaHasta = date("Y-m-d", strtotime($today.'+ 29 days'));
                }
                elseif($vigenciaf == "Vigentes")
                {
                    $vigencia = $today;
                    $signo = ">=";
                }
                elseif($vigenciaf == "Vencidos")
                {
                    $vigencia = date("Y-m-d", strtotime($today.'- 1 days'));
                    $signo = "<";
                }
                elseif($vigenciaf == "")
                {
                    $vigencia = strtotime("1970-01-01");
                    $signo = ">=";
                }
                

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                
                    if($vigenciaf == "-30 dias")
                    {
                        $detalles=DB::table('detalle_ingreso as di')
                        ->join('ingreso as i','di.idingreso','=','i.idingreso')
                        ->join('persona as p','i.idproveedor','=','p.idpersona')
                        ->join('articulo as a','di.idarticulo','=','a.idarticulo')
                        ->join('presentacion as pr','di.idpresentacion_inventario','=','pr.idpresentacion')
                        ->select('di.iddetalle_ingreso','di.idingreso','i.idproveedor','p.nombre as Proveedor','i.fecha','i.estado as EstadoIngreso','di.idarticulo','a.nombre as Articulo','a.minimo','di.codigo as Codigo','di.cantidad_total_compra','di.total_compra','di.descripcion_inventario','di.fecha_vencimiento','di.idpresentacion_inventario','pr.nombre as Presentacion','di.cantidadxunidad','di.total_unidades_inventario','di.costo_unidad_inventario','di.precio_sugerido','di.porcentaje_utilidad','di.precio_venta','di.precio_oferta','di.estado_oferta','di.stock','di.estado as EstadoDetalle')
                        ->whereBetween('i.fecha', [$desdef, $hastaf])
                        ->where('a.nombre','LIKE','%'.$articulof.'%')
                        ->where('p.nombre','LIKE','%'.$proveedorf.'%')
                        ->where('pr.nombre','LIKE','%'.$presentacionf.'%')
                        ->where('di.estado_oferta','LIKE','%'.$estadoOfertaf.'%')
                        ->where('i.estado','LIKE','%'.$estadof.'%')
                        ->where('di.stock','>',$stock)
                        ->whereBetween('di.fecha_vencimiento', [$vigenciaDesde, $vigenciaHasta])
                        ->orderBy('i.fecha','desc')
                        ->groupBy('di.iddetalle_ingreso','di.idingreso','i.idproveedor','p.nombre','i.fecha','i.estado','di.idarticulo','a.nombre','a.minimo','di.codigo','di.cantidad_total_compra','di.total_compra','di.descripcion_inventario','di.fecha_vencimiento','di.idpresentacion_inventario','pr.nombre','di.cantidadxunidad','di.total_unidades_inventario','di.costo_unidad_inventario','di.precio_sugerido','di.porcentaje_utilidad','di.precio_venta','di.precio_oferta','di.estado_oferta','di.stock','di.estado')
                        ->paginate(20);  
                    }else
                    {
                        $detalles=DB::table('detalle_ingreso as di')
                        ->join('ingreso as i','di.idingreso','=','i.idingreso')
                        ->join('persona as p','i.idproveedor','=','p.idpersona')
                        ->join('articulo as a','di.idarticulo','=','a.idarticulo')
                        ->join('presentacion as pr','di.idpresentacion_inventario','=','pr.idpresentacion')
                        ->select('di.iddetalle_ingreso','di.idingreso','i.idproveedor','p.nombre as Proveedor','i.fecha','i.estado as EstadoIngreso','di.idarticulo','a.nombre as Articulo','a.minimo','di.codigo as Codigo','di.cantidad_total_compra','di.total_compra','di.descripcion_inventario','di.fecha_vencimiento','di.idpresentacion_inventario','pr.nombre as Presentacion','di.cantidadxunidad','di.total_unidades_inventario','di.costo_unidad_inventario','di.precio_sugerido','di.porcentaje_utilidad','di.precio_venta','di.precio_oferta','di.estado_oferta','di.stock','di.estado as EstadoDetalle')
                        ->whereBetween('i.fecha', [$desdef, $hastaf])
                        ->where('a.nombre','LIKE','%'.$articulof.'%')
                        ->where('p.nombre','LIKE','%'.$proveedorf.'%')
                        ->where('pr.nombre','LIKE','%'.$presentacionf.'%')
                        ->where('di.estado_oferta','LIKE','%'.$estadoOfertaf.'%')
                        ->where('i.estado','LIKE','%'.$estadof.'%')
                        ->where('di.stock','>',$stock)
                        ->where('di.fecha_vencimiento',$signo,$vigencia)
                        ->orderBy('i.fecha','desc')
                        ->groupBy('di.iddetalle_ingreso','di.idingreso','i.idproveedor','p.nombre','i.fecha','i.estado','di.idarticulo','a.nombre','a.minimo','di.codigo','di.cantidad_total_compra','di.total_compra','di.descripcion_inventario','di.fecha_vencimiento','di.idpresentacion_inventario','pr.nombre','di.cantidadxunidad','di.total_unidades_inventario','di.costo_unidad_inventario','di.precio_sugerido','di.porcentaje_utilidad','di.precio_venta','di.precio_oferta','di.estado_oferta','di.stock','di.estado')
                        ->paginate(20); 
                    }
                    
               

                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.inventario.reporteinventario', compact('detalles','articulof','proveedorf','presentacionf','estadoOfertaf','estadof','stockf','vigenciaf','hoy','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->setPaper('A4', 'landscape');
                    $pdf->loadHTML($view);
                    return $pdf->download ('ReporteInventario'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.inventario.reporteinventario', compact('detalles','articulof','proveedorf','presentacionf','estadoOfertaf','estadof','stockf','vigenciaf','hoy','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->setPaper('A4', 'landscape');
                    $pdf->loadHTML($view);
                    return $pdf->stream ('ReporteInventario'.$nompdf.'.pdf');
                }
            }
        
    }

    public function reporteordenes(ReportesFormRequest $rrequest)
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

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $desde=trim($rrequest->get('searchDesde'));
                $hasta=trim($rrequest->get('searchHasta'));

                $desdef = date("Y-m-d", strtotime($desde));
                $hastaf = date("Y-m-d", strtotime($hasta));
                
                $verpdf=trim($rrequest->get('pdf'));
                $pacientef=trim($rrequest->get('searchPaciente'));
                $doctorf=trim($rrequest->get('searchDoctor'));
                $usuariof=trim($rrequest->get('searchUsuario'));
                $estadoordenf=trim($rrequest->get('searchEstadoorden'));
                $estadof=trim($rrequest->get('searchEstado'));
                
                $usufiltro=DB::table('users')
                ->where('id','=',$usuariof)
                ->where('idempresa','=',$idempresa)
                ->first();
                        
                $pacientefiltro=DB::table('paciente')
                ->where('idpaciente','=',$pacientef)
                ->first();

                $docfiltro=DB::table('users')
                ->where('tipo_usuario','=','Doctor')
                ->where('id','=',$doctorf)
                ->first();
                

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                
                if($desdef != '1970-01-01' or $hastaf != '1970-01-01')
                {
                    
                    $ordenes=DB::table('orden as o')
                    ->join('paciente as p','o.idpaciente','=','p.idpaciente')
                    ->join('users as d','o.iddoctor','=','d.id')
                    ->join('users as u','o.idusuario','=','u.id')
                    ->select('o.idorden','o.idventa','o.fecha','o.estado_orden','o.estado','o.total','p.idpaciente','p.nombre as Paciente','p.sexo','p.telefono','p.fecha_nacimiento','p.dpi','p.nit','d.id as iddoctor','d.name as Doctor','d.especialidad','u.id as idusuario','u.name as Usuario','u.tipo_usuario')
                    ->whereBetween('fecha', [$desdef, $hastaf])
                    ->where('p.idpaciente','LIKE','%'.$pacientef.'%')
                    ->where('d.id','LIKE','%'.$doctorf.'%')
                    ->where('u.id','LIKE','%'.$usuariof.'%')
                    ->where('o.estado_orden','LIKE','%'.$estadoordenf.'%')
                    ->where('o.estado','LIKE','%'.$estadof.'%')
                    ->orderBy('o.idorden','desc')
                    
                    ->paginate(20);
                }
                else
                {
                    $FechaMin = DB::table('orden')
                    ->first();
                    if(isset($FechaMin))
                    {
                        $desde = $FechaMin->fecha;
                        $desde = date("d-m-Y", strtotime($desde));
                        $hasta = date('d-m-Y');
                    }else
                    {
                        $desde = date('d-m-Y');
                        $hasta = date('d-m-Y');
                    }
    
                    $ordenes=DB::table('orden as o')
                    ->join('paciente as p','o.idpaciente','=','p.idpaciente')
                    ->join('users as d','o.iddoctor','=','d.id')
                    ->join('users as u','o.idusuario','=','u.id')
                    ->select('o.idorden','o.idventa','o.fecha','o.estado_orden','o.estado','o.total','p.idpaciente','p.nombre as Paciente','p.sexo','p.telefono','p.fecha_nacimiento','p.dpi','p.nit','d.id as iddoctor','d.name as Doctor','d.especialidad','u.id as idusuario','u.name as Usuario','u.tipo_usuario')
                    ->orderBy('o.idorden','desc')
                    
                    ->paginate(20);
                }
                    
               

                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.ordenes.reporteordenes', compact('ordenes','usufiltro','docfiltro','pacientefiltro','desdef','hastaf','estadoordenf','estadof','hoy','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->setPaper('A4', 'landscape');
                    $pdf->loadHTML($view);
                    return $pdf->download ('ReporteOrdenes'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.ordenes.reporteordenes', compact('ordenes','usufiltro','docfiltro','pacientefiltro','desdef','hastaf','estadoordenf','estadof','hoy','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->setPaper('A4', 'landscape');
                    $pdf->loadHTML($view);
                    return $pdf->stream ('ReporteOrdenes'.$nompdf.'.pdf');
                }
            }
        
    }

    public function reportecategorias(ReportesFormRequest $rrequest)
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
                                
                $usuarios=DB::table('users')
                ->where('idempresa','=',$idempresa)
                ->get();

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                

                $categorias=DB::table('categoria')
                ->where ('condicion','=','1')
                ->where('idempresa','=',$idempresa)
                ->orderBy('nombre','asc')
                ->get();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.categorias.reportecategorias', compact('categorias','hoy','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    return $pdf->download ('ListadoCategorias'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.categorias.reportecategorias', compact('categorias','hoy','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    return $pdf->stream ('ListadoCategorias'.$nompdf.'.pdf');
                }
            }
        
    }

    public function reportearticulos(ReportesFormRequest $rrequest)
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
                $path = public_path('imagenes/articulos/');
                $verpdf=trim($rrequest->get('pdf'));
                $articulo=trim($rrequest->get('searchArticulo'));
                $categoria=trim($rrequest->get('searchCategoria'));
                

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                $articulos=DB::table('articulo as a')
                    ->join('categoria as c','a.idcategoria','=','c.idcategoria')
                    ->select('a.idarticulo','a.idempresa','a.nombre','a.codigo','c.nombre as categoria','a.bodega','a.ubicacion','a.descripcion','a.imagen','a.estado')
                    ->where('a.nombre','LIKE','%'.$articulo.'%')
                    ->where('c.nombre','LIKE','%'.$categoria.'%')
                    ->where('a.estado','=','Activo')
                    ->orderBy('a.nombre','asc')
                    ->get();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.articulos.reportearticulos', compact('articulos','hoy','nombreusu','empresa','imagen','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    $pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('ListadoArticulos'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.articulos.reportearticulos', compact('articulos','hoy','nombreusu','empresa','imagen','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    $pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('ListadoArticulos'.$nompdf.'.pdf');
                }
            }
        
    }

    public function reporteproveedores(ReportesFormRequest $rrequest)
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
                

                $proveedores=DB::table('persona')
                ->where ('tipo','=','Proveedor')
                ->where('idempresa','=',$idempresa)
                ->orderBy('nombre','asc')
                ->get();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.proveedores.reporteproveedores', compact('proveedores','hoy','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                     $pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('ListadoProveedores'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.proveedores.reporteproveedores', compact('proveedores','hoy','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                     $pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('ListadoProveedores'.$nompdf.'.pdf');
                }
            }
        
    }

    public function reporteclientes(ReportesFormRequest $rrequest)
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
                


                $clientes=DB::table('persona')
                ->where ('tipo','=','Cliente')
                ->where('idempresa','=',$idempresa)
                ->orderBy('nombre','asc')
                ->get();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.clientes.reporteclientes', compact('clientes','hoy','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                     $pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('ListadoClientes'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.clientes.reporteclientes', compact('clientes','hoy','nombreusu','empresa','imagen'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                     $pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('ListadoClientes'.$nompdf.'.pdf');
                }
            }
        
    }

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

    public function reportepacientes(ReportesFormRequest $rrequest)
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

                $path = public_path('imagenes/pacientes/');
                $verpdf=trim($rrequest->get('pdf'));
                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                


                $pacientes=DB::table('paciente')
                ->where('estado','=','Habilitado')
                ->orderBy('nombre','desc')
                ->get();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.pacientes.reportepacientes', compact('pacientes','hoy','nombreusu','empresa','imagen','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                     $pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('ListadoPacientes'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.pacientes.reportepacientes', compact('pacientes','hoy','nombreusu','empresa','imagen','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                     $pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('ListadoPacientes'.$nompdf.'.pdf');
                }
            }
        
    }

    public function reportedoctores(ReportesFormRequest $rrequest)
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

                $path = public_path('imagenes/pacientes/');
                $verpdf=trim($rrequest->get('pdf'));
                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                


                $doctores=DB::table('users')
                ->where('tipo_usuario','=','Doctor')
                ->where('email','!=','Eliminado')
                ->orderBy('name','desc')
                ->get();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.doctores.reportedoctores', compact('doctores','hoy','nombreusu','empresa','imagen','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                     $pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('ListadoDoctores'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.doctores.reportedoctores', compact('doctores','hoy','nombreusu','empresa','imagen','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                     $pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('ListadoDoctores'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistaarticulo(ReportesFormRequest $rrequest)
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
                $idarticulo=trim($rrequest->get('rid'));
                $nombrearticulo=trim($rrequest->get('rnombre'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                


                $articulo=DB::table('articulo as a')
                ->join('categoria as c','a.idcategoria','=','c.idcategoria')
                ->select('a.idarticulo','c.nombre as categoria','a.codigo','a.nombre','a.bodega','a.ubicacion','a.descripcion','a.imagen','a.estado')
                ->where('a.estado','=','Activo')
                ->where('a.idarticulo','=',$idarticulo)
                ->where('a.idempresa','=',$idempresa)
                ->first();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.articulos.vista.vistaarticulo', compact('articulo','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('VistaArticulo'.'-'.$nombrearticulo.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.articulos.vista.vistaarticulo', compact('articulo','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('VistaArticulo'.'-'.$nombrearticulo.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistapaciente(ReportesFormRequest $rrequest)
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
                $path = public_path('imagenes/pacientes/');
                $verpdf=trim($rrequest->get('pdf'));
                $idpaciente=trim($rrequest->get('rid'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                


                $paciente=DB::table('paciente')
                ->where('idpaciente','=',$idpaciente)
                ->first();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.pacientes.vista.vistapaciente', compact('paciente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('VistaPaciente'.'-'.$paciente->nombre.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.pacientes.vista.vistapaciente', compact('paciente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('VistaPaciente'.'-'.$paciente->nombre.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistadoctor(ReportesFormRequest $rrequest)
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
                $path = public_path('imagenes/usuarios/');
                $verpdf=trim($rrequest->get('pdf'));
                $iddoctor=trim($rrequest->get('rid'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                


                $doctor=DB::table('users')
                ->where('id','=',$iddoctor)
                ->first();

                $diasfecha = date("Y-m-d", strtotime ($hoy."- 1 days"));

                $dias=DB::table('dias')
                ->where('iddoctor','=',$iddoctor)
                ->where('fecha','>=',$diasfecha)
                ->orderBy('fecha','asc')
                ->get();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.doctores.vista.vistadoctores', compact('doctor','hoy','nombreusu','empresa','imagen','moneda','path','dias'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('VistaDoctor'.'-'.$doctor->name.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.doctores.vista.vistadoctor', compact('doctor','hoy','nombreusu','empresa','imagen','moneda','path','dias'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('VistaDoctor'.'-'.$doctor->name.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistaproveedor(ReportesFormRequest $rrequest)
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
                $idproveedor=trim($rrequest->get('rid'));
                $nombreproveedor=trim($rrequest->get('rnombre'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                


                $proveedor=DB::table('persona')
                ->where('idpersona','=',$idproveedor)
                ->first();

                $vendedores=DB::table('vendedor')
                ->where('idproveedor','=',$idproveedor)
                ->get();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.proveedores.vista.vistaproveedor', compact('proveedor','vendedores','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('VistaProveedor'.'-'.$nombreproveedor.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.proveedores.vista.vistaproveedor', compact('proveedor','vendedores','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('VistaProveedor'.'-'.$nombreproveedor.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistacliente(ReportesFormRequest $rrequest)
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
                $idcliente=trim($rrequest->get('rid'));
                $nombrecliente=trim($rrequest->get('rnombre'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                


                $cliente=DB::table('persona')
                ->where('idpersona','=',$idcliente)
                ->first();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.clientes.vista.vistacliente', compact('cliente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('Vistacliente'.'-'.$nombrecliente.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.clientes.vista.vistacliente', compact('cliente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('Vistacliente'.'-'.$nombrecliente.'-'.$nompdf.'.pdf');
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

    public function vistacita(ReportesFormRequest $rrequest)
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
                
                $verpdf=trim($rrequest->get('pdf'));
                $idcita=trim($rrequest->get('rid'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                $cita=DB::table('cita')
                ->where('idcita','=',$idcita)
                ->first();

                $paciente=DB::table('paciente')
                ->where('idpaciente','=',$cita->idpaciente)
                ->first();

                $doctor=DB::table('users')
                ->where('id','=',$cita->iddoctor)
                ->first();

                $usuario=DB::table('users')
                ->where('id','=',$cita->idusuario)
                ->first();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.citas.vista.vistacita', compact('cita','paciente','doctor','usuario','hoy','nombreusu','empresa','imagen','moneda'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('VistaCita'.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.citas.vista.vistacita', compact('cita','paciente','doctor','usuario','hoy','nombreusu','empresa','imagen','moneda'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('VistaCita'.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistaventa(ReportesFormRequest $rrequest)
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
                $idventa=trim($rrequest->get('rid'));
                $comprobante=trim($rrequest->get('rcomprobante'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                $venta=DB::table('venta as v')
                ->join('paciente as p','v.idcliente','=','p.idpaciente')
                ->join('users as u','v.idusuario','=','u.id')
                ->where('v.idventa','=',$idventa)
                ->first();


                $detalles=DB::table('detalle_venta as d')
                ->join('articulo as a','d.idarticulo','=','a.idarticulo')
                ->join('detalle_ingreso as di','d.iddetalle_ingreso','=','di.iddetalle_ingreso')
                ->select('d.iddetalle_ingreso','a.nombre as articulo','di.codigo','d.cantidad','d.descuento','d.precio_compra','d.precio_venta','d.precio_oferta')
                ->where('d.idventa','=',$idventa)
                ->get();

                if($detalles->count() == 0)
                {
                $detalles=DB::table('detalle_venta as d')
                    ->join('articulo as a','d.idarticulo','=','a.idarticulo')
                    ->select('iddetalle_ingreso','a.nombre as articulo','a.codigo','d.cantidad','d.descuento','d.precio_compra','d.precio_venta')
                    ->where('d.idventa','=',$idventa)
                    ->get();
                }
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.ventas.vista.vistaventa', compact('venta','detalles','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('Vistaventa'.'-'.$comprobante.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.ventas.vista.vistaventa', compact('venta','detalles','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('Vistaventa'.'-'.$comprobante.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistaorden(ReportesFormRequest $rrequest)
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
                $idorden=trim($rrequest->get('rid'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                $orden=DB::table('orden as o')
                ->join('paciente as p','o.idpaciente','=','p.idpaciente')
                ->join('users as d','o.iddoctor','=','d.id')
                ->join('users as u','o.idusuario','=','u.id')
                ->select('o.idorden','o.fecha','o.codigoeeps','o.codigopapanicolau','o.observaciones','o.estado_orden','o.estado','o.total','p.idpaciente','p.nombre as Paciente','p.sexo','p.telefono','p.fecha_nacimiento','p.dpi','p.nit','d.id as iddoctor','d.name as Doctor','d.especialidad','u.id as idusuario','u.name as Usuario','u.tipo_usuario','o.idventa')
                ->where('o.idorden','=',$idorden)   
                ->first();

                $rubros=DB::table('rubro')
                ->get();

                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.ordenes.vista.vistaorden', compact('orden','rubros','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('Vistaorden'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.ordenes.vista.vistaorden', compact('orden','rubros','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('Vistaorden'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistaventareporte(ReportesFormRequest $rrequest)
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
                $idventa=trim($rrequest->get('rid'));
                $comprobante=trim($rrequest->get('rcomprobante'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                $venta=DB::table('venta as v')
                    ->join('persona as p','v.idcliente','=','p.idpersona')
                    ->join('users as u','v.idusuario','=','u.id')
                    ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                    ->select('v.idventa','v.fecha','p.nombre','p.tipo_documento','p.num_documento','p.telefono','p.direccion','u.name','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estadosaldo','v.estadoventa','tipopago','v.idorden')
                    ->where('v.idventa','=',$idventa)
                    ->where('v.idempresa','=',$idempresa)
                    ->first();

                $detalles=DB::table('detalle_venta as d')
                    ->join('articulo as a','d.idarticulo','=','a.idarticulo')
                    ->select('a.nombre as articulo','a.codigo','d.cantidad','d.descuento','d.precio_compra','d.precio_venta')
                    ->where('d.idventa','=',$idventa)
                    ->get();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('reportes.ventas.vista.vistaventareporte', compact('venta','detalles','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('Vistaventareporte'.'-'.$comprobante.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('reportes.ventas.vista.vistaventareporte', compact('venta','detalles','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('Vistaventareporte'.'-'.$comprobante.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistacompra(ReportesFormRequest $rrequest)
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
                $idcompra=trim($rrequest->get('rid'));
                $comprobante=trim($rrequest->get('rcomprobante'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                

                $ingreso=DB::table('ingreso as i')
                    ->join('persona as p','i.idproveedor','=','p.idpersona')
                    ->join('users as u','i.idusuario','=','u.id')
                    ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
                    ->select('i.idingreso','i.fecha','p.nombre','p.tipo_documento','p.num_documento','p.telefono','p.direccion','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.total_compra) as total'))
                    ->groupBy('i.idingreso','i.fecha','p.nombre','p.tipo_documento','p.num_documento','p.telefono','p.direccion','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
                    ->where('i.idingreso','=',$idcompra)
                    ->where('i.idempresa','=',$idempresa)
                    ->first();
        
                $detalles=DB::table('detalle_ingreso as d')
                    ->join('articulo as a','d.idarticulo','=','a.idarticulo')
                    ->join('presentacion as pc','d.idpresentacion_compra','=','pc.idpresentacion')
                    ->join('presentacion as pi','d.idpresentacion_inventario','=','pi.idpresentacion')
                    ->select('d.iddetalle_ingreso','d.idingreso','d.idarticulo','a.codigo as CodigoIngreso','a.nombre as articulo','d.codigo as CodigoInventario','a.minimo','d.idpresentacion_compra','pc.nombre as PresentacionCompra','d.cantidad_compra','d.bonificacion','d.cantidad_total_compra','d.costo_unidad_compra','d.sub_total_compra','d.descuento','d.total_compra','d.fecha_vencimiento','d.idpresentacion_inventario','pi.nombre as PresentacionInventario','d.cantidadxunidad','d.total_unidades_inventario','d.costo_unidad_inventario','d.descripcion_inventario','d.precio_venta','d.precio_oferta','d.estado_oferta','d.stock','d.estado')
                    ->where('d.idingreso','=',$idcompra)
                    ->get();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.compras.vista.vistacompra', compact('ingreso','detalles','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    $pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('Vistacompra'.'-'.$comprobante.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.compras.vista.vistacompra', compact('ingreso','detalles','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    $pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('Vistacompra'.'-'.$comprobante.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistacomprareporte(ReportesFormRequest $rrequest)
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
                $idcompra=trim($rrequest->get('rid'));
                $comprobante=trim($rrequest->get('rcomprobante'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                $ingreso=DB::table('ingreso as i')
                    ->join('persona as p','i.idproveedor','=','p.idpersona')
                    ->join('users as u','i.idusuario','=','u.id')
                    ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
                    ->select('i.idingreso','i.fecha','p.nombre','p.tipo_documento','p.num_documento','p.telefono','p.direccion','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))
                    ->groupBy('i.idingreso','i.fecha','p.nombre','p.tipo_documento','p.num_documento','p.telefono','p.direccion','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
                    ->where('i.idingreso','=',$idcompra)
                    ->where('i.idempresa','=',$idempresa)
                    ->first();

                $detalles=DB::table('detalle_ingreso as d')
                    ->join('articulo as a','d.idarticulo','=','a.idarticulo')
                    ->select('a.codigo as codigo','a.nombre as articulo','d.cantidad','d.precio_compra','d.precio_venta','d.precio_oferta')
                    ->where('d.idingreso','=',$idcompra)
                    ->get();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('reportes.ingresos.vista.vistacomprareporte', compact('ingreso','detalles','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('Vistacomprareporte'.'-'.$comprobante.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('reportes.ingresos.vista.vistacomprareporte', compact('ingreso','detalles','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('Vistacomprareporte'.'-'.$comprobante.'-'.$nompdf.'.pdf');
                }
            }
        
    }
    
    
    public function vistacotizacion(ReportesFormRequest $rrequest)
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
                $idcotizacion=trim($rrequest->get('rid'));
                $cliente=trim($rrequest->get('rcliente'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                $cotizacion=DB::table('cotizacion as v')
                    ->join('persona as p','v.idcliente','=','p.idpersona')
                    ->join('users as u','v.idusuario','=','u.id')
                    ->join('detalle_cotizacion as dv','v.idcotizacion','=','dv.idcotizacion')
                    ->select('v.idcotizacion','v.fecha','p.nombre','p.tipo_documento','p.num_documento','p.telefono','p.direccion','u.name','v.tipo_comprobante','v.impuesto','v.estado','v.total_cotizacion','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','observaciones')
                    ->where('v.idcotizacion','=',$idcotizacion)
                    ->where('v.idempresa','=',$idempresa)
                    ->first();

                $detalles=DB::table('detalle_cotizacion as d')
                    ->join('articulo as a','d.idarticulo','=','a.idarticulo')
                    ->select('a.nombre as articulo','a.descripcion as descripcion','a.imagen as imagen','a.codigo','d.cantidad','d.descuento','d.precio_compra','d.precio_venta')
                    ->where('d.idcotizacion','=',$idcotizacion)
                    ->get();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.cotizaciones.vista.vistacotizacion', compact('cotizacion','detalles','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('Vistacotizacion'.'-'.$cliente.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.cotizaciones.vista.vistacotizacion', compact('cotizacion','detalles','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('Vistacotizacion'.'-'.$cliente.'-'.$nompdf.'.pdf');
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

    public function reportecitas(ReportesFormRequest $rrequest)
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
                $mananaBuscar = date("Y-m-d", strtotime($fecha.'+ 1 days'));
                $doctor=trim($rrequest->get('rdoctor'));

                $usuarios=DB::table('users')
                ->where('idempresa','=',$idempresa)
                ->get();

                $docfiltro=DB::table('users')
					->select('name')
                	->where('id','=',$doctor)
                    ->first();

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                if($fecha != '1970-01-01')
                {
                    $citas=DB::table('cita')
                    ->where('iddoctor','LIKE', '%'.$doctor.'%')
                    ->where('fecha_inicio','>=', $fecha)
                    ->where('fecha_inicio','<', $mananaBuscar)
                    ->orderBy('fecha_inicio','asc')
                    ->paginate(20);
                }
                else
                {
                    $citas=DB::table('cita')
                    ->where('fecha_inicio','>=',$fecha)
                    ->where('fecha_inicio','<',$manana)
                    ->orderBy('fecha_inicio','asc')
                    ->paginate(20);
                }  
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.citas.reportecitas', compact('citas','fecha','doctor','hoy','nombreusu','empresa','imagen','docfiltro'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    return $pdf->download ('ReporteCitas'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.citas.reportecitas', compact('citas','fecha','doctor','hoy','nombreusu','empresa','imagen','docfiltro'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    return $pdf->stream ('ReporteCitas'.$nompdf.'.pdf');
                }
            }
        
    }

    public function reportearticuloscliente(ReportesFormRequest $rrequest)
    {  
            if ($rrequest)
            {
                $datosConfig=DB::table('users')->first();
                $idempresa = $datosConfig->idempresa;
                $nombreusu = $datosConfig->name;
                $empresa = $datosConfig->empresa;
                if ($datosConfig->logo == null)
                {
                    $logo = null;
                    $imagen = null;
                }
                else
                {
                     $logo = $datosConfig->logo;
                     $imagen = public_path('imagenes/logos/'.$logo);
                     
                }
                $path = public_path('imagenes/articulos/');
                $verpdf=trim($rrequest->get('pdf'));
                $stock=trim($rrequest->get('rstock'));
                $oferta=trim($rrequest->get('roferta'));
                

                $zona_horaria = $datosConfig->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                if ( $stock == "Stock" )
                {
                    $articulos=DB::table('articulo as a')
                    ->join('categoria as c','a.idcategoria','=','c.idcategoria')
                    ->select('a.idarticulo','a.idempresa','a.nombre','a.codigo','a.stock','c.nombre as categoria','a.bodega','a.ubicacion','a.descripcion','a.imagen','a.estado','a.ultimo_precio_venta','a.ultimo_precio_compra','ultimo_precio_oferta','oferta_activar')
                    ->where('a.idempresa','=',$idempresa)
                    ->where('a.estado','=','Activo')
                    ->where('a.activar_tienda','=','Activado')
                    ->where('a.stock','>',0)
                    ->where('a.oferta_activar','LIKE','%'.$oferta.'%')
                    ->orderBy('c.nombre','asc')
                    ->orderBy('a.nombre','asc')
                    ->get();
                }
                else
                {
                    $articulos=DB::table('articulo as a')
                    ->join('categoria as c','a.idcategoria','=','c.idcategoria')
                    ->select('a.idarticulo','a.idempresa','a.nombre','a.codigo','a.stock','c.nombre as categoria','a.bodega','a.ubicacion','a.descripcion','a.imagen','a.estado','a.ultimo_precio_venta','a.ultimo_precio_compra','ultimo_precio_oferta','oferta_activar')
                    ->where('a.idempresa','=',$idempresa)
                    ->where('a.estado','=','Activo')
                    ->where('a.activar_tienda','=','Activado')
                    ->where('a.oferta_activar','LIKE','%'.$oferta.'%')
                    ->orderBy('c.nombre','asc')
                    ->orderBy('a.nombre','asc')
                    ->get();
                }
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.articulos.cliente.reportearticulos', compact('articulos','hoy','nombreusu','empresa','imagen','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    $pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('ListadoArticulos'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.articulos.cliente.reportearticulos', compact('articulos','hoy','nombreusu','empresa','imagen','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    $pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('ListadoArticulos'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistareceta(ReportesFormRequest $rrequest)
    {  
            if ($rrequest)
            {
                
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
                $idreceta=trim($rrequest->get('rid'));
                $idpaciente=trim($rrequest->get('ridpaciente'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                $receta=DB::table('receta as r')
                ->join('paciente as p','r.idpaciente','=','p.idpaciente')
                ->join('users as d','r.iddoctor','=','d.id')
                ->join('users as u','r.idusuario','=','u.id')
                ->select('r.idreceta','r.fecha','r.iddoctor','d.name as Doctor','d.no_colegiado','d.foto as Imgdoctor','d.especialidad','r.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','r.idusuario','u.name as Usuario','u.tipo_usuario')
                ->where('r.idreceta','=',$idreceta) 
                ->first();

                $paciente=DB::table('paciente')
                ->where('idpaciente','=',$idpaciente)
                ->first();

                $detalles=DB::table('receta_medicamento as rm')
                ->join('presentacion as p','rm.presentacion','=','p.idpresentacion')
                ->where('idreceta','=',$idreceta) 
                ->get();

                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.recetas.vistareceta', compact('receta','detalles','paciente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('VistaReceta'.'-'.$paciente->nombre.'-'.$receta->fecha.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.recetas.vistareceta', compact('receta','detalles','paciente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('VistaReceta'.'-'.$paciente->nombre.'-'.$receta->fecha.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistahistoria(ReportesFormRequest $rrequest)
    {  
            if ($rrequest)
            {
                
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
                $idhistoria=trim($rrequest->get('rid'));
                $idpaciente=trim($rrequest->get('ridpaciente'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                $historia = DB::table('historia')
                ->where('idpaciente','=',$idpaciente)
                ->first();

                $paciente=DB::table('paciente')
                ->where('idpaciente','=',$idpaciente)
                ->first();
                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.historias.vistahistoria', compact('historia','paciente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('VistaHistoria'.'-'.$paciente->nombre.'-'.$historia->fecha.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.historias.vistahistoria', compact('historia','paciente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('VistaHistoria'.'-'.$paciente->nombre.'-'.$historia->fecha.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistafisico(ReportesFormRequest $rrequest)
    {  
            if ($rrequest)
            {
                
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
                $path = public_path('imagenes/fisicos/');
                $verpdf=trim($rrequest->get('pdf'));
                $idfisico=trim($rrequest->get('rid'));
                $idpaciente=trim($rrequest->get('ridpaciente'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                $fisico=DB::table('fisico as f')
                ->join('paciente as p','f.idpaciente','=','p.idpaciente')
                ->join('users as d','f.iddoctor','=','d.id')
                ->join('users as u','f.idusuario','=','u.id')
                ->select('f.idfisico','f.fecha','f.iddoctor','d.name as Doctor','d.especialidad','f.idpaciente','p.nombre as Paciente','f.idusuario','u.name as Usuario','u.tipo_usuario','f.motivo_consulta','f.peso','f.talla','f.perimetro_abdominal','f.presion_arterial','f.frecuencia_cardiaca','f.frecuencia_respiratoria','f.temperatura','f.saturacion_oxigeno','f.impresion_clinica','f.plan_diagnostico','f.plan_tratamiento','f.recomendaciones_generales','f.recomendaciones_especificas','f.cabeza_cuello','f.tiroides','f.mamas_axilas','f.cardiopulmonar','f.abdomen','f.genitales_externos','f.especuloscopia','f.tacto_bimanual','f.miembros_inferiores')
                ->where('f.idfisico','=',$idfisico) 
                ->first();

                $paciente=DB::table('paciente')
                ->where('idpaciente','=',$idpaciente)
                ->first();

                $fisicoimgs=DB::table('fisico_img')
                ->where('idfisico','=',$idfisico) 
                ->get();

                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.fisicos.vistafisico', compact('fisico','paciente','fisicoimgs','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('VistaExamenFisico'.'-'.$paciente->nombre.'-'.$fisico->fecha.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.fisicos.vistafisico', compact('fisico','paciente','fisicoimgs','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('VistaExamenFisico'.'-'.$paciente->nombre.'-'.$fisico->fecha.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistaembarazo(ReportesFormRequest $rrequest)
    {  
            if ($rrequest)
            {
                
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
                $path = public_path('imagenes/embarazos/');
                $verpdf=trim($rrequest->get('pdf'));
                $idembarazo=trim($rrequest->get('rid'));
                $idpaciente=trim($rrequest->get('ridpaciente'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
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

                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.embarazos.vistaembarazo', compact('embarazo','historia','controles','embarazoimgs','paciente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    $pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('VistaEmbarazo'.'-'.$paciente->nombre.'-'.$embarazo->fecha.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.embarazos.vistaembarazo', compact('embarazo','historia','controles','embarazoimgs','paciente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    $pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('VistaEmbarazo'.'-'.$paciente->nombre.'-'.$embarazo->fecha.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistaradiofrecuencia(ReportesFormRequest $rrequest)
    {  
            if ($rrequest)
            {
                
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
                $idradiofrecuencia=trim($rrequest->get('rid'));
                $idpaciente=trim($rrequest->get('ridpaciente'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
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

                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.radiofrecuencias.vistaradiofrecuencia', compact('radiofrecuencia','historia','sesiones','sesionesFotomodulacion','sesionesLaser','paciente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    $pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('VistaRadiofrecuencia'.'-'.$paciente->nombre.'-'.$radiofrecuencia->fecha.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.radiofrecuencias.vistaradiofrecuencia', compact('radiofrecuencia','historia','sesiones','sesionesFotomodulacion','sesionesLaser','paciente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    $pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('VistaRadiofrecuencia'.'-'.$paciente->nombre.'-'.$radiofrecuencia->fecha.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistasillaciclo(ReportesFormRequest $rrequest)
    {  
            if ($rrequest)
            {
                
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
                $idSillaCiclo=trim($rrequest->get('rid'));
                $idpaciente=trim($rrequest->get('ridpaciente'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                $sillaCiclo=DB::table('sillae_ciclo as s')
                ->join('paciente as p','s.idpaciente','=','p.idpaciente')
                ->join('users as d','s.iddoctor','=','d.id')
                ->join('users as u','s.idusuario','=','u.id')
                ->select('s.idsillae_ciclo','s.fecha','s.ciclo_numero','s.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','s.idpaciente','p.nombre as Paciente','p.foto as Imgpaciente','s.idusuario','u.name as Usuario','u.tipo_usuario')
                ->where('s.idsillae_ciclo','=',$idSillaCiclo) 
                ->first();

                $paciente=DB::table('paciente')
                ->where('idpaciente','=',$sillaCiclo->idpaciente)
                ->first();

                $sesiones=DB::table('sillae_ciclo_sesion')
                ->where('idsillae_ciclo','=',$sillaCiclo->idsillae_ciclo)
                ->get();

                $historia = DB::table('historia')
                ->where('idpaciente','=',$sillaCiclo->idpaciente)
                ->first();

                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.sillas.vistasillaciclo', compact('sillaCiclo','historia','sesiones','paciente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    $pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('VistaSillaElectromagnetica'.'-'.$paciente->nombre.'-'.$sillaCiclo->fecha.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.sillas.vistasillaciclo', compact('sillaCiclo','historia','sesiones','paciente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    $pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('VistaSillaElectromagnetica'.'-'.$paciente->nombre.'-'.$sillaCiclo->fecha.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistaclimaymeno(ReportesFormRequest $rrequest)
    {  
            if ($rrequest)
            {
                
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
                $path = public_path('imagenes/climaymenos/');
                $verpdf=trim($rrequest->get('pdf'));
                $idclimaymeno=trim($rrequest->get('rid'));
                $idpaciente=trim($rrequest->get('ridpaciente'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
                $climaymeno=DB::table('climaymeno as c')
                ->join('paciente as p','c.idpaciente','=','p.idpaciente')
                ->join('users as d','c.iddoctor','=','d.id')
                ->join('users as u','c.idusuario','=','u.id')
                ->select('c.idclimaymeno','c.fecha','c.iddoctor','d.name as Doctor','d.foto as Imgdoctor','d.especialidad','c.idpaciente','p.nombre as Paciente','p.sexo','p.foto as Imgdpaciente','c.idusuario','u.name as Usuario','u.tipo_usuario')
                ->where('c.idclimaymeno','=',$idclimaymeno) 
                ->orderby('c.fecha','desc')
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
                ->where('idclimaymeno','=',$climaymeno->idclimaymeno) 
                ->get();

                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.climaymenos.vistaclimaymeno', compact('climaymeno','historia','controles','climaymenoimgs','paciente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    $pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('Vista_Climaterio_Menopausea'.'-'.$paciente->nombre.'-'.$climaymeno->fecha.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.climaymenos.vistaclimaymeno', compact('climaymeno','historia','controles','climaymenoimgs','paciente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    $pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('Vista_Climaterio_Menopausea'.'-'.$paciente->nombre.'-'.$climaymeno->fecha.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistaincontinencia(ReportesFormRequest $rrequest)
    {  
            if ($rrequest)
            {
                
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
                $idincontinencia=trim($rrequest->get('rid'));
                $idpaciente=trim($rrequest->get('ridpaciente'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');
                
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

                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.incontinencias.vistaincontinencia', compact('incontinencia','historia','cuestionarios','paciente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    $pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('Vista_Incontinencia_Urinaria'.'-'.$paciente->nombre.'-'.$incontinencia->fecha.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.incontinencias.vistaincontinencia', compact('incontinencia','historia','cuestionarios','paciente','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    $pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('Vista_Incontinencia_Urinaria'.'-'.$paciente->nombre.'-'.$incontinencia->fecha.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistacolposcopia(ReportesFormRequest $rrequest)
    {  
            if ($rrequest)
            {
                
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
                $path = public_path('imagenes/colposcopias/');
                $verpdf=trim($rrequest->get('pdf'));
                $idcolposcopia=trim($rrequest->get('rid'));
                $idpaciente=trim($rrequest->get('ridpaciente'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');

                $colposcopia=DB::table('colposcopia as c')
                ->join('paciente as p','c.idpaciente','=','p.idpaciente')
                ->join('users as d','c.iddoctor','=','d.id')
                ->join('users as u','c.idusuario','=','u.id')
                ->select('c.idcolposcopia','c.fecha','c.iddoctor','d.name as Doctor','d.especialidad','c.idpaciente','p.nombre as Paciente','c.idusuario','u.name as Usuario','u.tipo_usuario','c.union_escamoso_cilindrica','c.legrado_endocervical','colposcopia_insatisfactoria','hd_eap','hd_eam','hd_leucoplasia','hd_punteando','hd_mosaico','hd_vasos','hd_area','hd_otros','hd_otros_especificar','hallazgos_fuera','carcinoma_invasor','otros_hallazgos','dcn_insatisfactoria','dcn_insatisfactoria_especifique','hallazgos_nomales','inflamacion_infeccion','inflamacion_infeccion_especifique','biopsia','numero_localizacion','legrado','otros_hallazgos_colposcopicos')
                ->where('c.idcolposcopia','=',$idcolposcopia) 
                ->first();

                $paciente=DB::table('paciente')
                ->where('idpaciente','=',$idpaciente)
                ->first();

                $colposcopiaimgs=DB::table('colposcopia_img')
                ->where('idcolposcopia','=',$idcolposcopia) 
                ->get();

                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.colposcopias.vistacolposcopia', compact('colposcopia','paciente','colposcopiaimgs','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('VistaColposcopia'.'-'.$paciente->nombre.'-'.$colposcopia->fecha.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.colposcopias.vistacolposcopia', compact('colposcopia','paciente','colposcopiaimgs','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('VistaColposcopia'.'-'.$paciente->nombre.'-'.$colposcopia->fecha.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    public function vistaultrasonido(ReportesFormRequest $rrequest)
    {  
            if ($rrequest)
            {
                
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
                $path = public_path('imagenes/ultrasonidos/');
                $verpdf=trim($rrequest->get('pdf'));
                $idultrasonido=trim($rrequest->get('rid'));
                $idpaciente=trim($rrequest->get('ridpaciente'));

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $nompdf = Carbon::now($zona_horaria);
                $nompdf = $nompdf->format('Y-m-d H:i:s');

                $ultrasonido=DB::table('ultrasonido_obstetrico as uo')
                ->join('paciente as p','uo.idpaciente','=','p.idpaciente')
                ->join('users as d','uo.iddoctor','=','d.id')
                ->join('users as u','uo.idusuario','=','u.id')
                ->select('uo.idultrasonido_obstetrico','uo.fecha','uo.iddoctor','d.name as Doctor','d.especialidad','d.no_colegiado','uo.idpaciente','p.nombre as Paciente','uo.idusuario','u.name as Usuario','u.tipo_usuario','uo.spp','uo.fcardiaca_fetal','pubicacion','liquido_amniotico','utero_anexos','cervix','diametro_biparietal_medida','diametro_biparietal_semanas','circunferencia_cefalica_medida','circunferencia_cefalica_semanas','circunferencia_abdominal_medida','circunferencia_abdominal_semanas','longitud_femoral_medida','longitud_femoral_semanas','fetometria','peso_estimado','percentilo','comentarios','interpretacion','recomendaciones','observaciones','embarazo_unico','embarazo_unico_comentar','alteraciones_crecimiento','alteraciones_crecimiento_comentar','alteraciones_frecuencia','alteraciones_frecuencia_comentar','placenta','placenta_comentar','liquido','liquido_comentar','prematuro','prematuro_comentar')
                ->where('uo.idultrasonido_obstetrico','=',$idultrasonido) 
                ->first();

                $paciente=DB::table('paciente')
                ->where('idpaciente','=',$idpaciente)
                ->first();

                $ultrasonidoimgs=DB::table('ultrasonido_obstetrico_img')
                ->where('idultrasonido_obstetrico','=',$idultrasonido) 
                ->get();

                $historia = DB::table('historia')
                ->where('idpaciente','=',$ultrasonido->idpaciente)
                ->first();

                
                if ( $verpdf == "Descargar" )
                {
                    $view = \View::make('pdf.ultrasonidos.vistaultrasonido', compact('ultrasonido','paciente','ultrasonidoimgs','historia','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->download ('VistaUltrasonidoObstetrico'.'-'.$paciente->nombre.'-'.$ultrasonido->fecha.'-'.$nompdf.'.pdf');
                }
                if ( $verpdf == "Navegador" )
                {
                    $view = \View::make('pdf.ultrasonidos.vistaultrasonido', compact('ultrasonido','paciente','ultrasonidoimgs','historia','hoy','nombreusu','empresa','imagen','moneda','path'))->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->loadHTML($view);
                    //$pdf->setPaper('A4', 'landscape');
                    return $pdf->stream ('VistaUltrasonidoObstetrico'.'-'.$paciente->nombre.'-'.$ultrasonido->fecha.'-'.$nompdf.'.pdf');
                }
            }
        
    }

    
}
