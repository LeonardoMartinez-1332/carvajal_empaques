<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $table = 'turnos';

    protected $fillable = [
        'nombre_turno',
        'activo',
    ];

    /**
     * Casts para que el JSON salga bonito (true/false real).
     */
    protected $casts = [
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* =====================
    Relaciones
       ===================== */

    // 1 Turno -> N Camiones
    public function camiones()
    {
        return $this->hasMany(Camion::class, 'id_turno');
    }

    // 1 Turno -> N Usuarios
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_turno');
    }

    /* =====================
    Scopes útiles en API
       ===================== */

    /**
     * Solo turnos activos.
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Búsqueda rápida por nombre_turno.
     */
    public function scopeBuscar($query, ?string $texto)
    {
        if (!empty($texto)) {
            $query->where('nombre_turno', 'like', "%{$texto}%");
        }
        return $query;
    }

    /* =====================
    Orden por defecto
       ===================== */

    protected static function booted()
    {
        static::addGlobalScope('orden_nombre', function ($query) {
            $query->orderBy('nombre_turno');
        });
    }
}
