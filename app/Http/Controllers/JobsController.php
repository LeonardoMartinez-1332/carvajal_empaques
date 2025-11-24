<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\TiDirecta;
use Barryvdh\DomPDF\Facade\Pdf;

class JobsController extends Controller
{
    /**
     * Lista productos con stock para Jobs, filtrando por almacén origen.
     * GET /api/jobs/productos?almacen_id=1
     */
    public function productosConStock($almacenId)
    {
        try {
            $productos = Inventario::with('producto')
                ->where('almacen_id', $almacenId)
                ->where('cajas', '>', 0)
                ->orderBy('almacen_id', 'asc')
                ->orderBy('producto_id', 'asc')
                ->get();

            return response()->json($productos, 200);

        } catch (\Throwable $e) {
            return response()->json([
                "error" => "Error en productosConStock",
                "message" => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Historial TI Jobs
     */
    public function listarTi(Request $request)
    {
        $user = $request->user();

        $query = TiDirecta::with(['lineas.producto'])
            ->where('creado_por', $user->id)
            ->orderByDesc('id');

        if ($folio = $request->query('folio')) {
            $query->where('folio', 'like', "%{$folio}%");
        }

        if ($codigo = $request->query('codigo')) {
            $query->whereHas('lineas.producto', function ($q) use ($codigo) {
                $q->where('codigo', 'like', "%{$codigo}%");
            });
        }

        $tis = $query->paginate(20);

        return response()->json($tis);
    }

    /**
     * Detalle TI
     */
    public function verTi($id)
    {
        $ti = TiDirecta::with([
            'lineas.producto',
            'almacenOrigen',
            'almacenDestino',
            'creadoPor',
        ])->findOrFail($id);

        return response()->json($ti);
    }

    /**
     * PDF TI (lo usa el endpoint /jobs/ti/{id}/pdf)
     */
    public function pdfTi($id)
    {
        // Reutiliza la consulta de detalle
        $ti = TiDirecta::with([
            'lineas.producto',
            'almacenOrigen',
            'almacenDestino',
            'creadoPor',
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.ti', [
            'ti' => $ti,
        ])->setPaper('A4', 'portrait');

        return $pdf->download("TI-{$ti->folio}.pdf");
    }

}
