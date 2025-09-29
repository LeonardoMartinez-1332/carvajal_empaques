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
        'activo' // Si se usa en la BD, permitimos modificarlo
    ];

    /**
     * Relación de Turno con Camion (1 Turno puede tener muchos Camiones)
     */
    public function camiones()
    {
        return $this->hasMany(Camion::class, 'id_turno');
    }

    /**
     * Relación de Turno con Usuario (Si los usuarios tienen asignado un turno)
     */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_turno');
    }

    /**
     * Scope para obtener solo turnos activos
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
}

