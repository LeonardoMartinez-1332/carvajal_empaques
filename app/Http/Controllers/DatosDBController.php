<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Usuario;
use App\Models\Turno;
use App\Models\BitacoraCamion;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BitacoraExport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Log;

class DatosDBController extends Controller
{
    private $texto = "";

    // Método para recuperar e insertar datos
    public function recuperarBD()
    {
        $this->crearUsuarios();
        $this->crearTurnos();
        return Response::json(['mensaje' => $this->texto]);
    }

    public function crearUsuarios()
    {
        $ruta = Storage::path('usuarios.json');

        if (!file_exists($ruta)) {
            $this->texto .= "El archivo usuarios.json no existe.<br>";
            return;
        }

        $json = file_get_contents($ruta);
        $usuarios = json_decode($json, true)['usuarios'] ?? [];

        foreach ($usuarios as $usuario) {
            try {
                if (!isset($usuario['correo'], $usuario['nombre'], $usuario['password'], $usuario['rol'])) {
                    $this->texto .= "Datos incompletos para el usuario '{$usuario['nombre']}'<br>";
                    continue;
                }

                $existe = Usuario::where('correo', $usuario['correo'])->exists();

                if (!$existe) {
                    Usuario::create([
                        'nombre' => $usuario['nombre'],
                        'correo' => $usuario['correo'],
                        'password' => bcrypt($usuario['password']),
                        'rol' => $usuario['rol'],
                        'activo' => $usuario['activo'] ?? true
                    ]);
                    $this->texto .= "Usuario '{$usuario['nombre']}' creado correctamente. <br>";
                } else {
                    $this->texto .= "El usuario '{$usuario['correo']}' ya existe. <br>";
                }
            } catch (\Exception $e) {
                $this->texto .= "Error al crear el usuario '{$usuario['nombre']}': {$e->getMessage()}<br>";
            }
        }
    }

    // Método para editar usuarios
    public function editarUsuario($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('editar_usuario', compact('usuario'));
    }

    public function actualizarUsuario(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuarios,correo,' . $id,
            'rol' => 'required|in:usuario,supervisor,superusuario',
        ]);

        $usuario = Usuario::findOrFail($id);
        $usuario->update($request->only(['nombre', 'correo', 'rol']));

        return redirect()->route('admin.panel')->with('success', 'Usuario actualizado correctamente.');
    }
    // metodo para desbloquear usuario
    public function desbloquearUsuario($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update([
            'bloqueado' => false,
            'intentos_fallidos' => 0
        ]);

        return back()->with('success', 'Usuario desbloqueado correctamente.');
    }

    public function resetearPassword($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update([
            'password' => bcrypt('12345678'),
            'intentos_fallidos' => 0,
            'bloqueado' => false
        ]);

        return back()->with('success', 'Contraseña reseteada. Nueva: 12345678');
    }

    // meotdo para crear turnos
    public function crearTurnos()
    {
        $ruta = storage_path('app/turnos.json');

        if (!file_exists($ruta)) {
            $this->texto .= "El archivo turnos.json no existe.<br>";
            return;
        }

        $json = file_get_contents($ruta);
        $turnos = json_decode($json, true)['turnos'] ?? [];

        foreach ($turnos as $turno) {
            try {
                if (!isset($turno['nombre_turno'])) {
                    $this->texto .= "Datos incompletos para el turno.<br>";
                    continue;
                }

                $existe = Turno::where('nombre_turno', $turno['nombre_turno'])->exists();

                if (!$existe) {
                    Turno::create([
                        'nombre_turno' => $turno['nombre_turno'],
                        'activo' => $turno['activo'] ?? true
                    ]);
                    $this->texto .= "Turno '{$turno['nombre_turno']}' creado correctamente. <br>";
                } else {
                    $this->texto .= "El turno '{$turno['nombre_turno']}' ya existe. <br>";
                }
            } catch (\Exception $e) {
                $this->texto .= "Error al crear el turno '{$turno['nombre_turno']}': {$e->getMessage()}<br>";
            }
        }
    }

    public function mostrarDatos($nom_tabla)
    {
    try {
        if (!Schema::hasTable($nom_tabla)) {
            return response()->json(["error" => "La tabla '{$nom_tabla}' no existe."], 404);
        }

        $datos = DB::table($nom_tabla)->get(); 

        if ($datos->isEmpty()) {
            return response()->json(["mensaje" => "La tabla '{$nom_tabla}' está vacía."], 200);
        }

        return response()->json($datos, 200);
    } catch (\Exception $e) {
        return response()->json(["error" => "Error al mostrar los datos de la tabla '{$nom_tabla}': " . $e->getMessage()], 500);
    }
    }

    // metodos para panel de gestion de usuarios

    public function guardarUsuario(Request $request)
    {
    // Validación de datos
    $request->validate([
        'nombre' => 'required|string|max:255',
        'correo' => 'required|email|unique:usuarios,correo',
        'password' => 'required|min:6',
        'rol' => 'required|in:usuario,supervisor,superusuario',
    ]);

    // Guardar usuario en la base de datos
    Usuario::create([
        'nombre' => $request->nombre,
        'correo' => $request->correo,
        'password' => bcrypt($request->password), // Encriptar contraseña
        'rol' => $request->rol,
        'activo' => true,
    ]);

    return redirect()->back()->with('success', 'Usuario creado correctamente');
    }

    public function verPanelAdmin(Request $request)
    {
        $query = DB::table('usuarios');

    if ($request->has('buscar')) {
        $buscar = $request->input('buscar');
        $query->where('id', 'like', "%$buscar%")
            ->orWhere('nombre', 'like', "%$buscar%")
            ->orWhere('correo', 'like', "%$buscar%");
    }

    $usuarios = $query->get();

    return view('panel_admin_usuarios', ['usuarios' => $usuarios]);
    }

    
    public function eliminarUsuario($id)
    {
        $usuario = Usuario::find($id);

    if (!$usuario) {
        return redirect()->back()->with('error', 'Usuario no encontrado.');
        }

        $usuario->delete();

    return redirect()->back()->with('success', 'Usuario eliminado correctamente.');
    }

    // metodos para panel de gestion de productos
    public function verPanelProductos(Request $request)
    {
        $query = DB::table('productos');

        if ($request->has('buscar')) {
            $buscar = $request->input('buscar');
            $query->where('id', 'like', "%$buscar%")
                ->orWhere('codigo', 'like', "%$buscar%");
        }

        $productos = $query->get();
        $no_results = $request->has('buscar') && $productos->isEmpty();

        return view('gestionar_productos', [
            'productos' => $productos,
            'no_results' => $no_results
        ]);
    }

    public function crearProducto(Request $request)
    {
        $request->validate([
            'num_caja' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',
            'camas' => 'required|integer|min:1',
            'cajas_por_cama' => 'required|integer|min:1',
            'total_cajas' => 'required|integer|min:1',
        ]);

        $id = DB::table('productos')->insertGetId([
            'num_caja' => $request->num_caja,
            'codigo' => $request->codigo,
            'camas' => $request->camas,
            'cajas_por_cama' => $request->cajas_por_cama,
            'total_cajas' => $request->total_cajas,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Guardar log
        Log::create([
            'usuario' => Auth::user()->nombre,
            'accion' => 'agregó un producto',
            'descripcion' => 'Agregó el producto ID ' . $id . ' (Código: ' . $request->codigo . ')',
        ]);        

        return redirect('/gestionar-productos')->with('success', 'Producto agregado correctamente.');
    }

    public function eliminarProducto($id)
    {
        $producto = DB::table('productos')->where('id', $id)->first();

        DB::table('productos')->where('id', $id)->delete();

        // Guardar log
        Log::create([
            'usuario' => Auth::user()->nombre,
            'accion' => 'eliminó un producto',
            'descripcion' => 'Eliminó el producto ID ' . $id . ' (Código: ' . ($producto->codigo ?? 'N/A') . ')',
        ]);        

        return redirect()->route('productos.panel')->with('success', 'Producto eliminado correctamente.');
    }

    public function editarProducto($id)
    {
        $producto = DB::table('productos')->where('id', $id)->first();
        if (!$producto) {
            return redirect()->route('productos.panel')->with('error', 'Producto no encontrado.');
        }

        return view('editar_producto', compact('producto'));
    }

    public function actualizarProducto(Request $request, $id)
    {
        $request->validate([
            'num_caja' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',
            'camas' => 'required|integer|min:1',
            'cajas_por_cama' => 'required|integer|min:1',
            'total_cajas' => 'required|integer|min:1',
        ]);

        DB::table('productos')->where('id', $id)->update([
            'num_caja' => $request->num_caja,
            'codigo' => $request->codigo,
            'camas' => $request->camas,
            'cajas_por_cama' => $request->cajas_por_cama,
            'total_cajas' => $request->total_cajas,
            'updated_at' => now(),
        ]);

        // Guardar log
        Log::create([
            'usuario' => Auth::user()->nombre,
            'accion' => 'editó un producto',
            'descripcion' => 'Editó el producto ID ' . $id . ' (Código: ' . $request->codigo . ')',
        ]);

        return redirect()->route('productos.panel')->with('success', 'Producto actualizado correctamente.');
    }

    // método para la bitácora de camiones

    public function importarDesdeJson()
    {
        $ruta = storage_path('app/bitacora_camiones.json');

        if (!file_exists($ruta)) {
            return response()->json(['error' => 'El archivo bitacora_camiones.json no existe en storage/app.'], 400);
        }

        // Leer el contenido del JSON
        $json = file_get_contents($ruta);
        $data = json_decode($json, true);

        // Validación de estructura del JSON
        if (!is_array($data)) {
            return response()->json(['error' => 'El JSON tiene un formato incorrecto.'], 400);
        }

        // Insertar cada registro en la base de datos
        foreach ($data as $item) {
            BitacoraCamion::create([
                'fecha' => $item['fecha'],
                'hora_llegada' => $item['hora_llegada'],
                'id_turno' => $item['id_turno'],
                'id_transp' => $item['id_transp'],
                'logistica' => $item['logistica'],
                'num_asn' => $item['num_asn'],
                'id_supervisor' => $item['id_supervisor'],
                'cantidad_tarimas' => $item['cantidad_tarimas'],
                'hora_salida' => $item['hora_salida'] ?? null,
            ]);
        }

        return response()->json(['mensaje' => 'Bitácora de camiones importada correctamente.']);
    }

    public function verPanelBitacora(Request $request)
    {
        $query = \App\Models\BitacoraCamion::query();

        if ($request->has('buscar')) {
            $buscar = $request->input('buscar');
            $query->where('id', 'like', "%$buscar%")
                ->orWhere('logistica', 'like', "%$buscar%")
                ->orWhere('fecha', 'like', "%$buscar%");
        }

        $bitacoras = $query->get();

        return view('admin_bitacora', ['bitacoras' => $bitacoras]);
    }

    public function eliminarBitacora($id)
    {
        $registro = BitacoraCamion::find($id);

        if ($registro) {
            $registro->delete();
            return redirect()->back()->with('mensaje', 'Registro eliminado correctamente.');
        }

        return redirect()->back()->with('error', 'Registro no encontrado.');
    }

    // funcion para editar bitacora

    public function editarBitacora($id)
    {
        $bitacora = BitacoraCamion::findOrFail($id);
        return view('editar_bitacora', compact('bitacora'));
    }

    public function actualizarBitacora(Request $request, $id)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora_llegada' => 'required',
            'logistica' => 'required',
            'num_asn' => 'required',
            'id_supervisor' => 'required|integer',
            'cantidad_tarimas' => 'required|integer',
            'hora_salida' => 'nullable',
        ]);

        $bitacora = BitacoraCamion::findOrFail($id);
        $bitacora->update($request->all());

        return redirect()->route('bitacora.panel')->with('success', 'Registro actualizado correctamente.');
    }

    public function actualizar(Request $request, $id)
    {
        // validación y actualización
        return redirect()->route('bitacora.admin')->with('success', 'Registro actualizado correctamente');
    }


    // vista de login
    public function loginForm()
        {
            return view('login'); 
        }
    
        public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    // metodo para el login con intentos fallidos y bloqueo de usuario
    public function procesarLogin(Request $request)
    {
        $credentials = [
            'correo' => $request->input('correo'),
            'password' => $request->input('password')
        ];

        $usuario = Usuario::where('correo', $credentials['correo'])->first();

        if (!$usuario) {
            return back()->withErrors(['correo' => 'Credenciales inválidas.']);
        }

        if ($usuario->bloqueado) {
            return back()->with('bloqueado', 'Tu cuenta ha sido bloqueada por motivos de seguridad tras varios intentos fallidos.');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $usuario->update(['intentos_fallidos' => 0]);

            switch ($usuario->rol) {
                case 'usuario':
                    return redirect()->route('usuario.panel');
                case 'supervisor':
                    return redirect()->route('supervisor.panel');
                case 'superusuario':
                    return redirect()->route('superusuario.panel');
                default:
                    Auth::logout();
                    return redirect('/login')->withErrors(['rol' => 'Rol no autorizado.']);
            }
        } else {
            $usuario->intentos_fallidos += 1;

            if ($usuario->intentos_fallidos >= 3) {
                $usuario->bloqueado = true;
            }

            $usuario->save();

            if (!$usuario->bloqueado) {
                $intentosRestantes = 3 - $usuario->intentos_fallidos;
                return back()->with('intentos', "Credenciales inválidas. Te quedan $intentosRestantes intento(s) antes de ser bloqueado.");
            }

            return back()->withErrors(['correo' => 'Credenciales inválidas.']);
        }
    }

    public function desbloquear($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->intentos_fallidos = 0;
        $usuario->bloqueado = false;
        $usuario->save();

        return redirect()->back()->with('success', 'Usuario desbloqueado correctamente.');
    }

    public function resetearContrasena($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->password = Hash::make('12345678');
        $usuario->save();

        return redirect()->back()->with('success', 'Contraseña reseteada a: 12345678');
    }


    public function vistaSuperusuario()
    {
        return view('panel_superusuario');
    }

    
    public function vistaSupervisor()
    {
        return view('panel_supervisor');
    }

    public function vistaConsultaProducto()
    {
        return view('Consulta_producto');
    }

    public function vistaBitacora()
    {
        return view('Bitacora_camiones');
    }

    public function vistaCrearProducto() {
        return view('crear_producto');
    }
    

}
