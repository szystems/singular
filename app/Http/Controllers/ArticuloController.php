<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisVentasWeb\Http\Requests\ArticuloFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use sisVentasWeb\Articulo;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use DB;
use Auth;
use sisVentasWeb\User;

class ArticuloController extends Controller
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
            $queryArticulo=trim($request->get('farticulo'));
            $queryCategoria=trim($request->get('fcategoria'));
            $articulos=DB::table('articulo as a')
            ->join('categoria as c','a.idcategoria','=','c.idcategoria')
            ->select('a.idarticulo','a.idempresa','a.nombre','a.codigo','a.minimo','c.nombre as categoria','a.bodega','a.ubicacion','a.descripcion','a.imagen','a.estado')
            ->where('a.nombre','LIKE','%'.$queryArticulo.'%')
            ->where('c.nombre','LIKE','%'.$queryCategoria.'%')
            ->where('a.estado','=','Activo')
            ->orderBy('a.nombre','asc')
            ->paginate(20);

            $filtroArticulos=DB::table('articulo')
            ->where('estado','=','Activo')
            ->orderBy('nombre','asc')
            ->get();

            $filtroCategorias=DB::table('categoria')
            ->where('condicion','=','1')
            ->orderBy('nombre','asc')
            ->get();

            return view('almacen.articulo.index',["articulos"=>$articulos,"filtroArticulos"=>$filtroArticulos,"filtroCategorias"=>$filtroCategorias,"queryArticulo"=>$queryArticulo,"queryCategoria"=>$queryCategoria]);
        }
    }
    public function create()
    {
        $idempresa = Auth::user()->idempresa;
        $categorias=DB::table('categoria')
        ->where('idempresa','=',$idempresa)
        ->where('condicion','=','1')
        ->get();
        return view("almacen.articulo.create",["categorias"=>$categorias]);
    }
    public function store (ArticuloFormRequest $request)
    {
        
        $idempresa = Auth::user()->idempresa;
        $articulo=new Articulo;
        $articulo->idcategoria=$request->get('idcategoria');
        $articulo->codigo=$request->get('codigo');
        $articulo->nombre=$request->get('nombre');
        $articulo->bodega=$request->get('bodega');
        $articulo->ubicacion=$request->get('ubicacion');
        $articulo->minimo=$request->get('minimo');
        $articulo->descripcion=$request->get('descripcion');
        $articulo->estado='Activo';
        $articulo->idempresa=$idempresa;

        if (input::hasfile('imagen')){
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);

        	$file=Input::file('imagen');
        	$file->move(public_path().'/imagenes/articulos/',$generar_codigo_imagen.$file->getClientOriginalName());
        	$articulo->imagen=$generar_codigo_imagen.$file->getClientOriginalName();
        }

        $articulo->save();

        $request->session()->flash('alert-success', 'Se agrego correctamente un articulo.');

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Almacen ";
        $bitacora->descripcion="Se creo un artículo nuevo, Nombre: ".$articulo->nombre.", Código: ".$articulo->codigo.", Stock minimo: ".$articulo->minimo.", Descripción: ".$articulo->descripcion.", Bodega: ".$articulo->bodega.", Ubicación: ".$articulo->ubicacion;
        $bitacora->save();

        return Redirect::to('almacen/articulo');

    }

    public function show($id)
    {
        $idempresa = Auth::user()->idempresa;
    	$articulo=DB::table('articulo as a')
            ->join('categoria as c','a.idcategoria','=','c.idcategoria')
            ->select('a.idarticulo','c.nombre as categoria','a.codigo','a.nombre','a.minimo','a.bodega','a.ubicacion','a.descripcion','a.imagen','a.estado')
            ->where('a.estado','=','Activo')
            ->where('a.idarticulo','=',$id)
            ->where('a.idempresa','=',$idempresa)
            ->first();
        
        

        return view("almacen.articulo.show",["articulo"=>$articulo]);
    }

    public function edit($id)
    {
        $idempresa = Auth::user()->idempresa;
        $articulo=Articulo::findOrFail($id);
        $categorias=DB::table('categoria')
        ->where('idempresa','=',$idempresa)
        ->where('condicion','=','1')
        ->get();
        
       
        return view("almacen.articulo.edit",["articulo"=>$articulo,"categorias"=>$categorias]);
    }


    public function update(ArticuloFormRequest $request,$id)
    {
        $articulo=Articulo::findOrFail($id);
        $articulo->idcategoria=$request->get('idcategoria');
        $articulo->codigo=$request->get('codigo');
        $articulo->nombre=$request->get('nombre');
        $articulo->minimo=$request->get('minimo');
        $articulo->bodega=$request->get('bodega');
        $articulo->ubicacion=$request->get('ubicacion');
        $articulo->descripcion=$request->get('descripcion');
        $articulo->estado='Activo';

        if (input::hasfile('imagen')){
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);

        	$file=Input::file('imagen');
        	$file->move(public_path().'/imagenes/articulos/',$generar_codigo_imagen.$file->getClientOriginalName());
        	$articulo->imagen=$generar_codigo_imagen.$file->getClientOriginalName();
        }
        $articulo->update();

        $request->session()->flash('alert-success', 'Se edito correctamente un articulo.');

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Almacen ";
        $bitacora->descripcion="Se edito un artículo, Nombre: ".$articulo->nombre.", Código: ".$articulo->codigo.", Stock minimo: ".$articulo->minimo.", Descripción: ".$articulo->descripcion.", Bodega: ".$articulo->bodega.", Ubicación: ".$articulo->ubicacion;
        $bitacora->save();

        return Redirect::to('almacen/articulo');
    }

    

    public function destroy($id)
    {
        
            $articulo=Articulo::findOrFail($id);
            $articulo->estado='Inactivo';
            $articulo->update();

            $art=DB::table('articulo')->where('idarticulo','=',$id)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Almacen";
            $bitacora->descripcion="Se elimino un artículo, Nombre: ".$art->nombre;
            $bitacora->save();

        
        return Redirect::to('almacen/articulo');
    }
}
