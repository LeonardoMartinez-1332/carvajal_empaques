<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camion extends Model
{
    use HasFactory;

    protected $table = 'camiones';

    protected $fillable = [
        'nom_linea',
        'id_turno',
        'id_usuario' // Asegurando que se pueda asignar un usuario al camión
    ];

    /**
     * Relación con Turno (Cada camión pertenece a un Turno)
     */
    public function turno()
    {
        return $this->belongsTo(Turno::class, 'id_turno');
    }

    /**
     * Relación con Usuario (Cada camión tiene asignado un Usuario)
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    /**
     * Scope para obtener camiones de un turno específico
     */
    public function scopePorTurno($query, $turnoId)
    {
        return $query->where('id_turno', $turnoId);
    }
}
