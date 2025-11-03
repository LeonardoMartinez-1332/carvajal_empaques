<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'correo',
        'password',
        'rol',
        'activo',
    ];

    /**
     * Ocultar campos sensibles en respuestas JSON.
     */
    protected $hidden = [
        'password',
        'remember_token', // si no existe en tu tabla no pasa nada
    ];

    /**
     * Casts útiles. Si tienes Laravel 10+, puedes usar 'hashed'.
     */
    protected $casts = [
        // 'password' => 'hashed', // <- si usas Laravel 10+, descomenta esta línea y elimina el mutator de abajo.
    ];

    /**
     * Mutator para asegurar que la contraseña quede cifrada
     * (déjalo si NO estás usando el cast 'hashed' de Laravel 10+).
     */
    public function setPasswordAttribute($value)
    {
        // Evita re-hashear si ya viene hasheado
        if ($value && !password_get_info($value)['algo']) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    /**
     * Accesor opcional: algunas librerías/plantillas esperan 'email'.
     * Así exponemos 'correo' como 'email' sin cambiar tu DB.
     */
    public function getEmailAttribute()
    {
        return $this->attributes['correo'] ?? null;
    }

    /* =====================
    Relaciones que ya tenías
       ===================== */

    public function camiones()
    {
        return $this->hasMany(Camion::class, 'id_usuario');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'id_turno');
    }
}
