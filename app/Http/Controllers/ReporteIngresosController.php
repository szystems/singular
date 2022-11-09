<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisVentasWeb\Http\Requests\ReporteIngresosFormRequest;
use sisVentasWeb\ReporteIngresos;
use sisVentasWeb\Articulo;
use sisVentasWeb\DetalleIngreso;
use DB;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;


use Auth;
use sisVentasWeb\User;

class ReporteIngresosController extends Controller
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
					->select('name','id')
                    ->where('id','=',$usuario)
                    ->where('idempresa','=',$idempresa)
                    ->get();
                    
                $provfiltro=DB::table('persona')
                ->select('nombre','idpersona')
                ->where('idpersona','=',$proveedor)
                ->where('idempresa','=',$idempresa)
                ->get();

                $zona_horaria = Auth::user()->zona_horaria;
                $hoy = Carbon::now($zona_horaria);
                $hoy = $hoy->format('d-m-Y');
    				//$ingreso->fecha=$mytime->toDateTimeString();

                if($desde != '1970-01-01' or $hasta != '1970-01-01')
                {
                    $ingresos=DB::table('ingreso as i')
                    ->join('persona as p','i.idproveedor','=','p.idpersona')
                    ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
                    ->join('users as u','i.idusuario','=','u.id')
                    ->select('i.idingreso','i.fecha','p.nombre','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))
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
                    ->select('i.idingreso','i.fecha','p.nombre','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))
                    ->where('i.idempresa','=',$idempresa)
                    ->orderBy('i.fecha','desc')
                    ->groupBy('i.idingreso','i.fecha','p.nombre','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
                    ->paginate(20);
                }
                return view('reportes.ingresos.index',["ingresos"=>$ingresos,"personas"=>$personas,"usuarios"=>$usuarios,"desde"=>$desde,"hasta"=>$hasta,"proveedor"=>$proveedor,"usuario"=>$usuario,"estado"=>$estado,"hoy"=>$hoy,"usufiltro"=>$usufiltro,"provfiltro"=>$provfiltro]);
            }
        
    }

    public function show($id)
    {
        $idempresa = Auth::user()->idempresa;

    	$ingreso=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')
            ->join('users as u','i.idusuario','=','u.id')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->select('i.idingreso','i.fecha','p.nombre','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))
            ->groupBy('i.idingreso','i.fecha','p.nombre','u.name','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
            ->where('i.idingreso','=',$id)
            ->where('i.idempresa','=',$idempresa)
            ->first();

        $detalles=DB::table('detalle_ingreso as d')
        	->join('articulo as a','d.idarticulo','=','a.idarticulo')
        	->select('a.nombre as articulo','d.cantidad','d.precio_compra','d.precio_venta','d.precio_oferta')
            ->where('d.idingreso','=',$id)
        	->get();

        return view("reportes.ingresos.show",["ingreso"=>$ingreso,"detalles"=>$detalles]);
    }
    
}
