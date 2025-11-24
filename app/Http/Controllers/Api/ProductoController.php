<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function index()
    {
        // devuelve SOLO lo que Flutter espera
        return Producto::select(
            'id',
            'codigo',
            'descripcion',
            'camas',
            'cajas_por_cama',
            'cajas_por_tarima',
            'pz_x_pt',
            'vol',
            'w',
            'tipo',
            'udm',
            'volumen_unitario',
            'costo_pac_unitario'
        )->get();
    }
}
