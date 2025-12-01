<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\TiDirecta;
use App\Models\TiDetalle;
use App\Models\Producto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class JobsController extends Controller
{
    /**
     * Lista productos con stock para Jobs, filtrando por almacén origen.
     *
     *  - GET /api/jobs/productos
     *  - GET /api/jobs/productos/2
     *  - GET /api/jobs/productos?almacen_id=1
     */
    public function productosConStock($almacenId = null)
    {
        Log::alert("🟢 Entra a productosConStock. Param almacenId = " . var_export($almacenId, true));

        $mapAlmacenes = [
            1 => 'EMMX019',
            2 => 'EMMXEX023',
        ];

        $almacenId = $almacenId ?? request('almacen_id') ?? 1;

        if (!isset($mapAlmacenes[$almacenId])) {
            return response()->json([
                "error"   => "Almacén no válido",
                "message" => "No existe mapeo para almacenId={$almacenId}"
            ], 400);
        }

        $codigoAlmacen = $mapAlmacenes[$almacenId];

        try {
            $inventarios = Inventario::with('producto')
                ->where('almacen', $codigoAlmacen)
                ->where(function ($q) {
                    $q->where('tarimas', '>', 0)
                    ->orWhere('cajas', '>', 0)
                    ->orWhere('piezas', '>', 0);
                })
                ->orderBy('producto_id', 'asc')
                ->get();

            $productos = $inventarios->map(function ($inv) {
                $prod = $inv->producto;

                return [
                    'id'               => $prod->id ?? $inv->producto_id,
                    'codigo'           => $prod->codigo ?? '',
                    'descripcion'      => $prod->descripcion ?? '',
                    'udm'              => $prod->udm ?? null,
                    'cajas_por_tarima' => $prod->cajas_por_tarima ?? null,
                    'pz_x_pt'          => $prod->pz_x_pt ?? null,
                    'stock_tarimas'    => (int) ($inv->tarimas ?? 0),
                    'stock_cajas'      => (int) ($inv->cajas ?? 0),
                ];
            });

            return response()->json([
                'data' => $productos,
            ], 200);

        } catch (\Throwable $e) {
            Log::error("❌ Error en productosConStock: {$e->getMessage()}");

            return response()->json([
                "error"   => "Error en productosConStock",
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear TI directa (Jobs) con varias líneas desde Flutter.
     *
     * Endpoint: POST /api/jobs/ti
     */
    public function crearTi(Request $request)
    {
        $user = $request->user(); // usuario autenticado por Sanctum

        try {
            // 1) Validar estructura básica del request
            $validated = $request->validate([
                'almacen_origen_id'    => ['required', 'integer'],
                'almacen_destino_id'   => ['required', 'integer', 'different:almacen_origen_id'],
                'comentario'           => ['nullable', 'string', 'max:500'],
                'lineas'               => ['required', 'array', 'min:1'],
                'lineas.*.producto_id' => ['nullable', 'integer', 'exists:productos,id'],
                'lineas.*.productoId'  => ['nullable', 'integer'], // camelCase por si acaso
                'lineas.*.tarimas'     => ['nullable', 'integer', 'min:0'],
                'lineas.*.cajas'       => ['nullable', 'integer', 'min:0'],
            ]);

            // 2) Mapa de IDs → código de almacén
            $mapAlmacenes = [
                1 => 'EMMX019',
                2 => 'EMMXEX023',
            ];

            if (
                !isset($mapAlmacenes[$validated['almacen_origen_id']]) ||
                !isset($mapAlmacenes[$validated['almacen_destino_id']])
            ) {
                return response()->json([
                    'message' => 'Almacén origen/destino no válido.',
                ], 422);
            }

            $codigoOrigen  = $mapAlmacenes[$validated['almacen_origen_id']];
            $codigoDestino = $mapAlmacenes[$validated['almacen_destino_id']];

            // 3) Normalizar líneas (aceptar productoId o producto_id) y quitar líneas vacías
            $lineas = collect($request->input('lineas', []))
                ->map(function ($l) {
                    $productoId = $l['producto_id'] ?? $l['productoId'] ?? null;
                    $tarimas    = (int) ($l['tarimas'] ?? 0);
                    $cajas      = (int) ($l['cajas'] ?? 0);

                    return [
                        'producto_id' => $productoId,
                        'tarimas'     => $tarimas,
                        'cajas'       => $cajas,
                    ];
                })
                ->filter(function ($l) {
                    return $l['producto_id'] && ($l['tarimas'] > 0 || $l['cajas'] > 0);
                })
                ->values();

            if ($lineas->isEmpty()) {
                return response()->json([
                    'message' => 'Debes enviar al menos una línea con tarimas o cajas.',
                ], 422);
            }

            // 4) Transacción para crear TI + descontar/sumar inventario
            $ti = DB::transaction(function () use ($validated, $lineas, $user, $codigoOrigen, $codigoDestino) {

                // Folios tipo viejo
                $nextId = (TiDirecta::max('id') ?? 0) + 1;
                $numTi  = 'TI-'  . str_pad($nextId, 6, '0', STR_PAD_LEFT);
                $npi    = 'NPI-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

                // Cabecera basada en la primera línea
                $lineaCab      = $lineas->first();
                $productoCabId = $lineaCab['producto_id'];
                $productoCab   = Producto::find($productoCabId);

                /** @var \App\Models\TiDirecta $ti */
                $ti = TiDirecta::create([
                    'npi'                  => $npi,
                    'num_ti'               => $numTi,
                    'producto_id'          => $productoCabId,
                    'codigo_producto'      => $productoCab->codigo ?? '',
                    'descripcion_producto' => $productoCab->descripcion ?? '',
                    'cantidad'             => $lineaCab['cajas'] ?? 0,
                    'unidad'               => 'CJ',
                    'almacen_origen'       => $codigoOrigen,
                    'almacen_destino'      => $codigoDestino,
                    'creado_por'           => $user->id,
                    'estatus'              => 'emitida',
                ]);

                // Procesar cada línea
                foreach ($lineas as $l) {
                    $productoId = $l['producto_id'];
                    $tarimas    = $l['tarimas'];
                    $cajas      = $l['cajas'];

                    // 4.1 Descontar inventario en almacén origen
                    $invOrigen = Inventario::where('almacen', $codigoOrigen)
                        ->where('producto_id', $productoId)
                        ->lockForUpdate()
                        ->first();

                    if (
                        !$invOrigen ||
                        $invOrigen->tarimas < $tarimas ||
                        $invOrigen->cajas   < $cajas
                    ) {
                        // 👇 Esta excepción es la que vamos a atrapar afuera como 422
                        throw new \RuntimeException(
                            "Inventario insuficiente para producto {$productoId} en almacén {$codigoOrigen}."
                        );
                    }

                    $invOrigen->tarimas -= $tarimas;
                    $invOrigen->cajas   -= $cajas;
                    if ($invOrigen->tarimas < 0) $invOrigen->tarimas = 0;
                    if ($invOrigen->cajas   < 0) $invOrigen->cajas   = 0;
                    $invOrigen->save();

                    // 4.2 Sumar inventario en almacén destino
                    $invDestino = Inventario::where('almacen', $codigoDestino)
                        ->where('producto_id', $productoId)
                        ->lockForUpdate()
                        ->first();

                    if (!$invDestino) {
                        $invDestino = new Inventario();
                        $invDestino->producto_id = $productoId;
                        $invDestino->almacen     = $codigoDestino;
                        $invDestino->tarimas     = 0;
                        $invDestino->cajas       = 0;
                        $invDestino->piezas      = 0;
                    }

                    $invDestino->tarimas += $tarimas;
                    $invDestino->cajas   += $cajas;
                    $invDestino->save();

                    // 4.3 Crear detalle
                    $ti->lineas()->create([
                        'producto_id' => $productoId,
                        'tarimas'     => $tarimas,
                        'cajas'       => $cajas,
                        'piezas'      => 0,
                    ]);
                }

                return $ti;
            });

            // 5) Cargar relaciones para regresarle todo a Flutter
            // Después de crear la TI
            $ti->load(['producto', 'lineas.producto', 'creador']);


            return response()->json([
                'message' => 'TI creada correctamente.',
                'data'    => $ti,
            ], 201);

        } catch (\RuntimeException $e) {
            // ❗ Errores de negocio (ej. inventario insuficiente) → 422 bonito
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);

        } catch (\Throwable $e) {
            // 🔥 Cualquier otra cosa rara → 500 y log
            Log::error("❌ Error al crear TI: {$e->getMessage()}");

            return response()->json([
                'message' => 'Error interno al crear TI.',
            ], 500);
        }
    }

    /**
     * Historial TI Jobs
     */
    public function listarTi(Request $request)
    {
        $user = $request->user();

        $query = TiDirecta::with(['lineas.producto', 'creador'])
            ->where('creado_por', $user->id)
            ->orderByDesc('id');

        if ($folio = $request->query('folio')) {
            $query->where('num_ti', 'like', "%{$folio}%");
        }

        if ($codigo = $request->query('codigo')) {
            $query->whereHas('lineas.producto', function ($q) use ($codigo) {
                $q->where('codigo', 'like', "%{$codigo}%");
            });
        }

        $tis = $query->paginate(20);

        return response()->json($tis);
    }

    public function verTi($id)
    {
        $ti = TiDirecta::with([
            'producto',         // encabezado
            'lineas.producto',  // líneas
            'creador',
        ])->findOrFail($id);

        return response()->json($ti);
    }

    public function pdfTi($id)
    {
        $ti = TiDirecta::with([
            'producto',
            'lineas.producto',
            'creador',
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.ti', [
            'ti' => $ti,
        ])->setPaper('A4', 'portrait');

        return $pdf->download("TI-{$ti->num_ti}.pdf");
    }
}