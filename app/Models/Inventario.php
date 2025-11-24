<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventarios';

    protected $fillable = [
        'producto_id',
        'almacen',
        'tarimas',
        'cajas',
        'piezas',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
