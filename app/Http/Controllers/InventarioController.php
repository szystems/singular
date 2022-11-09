<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisVentasWeb\Http\Requests\IngresoFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use sisVentasWeb\Ingreso;
use sisVentasWeb\Articulo;
use sisVentasWeb\DetalleIngreso;
use sisVentasWeb\Bitacora;
use sisVentasWeb\Persona;
use Carbon\Carbon;
use DB;

use Response;
use Illuminate\Support\Collection;

use Auth;
use sisVentasWeb\User;

class InventarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        
            if ($request)
            {
                $idempresa = Auth::user()->idempresa;
                $desde=trim($request->get('desde'));
                $hasta=trim($request->get('hasta'));
                
                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');

                $desdef = date("Y-m-d", strtotime($desde));
                $hastaf = date("Y-m-d", strtotime($hasta));

                $articulof=trim($request->get('articulof'));
                $proveedorf=trim($request->get('proveedorf'));
                $presentacionf=trim($request->get('presentacionf'));
                $estadoOfertaf=trim($request->get('estadoOfertaf'));
                $estadof=trim($request->get('estadof'));

                $stockf=trim($request->get('stockf'));
                if($stockf == "Stock")
                {
                    $stock=0;
                }else
                {
                    $stock=-1;
                }

                $vigenciaf=trim($request->get('vigenciaf'));
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


                $articulos=DB::table('articulo')
                ->where('estado','=','Activo')
                ->get();

                $proveedores=DB::table('persona')
                ->where('tipo','=','Proveedor')
                ->get();

                $presentaciones=DB::table('presentacion')
                ->get();

                
                
                if($desdef != '1970-01-01' or $hastaf != '1970-01-01')
                {
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
                    
                }else
                {
                    $FechaMin = DB::table('ingreso')
					->where('estado','=','Activo')
					->first();
					if(isset($FechaMin))
					{
						$desdef = $FechaMin->fecha;
						$desdef = date("d-m-Y", strtotime($desdef));
						$hastaf = date('d-m-Y');
					}else
					{
						$desdef = date('d-m-Y');
						$hastaf = date('d-m-Y');
					}

                    $detalles=DB::table('detalle_ingreso as di')
                    ->join('ingreso as i','di.idingreso','=','i.idingreso')
                    ->join('persona as p','i.idproveedor','=','p.idpersona')
                    ->join('articulo as a','di.idarticulo','=','a.idarticulo')
                    ->join('presentacion as pr','di.idpresentacion_inventario','=','pr.idpresentacion')
                    ->select('di.iddetalle_ingreso','di.idingreso','i.idproveedor','p.nombre as Proveedor','i.fecha','i.estado as EstadoIngreso','di.idarticulo','a.nombre as Articulo','a.minimo','di.codigo as Codigo','di.cantidad_total_compra','di.total_compra','di.descripcion_inventario','di.fecha_vencimiento','di.idpresentacion_inventario','pr.nombre as Presentacion','di.cantidadxunidad','di.total_unidades_inventario','di.costo_unidad_inventario','di.precio_sugerido','di.porcentaje_utilidad','di.precio_venta','di.precio_oferta','di.estado_oferta','di.stock','di.estado as EstadoDetalle')
                    
                    ->orderBy('i.fecha','desc')
                    ->groupBy('di.iddetalle_ingreso','di.idingreso','i.idproveedor','p.nombre','i.fecha','i.estado','di.idarticulo','a.nombre','a.minimo','di.codigo','di.cantidad_total_compra','di.total_compra','di.descripcion_inventario','di.fecha_vencimiento','di.idpresentacion_inventario','pr.nombre','di.cantidadxunidad','di.total_unidades_inventario','di.costo_unidad_inventario','di.precio_sugerido','di.porcentaje_utilidad','di.precio_venta','di.precio_oferta','di.estado_oferta','di.stock','di.estado')
                    ->paginate(20);
                }
               
                return view('ventas.inventario.index',["detalles"=>$detalles,"articulos"=>$articulos,"proveedores"=>$proveedores,"presentaciones"=>$presentaciones,"articulof"=>$articulof,"proveedorf"=>$proveedorf,"presentacionf"=>$presentacionf,"estadoOfertaf"=>$estadoOfertaf,"estadof"=>$estadof,"desdef"=>$desdef,"hastaf"=>$hastaf,"stockf"=>$stockf,"vigenciaf"=>$vigenciaf,"hoy"=>$hoy]);
            } 
    }

    public function update(Request $request,$id)
    {
        //try
    	//{
            //recibir datos de edicion de articulo de inventario
            //inventario
            
            $idingreso=$request->get('idingreso');
            $total_compra=$request->get('total_compra');
            $cantidad_total_compra=$request->get('cantidad_total_compra');
            $codigo_inventario=$request->get('codigo_inventario');
            $fecha_vencimiento = $request->get('fecha_vencimiento');
            $idpresentacion_inventario = $request->get('idpresentacion_inventario');//
            $descripcion_inventario = $request->get('descripcion_inventario');
            $cantidadxunidad = $request->get('cantidadxunidad');
            $precio_sugerido = $request->get('precio_sugerido');
            $porcentaje_sujerido = $request->get('porcentaje_sujerido');
            $precio_venta = $request->get('precio_venta');
            $precio_oferta = $request->get('precio_oferta');
            $estado_oferta = $request->get('estado_oferta');

            $total_unidades_inventario_actual = $request->get('total_unidades_inventario_actual');
            $stock_actual = $request->get('stock_actual');

            //calcular cantidad y costo por cantidadxunidad
            $total_unidades_inventario = ($cantidad_total_compra*$cantidadxunidad);
            $costo_unidad_inventario = (($total_compra/$cantidad_total_compra)/$cantidadxunidad);

            if($total_unidades_inventario_actual != $stock_actual)
            {
                $stock=$stock_actual;
            }else
            {
                $stock=$total_unidades_inventario;
            }

            //calculamos el % de utilidad a partir del precio de venta y costo
            $nuevo_porcentaje_utilidad=((($precio_venta-$costo_unidad_inventario)/$costo_unidad_inventario)*100);

            //Formato fecha de vencimiento
            $fecha_vencimiento_articulo=$fecha_vencimiento;
            $fecha_vencimiento_articulo = date("Y-m-d", strtotime($fecha_vencimiento_articulo));

            //comienza transaccion
            //DB::beginTransaction();
    		$detalle =DetalleIngreso::findOrFail($id);
            //inventario
            $detalle->codigo=$codigo_inventario;
            $detalle->fecha_vencimiento=$fecha_vencimiento_articulo;
            $detalle->idpresentacion_inventario=$idpresentacion_inventario;
            $detalle->cantidadxunidad=$cantidadxunidad;
            $detalle->total_unidades_inventario=$total_unidades_inventario;
            $detalle->costo_unidad_inventario=$costo_unidad_inventario;
            $detalle->descripcion_inventario=$descripcion_inventario;
            $detalle->precio_sugerido=$precio_sugerido;
            $detalle->porcentaje_utilidad=$nuevo_porcentaje_utilidad;
            $detalle->precio_venta=$precio_venta;
            $detalle->precio_oferta=$precio_oferta;
            $detalle->estado_oferta=$estado_oferta;
            $detalle->stock=$stock;
    		$detalle->update();
            //DB::commit();
        //}
        //catch(\Exception $e)
    	//{
    		//DB::rollback();
    	//}

        return Redirect::to('ventas/inventario');
    }
}
