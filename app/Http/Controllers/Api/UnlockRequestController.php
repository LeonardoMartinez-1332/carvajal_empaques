<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserUnlockRequest;
use App\Models\Usuario; // tu modelo de usuarios

class UserUnlockRequestController extends Controller
{
    /**
     * GET /api/admin/unlock-requests?status=pendiente
     */
    public function index(Request $request)
    {
        $status = $request->query('status'); // ej. pendiente / aprobada / rechazada

        $query = UserUnlockRequest::with('usuario')
            ->orderByDesc('created_at');

        if ($status) {
            $query->where('status', $status);
        }

        // 🔹 Flutter espera un array plano de objetos JSON
        return response()->json($query->get(), 200);
    }

    /**
     * POST /api/admin/unlock-requests/{id}/approve
     */
    public function approve($id)
    {
        $req = UserUnlockRequest::with('usuario')->findOrFail($id);

        // Cambiamos estado de la solicitud
        $req->status = 'aprobada';
        // si tienes columna resolved_at, descomenta:
        // $req->resolved_at = now();
        $req->save();

        // Desbloquear usuario (ajusta el campo real)
        $usuario = $req->usuario;
        if ($usuario) {
            // ⚠️ AJUSTA ESTO AL CAMPO REAL DE TU TABLA `usuarios`
            // Ejemplos posibles:
            // $usuario->bloqueado = false;
            // $usuario->intentos_fallidos = 0;
            // $usuario->estado = 'activo';

            $usuario->bloqueado = false;  // 👈 cambia "bloqueado" por tu campo real
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

        $req->status = 'rechazada';
        // $req->resolved_at = now(); // opcional
        $req->save();

        return response()->json([
            'message' => 'Solicitud rechazada',
        ], 200);
    }
}
