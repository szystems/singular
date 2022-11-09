<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;
use sisVentasWeb\Http\Requests;
use sisVentasWeb\Persona;
use sisVentasWeb\Vendedor;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\PersonaFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;

class ProveedorController extends Controller
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
            $personas=DB::table('persona')
            ->where('nombre','LIKE','%'.$query.'%')
            ->where ('tipo','=','Proveedor')
            ->where('idempresa','=',$idempresa)
            ->orwhere('num_documento','LIKE','%'.$query.'%')
            ->where ('tipo','=','Proveedor')
            ->where('idempresa','=',$idempresa)
            ->orderBy('nombre','asc')
            ->paginate(20);
            return view('compras.proveedor.index',["personas"=>$personas,"searchText"=>$query]);
        }
    }
    public function create()
    {
        return view("compras.proveedor.create");
    }
    public function store (PersonaFormRequest $request)
    {
        $idempresa = Auth::user()->idempresa;
        $persona=new Persona;
        $persona->idempresa=$idempresa;
        $persona->tipo='Proveedor';
        $persona->nombre=$request->get('nombre');
        $persona->tipo_documento=$request->get('tipo_documento');
        $persona->num_documento=$request->get('num_documento');
        $persona->direccion=$request->get('direccion');
        $persona->telefono=$request->get('telefono');
        $persona->email=$request->get('email');
        $persona->banco=$request->get('banco');
        $persona->nombre_cuenta=$request->get('nombre_cuenta');
        $persona->numero_cuenta=$request->get('numero_cuenta');
        $persona->save();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Compras";
        $bitacora->descripcion="Se creo un proveedor nuevo, Nombre: ".$persona->nombre.", Documento: ".$persona->tipo_documento."-".$persona->num_documento.", Dirección: ".$persona->direccion.", Teléfono: ".$persona->telefono.", Email: ".$persona->email.", Banco: ".$persona->banco.", Nombre Cuenta: ".$persona->nombre_cuenta.", Numero Cuenta: ".$persona->numero_cuenta;
        $bitacora->save();

        return Redirect::to('compras/proveedor');

    }
    public function show($id)
    {
        $vendedores=DB::table('vendedor')
        ->where('idproveedor','=',$id)
        ->get();

        return view("compras.proveedor.show",["persona"=>Persona::findOrFail($id),"vendedores"=>$vendedores]);
    }
    public function edit($id)
    {
        $vendedores=DB::table('vendedor')
        ->where('idproveedor','=',$id)
        ->get();

        return view("compras.proveedor.edit",["persona"=>Persona::findOrFail($id),"vendedores"=>$vendedores]);
    }
    public function update(PersonaFormRequest $request,$id)
    {
        $persona=Persona::findOrFail($id);
        $persona->tipo_documento=$request->get('tipo_documento');
        $persona->nombre=$request->get('nombre');
        $persona->num_documento=$request->get('num_documento');
        $persona->direccion=$request->get('direccion');
        $persona->telefono=$request->get('telefono');
        $persona->email=$request->get('email');
        $persona->banco=$request->get('banco');
        $persona->nombre_cuenta=$request->get('nombre_cuenta');
        $persona->numero_cuenta=$request->get('numero_cuenta');
        $persona->update();

        $zonahoraria = Auth::user()->zona_horaria;
        $moneda = Auth::user()->moneda;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Compras";
        $bitacora->descripcion="Se edito un proveedor, Nombre: ".$persona->nombre.", Documento: ".$persona->tipo_documento."-".$persona->num_documento.", Dirección: ".$persona->direccion.", Teléfono: ".$persona->telefono.", Email: ".$persona->email.", Banco: ".$persona->banco.", Nombre Cuenta: ".$persona->nombre_cuenta.", Numero Cuenta: ".$persona->numero_cuenta;
        $bitacora->save();


        return Redirect::to('compras/proveedor');
    }
    public function destroy($id)
    {
        $persona=Persona::findOrFail($id);
        $persona->tipo='Inactivo';
        $persona->update();

        $pro=DB::table('persona')->where('idpersona','=',$id)->first();

            $zonahoraria = Auth::user()->zona_horaria;
            $fechahora= Carbon::now($zonahoraria);
            $bitacora=new Bitacora;
            $bitacora->idempresa=Auth::user()->idempresa;
            $bitacora->idusuario=Auth::user()->id;
            $bitacora->fecha=$fechahora;
            $bitacora->tipo="Compras";
            $bitacora->descripcion="Se elimino un proveedor, Nombre: ".$pro->nombre;
            $bitacora->save();

        return Redirect::to('compras/proveedor');
    }
}
