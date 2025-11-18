<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';

    // Si tu PK no es "id", define esto:
    // protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nombre',
        'correo',
        'password',
        'rol',
        'activo',
        // 'id_turno', // si lo manejas en la tabla, puedes agregarlo aquí
    ];

    /**
     * Ocultar campos sensibles en respuestas JSON.
     */
    protected $hidden = [
        'password',
        'remember_token', // si no existe en tu tabla no pasa nada
    ];

    /**
     * Casts útiles.
     */
    protected $casts = [
        'activo' => 'boolean',
        // Si usas Laravel 10+ puedes descomentar esta línea
        // y quitar el mutator de password:
        // 'password' => 'hashed',
    ];

    /**
     * Mutator para asegurar que la contraseña quede cifrada
     * (déjalo si NO estás usando el cast 'hashed').
     */
    public function setPasswordAttribute($value)
    {
        if ($value && ! password_get_info($value)['algo']) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    /**
     * Accesor: muchas libs esperan 'email'.
     * Mapeamos 'correo' -> 'email' sin cambiar la DB.
     */
    public function getEmailAttribute()
    {
        return $this->attributes['correo'] ?? null;
    }

    /* =====================
    Relaciones
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
