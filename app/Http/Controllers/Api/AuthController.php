<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // El front manda { email, password }. Aceptamos también { correo, password } por si acaso.
        $email = $request->input('email') ?? $request->input('correo');
        $password = $request->input('password');

        $request->validate([
            'password' => ['required','string'],
            // Validamos el email si viene
        ]);

        if (!$email) {
            return response()->json(['message' => 'Falta el correo'], 422);
        }

        $user = Usuario::where('correo', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $token = $user->createToken('erp-carvajal')->plainTextToken;

        // Mapeamos a lo que espera Flutter: { id, name, email, role }
        return response()->json([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->nombre,
                'email' => $user->correo,
                'role'  => $user->rol ?? 'usuario',
            ],
        ]);
    }

    public function me(Request $request)
    {
        /** @var \App\Models\Usuario $u */
        $u = $request->user();
        return response()->json([
            'id'    => $u->id,
            'name'  => $u->nombre,
            'email' => $u->correo,
            'role'  => $u->rol ?? 'usuario',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();
        return response()->json(['ok' => true]);
    }
}
