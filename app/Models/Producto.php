<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'codigo', 'descripcion',
        'pz_x_pt', 'vol', 'w', 'tipo',
        'udm', 'cajas_por_tarima',
        'volumen_unitario', 'costo_pac_unitario',
        'num_caja', 'camas', 'cajas_por_cama', 'total_cajas',
    ];

    // Para que Eloquent te devuelva tipos correctos
    protected $casts = [
        'num_caja'          => 'integer',
        'camas'             => 'integer',
        'cajas_por_cama'    => 'integer',
        'total_cajas'       => 'integer',
        'pz_x_pt'           => 'integer',
        'cajas_por_tarima'  => 'integer',
        'vol'               => 'float',
        'w'                 => 'float',
        'volumen_unitario'  => 'float',
        'costo_pac_unitario'=> 'float',
    ];
}
