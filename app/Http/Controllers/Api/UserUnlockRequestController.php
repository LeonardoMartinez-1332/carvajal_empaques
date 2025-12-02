<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserUnlockRequest;
use App\Models\Usuario;

class UserUnlockRequestController extends Controller
{
    /**
     * GET /api/admin/unlock-requests?status=pendiente
     */
    public function index(Request $request)
    {
        $status = $request->query('status'); // pendiente / aprobada / rechazada

        $query = UserUnlockRequest::with('usuario')
            ->orderByDesc('created_at');

        if ($status) {
            $query->where('status', $status);
        }

        return response()->json($query->get(), 200);
    }

    /**
     * POST /api/admin/unlock-requests/{id}/approve
     */
    public function approve($id)
    {
        $req = UserUnlockRequest::with('usuario')->findOrFail($id);

        $req->status = 'aprobado';
        $req->save();

        // 🔓 Desbloquear usuario (ajusta al campo real de tu tabla `usuarios`)
        $usuario = $req->usuario;
        if ($usuario) {
            // ejemplo: si tienes campo `bloqueado` tipo bool/int
            $usuario->bloqueado = false;
            $usuario->save();
        }

        return response()->json([
            'message' => 'Solicitud aprobada y usuario desbloqueado',
        ], 200);
    }

    /**
     * POST /api/admin/unlock-requests/{id}/reject
     */
    public function reject($id)
    {
        $req = UserUnlockRequest::findOrFail($id);

        $req->status = 'rechazado';
        $req->save();

        return response()->json([
            'message' => 'Solicitud rechazada',
        ], 200);
    }
}
