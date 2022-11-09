<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisVentasWeb\Http\Requests\OrdenFormRequest;


use sisVentasWeb\Orden;
use sisVentasWeb\DetalleOrden;
use sisVentasWeb\Venta;
use sisVentasWeb\DetalleVenta;
use sisVentasWeb\Articulo;
use sisVentasWeb\Bitacora;
use DB;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

use Auth;
use sisVentasWeb\User;

class OrdenController extends Controller
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

            $paciente=trim($request->get('paciente'));
            $doctor=trim($request->get('doctor'));
            $usuario=trim($request->get('usuario'));
            $estadoorden=trim($request->get('estadoorden'));
            $estado=trim($request->get('estado'));
            
            $pacientes=DB::table('paciente')
            ->where('estado','=','Habilitado')
            ->get();

            $doctores=DB::table('users')
            ->where('tipo_usuario','=','Doctor')
            ->where('email','!=','Eliminado')
            ->get();

            $usuarios=DB::table('users')
            ->where('email','!=','Eliminado')
            ->where('tipo_usuario','!=','Doctor')
            ->get();

            $usufiltro=DB::table('users')
            ->where('id','=',$usuario)
            ->where('idempresa','=',$idempresa)
            ->first();
                    
            $pacientefiltro=DB::table('paciente')
            ->where('idpaciente','=',$paciente)
            ->first();

            $docfiltro=DB::table('users')
            ->where('tipo_usuario','=','Doctor')
            ->where('id','=',$doctor)
            ->first();

            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $hoy = $hoy->format('d-m-Y');

                
            if($desde != '1970-01-01' or $hasta != '1970-01-01')
            {
                
                $ordenes=DB::table('orden as o')
                ->join('paciente as p','o.idpaciente','=','p.idpaciente')
                ->join('users as d','o.iddoctor','=','d.id')
                ->join('users as u','o.idusuario','=','u.id')
                ->select('o.idorden','o.idventa','o.fecha','o.estado_orden','o.estado','o.total','p.idpaciente','p.nombre as Paciente','p.sexo','p.telefono','p.fecha_nacimiento','p.dpi','p.nit','d.id as iddoctor','d.name as Doctor','d.especialidad','u.id as idusuario','u.name as Usuario','u.tipo_usuario')
                ->whereBetween('fecha', [$desde, $hasta])
                ->where('p.idpaciente','LIKE','%'.$paciente.'%')
                ->where('d.id','LIKE','%'.$doctor.'%')
                ->where('u.id','LIKE','%'.$usuario.'%')
                ->where('o.estado_orden','LIKE','%'.$estadoorden.'%')
                ->where('o.estado','LIKE','%'.$estado.'%')
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
            return view('ventas.orden.index',["ordenes"=>$ordenes,"pacientes"=>$pacientes,"usuarios"=>$usuarios,"doctores"=>$doctores,"desde"=>$desde,"hasta"=>$hasta,"paciente"=>$paciente,"doctor"=>$doctor,"usuario"=>$usuario,"estadoorden"=>$estadoorden,"estado"=>$estado,"hoy"=>$hoy,"usufiltro"=>$usufiltro,"pacientefiltro"=>$pacientefiltro,"docfiltro"=>$docfiltro]);
        }

    }

    public function create()
    {
    	$pacientes=DB::table('paciente')
        ->where('estado','=','Habilitado')
        ->get();
        $doctores=DB::table('users')
        ->where('tipo_usuario','=','Doctor')
        ->where('email','!=','Habilitado')
        ->get();
    	$rubros=DB::table('rubro')
    	->where('estado_rubro','=','Habilitado')
        ->where('estado','=','Habilitado')
    	->get();
    	return view("ventas.orden.create",["pacientes"=>$pacientes,"doctores"=>$doctores,"rubros"=>$rubros]);
    }

    public function store (OrdenFormRequest $request)
    {
    	try
    	{ 
            //Recibir datos
            $idpaciente = $request->get('idpaciente');
            $iddoctor = $request->get('iddoctor');
            $idusuario = $request->get('idusuario');
            $zona_horaria = Auth::user()->zona_horaria;
            $comision = Auth::user()->comision;

            $fecha=trim($request->get('fecha'));
            $fecha = date("Y-m-d", strtotime($fecha));

            $codigoeeps=$request->get('codigoeeps');
            $codigopapanicolau=$request->get('codigopapanicolau');
            $observaciones=$request->get('observaciones');
            $estado_orden=$request->get('estado_orden');
            $estado=$request->get('estado');

    		DB::beginTransaction();

            $orden=new Orden;
            $orden->fecha=$fecha;
            $orden->idpaciente=$idpaciente;
            $orden->iddoctor=$iddoctor;
            $orden->idusuario=$idusuario;
    		$orden->codigoeeps=$codigoeeps;
            $orden->codigopapanicolau=$codigopapanicolau;
    		$orden->observaciones=$observaciones;
            $orden->estado_orden=$estado_orden;
            $orden->estado=$estado;
    		$orden->save();


            $articulos_rubros = DB::table('rubro_articulo as ra')
            ->join('articulo as a','ra.idarticulo','=','a.idarticulo')
			->get();

            $numArticulosRubro = $articulos_rubros->count();

    		$total = 0;

    		foreach($articulos_rubros as $ar) 
    		{
                $check = $request->get('check'.$ar->idrubro_articulo);
                if($check == "Habilitado")
                {
                    $precio_final = $request->get('precio_final'.$ar->idrubro_articulo);

                    $precio_venta = $precio_final;
                    
                    $detalle = new DetalleOrden();
                    $detalle->idorden=$orden->idorden;
                    $detalle->idarticulo=$request->get('idarticulo'.$ar->idrubro_articulo);
                    $detalle->cantidad=$request->get('cantidad'.$ar->idrubro_articulo);
                    $detalle->precio_venta=$precio_venta;
                    $detalle->precio_costo=$request->get('precio_costo'.$ar->idrubro_articulo);
                    $detalle->save();

                    $total = $total + $precio_venta;
                }	
            }

            $totalorden=Orden::findOrFail($orden->idorden);
            $totalorden->total=$total;
    		$totalorden->save();
            
            
            $pac=DB::table('paciente')->where('idpaciente','=',$orden->idpaciente)->first();
            $doc=DB::table('users')->where('id','=',$orden->iddoctor)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Ventas";
            $bitacora->descripcion="Se creo una orden nueva, Paciente: ".$pac->nombre.", Doctor: ".$doc->name.", Total:".$moneda.$total;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}

    	return Redirect::to('ventas/orden');
    }

    public function show($id)
    {
    	$orden=DB::table('orden as o')
        ->join('paciente as p','o.idpaciente','=','p.idpaciente')
        ->join('users as d','o.iddoctor','=','d.id')
        ->join('users as u','o.idusuario','=','u.id')
        ->select('o.idorden','o.fecha','o.codigoeeps','o.codigopapanicolau','o.observaciones','o.estado_orden','o.estado','o.total','p.idpaciente','p.nombre as Paciente','p.sexo','p.telefono','p.fecha_nacimiento','p.dpi','p.nit','d.id as iddoctor','d.name as Doctor','d.especialidad','u.id as idusuario','u.name as Usuario','u.tipo_usuario','o.idventa')
        ->where('o.idorden','=',$id)   
        ->first();

        $rubros=DB::table('rubro')
    	->get();

        return view("ventas.orden.show",["orden"=>$orden,"rubros"=>$rubros]);
    }

    public function edit($id)
    {
        
        $pacientes=DB::table('paciente')
        ->where('estado','=','Habilitado')
        ->get();
        $doctores=DB::table('users')
        ->where('tipo_usuario','=','Doctor')
        ->where('email','!=','Habilitado')
        ->get();
    	$rubros=DB::table('rubro')
    	->where('estado_rubro','=','Habilitado')
        ->where('estado','=','Habilitado')
    	->get();

    	$orden=DB::table('orden as o')
            ->join('paciente as p','o.idpaciente','=','p.idpaciente')
            ->select('o.idorden','o.fecha','o.codigoeeps','o.codigopapanicolau','o.observaciones','o.estado_orden','o.estado','o.total','p.idpaciente','p.nombre as Paciente','p.sexo','p.telefono','p.fecha_nacimiento','p.dpi','p.nit','o.iddoctor','o.idusuario')
            ->where('o.idorden','=',$id)
            ->first();

        $detalles=DB::table('detalle_orden')
        	->where('idorden','=',$id)
        	->get();

        return view("ventas.orden.edit",["orden"=>$orden,"detalles"=>$detalles,"pacientes"=>$pacientes,"doctores"=>$doctores,"rubros"=>$rubros]);
    }

    public function update(OrdenFormRequest $request,$id)
    {
        try
    	{ 
            //Recibir datos
            $idorden = $request->get('idorden');
            $idpaciente = $request->get('idpaciente');
            $iddoctor = $request->get('iddoctor');
            $idusuario = $request->get('idusuario');
            $zona_horaria = Auth::user()->zona_horaria;
            $comision = Auth::user()->comision;

            $fecha=trim($request->get('fecha'));
            $fecha = date("Y-m-d", strtotime($fecha));

            $codigoeeps=$request->get('codigoeeps');
            $codigopapanicolau=$request->get('codigopapanicolau');
            $observaciones=$request->get('observaciones');
            $estado_orden=$request->get('estado_orden');
            $estado=$request->get('estado');


    		DB::beginTransaction();

            $orden=Orden::findOrFail($idorden);
            $orden->fecha=$fecha;
            $orden->idpaciente=$idpaciente;
            $orden->iddoctor=$iddoctor;
            $orden->idusuario=$idusuario;
    		$orden->codigoeeps=$codigoeeps;
            $orden->codigopapanicolau=$codigopapanicolau;
    		$orden->observaciones=$observaciones;
            $orden->estado_orden=$estado_orden;
            $orden->estado=$estado;
    		$orden->save();


            $articulos_rubros = DB::table('rubro_articulo as ra')
            ->join('articulo as a','ra.idarticulo','=','a.idarticulo')
			->get();

            $numArticulosRubro = $articulos_rubros->count();

    		$total = 0;

    		foreach($articulos_rubros as $ar) 
    		{
                $check = $request->get('check'.$ar->idrubro_articulo);
                if($check == "Habilitado")
                {
                    //ver si el articulo de rubro ya existe en base de datos
                    $ExisteArticuloOrden=DB::table('detalle_orden')
                    ->where('idorden','=',$idorden)
                    ->where('idarticulo','=',$ar->idarticulo)
                    ->get();
                    
                    if($ExisteArticuloOrden->count() >= 1)
                    {
                        $precio_final = $request->get('precio_final'.$ar->idrubro_articulo);

                            $precio_venta = $precio_final;
                            $precio_oferta = 0;
                            $descuento = 0;

                        $detalleOrden=DB::table('detalle_orden')
                        ->where('idorden','=',$idorden)
                        ->where('idarticulo','=',$ar->idarticulo)
                        ->first();

                        $detalle =DetalleOrden::findOrFail($detalleOrden->iddetalle_orden);
                        $detalle->precio_venta=$precio_venta;
                        $detalle->precio_costo=$request->get('precio_costo'.$ar->idrubro_articulo);
                        $detalle->save();

                        $total = $total + $precio_venta;

                    }
                    else
                    {
                        $precio_final = $request->get('precio_final'.$ar->idrubro_articulo);

                            $precio_venta = $precio_final;
                            $precio_oferta = 0;
                            $descuento = 0;

                        $detalle = new DetalleOrden();
                        $detalle->idorden=$orden->idorden;
                        $detalle->idarticulo=$request->get('idarticulo'.$ar->idrubro_articulo);
                        $detalle->cantidad=$request->get('cantidad'.$ar->idrubro_articulo);
                        $detalle->precio_venta=$precio_venta;
                        $detalle->precio_costo=$request->get('precio_costo'.$ar->idrubro_articulo);
                        $detalle->save();

                        $total = $total + $precio_venta;

                    }

                    
                }
                else
                {
                    //ver si el articulo de rubro ya existe en base de datos
                    $ExisteArticuloOrden=DB::table('detalle_orden')
                    ->where('idorden','=',$idorden)
                    ->where('idarticulo','=',$ar->idarticulo)
                    ->get();
                    
                    if($ExisteArticuloOrden->count() >= 1)
                    {
                        $detalleOrden=DB::table('detalle_orden')
                        ->where('idorden','=',$idorden)
                        ->where('idarticulo','=',$ar->idarticulo)
                        ->first();

                        $OrdenTotal=DB::table('orden')
                        ->where('idorden','=',$idorden)
                        ->first();

                        $nuevoTotal= $OrdenTotal->total - $detalleOrden->precio_venta;

                        $restaOrden=Orden::findOrFail($idorden);
                        $restaOrden->total = $nuevoTotal;
                        $restaOrden->save();

                        $EliminarDetalle=DetalleOrden::findOrFail($detalleOrden->iddetalle_orden);
                        $EliminarDetalle->delete();
                    }
                }	
            }

            $totalorden=Orden::findOrFail($orden->idorden);
            $totalorden->total=$total;
            $totalorden->estado_orden="Pendiente";
    		$totalorden->save();
            
            
            $pac=DB::table('paciente')->where('idpaciente','=',$orden->idpaciente)->first();
            $doc=DB::table('users')->where('id','=',$orden->iddoctor)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Ventas";
            $bitacora->descripcion="Se edito una orden, Paciente: ".$pac->nombre.", Doctor: ".$doc->name.", Total:".$moneda.$total;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}

    	return Redirect::to('ventas/orden');
        
    }

    public function aventa(Request $request)
    {
        try
    	{ 
            //cargar orden
            $idorden = $request->get('idorden');
            $orden=DB::table('orden as o')
            ->join('paciente as p','o.idpaciente','=','p.idpaciente')
            ->join('users as d','o.iddoctor','=','d.id')
            ->join('users as u','o.idusuario','=','u.id')
            ->select('o.idorden','o.idventa','o.fecha','o.codigoeeps','o.codigopapanicolau','o.observaciones','o.estado_orden','o.estado','o.total','p.idpaciente','p.nombre as Paciente','p.sexo','p.telefono','p.fecha_nacimiento','p.dpi','p.nit','d.id as iddoctor','d.name as Doctor','d.especialidad','u.id as idusuario','u.name as Usuario','u.tipo_usuario')
            ->where('o.idorden','=',$idorden)   
            ->first();

            $detalles=DB::table('detalle_orden')
            ->where('idorden','=',$idorden)
            ->get();

            $idempresa = Auth::user()->idempresa;
            $porcentaje_imp = Auth::user()->porcentaje_imp;
            $max_descuento = Auth::user()->max_descuento;
            $idusuario = Auth::user()->id;
            $zona_horaria = Auth::user()->zona_horaria;
            $comision = Auth::user()->comision;

            $fecha=Carbon::now($zona_horaria);


            $total_venta=0;
            $total_compra=0;
            $impuesto=0;
            $total_impuesto=0;
            $total_comision=0;
            
            
            $total_abonado=0;
            $estadoventa="Cerrada";
            $tipopago="Efectivo";

            foreach($detalles as $detalle)
            {
                //total_venta
                $totalPrecioVenta = $detalle->precio_venta;
                $total_venta = $total_venta + $totalPrecioVenta;

                //total_compra
                $totalPrecioCompra = $detalle->precio_costo;
                $total_compra = $total_compra + $totalPrecioCompra;
            }
            
            if($orden->idventa == null)
            {
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
                $venta->idcliente=$orden->idpaciente;
                $venta->idusuario=$idusuario;
                $venta->tipo_comprobante="";
                $venta->serie_comprobante=null;
                $venta->num_comprobante=null;
                
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
                $venta->idorden=$idorden;
                $venta->save();


                $idarticulo = $request->get('idarticulo');
                $cantidad = $request->get('cantidad');
                $descuento = $request->get('descuento');
                $precio_venta = $request->get('precio_venta');
                $precio_compra = $request->get('precio_compra');
                $precio_oferta = $request->get('precio_oferta');


                foreach($detalles as $od) 
                {
                    $detalle = new DetalleVenta();
                    $detalle->idventa=$venta->idventa;
                    $detalle->idarticulo=$od->idarticulo;
                    $detalle->cantidad=$od->cantidad;
                    $detalle->precio_venta=$od->precio_venta;
                    $detalle->precio_compra=$od->precio_costo;
                    $detalle->precio_oferta=0;
                    $detalle->descuento=0;
                    $detalle->agregado= "SI";
                    $detalle->save();	
                }

                //cambiamos estado de orden a finalizada y agregamos id de venta

                $totalorden=Orden::findOrFail($orden->idorden);
                $totalorden->estado_orden="Finalizada";
                $totalorden->idventa=$venta->idventa;
                $totalorden->save();

            }else
            {
                $ventaAnterior = DB::table('venta')
                ->where('idventa','=',$orden->idventa)
                ->first();

                $total_abonado=$ventaAnterior->abonado;

                if ($total_abonado == $total_venta)
                {
                    $saldo = "Pagado";
                }
                else
                {
                    $saldo = "Pendiente";
                }

                DB::beginTransaction();

                $venta=Venta::findOrFail($orden->idventa);
                $venta->idempresa=$idempresa;
                $venta->idcliente=$orden->idpaciente;
                $venta->idusuario=$idusuario;
                $venta->tipo_comprobante="";
                $venta->serie_comprobante=null;
                $venta->num_comprobante=null;
                
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
                $venta->idorden=$idorden;
                $venta->save();

                $idarticulo = $request->get('idarticulo');
                $cantidad = $request->get('cantidad');
                $descuento = $request->get('descuento');
                $precio_venta = $request->get('precio_venta');
                $precio_compra = $request->get('precio_compra');
                $precio_oferta = $request->get('precio_oferta');

                $BorrarDetallesVenta=DetalleVenta::where('idventa',$orden->idventa)->delete();

                foreach($detalles as $od) 
                {
                    $detalle = new DetalleVenta();
                    $detalle->idventa=$venta->idventa;
                    $detalle->idarticulo=$od->idarticulo;
                    $detalle->cantidad=$od->cantidad;
                    $detalle->precio_venta=$od->precio_venta;
                    $detalle->precio_compra=$od->precio_costo;
                    $detalle->precio_oferta=0;
                    $detalle->descuento=0;
                    $detalle->agregado= "SI";
                    $detalle->save();	
                }

                //cambiamos estado de orden a finalizada y agregamos id de venta

                $totalorden=Orden::findOrFail($orden->idorden);
                $totalorden->estado_orden="Finalizada";
                $totalorden->save();
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
            $bitacora->descripcion="Se creo una venta nueva desde una orden, Cliente: ".$cli->nombre.", Comprobante: ".$venta->tipo_comprobante." ".$venta->serie_comprobante."-".$venta->num_comprobante.", Fecha: ".$venta->fecha.", Total Venta: Q.".$venta->total_venta.", Abonado: ".$venta->abonado.", Estado Saldo: ".$venta->estadosaldo.", Estado Venta: ".$venta->estadoventa.", Tipo Pago: ".$venta->tipopago;
            $bitacora->save();

    		DB::commit();

    	}catch(\Exception $e)
    	{
    		DB::rollback();
    	}   
        return Redirect::to('ventas/orden');
    }

    public function destroy($id)
    {
    	$orden=Orden::findOrFail($id);
    	$orden->Estado='Cancelada';
        $orden->update();
  
        $pac=DB::table('paciente')->where('idpaciente','=',$orden->idpaciente)->first();
        $doc=DB::table('users')->where('id','=',$orden->iddoctor)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Ventas";
            $bitacora->descripcion="Se Cancelo una orden, Paciente: ".$pac->nombre.", Doctor: ".$doc->name.", Total:".$moneda.$orden->total;
            $bitacora->save();

    	return Redirect::to('ventas/orden');
    }
}
