<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\UserUnlockRequest;

class AuthController extends Controller
{
    /**
     * Login API (para Flutter / clientes externos)
     */
    public function login(Request $request)
    {
        // 1) Validamos entrada: aceptamos email o correo (uno de los dos es obligatorio)
        $data = $request->validate([
            'email'    => ['nullable', 'email', 'required_without:correo'],
            'correo'   => ['nullable', 'email', 'required_without:email'],
            'password' => ['required', 'string'],
        ]);

        // Normalizamos: el "email" real que usaremos es el de la columna "correo"
        $email    = $data['email']  ?? $data['correo'] ?? null;
        $password = $data['password'];

        // 2) Buscar usuario por la columna real "correo"
        /** @var \App\Models\Usuario|null $user */
        $user = Usuario::where('correo', $email)->first();

        // Si no existe, regresamos credenciales inválidas sin más detalle
        if (! $user) {
            return response()->json([
                'message' => 'Las credenciales proporcionadas no son válidas.',
            ], 401);
        }

        // 3) Si ya está bloqueado, no dejamos ni intentar
        if ($user->bloqueado) {
            return response()->json([
                'message'   => 'Este usuario está bloqueado. Contacta al administrador.',
                'bloqueado' => true,
            ], 403); // o 423 si quieres ser más estricto
        }

        // 4) Bloquear usuarios inactivos (regla de negocio que ya tenías)
        if (! $user->activo) {
            return response()->json([
                'message'  => 'Este usuario se encuentra inactivo.',
                'inactivo' => true,
            ], 403);
        }

        // 5) Validar contraseña
        if (! Hash::check($password, $user->password)) {
            // sumamos intento fallido
            $user->intentos_fallidos = ($user->intentos_fallidos ?? 0) + 1;

            // si se pasó del umbral, bloqueamos
            if ($user->intentos_fallidos >= 5) {
                $user->bloqueado = true;
            }

            $user->save();

            return response()->json([
                'message'           => 'Credenciales incorrectas.',
                'bloqueado'         => (bool) $user->bloqueado,
                'intentos_fallidos' => (int) $user->intentos_fallidos,
            ], 401);
        }

        // 6) Si la contraseña es correcta, reseteamos los intentos
        if ($user->intentos_fallidos > 0 || $user->bloqueado) {
            $user->intentos_fallidos = 0;
            // por si en un futuro lo desbloqueas manualmente y quieres asegurarte
            // de que no quede bloqueado después de login correcto
            // $user->bloqueado = false;
            $user->save();
        }

        // 7) (Opcional pero pro) Dejar solo una sesión activa por usuario
        $user->tokens()->delete();

        // 8) Crear token Sanctum para el ERP / Flutter
        $token = $user->createToken('erp-carvajal')->plainTextToken;

        // 9) Respuesta estándar para el front (Flutter)
        return response()->json([
            'message' => 'Login correcto',
            'token'   => $token,
            'user'    => [
                'id'                => $user->id,
                'name'              => $user->nombre,
                'email'             => $user->correo,          // el correo real de la BD
                'role'              => $user->role ?? 'usuario',
                'activo'            => (bool) $user->activo,
                'bloqueado'         => (bool) $user->bloqueado,
                'intentos_fallidos' => (int) $user->intentos_fallidos,
            ],
        ]);
    }

    /**
     * Devuelve el usuario autenticado (útil para "perfil" o validar sesión)
     */
    public function me(Request $request)
    {
        /** @var \App\Models\Usuario $u */
        $u = $request->user();

        return response()->json([
            'id'                => $u->id,
            'name'              => $u->nombre,
            'email'             => $u->correo,
            'role'              => $u->role ?? 'usuario',
            'activo'            => (bool) $u->activo,
            'bloqueado'         => (bool) $u->bloqueado,
            'intentos_fallidos' => (int) $u->intentos_fallidos,
        ]);
    }

    /**
     * Logout API: revoca SOLO el token actual
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'ok'      => true,
            'message' => 'Sesión cerrada correctamente',
        ]);
    }

    public function unlockRequest(Request $request)
    {
        $data = $request->validate([
            'email'  => ['required', 'email'],
            'motivo' => ['nullable', 'string', 'max:255'],
        ]);

        $email = $data['email'];

        /** @var \App\Models\Usuario|null $user */
        $user = Usuario::where('correo', $email)->first();

        if (! $user) {
            return response()->json([
                'message' => 'No existe un usuario con ese correo.',
            ], 404);
        }

        if (! $user->bloqueado) {
            return response()->json([
                'message' => 'Este usuario no está bloqueado actualmente.',
            ], 422);
        }

        // Buscar si ya hay una solicitud pendiente
        $yaPendiente = UserUnlockRequest::where('usuario_id', $user->id)
            ->where('status', 'pendiente')
            ->exists();

        if ($yaPendiente) {
            return response()->json([
                'message' => 'Ya existe una solicitud de desbloqueo pendiente.',
            ], 409);
        }

        $req = UserUnlockRequest::create([
            'usuario_id' => $user->id,
            'email'      => $user->correo,
            'motivo'     => $data['motivo'] ?? null,
            'status'     => 'pendiente',
        ]);

        return response()->json([
            'message' => 'Solicitud de desbloqueo registrada.',
            'request' => $req,
        ], 201);
    }
}
