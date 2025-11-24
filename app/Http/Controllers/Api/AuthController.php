<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Usuario;

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

        // 3) Validar credenciales
        if (! $user || ! Hash::check($password, $user->password)) {
            // Usamos ValidationException para mantener el formato típico de errores de Laravel
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas no son válidas.'],
            ]);
        }

        //bloquear usuarios inactivos
        
        if (! $user->activo) {
            throw ValidationException::withMessages([
                'email' => ['Este usuario se encuentra inactivo.'],
            ]);
        }
        

        // 4) (Opcional pero pro) Dejar solo una sesión activa por usuario
        $user->tokens()->delete();

        // 5) Crear token Sanctum para el ERP / Flutter
        $token = $user->createToken('erp-carvajal')->plainTextToken;

        // 6) Respuesta estándar para el front (Flutter)
        return response()->json([
            'message' => 'Login correcto',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->nombre,
                'email' => $user->correo,          // el correo real de la BD
                'role'  => $user->role ?? 'usuario',
                'activo'=> (bool) $user->activo,
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
            'id'    => $u->id,
            'name'  => $u->nombre,
            'email' => $u->correo,
            'role'  => $u->role ?? 'usuario',
            'activo'=> (bool) $u->activo,
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
}
