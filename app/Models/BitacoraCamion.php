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
        'origen',
        'destino',
        'num_fi_ti',
        'id_supervisor',
        'cantidad_tarimas',
        'rampa',
        'estado',
        'hora_salida',

        // nuevos campos
        'npi',
        'estatus_aprobacion',
        'aprobado_por',
        'aprobado_at',
    ];


    public function turno()
    {
        return $this->belongsTo(Turno::class, 'id_turno');
    }

    public function camion()
    {
        return $this->belongsTo(Camion::class, 'id_transp');
    }

    public function supervisor()
    {
        return $this->belongsTo(Usuario::class, 'id_supervisor');
    }
}
