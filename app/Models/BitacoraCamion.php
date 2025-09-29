<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraCamion extends Model
{
    use HasFactory;

    protected $table = 'bitacora_camiones';

    protected $fillable = [
        'fecha',
        'hora_llegada',
        'id_turno',
        'id_transp',
        'logistica',
        'num_asn',
        'id_supervisor',
        'cantidad_tarimas',
        'hora_salida',
    ];

    /**
     * Relación con Turno (Uno a Muchos - Un turno tiene muchas bitácoras)
     */
    public function turno()
    {
        return $this->belongsTo(Turno::class, 'id_turno');
    }

    /**
     * Relación con Camion (Uno a Muchos - Un camión puede estar en varias bitácoras)
     */
    public function camion()
    {
        return $this->belongsTo(Camion::class, 'id_transp');
    }

    /**
     * Relación con Usuario (Uno a Muchos - Un usuario/supervisor puede tener muchas bitácoras)
     */
    public function supervisor()
    {
        return $this->belongsTo(Usuario::class, 'id_supervisor');
    }
}
