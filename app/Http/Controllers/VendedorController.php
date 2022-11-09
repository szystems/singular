<?php

namespace sisVentasWeb\Http\Controllers;

use Illuminate\Http\Request;

use sisVentasWeb\Http\Requests;
use sisVentasWeb\Vendedor;
use sisVentasWeb\Bitacora;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use sisVentasWeb\Http\Requests\VendedorFormRequest;
use sisVentasWeb\Http\Requests\BitacoraFormRequest;
use DB;
use Auth;
use sisVentasWeb\User;
use Illuminate\Support\Facades\Input;

class VendedorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store (VendedorFormRequest $request)
    {

        $vendedor=new Vendedor;
        $vendedor->idproveedor=$request->get('idproveedor');
        $vendedor->nombre=$request->get('nombre');
        $vendedor->telefono=$request->get('telefono');
        $vendedor->email=$request->get('email');
        $vendedor->codigo=$request->get('codigo');
        $vendedor->save();

        $zonahoraria = Auth::user()->zona_horaria;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Compras";
        $bitacora->descripcion="Se agrego un nuevo Vendedor, Nombre: ".$vendedor->nombre;
        $bitacora->save();

        $request->session()->flash('alert-success', 'Se agrego el nuevo vendedor.');

        return app('sisVentasWeb\Http\Controllers\ProveedorController')->show($vendedor->idproveedor);

    }
    
    public function update(VendedorFormRequest $request,$id)
    {
        $vendedor=Vendedor::findOrFail($id);
        $vendedor->idproveedor=$request->get('idproveedor');
        $vendedor->nombre=$request->get('nombre');
        $vendedor->telefono=$request->get('telefono');
        $vendedor->email=$request->get('email');
        $vendedor->codigo=$request->get('codigo');
        $vendedor->update();

        $zonahoraria = Auth::user()->zona_horaria;
        $fechahora= Carbon::now($zonahoraria);
        $bitacora=new Bitacora;
        $bitacora->idempresa=Auth::user()->idempresa;
        $bitacora->idusuario=Auth::user()->id;
        $bitacora->fecha=$fechahora;
        $bitacora->tipo="Compras";
        $bitacora->descripcion="Se edito un vendedor, Nombre: ".$vendedor->nombre;
        $bitacora->save();
        
        $request->session()->flash('alert-success', 'Se edito el vendedor.');

        return app('sisVentasWeb\Http\Controllers\ProveedorController')->show($vendedor->idproveedor);
    }
    public function destroy($id)
    {
        $vendedor=DB::table('vendedor')
        ->where('idvendedor','=',$id)
        ->first();

        $idproveedor= $vendedor->idproveedor;

        $borrarvendedor=Vendedor::where('idvendedor',$id)->delete();

        return app('sisVentasWeb\Http\Controllers\ProveedorController')->show($idproveedor);
    }
}
