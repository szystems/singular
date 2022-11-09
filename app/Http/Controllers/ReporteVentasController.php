<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisVentasWeb\Http\Requests\ReporteVentasFormRequest;
use sisVentasWeb\ReporteVentas;
use sisVentasWeb\Articulo;
use sisVentasWeb\DetalleVenta;
use DB;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

use Auth;
use sisVentasWeb\User;

class ReporteVentasController extends Controller
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

            $cliente=trim($request->get('cliente'));
            $usuario=trim($request->get('usuario'));
            $estadosaldo=trim($request->get('estadosaldo'));
            $estadoventa=trim($request->get('estadoventa'));
            $tipopago=trim($request->get('tipopago'));
            $personas=DB::table('persona')
            ->where('tipo','=','Cliente')
            ->where('idempresa','=',$idempresa)
            ->get();

            $usuarios=DB::table('users')
            ->where('idempresa','=',$idempresa)
            ->get();

            $usufiltro=DB::table('users')
			->select('name','id')
            ->where('id','=',$usuario)
            ->where('idempresa','=',$idempresa)
            ->get();
                    
            $clientefiltro=DB::table('persona')
            ->select('nombre','idpersona')
            ->where('idpersona','=',$cliente)
            ->where('idempresa','=',$idempresa)
            ->get();

            $zona_horaria = Auth::user()->zona_horaria;
            $hoy = Carbon::now($zona_horaria);
            $hoy = $hoy->format('d-m-Y');

                
            if($desde != '1970-01-01' or $hasta != '1970-01-01')
            {
                if ( $estadosaldo != null )
                {
                    $ventas=DB::table('venta as v')
                    ->join('persona as p','v.idcliente','=','p.idpersona')
                    ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                    ->join('users as u','v.idusuario','=','u.id')
                    ->select('v.idventa','p.nombre','u.name','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.fecha','v.impuesto','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estado','v.estadosaldo','v.estadoventa','v.tipopago')
                    ->whereBetween('fecha', [$desde, $hasta])
                    ->where('p.idpersona','LIKE','%'.$cliente.'%')
                    ->where('u.id','LIKE','%'.$usuario.'%')
                    ->where('v.idempresa','=',$idempresa)
                    ->where('v.estado','=','A')
                    ->where('v.estadosaldo','=',$estadosaldo)
                    ->where('v.estadoventa','LIKE','%'.$estadoventa.'%')
                    ->where('v.tipopago','LIKE','%'.$tipopago.'%')
                    ->orderBy('v.fecha','asc')
                    ->groupBy('v.idventa','p.nombre','u.name','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.fecha','v.impuesto','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estado','v.estadosaldo','v.estadoventa','v.tipopago')
                    ->paginate(20);
                }
                else
                {
                    $ventas=DB::table('venta as v')
                    ->join('persona as p','v.idcliente','=','p.idpersona')
                    ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                    ->join('users as u','v.idusuario','=','u.id')
                    ->select('v.idventa','p.nombre','u.name','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.fecha','v.impuesto','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estado','v.estadosaldo','v.estadoventa','v.tipopago')
                    ->whereBetween('fecha', [$desde, $hasta])
                    ->where('p.idpersona','LIKE','%'.$cliente.'%')
                    ->where('u.id','LIKE','%'.$usuario.'%')
                    ->where('v.idempresa','=',$idempresa)
                    ->where('v.estado','=','A')
                    ->where('v.estadoventa','LIKE','%'.$estadoventa.'%')
                    ->where('v.tipopago','LIKE','%'.$tipopago.'%')
                    ->where('v.estadosaldo','!=',NULL)
                    ->orderBy('v.fecha','asc')
                    ->groupBy('v.idventa','p.nombre','u.name','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.fecha','v.impuesto','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estado','v.estadosaldo','v.estadoventa','v.tipopago')
                    ->paginate(20);
                        
                }
            }
            else
            {
                $ventas=DB::table('venta as v')
                    ->join('persona as p','v.idcliente','=','p.idpersona')
                    ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                    ->join('users as u','v.idusuario','=','u.id')
                    ->select('v.idventa','p.nombre','u.name','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.fecha','v.impuesto','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estado','v.estadosaldo','v.estadoventa','v.tipopago')
                    ->where('v.idempresa','=',$idempresa)
                    ->where('v.estado','=','A')
                    ->where('v.estadoventa','LIKE','%'.$estadoventa.'%')
                    ->where('v.tipopago','LIKE','%'.$tipopago.'%')
                    ->where('v.estadosaldo','!=',NULL)
                    ->orderBy('v.fecha','asc')
                    ->groupBy('v.idventa','p.nombre','u.name','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.fecha','v.impuesto','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estado','v.estadosaldo','v.estadoventa','v.tipopago')
                    ->paginate(20);
            }
            return view('reportes.ventas.index',["ventas"=>$ventas,"personas"=>$personas,"usuarios"=>$usuarios,"desde"=>$desde,"hasta"=>$hasta,"cliente"=>$cliente,"usuario"=>$usuario,"estadosaldo"=>$estadosaldo,"estadoventa"=>$estadoventa,"tipopago"=>$tipopago,"hoy"=>$hoy,"usufiltro"=>$usufiltro,"clientefiltro"=>$clientefiltro]);
        }
    }

        public function show($id)
    {
        $idempresa = Auth::user()->idempresa;

    	$venta=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->join('users as u','v.idusuario','=','u.id')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->select('v.idventa','p.nombre','u.name','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.fecha','v.impuesto','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estado','v.estadosaldo','estadoventa','tipopago')
            ->groupBy('v.idventa','p.nombre','u.name','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.fecha','v.impuesto','v.total_venta','v.total_compra','v.total_comision','v.total_impuesto','v.abonado','v.estado','v.estadosaldo','estadoventa','tipopago')
            ->where('v.idventa','=',$id)
            ->where('v.idempresa','=',$idempresa)
            ->first();

        $detalles=DB::table('detalle_venta as d')
        	->join('articulo as a','d.idarticulo','=','a.idarticulo')
        	->select('a.nombre as articulo','a.codigo','d.cantidad','d.descuento','d.precio_compra','d.precio_venta','d.precio_oferta')
        	->where('d.idventa','=',$id)
        	->get();

        return view("reportes.ventas.show",["venta"=>$venta,"detalles"=>$detalles]);
    }
}
