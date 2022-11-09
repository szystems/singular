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

class IngresoController extends Controller
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

                $desde = date("Y-m-d", strtotime($desde));
                $hasta = date("Y-m-d", strtotime($hasta));

                $proveedor=trim($request->get('proveedor'));
                $usuario=trim($request->get('usuario'));
                $estado=trim($request->get('estado'));

                $personas=DB::table('persona')
                ->where('tipo','=','Proveedor')
                ->where('idempresa','=',$idempresa)
                ->get();

                $usuarios=DB::table('users')
                ->where('idempresa','=',$idempresa)
                ->get();

                $usufiltro=DB::table('users')
                    ->where('id','=',$usuario)
                    ->first();
                if($proveedor != null)
                {
                    $provfiltro=DB::table('persona')
                    ->where('idpersona','=',$proveedor)
                    ->first();
                }else
                {
                    $provfiltro = null;
                }   
                

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');
    				
                if($desde != '1970-01-01' or $hasta != '1970-01-01')
                {
                    $ingresos=DB::table('ingreso as i')
                    ->join('persona as p','i.idproveedor','=','p.idpersona')
                    ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
                    ->join('users as u','i.idusuario','=','u.id')
                    ->select('i.idingreso','i.fecha','p.nombre','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.total_compra) as total'))
                    ->whereBetween('fecha', [$desde, $hasta])
                    ->where('p.idpersona','LIKE','%'.$proveedor.'%')
                    ->where('u.id','LIKE','%'.$usuario.'%')
                    ->where('i.idempresa','=',$idempresa)
                    ->where('i.estado','LIKE','%'.$estado.'%')
                    ->orderBy('i.fecha','desc')
                    ->groupBy('i.idingreso','i.fecha','p.nombre','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
                    ->paginate(20);
                }
            
                else
                {
                    $ingresos=DB::table('ingreso as i')
                    ->join('persona as p','i.idproveedor','=','p.idpersona')
                    ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
                    ->join('users as u','i.idusuario','=','u.id')
                    ->select('i.idingreso','i.fecha','p.nombre','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.total_compra) as total'))
                    ->where('i.idempresa','=',$idempresa)
                    ->orderBy('i.fecha','desc')
                    ->groupBy('i.idingreso','i.fecha','p.nombre','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
                    ->paginate(20);
                }
                return view('compras.ingreso.index',["ingresos"=>$ingresos,"personas"=>$personas,"usuarios"=>$usuarios,"desde"=>$desde,"hasta"=>$hasta,"proveedor"=>$proveedor,"usuario"=>$usuario,"estado"=>$estado,"hoy"=>$hoy,"usufiltro"=>$usufiltro,"provfiltro"=>$provfiltro]);
            }

        
    }

    public function create()
    {
         $idempresa = Auth::user()->idempresa;
    	$personas=DB::table('persona')
        ->where('tipo','=','Proveedor')
        ->where('idempresa','=',$idempresa)
        ->get();
    	$articulos=DB::table('articulo as art')
    	->select(DB::raw('art.nombre AS articulo'),'art.idarticulo','art.descripcion','art.codigo','art.nombre')
    	->where('art.estado','=','Activo')
        ->where('art.idempresa','=',$idempresa)
    	->get();
        $presentaciones=DB::table('presentacion as pres')
    	->select('pres.nombre','pres.idpresentacion','pres.descripcion')
    	->where('pres.estado','=','Habilitado')
    	->get();
    	return view("compras.ingreso.create",["personas"=>$personas,"articulos"=>$articulos,"presentaciones"=>$presentaciones]);
    }

    public function store (IngresoFormRequest $request)
    {
    	
    //try
    	//{ 
         
            $idempresa = Auth::user()->idempresa;
            $porcentaje_imp = Auth::user()->porcentaje_imp;
            $max_descuento = Auth::user()->max_descuento;
            $idusuario = Auth::user()->id;
            $zona_horaria = Auth::user()->zona_horaria;

            $fecha=trim($request->get('fecha'));
            $fecha = date("Y-m-d", strtotime($fecha));

    		//DB::beginTransaction();

    		$ingreso=new Ingreso;
    		$ingreso->idempresa=$idempresa;
    		$ingreso->idproveedor=$request->get('idproveedor');
    		$ingreso->idusuario=$idusuario;
    		$ingreso->tipo_comprobante=$request->get('tipo_comprobante');
    		$ingreso->serie_comprobante=$request->get('serie_comprobante');
            $ingreso->num_comprobante=$request->get('num_comprobante');
    		$ingreso->fecha=$fecha;
    		$ingreso->impuesto=$request->get('impuesto');
    		$ingreso->estado='Activo';
            
    		$ingreso->save();

            //datos compra
    		$idarticulo = $request->get('idarticulo');
            $idpresentacion_compra = $request->get('idpresentacion_compra');
    		$cantidad_compra = $request->get('cantidad_compra');
            $bonificacion = $request->get('bonificacion');
            $cantidad_total_compra = $request->get('cantidad_total_compra');
    		$costo_unidad_compra = $request->get('costo_unidad_compra');
            $sub_total_compra = $request->get('sub_total_compra');
            $descuento = $request->get('descuento');
            $total_compra = $request->get('total_compra');
            //inventario
            $codigo = $request->get('codigo_inventario');
            $fecha_vencimiento = $request->get('fecha_vencimiento');
            $idpresentacion_inventario = $request->get('idpresentacion_inventario');
            $cantidadxunidad = $request->get('cantidadxunidad');
            $total_unidades_inventario = $request->get('total_unidades_inventario');
            $costo_unidad_inventario = $request->get('costo_unidad_inventario');
            $descripcion_inventario = $request->get('descripcion_inventario');
            $precio_sugerido = $request->get('precio_sugerido');
            $porcentaje_utilidad = $request->get('porcentaje_utilidad');
            $precio_venta = $request->get('precio_venta');
            $precio_oferta = $request->get('precio_oferta');
            $estado_oferta = $request->get('estado_oferta');

            

            $cont = 0;
            $total = 0;

    		while ($cont < count($idarticulo)) 
    		{
                //calculamos el % de utilidad a partir del precio de venta y costo
                $nuevo_porcentaje_utilidad=((($precio_venta[$cont]-$costo_unidad_inventario[$cont])/$costo_unidad_inventario[$cont])*100);
                //formato fecha a base de datos
                $fecha_vencimiento_articulo=$fecha_vencimiento[$cont];
                $fecha_vencimiento_articulo = date("Y-m-d", strtotime($fecha_vencimiento_articulo));
    			$detalle = new DetalleIngreso();
                //compra
    			$detalle->idingreso=$ingreso->idingreso;
    			$detalle->idarticulo=$idarticulo[$cont];
                $detalle->idpresentacion_compra=$idpresentacion_compra[$cont];
    			$detalle->cantidad_compra=$cantidad_compra[$cont];
    			$detalle->bonificacion=$bonificacion[$cont];
                $detalle->cantidad_total_compra=$cantidad_total_compra[$cont];
                $detalle->costo_unidad_compra=$costo_unidad_compra[$cont];
                $detalle->sub_total_compra=$sub_total_compra[$cont];
                $detalle->descuento=$descuento[$cont];
                $detalle->total_compra=$total_compra[$cont];
                
                //inventario
                $detalle->codigo=$codigo[$cont];
                $detalle->fecha_vencimiento=$fecha_vencimiento_articulo;
                $detalle->idpresentacion_inventario=$idpresentacion_inventario[$cont];
                $detalle->cantidadxunidad=$cantidadxunidad[$cont];
                $detalle->total_unidades_inventario=$total_unidades_inventario[$cont];
                $detalle->costo_unidad_inventario=$costo_unidad_inventario[$cont];
                $detalle->descripcion_inventario=$descripcion_inventario[$cont];
                $detalle->precio_sugerido=$precio_sugerido[$cont];
                $detalle->porcentaje_utilidad=$nuevo_porcentaje_utilidad;
                $detalle->precio_venta=$precio_venta[$cont];
                $detalle->precio_oferta=$precio_oferta[$cont];
                $detalle->estado_oferta=$estado_oferta[$cont];
                $detalle->stock=$total_unidades_inventario[$cont];
                $detalle->estado='Activo';
    			$detalle->save();

                $cont=$cont+1;
                $total=$total + $detalle->total_compra;	
    		}

                $pro=DB::table('persona')->where('idpersona','=',$ingreso->idproveedor)->first();

                $zonahoraria = Auth::user()->zona_horaria;
                $moneda = Auth::user()->moneda;
                $fechahora= Carbon::now($zonahoraria);
                $bitacora=new Bitacora;
                $bitacora->idempresa=Auth::user()->idempresa;
                $bitacora->idusuario=Auth::user()->id;
                $bitacora->fecha=$fechahora;
                $bitacora->tipo="Compras";
                $bitacora->descripcion="Se creo un ingreso nuevo, Proveedor: ".$pro->nombre.", Comprobante: ".$ingreso->tipo_comprobante." ".$ingreso->serie_comprobante."-".$ingreso->num_comprobante.", Fecha: ".$ingreso->fecha.", Total Compra: ".$moneda.$total;
                $bitacora->save();

    		//DB::commit();

    	//}catch(\Exception $e)
    	//{
    		//DB::rollback();
    	//}

    	return Redirect::to('compras/ingreso');
    }

    public function show($id)
    {
        $idempresa = Auth::user()->idempresa;

    	$ingreso=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')
            ->join('users as u','i.idusuario','=','u.id')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->select('i.idingreso','i.fecha','p.nombre','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.total_compra) as total'))
            ->groupBy('i.idingreso','i.fecha','p.nombre','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
            ->where('i.idingreso','=',$id)
            ->where('i.idempresa','=',$idempresa)
            ->first();

        $detalles=DB::table('detalle_ingreso as d')
        	->join('articulo as a','d.idarticulo','=','a.idarticulo')
            ->join('presentacion as pc','d.idpresentacion_compra','=','pc.idpresentacion')
            ->join('presentacion as pi','d.idpresentacion_inventario','=','pi.idpresentacion')
            ->select('d.iddetalle_ingreso','d.idingreso','d.idarticulo','a.nombre as Articulo','a.codigo as CodigoIngreso','d.codigo as CodigoInventario','a.minimo','d.idpresentacion_compra','pc.nombre as PresentacionCompra','d.cantidad_compra','d.bonificacion','d.cantidad_total_compra','d.costo_unidad_compra','d.sub_total_compra','d.descuento','d.total_compra','d.fecha_vencimiento','d.idpresentacion_inventario','pi.nombre as PresentacionInventario','d.cantidadxunidad','d.total_unidades_inventario','d.costo_unidad_inventario','d.descripcion_inventario','d.precio_sugerido','d.porcentaje_utilidad','d.precio_venta','d.precio_oferta','d.estado_oferta','d.stock','d.estado')
            ->where('d.idingreso','=',$id)
        	->get();

        return view("compras.ingreso.show",["ingreso"=>$ingreso,"detalles"=>$detalles]);
    }

    public function destroy($id)
    {
    	$ingreso=Ingreso::findOrFail($id);
    	$ingreso->estado='Cancelado';
    	$ingreso->update();

        /*Inicio quitar stock a articulos*/

        $idingreso=$id;//obtenemos id de ingreso
        //seleccionamos el detalle del ingreso
        $dets=DB::table('detalle_ingreso')->where('idingreso','=',$idingreso)->get();
        //recorrer detalles
        foreach ($dets as $det)
            {
                $cancelarDetalle=DetalleIngreso::findOrFail($det->iddetalle_ingreso);
                $cancelarDetalle->estado="Cancelado";
                $cancelarDetalle->update();
            }

        /*Fin agregar stock a articulos*/

        $ing=DB::table('ingreso')->where('idingreso','=',$id)->first();
        $pro=DB::table('persona')->where('idpersona','=',$ing->idproveedor)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Ingreso";
            $bitacora->descripcion="Se cancelo un ingreso, Proveedor: ".$pro->nombre.", Comprobante: ".$ingreso->tipo_comprobante." ".$ingreso->serie_comprobante."-".$ingreso->num_comprobante.", Fecha: ".$ingreso->fecha;
            $bitacora->save();

    	return Redirect::to('compras/ingreso');
    }
}
