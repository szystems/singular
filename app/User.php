<?php

namespace sisVentasWeb;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;

class User extends Authenticatable
{
    use Notifiable;

    protected $table='users';

    protected $primaryKey='id';


    protected $fillable = [
        'name',
        'foto',
        'email', 
        'password', 
        'empresa',
        'idempresa', 
        'telefono',
        'direccion',
        'fecha_nacimiento',
        'contacto_emergencia',
        'telefono_emergencia',
        'tipo_usuario',
        'especialidad',
        'zona_horaria', 
        'moneda',
        'principal',
        
        
    ];

    
    protected $hidden = [
        'password', 'remember_token',
    ];
}
