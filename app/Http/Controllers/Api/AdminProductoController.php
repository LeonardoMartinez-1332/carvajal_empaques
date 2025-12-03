<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class AdminProductoController extends Controller
{
    /**
     * GET /api/admin/productos
     * Lista + filtro
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Producto::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('codigo', 'like', "%{$search}%")
                ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        $productos = $query
            ->orderBy('codigo')
            ->get()
            ->map(function (Producto $p) {

                return [
                    'id'                 => $p->id,
                    'codigo'             => $p->codigo,
                    'descripcion'        => $p->descripcion,
                    'camas'              => $p->camas,
                    'cajas_por_cama'     => $p->cajas_por_cama,
                    'pz_x_pt'            => $p->pz_x_pt,
                    'cajas_por_tarima'   => $p->cajas_por_tarima,
                    'udm'                => $p->udm,
                    'tipo'               => $p->tipo,
                    'vol'                => $p->vol,
                    'w'                  => $p->w,
                    'volumen_unitario'   => $p->volumen_unitario,
                    'costo_pac_unitario' => $p->costo_pac_unitario,
                ];
            });

        return response()->json($productos);
    }

    /**
     * POST /api/admin/productos
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo'             => 'required|string|max:50|unique:productos,codigo',
            'descripcion'        => 'required|string|max:255',

            'camas'              => 'nullable|integer',
            'cajas_por_cama'     => 'nullable|integer',
            'pz_x_pt'            => 'nullable|integer',
            'cajas_por_tarima'   => 'nullable|integer',

            'udm'                => 'nullable|string|max:20',
            'tipo'               => 'nullable|string|max:50',

            'vol'                => 'nullable|numeric',
            'w'                  => 'nullable|numeric',
            'volumen_unitario'   => 'nullable|numeric',
            'costo_pac_unitario' => 'nullable|numeric',
        ]);

        $producto = Producto::create($data);

        return response()->json($producto, 201);
    }

    /**
     * PUT /api/admin/productos/{producto}
     */
    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'codigo'             => "required|string|max:50|unique:productos,codigo,{$producto->id}",
            'descripcion'        => 'required|string|max:255',

            'camas'              => 'nullable|integer',
            'cajas_por_cama'     => 'nullable|integer',
            'pz_x_pt'            => 'nullable|integer',
            'cajas_por_tarima'   => 'nullable|integer',

            'udm'                => 'nullable|string|max:20',
            'tipo'               => 'nullable|string|max:50',

            'vol'                => 'nullable|numeric',
            'w'                  => 'nullable|numeric',
            'volumen_unitario'   => 'nullable|numeric',
            'costo_pac_unitario' => 'nullable|numeric',
        ]);

        $producto->update($data);

        return response()->json($producto);
    }

    /**
     * DELETE /api/admin/productos/{producto}
     *
     * Aquí no existe "activo", así que eliminamos REAL:
     *    Opción 1: delete() si tu tabla no está ligada a procesos críticos
     *    Opción 2: bloquear la eliminación y solo permitir edición
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente'
        ], 200);
    }
}
