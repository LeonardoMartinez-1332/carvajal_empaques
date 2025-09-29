<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'correo',
        'password',
        'rol',
        'activo'
    ];

    public function camiones()
    {
        return $this->hasMany(Camion::class, 'id_usuario');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'id_turno');
    }

}
