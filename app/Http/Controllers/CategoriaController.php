<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Categoria;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\CategoriaFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class CategoriaController extends Controller
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
            $categorias=DB::table('categoria')
            ->where('nombre','LIKE','%'.$query.'%')
            ->where ('condicion','=','1')
            ->where('idempresa','=',$idempresa)
            ->orderBy('nombre','asc')
            ->paginate(20);
            return view('almacen.categoria.index',["categorias"=>$categorias,"searchText"=>$query]);
        }
    }
    public function create()
    {
        return view("almacen.categoria.create");
    }
    public function store (CategoriaFormRequest $request)
    {
        $idempresa = Auth::user()->idempresa;
        $categoria=new Categoria;
        $categoria->nombre=$request->get('nombre');
        $categoria->descripcion=$request->get('descripcion');
        if (input::hasfile('imagen')){
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);

        	$file=Input::file('imagen');
        	$file->move(public_path().'/imagenes/categorias/',$generar_codigo_imagen.$file->getClientOriginalName());
        	$categoria->imagen=$generar_codigo_imagen.$file->getClientOriginalName();
        }
        $categoria->condicion='1';
        $categoria->idempresa=$idempresa;
        $categoria->save();

        $zonahoraria = Auth::user()->zona_horaria;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Almacen";
        $bitacora->descripcion="Se creo una nueva categoría nueva, Nombre: ".$categoria->nombre.", Descripción: ".$categoria->descripcion;
        $bitacora->save();

        return Redirect::to('almacen/categoria');

    }
    public function show($id)
    {
        return view("almacen.categoria.show",["categoria"=>Categoria::findOrFail($id)]);
    }
    public function edit($id)
    {
        return view("almacen.categoria.edit",["categoria"=>Categoria::findOrFail($id)]);
    }
    public function update(CategoriaFormRequest $request,$id)
    {
        $categoria=Categoria::findOrFail($id);
        $categoria->nombre=$request->get('nombre');
        $categoria->descripcion=$request->get('descripcion');
        if (input::hasfile('imagen')){
            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $generar_codigo_imagen = substr(str_shuffle($permitted_chars), 0, 5);

        	$file=Input::file('imagen');
        	$file->move(public_path().'/imagenes/categorias/',$generar_codigo_imagen.$file->getClientOriginalName());
        	$categoria->imagen=$generar_codigo_imagen.$file->getClientOriginalName();
        }
        $categoria->update();

        $zonahoraria = Auth::user()->zona_horaria;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Almacen";
        $bitacora->descripcion="Se edito una categoría, Nombre: ".$categoria->nombre.", Descripción: ".$categoria->descripcion;
        $bitacora->save();

        return Redirect::to('almacen/categoria');
    }
    public function destroy($id)
    {
        $categoria=Categoria::findOrFail($id);
        $categoria->condicion='0';
        $categoria->update();

        $cat=DB::table('categoria')->where('idcategoria','=',$id)->first();

        $zonahoraria = Auth::user()->zona_horaria;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Almacen";
        $bitacora->descripcion="Se elimino una categoría, Nombre: ".$cat->nombre;
        $bitacora->save();

        return Redirect::to('almacen/categoria');
    }
}
