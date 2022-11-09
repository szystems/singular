<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Rubro;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\RubroFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class RubroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request)
        {
            $query=trim($request->get('searchText'));
            $rubros=DB::table('rubro')
            ->where('nombre','LIKE','%'.$query.'%')
            ->where ('estado','=','Habilitado')
            ->orderBy('nombre','asc')
            ->paginate(20);
            return view('ventas.rubro.index',["rubros"=>$rubros,"searchText"=>$query]);
        }
    }
    public function create()
    {
        return view("ventas.rubro.create");
    }
    public function store (RubroFormRequest $request)
    {
        $idempresa = Auth::user()->idempresa;

        if($request->get('estado_rubro') == 'Habilitado')
        {
            $estado_rubro = 'Habilitado';
        }else
        {
            $estado_rubro = 'Deshabilitado';
        }

        $rubro=new Rubro;
        $rubro->nombre=$request->get('nombre');
        $rubro->nota=$request->get('nota');
        $rubro->estado_rubro=$estado_rubro;
        $rubro->estado='Habilitado';
        $rubro->save();

        $zonahoraria = Auth::user()->zona_horaria;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Almacen";
        $bitacora->descripcion="Se creo un nuevo rubro, Nombre: ".$rubro->nombre.", Nota: ".$rubro->nota;
        $bitacora->save();

        return Redirect::to('ventas/rubro');

    }
    public function show($id)
    {
        $rubroArticulos=DB::table('rubro_articulo as ra')
            ->join('articulo as a','ra.idarticulo','=','a.idarticulo')
            ->where('ra.idrubro','=',$id)
			->get();

        $articulos=DB::table('articulo')
            ->where('estado','=',"Activo")
			->get();

        return view("ventas.rubro.show",["rubro"=>Rubro::findOrFail($id),"rubroArticulos"=>$rubroArticulos,"articulos"=>$articulos]);
    }
    public function edit($id)
    {
        return view("ventas.rubro.edit",["rubro"=>Rubro::findOrFail($id)]);
    }
    public function update(RubroFormRequest $request,$id)
    {
        if($request->get('estado_rubro') == 'Habilitado')
        {
            $estado_rubro = 'Habilitado';
        }else
        {
            $estado_rubro = 'Deshabilitado';
        }

        $rubro=Rubro::findOrFail($id);
        $rubro->nombre=$request->get('nombre');
        $rubro->nota=$request->get('nota');
        $rubro->estado_rubro=$estado_rubro;
        $rubro->update();

        $zonahoraria = Auth::user()->zona_horaria;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Ventas";
        $bitacora->descripcion="Se edito un rubro, Nombre: ".$rubro->nombre.", Nota: ".$rubro->nota;
        $bitacora->save();

        return Redirect::to('ventas/rubro');
    }
    public function destroy($id)
    {
        $rubro=Rubro::findOrFail($id);
        $rubro->estado_rubro='Deshabilitado';
        $rubro->estado='Eliminado';
        $rubro->update();

        $rub=DB::table('rubro')->where('idrubro','=',$id)->first();

        $zonahoraria = Auth::user()->zona_horaria;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Almacen";
        $bitacora->descripcion="Se elimino un rubro, Nombre: ".$rub->nombre;
        $bitacora->save();

        return Redirect::to('ventas/rubro');
    }
}
