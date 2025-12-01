<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiDirecta extends Model
{
    protected $table = 'ti_directas';

    protected $fillable = [
        'npi',
        'num_ti',
        'producto_id',
        'codigo_producto',
        'descripcion_producto',
        'cantidad',
        'unidad',
        'almacen_origen',
        'almacen_destino',
        'creado_por',
        'estatus',
    ];

    // 🔹 Detalles de la TI
    public function lineas()
    {
        return $this->hasMany(TiDetalle::class, 'ti_id', 'id');
    }

    // 🔹 Usuario que creó la TI
    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por', 'id');
    }

    // 🔹 Producto “de cabecera” (por compatibilidad)
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }
}
