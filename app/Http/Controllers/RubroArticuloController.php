<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Rubro;
use sisVentasWeb\RubroArticulo;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\RubroArticuloFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class RubroArticuloController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(RubroArticuloFormRequest $request)
    {
        $idrubro = $request->get('idrubro');
        $idarticulo = $request->get('idarticulo');
        $precio_costo = $request->get('precio_costo');
        $precio_venta = $request->get('precio_venta');

        $comprobarArticulo=DB::table('rubro_articulo')
            ->where('idarticulo','=',$idarticulo)
			->get();
        
        if($comprobarArticulo->count() >= 1)
        {
            $request->session()->flash('alert-danger', "Este articulo ya está en este u otro rubro.");

            $rubroArticulos=DB::table('rubro_articulo as ra')
            ->join('articulo as a','ra.idarticulo','=','a.idarticulo')
            ->where('ra.idrubro','=',$idrubro)
			->get();

            $articulos=DB::table('articulo')
            ->where('estado','=',"Activo")
			->get();
            
            return view("ventas.rubro.show",["rubro"=>Rubro::findOrFail($idrubro),"rubroArticulos"=>$rubroArticulos,"articulos"=>$articulos]);
        }
        else
        {
            $rubroArticulo=new RubroArticulo;
            $rubroArticulo->idrubro = $idrubro;
            $rubroArticulo->idarticulo = $idarticulo;
            $rubroArticulo->precio_costo = $precio_costo;
            $rubroArticulo->precio_venta = $precio_venta;
            $rubroArticulo->save();

            $articulo=DB::table('articulo')->where('idarticulo','=',$idarticulo)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $moneda = Auth::user()->moneda;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Almacen";
            $bitacora->descripcion="Se agrego un articulo al rubro, Articulo: ".$articulo->nombre;
            $bitacora->save();

            $request->session()->flash('alert-success', "Se agrego el articulo exitosamente, Articulo: ".$articulo->nombre);

            
            $rubroArticulos=DB::table('rubro_articulo as ra')
            ->join('articulo as a','ra.idarticulo','=','a.idarticulo')
            ->where('ra.idrubro','=',$idrubro)
			->get();

            $articulos=DB::table('articulo')
            ->where('estado','=',"Activo")
			->get();

            return view("ventas.rubro.show",["rubro"=>Rubro::findOrFail($idrubro),"rubroArticulos"=>$rubroArticulos,"articulos"=>$articulos]);
        }
    }

    public function update(Request $request,$id)
    {

        //obtenemos datos de formulario
        
        $idrubro=$request->get('idrubro');
        $precio_costo=$request->get('precio_costo');
        $precio_venta=$request->get('precio_venta');
        
        $rubroArticulo=RubroArticulo::findOrFail($id);
        $rubroArticulo->precio_costo=$precio_costo;
        $rubroArticulo->precio_venta=$precio_venta;
        $rubroArticulo->save();

        $request->session()->flash('alert-success', 'El articulo de rubro se edito correctamente.');

        $rubroArticulos=DB::table('rubro_articulo as ra')
            ->join('articulo as a','ra.idarticulo','=','a.idarticulo')
            ->where('ra.idrubro','=',$idrubro)
			->get();

        $articulos=DB::table('articulo')
            ->where('estado','=',"Activo")
			->get();

        return view("ventas.rubro.show",["rubro"=>Rubro::findOrFail($idrubro),"rubroArticulos"=>$rubroArticulos,"articulos"=>$articulos]);
    }

    public function destroy($id)
    {   
        $rubroArticulo=DB::table('rubro_articulo as ra')
            ->join('articulo as a','ra.idarticulo','=','a.idarticulo')
            ->where('ra.idrubro_articulo','=',$id)
			->first();

        $EliminarArticulo=RubroArticulo::where('idrubro_articulo',$id)->delete();

            $zonahoraria = Auth::user()->zona_horaria;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Almacen";
            $bitacora->descripcion="Se elimino un artículo del rubro.";
            $bitacora->save();

            $rubroArticulos=DB::table('rubro_articulo as ra')
            ->join('articulo as a','ra.idarticulo','=','a.idarticulo')
            ->where('ra.idrubro','=',$rubroArticulo->idrubro)
			->get();

            $articulos=DB::table('articulo')
            ->where('estado','=',"Activo")
			->get();

            return view("ventas.rubro.show",["rubro"=>Rubro::findOrFail($rubroArticulo->idrubro),"rubroArticulos"=>$rubroArticulos,"articulos"=>$articulos]);
    	
    }
}
