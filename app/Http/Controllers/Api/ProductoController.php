<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // GET /api/productos?codigo=XYZ (opcional)
    public function index(Request $request)
    {
        $q = Producto::query()->orderBy('id','desc');

        if ($request->filled('codigo')) {
            $q->where('codigo', $request->get('codigo'));
        }

        $items = $q->get(['id','num_caja','codigo','camas','cajas_por_cama','total_cajas']);

        return response()->json([
            'ok' => true,
            'data' => $items,
        ]);
    }

    public function show($id)
    {
        $p = Producto::find($id, ['id','num_caja','codigo','camas','cajas_por_cama','total_cajas']);

        if (!$p) {
            return response()->json(['ok' => false, 'message' => 'No encontrado'], 404);
        }

        return response()->json(['ok' => true, 'data' => $p]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'num_caja'       => ['required','string','max:50'],
            'codigo'         => ['required','string','max:50'],
            'camas'          => ['required','integer','min:0'],
            'cajas_por_cama' => ['required','integer','min:0'],
            'total_cajas'    => ['required','integer','min:0'],
        ]);

        $p = Producto::create($data);

        return response()->json(['ok' => true, 'data' => $p], 201);
    }

    public function update(Request $request, $id)
    {
        $p = Producto::find($id);
        if (!$p) return response()->json(['ok' => false, 'message' => 'No encontrado'], 404);

        $data = $request->validate([
            'num_caja'       => ['sometimes','string','max:50'],
            'codigo'         => ['sometimes','string','max:50'],
            'camas'          => ['sometimes','integer','min:0'],
            'cajas_por_cama' => ['sometimes','integer','min:0'],
            'total_cajas'    => ['sometimes','integer','min:0'],
        ]);

        $p->update($data);

        return response()->json(['ok' => true, 'data' => $p]);
    }

    public function destroy($id)
    {
        $p = Producto::find($id);
        if (!$p) return response()->json(['ok' => false, 'message' => 'No encontrado'], 404);

        $p->delete();
        return response()->json(['ok' => true]);
    }
}
