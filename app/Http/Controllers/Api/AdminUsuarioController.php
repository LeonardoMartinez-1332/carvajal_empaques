<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class AdminUsuarioController extends Controller
{
    // GET /api/admin/usuarios
    public function index()
    {
        try {
            $usuarios = Usuario::orderBy('id', 'desc')
                ->get(['id', 'nombre', 'correo', 'password', 'role', 'activo']);

            return response()->json($usuarios);
        } catch (\Throwable $e) {
            Log::error('Error listando usuarios: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // POST /api/admin/usuarios (crear)
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'   => ['required', 'string', 'max:255'],
            'correo'   => ['required', 'email', 'max:255', Rule::unique('usuarios', 'correo')],
            'password' => ['required', 'string', 'min:6'],
            'role'     => ['required', Rule::in(['usuario', 'supervisor', 'superusuario', 'jobs'])],
            'activo'   => ['nullable', 'boolean'],
        ]);

        $usuario = new Usuario();
        $usuario->nombre   = $data['nombre'];
        $usuario->correo   = $data['correo'];
        // 👉 tu mutator en el modelo ya cifra el password
        $usuario->password = $data['password'];
        $usuario->role     = $data['role'];          // 👈 AHORA SÍ
        $usuario->activo   = $data['activo'] ?? true;
        $usuario->save();

        return response()->json($usuario, 201);
    }

    // PUT /api/admin/usuarios/{id} (editar)
    // PUT /api/admin/usuarios/{id}  (actualizar)
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $data = $request->validate([
            'nombre'   => ['required', 'string', 'max:255'],
            'correo'   => [
                'required',
                'email',
                'max:255',
                Rule::unique('usuarios', 'correo')->ignore($usuario->id),
            ],
            'password' => ['nullable', 'string', 'min:6'],
            'role'     => ['required', Rule::in(['usuario', 'supervisor', 'superusuario', 'jobs'])],
            'activo'   => ['required', 'boolean'],
        ]);

        $usuario->nombre = $data['nombre'];
        $usuario->correo = $data['correo'];
        $usuario->role   = $data['role'];   // 👈 recuerda: columna se llama "role"
        $usuario->activo = $data['activo'];

        // Solo actualizar password si viene algo
        if (!empty($data['password'])) {
            $usuario->password = $data['password']; // tu mutator ya la encripta
        }

        $usuario->save();

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'usuario' => $usuario,
        ]);
    }


    // PATCH /api/admin/usuarios/{id}/estado
    public function updateEstado($id, Request $request)
    {
        $data = $request->validate([
            'activo' => 'required|boolean',
        ]);

        $usuario = Usuario::findOrFail($id);
        $usuario->activo = $data['activo'];
        $usuario->save();

        return response()->json([
            'message' => 'Estado actualizado correctamente',
            'usuario' => $usuario,
        ]);
    }

    // DELETE /api/admin/usuarios/{id}
    // DELETE /api/admin/usuarios/{id}
    public function destroy($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);

            $nombre = $usuario->nombre;

            $usuario->delete();

            return response()->json([
                'message' => "Usuario \"{$nombre}\" eliminado correctamente.",
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            // Código 23000 = error de FK (tiene movimientos relacionados)
            if ($e->getCode() === '23000') {
                return response()->json([
                    'message' => 'Este usuario no puede eliminarse porque tiene movimientos registrados en el sistema. Solo puede desactivarse.',
                ], 409);  // 👈 IMPORTANTE
            }

            Log::error('Error al eliminar usuario', [
                'id'    => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Error interno al eliminar el usuario.',
            ], 500);
        } catch (\Throwable $e) {
            Log::error('Error al eliminar usuario', [
                'id'    => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Error interno al eliminar el usuario.',
            ], 500);
        }
    }

}
