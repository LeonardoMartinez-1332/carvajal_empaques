<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiDetalle extends Model
{
    protected $table = 'ti_detalles';

    protected $fillable = [
        'ti_id',
        'producto_id',
        'tarimas',
        'cajas',
        'piezas',
    ];

    public function ti()
    {
        return $this->belongsTo(Ti::class, 'ti_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
