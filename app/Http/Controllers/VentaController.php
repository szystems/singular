<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisVentasWeb\Http\Requests\VentaFormRequest;
use sisVentasWeb\Http\Requests\VentaSaldoFormRequest;
use sisVentasWeb\Http\Requests\ArticuloFormRequest;


use sisVentasWeb\Venta;
use sisVentasWeb\Orden;
use sisVentasWeb\Ingreso;
use sisVentasWeb\DetalleIngreso;
use sisVentasWeb\DetalleVenta;
use sisVentasWeb\Articulo;
use sisVentasWeb\Bitacora;
use DB;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

use Auth;
use sisVentasWeb\User;

class VentaController extends Controller
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

            $desde = date("Y-m-d", strtotime($desde));
            $hasta = date("Y-m-d", strtotime($hasta));

            $cliente=trim($request->get('cliente'));
            $usuario=trim($request->get('usuario'));
            $estadosaldo=trim($request->get('estadosaldo'));
            $estadoventa=trim($request->get('estadoventa'));
            $tipopago=trim($request->get('tipopago'));
            $personas=DB::table('paciente')
            ->where('estado','=',"Habilitado")
            ->get();

            $usuarios=DB::table('users')
            ->where('idempresa','=',$idempresa)
            ->get();

            $usufiltro=DB::table('users')
            ->where('id','=',$usuario)
            ->first();
                    
            $clientefiltro=DB::table('paciente')
            ->where('idpaciente','=',$cliente)
            ->first();

            

            $today = Carbon::now($zona_horaria);
            $today = $today->format('Y-m-d');
                
            if($desde != '1970-01-01' or $hasta != '1970-01-01')
            {
                if ( $estadosaldo != null )
                {
                    $ventas=DB::table('venta as v')
                    ->join('paciente as p','v.idcliente','=','p.idpaciente')
                    ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                    ->join('users as u','v.idusuario','=','u.id')
                    ->select('v.idventa','p.nombre','u.name','u.tipo_usuario','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.fecha','v.impuesto','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estado','v.estadosaldo','v.estadoventa','v.tipopago','v.idorden')
                    ->whereBetween('fecha', [$desde, $hasta])
                    ->where('p.idpaciente','LIKE','%'.$cliente.'%')
                    ->where('u.id','LIKE','%'.$usuario.'%')
                    ->where('v.idempresa','=',$idempresa)
                    ->where('v.estado','=','A')
                    ->where('v.estadosaldo','=',$estadosaldo)
                    ->where('v.estadoventa','LIKE','%'.$estadoventa.'%')
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
                    ->where('v.idempresa','=',$idempresa)
                    ->where('v.estado','=','A')
                    ->where('v.estadoventa','LIKE','%'.$estadoventa.'%')
                    ->where('v.tipopago','LIKE','%'.$tipopago.'%')
                    ->where('v.estadosaldo','!=',NULL)
                    ->orderBy('v.idventa','desc')
                    ->groupBy('v.idventa','p.nombre','u.name','u.tipo_usuario','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.fecha','v.impuesto','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estado','v.estadosaldo','v.estadoventa','v.tipopago','v.idorden')
                    ->paginate(20);
                        
                }
            }
            else
            {
                $FechaMin = DB::table('venta')
				->where('estado','=','A')
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

                $ventas=DB::table('venta as v')
                    ->join('paciente as p','v.idcliente','=','p.idpaciente')
                    ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                    ->join('users as u','v.idusuario','=','u.id')
                    ->select('v.idventa','p.nombre','u.name','u.tipo_usuario','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.fecha','v.impuesto','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estado','v.estadosaldo','v.estadoventa','v.tipopago','v.idorden')
                    ->where('v.idempresa','=',$idempresa)
                    ->where('v.estado','=','A')
                    ->where('v.estadoventa','LIKE','%'.$estadoventa.'%')
                    ->where('v.tipopago','LIKE','%'.$tipopago.'%')
                    ->where('v.estadosaldo','!=',NULL)
                    ->orderBy('v.idventa','desc')
                    ->groupBy('v.idventa','p.nombre','u.name','u.tipo_usuario','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.fecha','v.impuesto','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estado','v.estadosaldo','v.estadoventa','v.tipopago','v.idorden')
                    ->paginate(20);
            }
            return view('ventas.venta.index',["ventas"=>$ventas,"personas"=>$personas,"usuarios"=>$usuarios,"desde"=>$desde,"hasta"=>$hasta,"cliente"=>$cliente,"usuario"=>$usuario,"estadosaldo"=>$estadosaldo,"estadoventa"=>$estadoventa,"tipopago"=>$tipopago,"hoy"=>$hoy,"usufiltro"=>$usufiltro,"clientefiltro"=>$clientefiltro]);
        }

    }

    public function create()
    {
        $idempresa = Auth::user()->idempresa;
    	$personas=DB::table('paciente')
        ->where('estado','=',"Habilitado")
        ->get();
    	$articulos=DB::table('detalle_ingreso as di')
            ->join('articulo as a','di.idarticulo','=','a.idarticulo')
            ->join('presentacion as pr','di.idpresentacion_inventario','=','pr.idpresentacion')
            ->select('di.iddetalle_ingreso','di.idingreso','a.nombre as Articulo','di.codigo','di.idarticulo','a.estado','di.idpresentacion_inventario','pr.nombre as Presentacion','di.stock','a.minimo','di.precio_venta','di.costo_unidad_inventario','di.precio_oferta','di.estado_oferta')
            ->where('a.estado','=','Activo')
            ->where('di.estado','=','Activo')
            ->where('di.stock','>','0')
            ->where('idempresa','=',$idempresa)
            ->groupBy('di.iddetalle_ingreso','di.idingreso','a.nombre','di.codigo','di.idarticulo','a.estado','di.idpresentacion_inventario','pr.nombre','di.stock','a.minimo','di.precio_venta','di.costo_unidad_inventario','di.precio_oferta','di.estado_oferta')
            ->get();
    	return view("ventas.venta.create",["personas"=>$personas,"articulos"=>$articulos]);
    }

    public function store (VentaFormRequest $request)
    {
    	try
    	{ 
            $idempresa = Auth::user()->idempresa;
            $porcentaje_imp = Auth::user()->porcentaje_imp;
            $max_descuento = Auth::user()->max_descuento;
            $idusuario = Auth::user()->id;
            $zona_horaria = Auth::user()->zona_horaria;
            $comision = Auth::user()->comision;

            $fecha=trim($request->get('fecha'));
            $fecha = date("Y-m-d", strtotime($fecha));

            $total_venta=$request->get('total_venta');
            $total_compra=$request->get('total_compra');
            $impuesto=$request->get('impuesto');
            $total_impuesto=$request->get('total_impuesto');
            $total_comision=$request->get('total_comision');
            $total_abonado=$request->get('abonado');
            $estadoventa=$request->get('estadoventa');
            $tipopago=$request->get('tipopago');

            if ($total_abonado == $total_venta)
            {
                $saldo = "Pagado";
            }
            else
            {
                $saldo = "Pendiente";
            }
            

    		DB::beginTransaction();

            $venta=new Venta;
            $venta->idempresa=$idempresa;
            $venta->idcliente=$request->get('idcliente');
            $venta->idusuario=$idusuario;
    		$venta->tipo_comprobante=$request->get('tipo_comprobante');
    		$venta->serie_comprobante=$request->get('serie_comprobante');
            $venta->num_comprobante=$request->get('num_comprobante');
            
            $venta->fecha=$fecha;
            $venta->impuesto=$impuesto;
            $venta->total_venta=$total_venta;
            $venta->total_compra=$total_compra;
            $venta->total_comision=$total_comision;
            $venta->total_impuesto=$total_impuesto;
            $venta->abonado=$total_abonado;
            $venta->estado='A';
            $venta->estadosaldo=$saldo;
            $venta->estadoventa=$estadoventa;
            $venta->tipopago=$tipopago;
    		$venta->save();


    		$idarticulo = $request->get('idarticulo');
            $iddetalle_ingreso = $request->get('iddetalle_ingreso');
            $idpresentacion = $request->get('idpresentacion');
    		$cantidad = $request->get('cantidad');
    		$descuento = $request->get('descuento');
            $precio_venta = $request->get('precio_venta');
            $precio_compra = $request->get('precio_compra');
            $precio_oferta = $request->get('precio_oferta');

    		$cont = 0;

    		while ($cont < count($idarticulo)) 
    		{
    			$detalle = new DetalleVenta();
    			$detalle->idventa=$venta->idventa;
    			$detalle->idarticulo=$idarticulo[$cont];
                $detalle->idpresentacion=$idpresentacion[$cont];
                $detalle->iddetalle_ingreso=$iddetalle_ingreso[$cont];
                
    			$detalle->cantidad=$cantidad[$cont];
                $detalle->precio_venta=$precio_venta[$cont];
                $detalle->precio_compra=$precio_compra[$cont];
                $detalle->precio_oferta=$precio_oferta[$cont];
                $detalle->descuento=$descuento[$cont];
                $detalle->agregado= "SI";
    			$detalle->save();

    			$cont=$cont+1;	
            }
            
            /*Inicio quitar stock a articulos*/

            $idventastock=$venta->idventa;//obtenemos id de venta
            //seleccionamos el detalle del venta
            $dets=DB::table('detalle_venta')->where('idventa','=',$idventastock)->get();
            //recorrer detalles
            foreach ($dets as $det)
                {
                    //encontrar articulo de detalle
                    $art=DB::table('detalle_ingreso')->where('iddetalle_ingreso','=',$det->iddetalle_ingreso)->first();
                    //sumar stock a articulo
                    $stocknuevo=$art->stock - $det->cantidad;
                    
                    //actualizamos stock y precios
                    $artupt=DetalleIngreso::findOrFail($art->iddetalle_ingreso);
                    $artupt->stock=$stocknuevo;
                    
                    $artupt->update();
                }

            /*Fin agregar stock a articulos*/
            $cli=DB::table('paciente')->where('idpaciente','=',$venta->idcliente)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Ventas";
            $bitacora->descripcion="Se creo una venta nueva, Cliente: ".$cli->nombre.", Comprobante: ".$venta->tipo_comprobante." ".$venta->serie_comprobante."-".$venta->num_comprobante.", Fecha: ".$venta->fecha.", Total Venta: Q.".$venta->total_venta.", Abonado: ".$venta->abonado.", Estado Saldo: ".$venta->estadosaldo.", Estado Venta: ".$venta->estadoventa.", Tipo Pago: ".$venta->tipopago;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}

    	return Redirect::to('ventas/venta');
    }



    public function edit($id)
    {
        $idempresa = Auth::user()->idempresa;
        $personas=DB::table('paciente')
        ->where('estado','=',"Habilitado")
        ->get();
    	$articulos=DB::table('detalle_ingreso as di')
            ->join('articulo as a','di.idarticulo','=','a.idarticulo')
            ->join('presentacion as pr','di.idpresentacion_inventario','=','pr.idpresentacion')
            ->select('di.iddetalle_ingreso','di.idingreso','a.nombre as Articulo','di.codigo','di.idarticulo','a.estado','di.idpresentacion_inventario','pr.nombre as Presentacion','di.stock','a.minimo','di.precio_venta','di.costo_unidad_inventario','di.precio_oferta','di.estado_oferta')
            ->where('a.estado','=','Activo')
            ->where('di.estado','=','Activo')
            ->where('di.stock','>','0')
            ->where('idempresa','=',$idempresa)
            ->groupBy('di.iddetalle_ingreso','di.idingreso','a.nombre','di.codigo','di.idarticulo','a.estado','di.idpresentacion_inventario','pr.nombre','di.stock','a.minimo','di.precio_venta','di.costo_unidad_inventario','di.precio_oferta','di.estado_oferta')
            ->get();
    	$venta=DB::table('venta as v')
            ->join('paciente as p','v.idcliente','=','p.idpaciente')
            ->join('users as u','v.idusuario','=','u.id')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->select('v.idventa','v.idcliente','v.idusuario','v.fecha','p.nombre','u.name','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estadosaldo','v.estadoventa','v.tipopago')
            ->where('v.idventa','=',$id)
            ->where('v.idempresa','=',$idempresa)
            ->first();

        $detalles=DB::table('detalle_venta as dv')
        	->join('articulo as a','dv.idarticulo','=','a.idarticulo')
            ->join('presentacion as pr','dv.idpresentacion','=','pr.idpresentacion')
            ->join('detalle_ingreso as di','dv.iddetalle_ingreso','=','di.iddetalle_ingreso')
        	->select('dv.iddetalle_venta','dv.idventa','dv.iddetalle_ingreso','dv.idpresentacion','pr.nombre as presentacion','a.nombre as articulo','di.codigo','dv.idarticulo as idarticulo','dv.idventa as idventa','dv.cantidad','dv.descuento','dv.precio_compra','dv.precio_venta','dv.precio_oferta')
        	->where('dv.idventa','=',$id)
        	->get();

        return view("ventas.venta.edit",["venta"=>$venta,"detalles"=>$detalles,"personas"=>$personas,"articulos"=>$articulos]);
    }

    public function updateVenta($id)
    {
        //try
    	//{ 
            $idempresa = Auth::user()->idempresa;
            $porcentaje_imp = Auth::user()->porcentaje_imp;
            $max_descuento = Auth::user()->max_descuento;
            $idusuario = $request->get('idusuario');
            $zona_horaria = Auth::user()->zona_horaria;
            $comision = Auth::user()->comision;

            $fecha=trim($request->get('fecha'));
            $fecha = date("Y-m-d", strtotime($fecha));

            $total_venta=$request->get('total_venta');
            $total_compra=$request->get('total_compra');
            $impuesto=$request->get('impuesto');
            $total_impuesto=$request->get('total_impuesto');
            $total_comision=$request->get('total_comision');
            $total_impuesto=$request->get('total_impuesto');
            $total_abonado=$request->get('abonado');
            $estadoventa=$request->get('estadoventa');
            $tipopago=$request->get('tipopago');

            if ($total_abonado == $total_venta)
            {
                $saldo = "Pagado";
            }
            else
            {
                $saldo = "Pendiente";
            }
            

    		DB::beginTransaction();

            $venta=Venta::findOrFail($id);
            $venta->idempresa=$idempresa;
            $venta->idcliente=$request->get('idcliente');
            $venta->idusuario=$idusuario;
    		$venta->tipo_comprobante=$request->get('tipo_comprobante');
    		$venta->serie_comprobante=$request->get('serie_comprobante');
            $venta->num_comprobante=$request->get('num_comprobante');
            
            $venta->fecha=$fecha;
            $venta->impuesto=$impuesto;
            $venta->total_venta=$total_venta;
            $venta->total_compra=$total_compra;
            $venta->total_comision=$total_comision;
            $venta->total_impuesto=$total_impuesto;
            $venta->abonado=$total_abonado;
            $venta->estado='A';
            $venta->estadosaldo=$saldo;
            $venta->estadoventa=$estadoventa;
    		$venta->save();


    		$idarticulo = $request->get('idarticulo');
    		$cantidad = $request->get('cantidad');
    		$descuento = $request->get('descuento');
            $precio_venta = $request->get('precio_venta');
            $precio_compra = $request->get('precio_compra');
            $precio_oferta = $request->get('precio_oferta');

    		$cont = 0;

    		while ($cont < count($idarticulo)) 
    		{
    			$detalle = new DetalleVenta();
    			$detalle->idventa=$venta->idventa;
    			$detalle->idarticulo=$idarticulo[$cont];
    			$detalle->cantidad=$cantidad[$cont];
                $detalle->precio_venta=$precio_venta[$cont];
                $detalle->precio_compra=$precio_compra[$cont];
                $detalle->precio_oferta=$precio_oferta[$cont];
                $detalle->descuento=$descuento[$cont];
                $detalle->agregado= "SI";
    			$detalle->save();

    			$cont=$cont+1;	
            }
            
            /*Inicio quitar stock a articulos*/

            $idventastock=$venta->idventa;//obtenemos id de venta
            //seleccionamos el detalle del venta
            $dets=DB::table('detalle_venta')->where('idventa','=',$idventastock)->get();
            //recorrer detalles
            foreach ($dets as $det)
                {
                    //encontrar articulo de detalle
                    $art=DB::table('articulo')->where('idarticulo','=',$det->idarticulo)->first();
                    //sumar stock a articulo
                    $stocknuevo=$art->stock - $det->cantidad;
                    
                    //actualizamos stock y precios
                    $artupt=Articulo::findOrFail($art->idarticulo);
                    $artupt->stock=$stocknuevo;
                    
                    $artupt->update();
                }

            /*Fin agregar stock a articulos*/

    		DB::commit();

    	//}catch(\Exception $e)
    	//{
    		//DB::rollback();
    	//}

    	return Redirect::to('ventas/venta');
    }

    public function show($id)
    {
        $idempresa = Auth::user()->idempresa;
    	$venta=DB::table('venta as v')
            ->join('paciente as p','v.idcliente','=','p.idpaciente')
            ->join('users as u','v.idusuario','=','u.id')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->select('v.idventa','v.idcliente','v.idusuario','v.fecha','p.nombre','u.name','u.tipo_usuario','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.impuesto','v.estado','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estadosaldo','estadoventa','tipopago','v.idorden')
            ->where('v.idventa','=',$id)
            ->where('v.idempresa','=',$idempresa)
            ->first();
        

        $detalles=DB::table('detalle_venta as d')
        	->join('articulo as a','d.idarticulo','=','a.idarticulo')
            ->join('detalle_ingreso as di','d.iddetalle_ingreso','=','di.iddetalle_ingreso')
        	->select('d.iddetalle_ingreso','a.nombre as articulo','di.codigo','d.cantidad','d.descuento','d.precio_compra','d.precio_venta','d.precio_oferta')
        	->where('d.idventa','=',$id)
        	->get();

        if($detalles->count() == 0)
        {
            $detalles=DB::table('detalle_venta as d')
        	->join('articulo as a','d.idarticulo','=','a.idarticulo')
        	->select('d.iddetalle_ingreso','a.nombre as articulo','a.codigo','d.cantidad','d.descuento','d.precio_compra','d.precio_venta','d.precio_oferta')
        	->where('d.idventa','=',$id)
        	->get();
        }

        return view("ventas.venta.show",["venta"=>$venta,"detalles"=>$detalles]);
    }

    public function destroy($id)
    {
    	$venta=Venta::findOrFail($id);
    	$venta->Estado='C';
        $venta->update();
        
        /*Inicio agregar stock a articulos*/

            $idventastock=$id;//obtenemos id de venta
            //seleccionamos el detalle del venta
            $dets=DB::table('detalle_venta')->where('idventa','=',$idventastock)->get();

            //recorrer detalles
            foreach ($dets as $det)
                {
                    //vemos si articulo es de ingreso o de orden, si es de orden lo omitimos
                    if($det->iddetalle_ingreso != null)
                    {
                        //encontrar articulo de detalle
                        $art=DB::table('detalle_ingreso')->where('iddetalle_ingreso','=',$det->iddetalle_ingreso)->first();
                        //sumar stock a articulo
                        $stocknuevo=$art->stock + $det->cantidad;
                        //actualizamos stock
                        $artupt=DetalleIngreso::findOrFail($art->iddetalle_ingreso);
                        $artupt->stock=$stocknuevo;
                        $artupt->update();
                    }
                    
                }

        /*Fin agregar stock a articulos*/

        //borrar idventa si existe en orden
        $orden=DB::table('orden')->where('idventa','=',$id)->first();
        if(isset($orden))
        {
            $ordenidventa=Orden::findOrFail($orden->idorden);
            $ordenidventa->idventa=null;
            $ordenidventa->estado_orden="Pendiente";
            $ordenidventa->update();
        }

            
        $ven=DB::table('venta')->where('idventa','=',$id)->first();
        $cli=DB::table('paciente')->where('idpaciente','=',$ven->idcliente)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Ventas";
            $bitacora->descripcion="Se elimino una venta, Cliente: ".$cli->nombre.", Comprobante: ".$ven->tipo_comprobante." ".$ven->serie_comprobante."-".$ven->num_comprobante.", Fecha: ".$ven->fecha.", Total Venta: ".$moneda.$ven->total_venta.", Abonado: ".$moneda.$ven->abonado.", Estado Saldo: ".$ven->estadosaldo.", Estado Venta: ".$ven->estadoventa.", Tipo Pago: ".$ven->tipopago;
            $bitacora->save();

    	return Redirect::to('ventas/venta');
    }

    public function detdestroy(Request $request)
    {
        /*Inicio agregar stock a articulos*/

            //obtenemos los datos del detalle de venta
            $iddetalle_venta = $request->get('iddetalle');
            $idventa = $request->get('iddetalleidventa');
            $iddetalle_ingreso = $request->get('iddetalle_ingreso');
            $idpresentacion = $request->get('iddetalleidpresentacion');
            $idarticulo = $request->get('iddetalleidarticulo');
            $cantidad = $request->get('iddetallecantidad');
            $precio_venta = $request->get('iddetalleprecioventa');
            $precio_compra = $request->get('iddetallepreciocompra');
            $precio_oferta = $request->get('iddetallepreciooferta');
            $descuento = $request->get('iddetalledescuento');
            $comision = $request->get('iddetallecomision');
            $impuesto = $request->get('iddetalleimpuesto');
            $cantidadaquitar = $request->get('cantidadaquitar');

            $totaldedetallesdeventa=DB::table('detalle_venta')
            ->where('idventa','=',$idventa)
            ->get();

            $numfilas = count($totaldedetallesdeventa);

            
                if (($cantidadaquitar == $cantidad) && ($numfilas > "1"))
                {
                    //encontrar articulo de detalle
                        $art=DB::table('detalle_ingreso')->where('iddetalle_ingreso','=',$iddetalle_ingreso)->first();
                    //sumar stock a articulo
                        $stocknuevo=$art->stock + $cantidad;
                    //actualizamos stock
                        $artupt=DetalleIngreso::findOrFail($art->iddetalle_ingreso);
                        $artupt->stock=$stocknuevo;
                        $artupt->update();
                    /*Fin agregar stock a articulos*/

                    //Recalcular datos de venta
                    //obtener venta
                    $venta=DB::table('venta')->where('idventa','=',$idventa)->first();
                    //obtener datos de venta
                    $venta_total_venta=$venta->total_venta;
                    $venta_total_compra=$venta->total_compra;
                    $venta_total_comision=$venta->total_comision;
                    $venta_total_impuesto=$venta->total_impuesto;
                    $venta_abonado=$venta->abonado;
                    //calcular total_venta
                    $total_venta_nuevo = $venta_total_venta-(($precio_venta*$cantidad)-$descuento);
                    //calcular total_compra
                    $total_compra_nuevo = $venta_total_compra-($precio_compra*$cantidad);
                    //calcular total_comision
                    $total_comision_nuevo = ($venta_total_comision-((($precio_venta*$cantidad)-$descuento)*($comision/100)));
                    //calcular total_impuesto
                    $total_impuesto_nuevo = ($venta_total_impuesto-(((($precio_venta*$cantidad)-$descuento)*$impuesto)/100));
                    //calcular estadosaldo
                    if ($venta_abonado == $total_venta_nuevo)
                    {
                        $estadosaldo_nuevo = "Pagado";
                    }
                    else
                    {
                        $estadosaldo_nuevo = "Pendiente";
                    }
                    //actualizamos venta
                        $ventaupdate=Venta::findOrFail($idventa);
                        $ventaupdate->total_venta=$total_venta_nuevo;
                        $ventaupdate->total_compra=$total_compra_nuevo;
                        $ventaupdate->total_comision=$total_comision_nuevo;
                        $ventaupdate->total_impuesto=$total_impuesto_nuevo;
                        $ventaupdate->estadosaldo=$estadosaldo_nuevo;
                        $ventaupdate->update();


                    $iddetalle = $request->get('iddetalle');
                    $detalle=DetalleVenta::findOrFail($iddetalle);
                    $detalle->delete();
                    
                    $request->session()->flash('alert-success', 'Articulo(s) eliminado(s) y agregado(s) al stock.');
                }
                if (($cantidadaquitar >= $cantidad) && ($numfilas <= "1"))
                {
                    $request->session()->flash('alert-danger', 'No se ha podido eliminar el detalle de venta, debe haber al menos un artículo en la venta.');
                }

                
                if ($cantidadaquitar < $cantidad)
                {
                    //encontrar articulo de detalle
                        $art=DB::table('detalle_ingreso')->where('iddetalle_ingreso','=',$iddetalle_ingreso)->first();
                    //encontrar detalle de venta*************************
                        $detventa=DB::table('detalle_venta')->where('iddetalle_venta','=',$iddetalle_venta)->first();
                    //sumar stock a articulo
                        $artstocknuevo=$art->stock + $cantidadaquitar;
                    //restar stock a detalle de venta********************
                        $detcantidadnuevo=$detventa->cantidad - $cantidadaquitar;
                    //actualizamos stock en articulo
                        $artupt=DetalleIngreso::findOrFail($art->iddetalle_ingreso);
                        $artupt->stock=$artstocknuevo;
                        $artupt->update();
                    /*Fin agregar stock a articulos*/

                    

                    //Recalcular datos de venta
                    //obtener venta
                    $venta=DB::table('venta')->where('idventa','=',$idventa)->first();
                    //obtener datos de venta
                    $venta_total_venta=$venta->total_venta;
                    $venta_total_compra=$venta->total_compra;
                    $venta_total_comision=$venta->total_comision;
                    $venta_total_impuesto=$venta->total_impuesto;
                    $venta_abonado=$venta->abonado;
                    //calcular total_venta
                    $total_venta_nuevo = $venta_total_venta-(($precio_venta*$cantidadaquitar)-(($descuento/$cantidad)*$cantidadaquitar));
                    //calcular total_compra
                    $total_compra_nuevo = $venta_total_compra-($precio_compra*$cantidadaquitar);
                    //calcular total_comision
                    $total_comision_nuevo = ($venta_total_comision-((($precio_venta*$cantidadaquitar)-(($descuento/$cantidad)*$cantidadaquitar))*($comision/100)));
                    //calcular total_impuesto
                    $total_impuesto_nuevo = ($venta_total_impuesto-(((($precio_venta*$cantidadaquitar)-(($descuento/$cantidad)*$cantidadaquitar))*$impuesto)/100));
                    //calcular descuento total
                    $total_descuento_nuevo = (($descuento/$cantidad)*($cantidad-$cantidadaquitar));
                    //calcular estadosaldo
                    if ($venta_abonado == $total_venta_nuevo)
                    {
                        $estadosaldo_nuevo = "Pagado";
                    }
                    else
                    {
                        $estadosaldo_nuevo = "Pendiente";
                    }
                    //actualizamos venta
                        $ventaupdate=Venta::findOrFail($idventa);
                        $ventaupdate->total_venta=$total_venta_nuevo;
                        $ventaupdate->total_compra=$total_compra_nuevo;
                        $ventaupdate->total_comision=$total_comision_nuevo;
                        $ventaupdate->total_impuesto=$total_impuesto_nuevo;
                        $ventaupdate->estadosaldo=$estadosaldo_nuevo;
                        $ventaupdate->update();
                    //actualizamos cantidad en detalle de venta y descuento total
                        $detventaupt=DetalleVenta::findOrFail($detventa->iddetalle_venta);
                        $detventaupt->cantidad=$detcantidadnuevo;
                        $detventaupt->descuento=$total_descuento_nuevo;
                        $detventaupt->update();
                    /*Fin quitar cantidad a detalle venta*/
                    $request->session()->flash('alert-success', 'Articulo(s) eliminado(s) y agregado(s) al stock.');
                }

            
        return Redirect::to('ventas/venta');
    }

    public function destroycerrar(Request $request)
    {

        $idventa = $request->get('idventa');
        
        $venta=Venta::findOrFail($idventa);
        $venta->estadoventa="Cerrada";
        $venta->update();

        $ven=DB::table('venta')->where('idventa','=',$idventa)->first();
        $cli=DB::table('paciente')->where('idpaciente','=',$ven->idcliente)->first();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Ventas";
        $bitacora->descripcion="Se cerro una venta, Cliente: ".$cli->nombre.", Comprobante: ".$ven->tipo_comprobante." ".$ven->serie_comprobante."-".$ven->num_comprobante.", Fecha: ".$ven->fecha.", Total Venta: ".$moneda.$ven->total_venta.", Total Abonado: ".$moneda.$venta->abonado.", Estado Saldo: ".$venta->estadosaldo.", Estado Venta: ".$venta->estadoventa.", Tipo Pago: ".$venta->tipopago;
        $bitacora->save();

        return Redirect::to('ventas/venta');
    }

    public function update(ventaSaldoFormRequest $request,$id)
    {
        $updateventa=$request->get('updateventa');
        if ($updateventa == "abonar")
        {
            $totalventa=$request->get('total_venta');
            $saldado=$request->get('abonado');
            $saldo=$totalventa-$saldado;
            $abonar=$request->get('abonar');
            $totalabono=$request->get('abonar');
            //$abonar=$totalventa=$request->get('abonar');
            $estadosaldo=$request->get('estadosaldo');
            $tipopago=$request->get('tipopago');
            if ($abonar <= $saldo)
            {
                
                $abonar=$abonar+$saldado;
                if ($totalventa == $abonar)
                {
                    $estadosaldo = "Pagado";
                }
                
                $venta=Venta::findOrFail($id);
                $venta->abonado=$abonar;
                $venta->estadosaldo=$estadosaldo;
                $venta->tipopago=$tipopago;
                $venta->update();
                $request->session()->flash('alert-success', 'Se abonó la cantidad exitosamente!');


                $ven=DB::table('venta')->where('idventa','=',$id)->first();
                $cli=DB::table('paciente')->where('idpaciente','=',$venta->idcliente)->first();

                $zonahoraria = Auth::user()->zona_horaria;
                $moneda = Auth::user()->moneda;
                $fechahora= Carbon::now($zonahoraria);
                $bitacora=new Bitacora;
                $bitacora->idempresa=Auth::user()->idempresa;
                $bitacora->idusuario=Auth::user()->id;
                $bitacora->fecha=$fechahora;
                $bitacora->tipo="Ventas";
                $bitacora->descripcion="Se abono una venta, Cliente: ".$cli->nombre.", Comprobante: ".$ven->tipo_comprobante." ".$ven->serie_comprobante."-".$ven->num_comprobante.", Fecha: ".$ven->fecha.", Total Venta: ".$moneda.$ven->total_venta.", Nuevo Abono: ".$moneda.$totalabono.", Total Abonado: ".$moneda.$venta->abonado.", Estado Saldo: ".$venta->estadosaldo.", Estado Venta: ".$venta->estadoventa.", Tipo Pago: ".$venta->tipopago;
                $bitacora->save();

                return Redirect::to('ventas/venta');
            }
            else
            {
                $request->session()->flash('alert-danger', 'La cantidad no se abonó ya que la cantidad supera al total de la venta!');
                return Redirect::to('ventas/venta');
            }
        }
        elseif ($updateventa == "update")
        {
            //try
            //{ 
                //obtenemos datos del usuario
                $idempresa = Auth::user()->idempresa;
                $porcentaje_imp = Auth::user()->porcentaje_imp;
                $max_descuento = Auth::user()->max_descuento;
                $idusuario = Auth::user()->id;
                $zona_horaria = Auth::user()->zona_horaria;
                $comision = Auth::user()->comision;
                //recibimos fecha nueva y la formateamos
                $fecha=trim($request->get('fecha'));
                $fecha = date("Y-m-d", strtotime($fecha));
                //obtenemos los datos nuevos de venta
                $total_venta=$request->get('total_venta');
                $total_compra=$request->get('total_compra');
                $impuesto=$request->get('impuesto');
                $total_impuesto=$request->get('total_impuesto');
                $total_comision=$request->get('total_comision');
                $total_abonado=$request->get('abonado');
                $estadoventa=$request->get('estadoventa');
                $tipopago=$request->get('tipopago');
                //obtenemos los datos anteriores de la venta
                $total_venta_anterior=$request->get('total_venta_anterior');
                $total_compra_anterior=$request->get('total_compra_anterior');
                $total_comision_anterior=$request->get('total_comision_anterior');
                $abonado_anterior=$request->get('abonado_anterior');
                $impuesto_anterior=$request->get('impuesto_anterior');
                //recalculamos los datos de la venta con los articulos agregados
                $total_abonado = $total_abonado + $abonado_anterior;
                $total_venta = $total_venta + $total_venta_anterior;
                $total_compra = $total_compra + $total_compra_anterior;
                $total_comision = $total_comision + $total_comision_anterior;
                $total_impuesto = $total_impuesto + $impuesto_anterior;
                
                //verificamos el estado del saldo de venta con lo abonado
                if ($total_abonado == $total_venta)
                {
                    $saldo = "Pagado";
                }
                else
                {
                    $saldo = "Pendiente";
                }
                
                //transaccion para guardar los cambios a datos de venta
                //DB::beginTransaction();

                $venta=Venta::findOrFail($id);
                $venta->idempresa=$idempresa;
                $venta->idcliente=$request->get('idcliente');
                $venta->idusuario=$idusuario;
                $venta->tipo_comprobante=$request->get('tipo_comprobante');
                $venta->serie_comprobante=$request->get('serie_comprobante');
                $venta->num_comprobante=$request->get('num_comprobante');
                
                $venta->fecha=$fecha;
                $venta->impuesto=$impuesto;
                $venta->total_venta=$total_venta;
                $venta->total_compra=$total_compra;
                $venta->total_comision=$total_comision;
                $venta->total_impuesto=$total_impuesto;
                $venta->abonado=$total_abonado;
                $venta->estado='A';
                $venta->estadosaldo=$saldo;
                $venta->estadoventa=$estadoventa;
                $venta->tipopago=$tipopago;
                $venta->save();

                //obtenemos los vectores de los nuevos detalles de venta
                $idarticulo = $request->get('idarticulo');
                //si hay mas de algun articulo para agregar procedemos a agregarlos
                if(isset($idarticulo[0]))
                {
                    $iddetalle_ingreso = $request->get('iddetalle_ingreso');
                    $idpresentacion = $request->get('idpresentacion');
                    $cantidad = $request->get('cantidad');
                    $descuento = $request->get('descuento');
                    $precio_venta = $request->get('precio_venta');
                    $precio_compra = $request->get('precio_compra');
                    $precio_oferta = $request->get('precio_oferta');

                    
                    $cont = 0;//inicializamos contador de registro de vector

                    //guardamos todos los nuevos detalles de compra con campo Agregado=NO para luego quitar stock al articulo
                    while ($cont < count($idarticulo)) 
                    {
                        $detalle = new DetalleVenta();
                        $detalle->idventa=$venta->idventa;
                        $detalle->idpresentacion=$idpresentacion[$cont];
                        $detalle->iddetalle_ingreso=$iddetalle_ingreso[$cont];
                        $detalle->idarticulo=$idarticulo[$cont];
                        $detalle->cantidad=$cantidad[$cont];
                        $detalle->precio_venta=$precio_venta[$cont];
                        $detalle->precio_compra=$precio_compra[$cont];
                        $detalle->precio_oferta=$precio_oferta[$cont];
                        $detalle->descuento=$descuento[$cont];
                        $detalle->agregado= "NO";
                        $detalle->save();

                        $cont=$cont+1;	
                    }
                    

                    /*Inicio quitar stock a articulos*/
                    $idventastock=$venta->idventa;//obtenemos id de venta
                    //seleccionamos el detalle del venta
                    $dets=DB::table('detalle_venta')->where('idventa','=',$idventastock)->where('agregado','=',"NO")->get();
                    //recorrer detalles
                    foreach ($dets as $det)
                        {
                            //encontrar articulo de detalle
                            $art=DB::table('detalle_ingreso')->where('iddetalle_ingreso','=',$det->iddetalle_ingreso)->first();
                            //restar stock a articulo
                            $stocknuevo=$art->stock - $det->cantidad;
                            
                            //actualizamos stock y precios
                            $artupt=DetalleIngreso::findOrFail($art->iddetalle_ingreso);
                            $artupt->stock=$stocknuevo;
                            $artupt->update();

                            //actualizamos campo agregado
                            $agregadoaventa=DetalleVenta::findOrFail($det->iddetalle_venta);
                            $agregadoaventa->agregado= "SI";
                            $agregadoaventa->update();

                            $request->session()->flash('alert-success', 'Se han agregado los articulos al detalle de venta.');
                        }
                }
                

                /*Fin agregar stock a articulos*/
                
                $cli=DB::table('paciente')->where('idpaciente','=',$venta->idcliente)->first();

                $zonahoraria = Auth::user()->zona_horaria;
                $moneda = Auth::user()->moneda;
                $fechahora= Carbon::now($zonahoraria);
                $bitacora=new Bitacora;
                $bitacora->idempresa=Auth::user()->idempresa;
                $bitacora->idusuario=Auth::user()->id;
                $bitacora->fecha=$fechahora;
                $bitacora->tipo="Ventas";
                $bitacora->descripcion="Se edito una venta, Cliente: ".$cli->nombre.", Comprobante: ".$venta->tipo_comprobante." ".$venta->serie_comprobante."-".$venta->num_comprobante.", Fecha: ".$venta->fecha.", Total Venta: ".$moneda.$venta->total_venta.", Abonado: ".$moneda.$venta->abonado.", Estado Saldo: ".$venta->estadosaldo.", Estado Venta: ".$venta->estadoventa.", Tipo Pago: ".$venta->tipopago;
                $bitacora->save();

                //DB::commit();
                

            //}catch(\Exception $e)
            //{
                //DB::rollback();
            //}

            return Redirect::to('ventas/venta'); 
        }
            
        
    }
    
}
