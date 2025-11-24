<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ti extends Model
{
    protected $table = 'tis';

    protected $fillable = [
        'numero',
        'almacen_origen',
        'almacen_destino',
        'usuario_id',
        'fecha',
        'total_tarimas',
        'total_cajas',
        'total_piezas',
    ];

    public function detalles()
    {
        return $this->hasMany(TiDetalle::class, 'ti_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
