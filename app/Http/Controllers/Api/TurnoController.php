<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Turno;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    public function index(Request $request)
    {
        $q        = $request->query('q');
        $activos  = $request->boolean('activos');
        $perPage  = (int) $request->integer('per_page', 0);

        $query = Turno::query()->orderBy('id');

        // scopes si los tienes en el modelo
        if (method_exists(Turno::class, 'scopeBuscar') && $q) {
            $query->buscar($q);
        }
        if (method_exists(Turno::class, 'scopeActivo') && $activos) {
            $query->activo();
        }

        // Campos que quieras devolver
        $columns = ['id','nombre_turno','activo'];

        if ($perPage > 0) {
            $p = $query->paginate($perPage, $columns);

            return response()->json([
                'ok'   => true,
                'data' => $p->items(),
                'meta' => [
                    'current_page' => $p->currentPage(),
                    'last_page'    => $p->lastPage(),
                    'per_page'     => $p->perPage(),
                    'total'        => $p->total(),
                ],
            ]);
        }

        // sin paginación
        $items = $query->get($columns);

        return response()->json([
            'ok'   => true,
            'data' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_turno' => ['required','string','max:255'],
            'activo'       => ['nullable','boolean'],
        ]);

        $turno = Turno::create([
            'nombre_turno' => $data['nombre_turno'],
            'activo'       => $data['activo'] ?? true,
        ]);

        return response()->json(['ok' => true, 'data' => $turno], 201);
    }

    public function show($id)
    {
        $turno = Turno::find($id, ['id','nombre_turno','activo']);

        if (!$turno) {
            return response()->json(['ok' => false, 'message' => 'No encontrado'], 404);
        }

        return response()->json(['ok' => true, 'data' => $turno]);
    }

    public function update(Request $request, $id)
    {
        $turno = Turno::find($id);
        if (!$turno) {
            return response()->json(['ok' => false, 'message' => 'No encontrado'], 404);
        }

        $data = $request->validate([
            'nombre_turno' => ['sometimes','required','string','max:255'],
            'activo'       => ['sometimes','boolean'],
        ]);

        $turno->update($data);

        return response()->json(['ok' => true, 'data' => $turno]);
    }

    public function destroy($id)
    {
        $turno = Turno::find($id);
        if (!$turno) {
            return response()->json(['ok' => false, 'message' => 'No encontrado'], 404);
        }

        $turno->delete();

        return response()->json(['ok' => true, 'message' => 'Eliminado']);
    }
}
