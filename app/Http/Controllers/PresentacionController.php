<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Presentacion;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\PresentacionFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;

class PresentacionController extends Controller
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
            $query=trim($request->get('searchText'));
            $presentaciones=DB::table('presentacion')
            ->where('nombre','LIKE','%'.$query.'%')
            ->where('estado','=','Habilitado')
            ->orderBy('nombre','asc')
            ->paginate(20);
            return view('almacen.presentacion.index',["presentaciones"=>$presentaciones,"searchText"=>$query]);
        }
    }
    public function create()
    {
        return view("almacen.presentacion.create");
    }
    public function store (PresentacionFormRequest $request)
    {
        $idempresa = Auth::user()->idempresa;
        $presentacion=new Presentacion;
        $presentacion->nombre=$request->get('nombre');
        $presentacion->descripcion=$request->get('descripcion');
        $presentacion->estado='Habilitado';
        $presentacion->save();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Almacen";
        $bitacora->descripcion="Se creo una presentacion nueva, Nombre: ".$presentacion->nombre.", Descripcion: ".$presentacion->descripcion;
        $bitacora->save();

        $request->session()->flash('alert-success', 'Se creo correctamente la nueva Presentación.');

        return Redirect::to('almacen/presentacion');

    }
    public function show($id)
    {
        return view("almacen.presentacion.show",["presentacion"=>Presentacion::findOrFail($id)]);
    }
    public function edit($id)
    {
        return view("almacen.presentacion.edit",["presentacion"=>Presentacion::findOrFail($id)]);
    }
    public function update(PresentacionFormRequest $request,$id)
    {
        $presentacion=Presentacion::findOrFail($id);
        $presentacion->nombre=$request->get('nombre');
        $presentacion->descripcion=$request->get('descripcion');
        $presentacion->update();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Almacen";
        $bitacora->descripcion="Se edito una presentacion, Nombre: ".$presentacion->nombre.", Descripcion: ".$presentacion->descripcion;
        $bitacora->save();

        $request->session()->flash('alert-success', 'Se edito correctamente la Presentación.');

        return Redirect::to('almacen/presentacion');
    }
    public function destroy($id)
    {
        $presentacion=Presentacion::findOrFail($id);
        $presentacion->estado='Eliminado';
        $presentacion->update();

            $zonahoraria = Auth::user()->zona_horaria;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Almacen";
            $bitacora->descripcion="Se elimino una Presentación, Nombre: ".$presentacion->nombre;
            $bitacora->save();

        return Redirect::to('almacen/presentacion');
    }
}
