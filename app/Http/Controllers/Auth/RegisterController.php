<?php

namespace sisVentasWeb\Http\Controllers\Auth;

use sisVentasWeb\User;
use sisVentasWeb\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use sisVentasWeb\Persona;
use sisVentasWeb\Http\Requests\PersonaFormRequest;
use DB;

use sisVentasWeb\Mail\CuentaUsuarioRegistro;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'vistas/vinicio';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telefono' => 'required|string|max:45',
            'password' => 'required|string|min:6|confirmed',
            'zona_horaria' => 'required|string|max:45',
            'moneda' => 'required|string|max:5',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \sisVentasWeb\User
     */
    protected function create(array $data)
    {
        $fecha = date('Y-m-j');
        $nuevafecha = strtotime ( '+30 day' , strtotime ( $fecha ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $datosConfig=DB::table('users')
        ->where('principal','=','SI')
        ->first();

        $persona=new Persona;
        $persona->idempresa=$datosConfig->idempresa;
        $persona->tipo='Cliente';
        $persona->nombre=$data['name'];
        $persona->pais='Guatemala';
        $persona->telefono=$data['telefono'];
        $persona->email=$data['email'];
        $persona->usuario='SI';
        $persona->save();

        $idcliente=DB::table('persona')
        ->where('idpersona','=',$persona->idpersona)
        ->first();

        $contrasena = $data['password'];
        $email=$data['email'];
        $nombre=$data['name'];
        Mail::to($email)->send(new CuentaUsuarioRegistro($nombre,$email,$contrasena));

        return User::create([
            'name' => $persona->nombre,
            'email' => $data['email'],
            'telefono' => $data['telefono'],
            'password' => bcrypt($data['password']),
            'empresa' => $datosConfig->empresa,
            'idempresa' => $datosConfig->idempresa,
            'tipo_usuario' => 'Cliente',
            'licencia' => 'Gratis',
            'zona_horaria' => $datosConfig->zona_horaria,
            'moneda' => $datosConfig->moneda,
            'fecha_renovacion' => $fecha,
            'fecha_vencimiento' => $nuevafecha,
            'porcentaje_imp' => '0',
            'max_descuento' => '0',
            'comision' => '0',
            'menu_almacen' => 'NO',
            'menu_compras' => 'NO',
            'menu_ventas' => 'NO',
            'menu_acceso' => 'NO',
            'menu_consultas' => 'NO',
            'menu_configuracion' => 'NO',
            'envio' => '0',
            'descripcion_envio' => '',
            'idcliente' => $idcliente->idpersona,
            'principal' => 'NO',

        ]);
    }
}
