<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TiDirecta;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TiDirectaController extends Controller
{
    /**
     * Listar TI directas (historial Jobs).
     */
    public function index(Request $request)
    {
        $query = TiDirecta::with(['producto', 'creador'])
            ->orderByDesc('created_at');

        if ($request->filled('search')) {
            $s = $request->get('search');
            $query->where(function ($q) use ($s) {
                $q->where('npi', 'like', "%{$s}%")
                ->orWhere('num_ti', 'like', "%{$s}%")
                ->orWhere('codigo_producto', 'like', "%{$s}%");
            });
        }

        return response()->json($query->paginate(20));
    }

    /**
     * Ver detalle de una TI directa.
     */
    public function show($id)
    {
        $ti = TiDirecta::with(['producto', 'creador'])->findOrFail($id);
        return response()->json($ti);
    }

    /**
     * Crear una nueva TI directa.
     *
     * Aquí es donde Jobs genera la TI usando inventario real.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'producto_id'     => ['required', 'integer', 'exists:productos,id'],
            'cantidad'        => ['required', 'integer', 'min:1'],
            'almacen_origen'  => ['required', 'string', 'max:50'],
            'almacen_destino' => ['nullable', 'string', 'max:50'],
            'unidad'          => ['nullable', 'string', 'max:20'],
        ]);

        // Por si no mandan unidad desde el front
        $unidad = $data['unidad'] ?? 'CJ';

        // Todo el movimiento en transacción
        return DB::transaction(function () use ($data, $unidad, $user) {

            /** @var \App\Models\Producto $producto */
            $producto = Producto::findOrFail($data['producto_id']);

            // 🔎 Aquí asumimos que el stock está en un campo "existencia".
            // Si tu columna tiene otro nombre o manejas stock por almacén,
            // ajustamos esta parte.
            if ($producto->existencia < $data['cantidad']) {
                return response()->json([
                    'message' => 'No hay inventario suficiente para este producto.',
                ], 422);
            }

            // Descontar stock (versión simple)
            $producto->existencia -= $data['cantidad'];
            $producto->save();

            // Generar folios
            $nextId = (TiDirecta::max('id') ?? 0) + 1;
            $numTi  = 'TI-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            $npi    = 'NPI-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

            $ti = TiDirecta::create([
                'npi'                   => $npi,
                'num_ti'                => $numTi,
                'producto_id'           => $producto->id,
                'codigo_producto'       => $producto->codigo ?? '',
                'descripcion_producto'  => $producto->descripcion ?? '',
                'cantidad'              => $data['cantidad'],
                'unidad'                => $unidad,
                'almacen_origen'        => $data['almacen_origen'],
                'almacen_destino'       => $data['almacen_destino'] ?? null,
                'creado_por'            => $user->id,
                'estatus'               => 'emitida',
            ]);

            // Aquí más adelante metemos la generación de PDF
            // y guardamos el path en $ti->pdf_path.

            return response()->json([
                'message' => 'TI directa creada correctamente.',
                'ti'      => $ti,
            ], 201);
        });
    }

    /**
     * (Opcional) Cancelar una TI directa.
     * Podríamos regresar inventario, etc.
     */
    public function cancelar($id)
    {
        $ti = TiDirecta::findOrFail($id);

        if ($ti->estatus === 'cancelada') {
            return response()->json([
                'message' => 'La TI ya está cancelada.',
            ], 422);
        }

        // TODO: si quieres, aquí podríamos regresar inventario al producto.

        $ti->estatus = 'cancelada';
        $ti->save();

        return response()->json([
            'message' => 'TI cancelada correctamente.',
            'ti'      => $ti,
        ]);
    }
}
