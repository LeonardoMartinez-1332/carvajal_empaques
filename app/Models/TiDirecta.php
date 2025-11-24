<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiDirecta extends Model
{
    use HasFactory;

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
        'pdf_path',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }
}
