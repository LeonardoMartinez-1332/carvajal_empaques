<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;

class ProductoController extends Controller
{
    /**
     * GET /api/productos
     */
    public function index(): JsonResponse
    {
        // ✅ Ya SIN num_caja NI total_cajas
        $productos = Producto::query()
            ->select([
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
                'costo_pac_unitario',
            ])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($productos);
    }
}
