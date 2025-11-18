<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'codigo',
        'descripcion',
        'camas',
        'cajas_por_cama',
        'pz_x_pt',
        'cajas_por_tarima',
        'udm',
        'tipo',
        'vol',
        'w',
        'volumen_unitario',
        'costo_pac_unitario',
    ];

    protected $casts = [
        'camas'             => 'integer',
        'cajas_por_cama'    => 'integer',
        'pz_x_pt'           => 'integer',
        'cajas_por_tarima'  => 'integer',
        'vol'               => 'float',
        'w'                 => 'float',
        'volumen_unitario'  => 'float',
        'costo_pac_unitario'=> 'float',
    ];
}
