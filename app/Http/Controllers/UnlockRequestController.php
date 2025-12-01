<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserUnlockRequest;
use Illuminate\Http\Request;

class UserUnlockRequestController extends Controller
{
    /**
     * Lista solicitudes (por defecto solo pendientes).
     * GET /api/admin/unlock-requests?status=pendiente|aprobado|rechazado|todas
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Solo superusuario / admin
        if (! in_array($user->role, ['superusuario', 'admin'])) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $status = $request->query('status', 'pendiente');

        $query = UserUnlockRequest::with('usuario')
            ->orderBy('created_at', 'desc');

        if ($status !== 'todas') {
            $query->where('status', $status);
        }

        $items = $query->get();

        return response()->json($items);
    }

    /**
     * Aprueba y desbloquea al usuario.
     * POST /api/admin/unlock-requests/{id}/approve
     */
    public function approve(Request $request, int $id)
    {
        $admin = $request->user();
        if (! in_array($admin->role, ['superusuario', 'admin'])) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $req = UserUnlockRequest::with('usuario')->findOrFail($id);

        if ($req->status !== 'pendiente') {
            return response()->json([
                'message' => 'La solicitud ya fue procesada.',
            ], 422);
        }

        $user = $req->usuario;
        if ($user) {
            $user->intentos_fallidos = 0;
            $user->bloqueado = false;
            $user->save();
        }

        $req->status = 'aprobado';
        $req->save();

        return response()->json([
            'message' => 'Usuario desbloqueado correctamente.',
        ]);
    }

    /**
     * Rechaza la solicitud.
     * POST /api/admin/unlock-requests/{id}/reject
     */
    public function reject(Request $request, int $id)
    {
        $admin = $request->user();
        if (! in_array($admin->role, ['superusuario', 'admin'])) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $req = UserUnlockRequest::findOrFail($id);

        if ($req->status !== 'pendiente') {
            return response()->json([
                'message' => 'La solicitud ya fue procesada.',
            ], 422);
        }

        $req->status = 'rechazado';
        $req->save();

        return response()->json([
            'message' => 'Solicitud rechazada.',
        ]);
    }
}
