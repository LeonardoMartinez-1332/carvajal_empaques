<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function index()
    {
        // Devuelve SOLO lo que Flutter necesita
        return Producto::select(
            'id',
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
            'costo_pac_unitario'
        )
        ->orderBy('codigo')
        ->get();
    }
}
