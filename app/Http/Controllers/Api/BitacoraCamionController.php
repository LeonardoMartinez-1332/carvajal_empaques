<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BitacoraCamion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Turno;
use App\Models\Camion;


class BitacoraCamionController extends Controller
{
    /**
     * Lista de registros de bitácora (para la vista principal).
     * Soporta búsqueda por TI/FI, placa, origen, destino y estado.
     */
    public function index(Request $request)
    {
        $search = $request->query('q');

        $query = BitacoraCamion::with(['camion', 'turno', 'supervisor'])
        ->where('estatus_aprobacion', 'Aprobado')  
        ->orderByDesc('fecha')
        ->orderByDesc('hora_llegada');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('num_fi_ti', 'like', "%{$search}%")  
                    ->orWhere('origen', 'like', "%{$search}%")
                    ->orWhere('destino', 'like', "%{$search}%")
                    ->orWhere('estado', 'like', "%{$search}%")
                    ->orWhereHas('camion', function ($qc) use ($search) {
                        $qc->where('nom_linea', 'like', "%{$search}%"); 
                });
            });
        }

        $items = $query->get()->map(function (BitacoraCamion $b) {
            return [
                'id'               => $b->id,
                'num_asn'          => $b->num_fi_ti,                
                'placa'            => optional($b->camion)->nom_linea,
                'origen'           => $b->origen,
                'destino'          => $b->destino,
                'rampa'            => $b->rampa,
                'estado'           => $b->estado,
                'fecha'            => $b->fecha,
                'hora_llegada'     => $b->hora_llegada,
                'hora_salida'      => $b->hora_salida,
                'cantidad_tarimas' => $b->cantidad_tarimas,
                'turno'            => optional($b->turno)->nombre_turno ?? null,
                'supervisor'       => optional($b->supervisor)->nombre ?? null,
            ];
        });

        return response()->json($items);
    }

    public function pendientes(Request $request)
    {
        $search = $request->query('q');

        $query = BitacoraCamion::with(['camion', 'turno', 'supervisor'])
            ->where('estatus_aprobacion', 'Pendiente')
            ->orderByDesc('fecha')
            ->orderByDesc('hora_llegada');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('npi', 'like', "%{$search}%")
                ->orWhere('origen', 'like', "%{$search}%")
                ->orWhere('destino', 'like', "%{$search}%")
                ->orWhereHas('camion', function ($qc) use ($search) {
                    $qc->where('nom_linea', 'like', "%{$search}%");
                });
            });
        }

        $items = $query->get()->map(function (BitacoraCamion $b) {
            return [
                'id'                 => $b->id,
                'npi'                => $b->npi,              // 👈 clave para admin
                'num_asn'            => $b->num_fi_ti,        // normalmente null aquí
                'placa'              => optional($b->camion)->nom_linea,
                'origen'             => $b->origen,
                'destino'            => $b->destino,
                'rampa'              => $b->rampa,
                'estado'             => $b->estado,
                'fecha'              => $b->fecha,
                'hora_llegada'       => $b->hora_llegada,
                'hora_salida'        => $b->hora_salida,
                'cantidad_tarimas'   => $b->cantidad_tarimas,
                'turno'              => optional($b->turno)->nombre_turno ?? null,
                'supervisor'         => optional($b->supervisor)->nombre ?? null,
                'estatus_aprobacion' => $b->estatus_aprobacion,
            ];
        });

        return response()->json($items);
    }


    public function store(Request $request)
    {
        try {
            // ✅ Validación base COMÚN (sin id_supervisor y sin num_asn)
            $data = $request->validate([
                'fecha'            => ['required', 'date'],
                'hora_llegada'     => ['nullable', 'date_format:H:i'],
                'id_turno'         => ['required', 'exists:turnos,id'],
                'id_transp'        => ['required', 'exists:camiones,id'],
                'origen'           => ['required', 'string', 'max:255'],
                'destino'          => ['required', 'string', 'max:255'],
                'rampa'            => ['nullable', 'string', 'max:50'],
                'estado'           => ['nullable', 'in:Programado,En descarga,Completado'],
                'cantidad_tarimas' => ['required', 'integer', 'min:0'],
                'hora_salida'      => ['nullable', 'date_format:H:i'],
            ]);

            /** @var \App\Models\Usuario|null $user */
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'message' => 'No autenticado',
                ], 401);
            }

            // Por si no mandan estado
            if (!isset($data['estado'])) {
                $data['estado'] = 'Programado';
            }

            // 🔐 Siempre el supervisor será el usuario logueado
            $data['id_supervisor'] = $user->id;

            // 🔹 SIEMPRE se comporta como SUPERVISOR:
            // genera NPI y deja pendiente de aprobación
            $data['npi'] = 'NPI-' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);

            $data['estatus_aprobacion'] = 'Pendiente';
            $data['num_fi_ti']          = null;
            $data['aprobado_por']       = null;
            $data['aprobado_at']        = null;

            $bitacora = BitacoraCamion::create($data);

            return response()->json([
                'message' => 'Solicitud enviada a autorización.',
                'data'    => $bitacora->load(['camion', 'turno', 'supervisor']),
            ], 201);

        } catch (ValidationException $e) {
            // 👀 Para depurar si vuelve a salir 422
            return response()->json([
                'message' => 'Los datos enviados no son válidos',
                'errors'  => $e->errors(),
            ], 422);
        }
    }




    public function show($id)
    {
        $b = BitacoraCamion::with(['camion', 'turno', 'supervisor'])->findOrFail($id);

        return response()->json([
            'id'               => $b->id,
            'num_asn'          => $b->num_fi_ti,
            'placa'            => optional($b->camion)->nom_linea,
            'origen'           => $b->origen,
            'destino'          => $b->destino,
            'rampa'            => $b->rampa,
            'estado'           => $b->estado,
            'fecha'            => $b->fecha,
            'hora_llegada'     => $b->hora_llegada,
            'hora_salida'      => $b->hora_salida,
            'cantidad_tarimas' => $b->cantidad_tarimas,
            'turno'            => optional($b->turno)->nombre_turno ?? null,
            'id_turno'         => $b->id_turno,
            'id_transp'        => $b->id_transp,
            'id_supervisor'    => $b->id_supervisor,
            'supervisor'       => optional($b->supervisor)->nombre ?? null,
        ]);
    }

    public function update(Request $request, $id)
    {
        $bitacora = BitacoraCamion::findOrFail($id);

        $data = $request->validate([
            'fecha'            => ['sometimes', 'date'],
            'hora_llegada'     => ['sometimes', 'nullable', 'date_format:H:i'],
            'id_turno'         => ['sometimes', 'exists:turnos,id'],
            'id_transp'        => ['sometimes', 'exists:camiones,id'],
            'origen'           => ['sometimes', 'string', 'max:255'],
            'destino'          => ['sometimes', 'string', 'max:255'],
            'num_asn'          => ['sometimes', 'string', 'max:50'],
            'rampa'            => ['sometimes', 'nullable', 'string', 'max:50'],
            'estado'           => ['sometimes', 'in:Programado,En descarga,Completado'],
            'id_supervisor'    => ['sometimes', 'exists:usuarios,id'],
            'cantidad_tarimas' => ['sometimes', 'integer', 'min:0'],
            'hora_salida'      => ['sometimes', 'nullable', 'date_format:H:i'],
        ]);

        if (isset($data['num_asn'])) {
            $data['num_fi_ti'] = $data['num_asn'];
            unset($data['num_asn']);
        }

        $bitacora->update($data);

        return response()->json([
            'message' => 'Registro de bitácora actualizado correctamente.',
            'data'    => $bitacora->load(['camion', 'turno', 'supervisor']),
        ]);
    }

    // Endipoint para que un admin apruebe una solicitud de TI/FI
    public function aprobar($id)
    {
        $b = BitacoraCamion::findOrFail($id);

        // Sólo TI reales se generan aquí
        $b->num_fi_ti = 'TI-' . str_pad(random_int(0, 9999999), 7, '0', STR_PAD_LEFT);

        $b->estatus_aprobacion = 'Aprobado';
        $b->aprobado_por = Auth::id();
        $b->aprobado_at = now();

        $b->save();

        return response()->json([
            'message' => 'Solicitud aprobada y TI generada correctamente.',
            'data' => $b
        ]);
    }

    public function rechazar($id)
    {
        $b = BitacoraCamion::findOrFail($id);

        $b->estatus_aprobacion = 'Rechazado';
        $b->aprobado_por = Auth::id();
        $b->aprobado_at = now();

        $b->save();

        return response()->json([
            'message' => 'Solicitud rechazada.',
            'data' => $b
        ]);
    }

    public function misSolicitudes(Request $request)
    {
        $user = Auth::user();
        $search = $request->query('q');

        $query = BitacoraCamion::with(['camion', 'turno', 'supervisor'])
            ->where('id_supervisor', $user->id) // solo las suyas
            ->orderByDesc('fecha')
            ->orderByDesc('hora_llegada');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('npi', 'like', "%{$search}%")
                ->orWhere('num_fi_ti', 'like', "%{$search}%")
                ->orWhere('origen', 'like', "%{$search}%")
                ->orWhere('destino', 'like', "%{$search}%")
                ->orWhereHas('camion', function ($qc) use ($search) {
                    $qc->where('nom_linea', 'like', "%{$search}%");
                });
            });
        }

        $items = $query->get()->map(function (BitacoraCamion $b) {
            return [
                'id'                 => $b->id,
                'npi'                => $b->npi,
                'num_asn'            => $b->num_fi_ti, // TI real si está aprobada
                'placa'              => optional($b->camion)->nom_linea,
                'origen'             => $b->origen,
                'destino'            => $b->destino,
                'fecha'              => $b->fecha,
                'hora_llegada'       => $b->hora_llegada,
                'hora_salida'        => $b->hora_salida,
                'cantidad_tarimas'   => $b->cantidad_tarimas,
                'turno'              => optional($b->turno)->nombre_turno ?? null,
                'supervisor'         => optional($b->supervisor)->nombre ?? null,
                'estatus_aprobacion' => $b->estatus_aprobacion, // 👈 importante
            ];
        });

        return response()->json($items);
    }


    public function destroy($id)
    {
        $bitacora = BitacoraCamion::findOrFail($id);
        $bitacora->delete();

        return response()->json([
            'message' => 'Registro eliminado de la bitácora.',
        ]);
    }

    // 🔹 Catálogo de turnos para la app
    public function catalogoTurnos()
    {
        // Solo turnos activos; columna real: nombre_turno
        $turnos = Turno::activo()->get(['id', 'nombre_turno']);

        // Los exponemos como { id, nombre } para Flutter
        $payload = $turnos->map(function ($t) {
            return [
                'id'     => $t->id,
                'nombre' => $t->nombre_turno,
            ];
        });

        return response()->json($payload->values());
    }

    // 🔹 Catálogo de transportes (camiones) para la app
    public function catalogoTransportes()
    {
        // Traemos camiones con su turno (por si quieres usarlo en el front)
        $camiones = Camion::with('turno')
            ->orderBy('nom_linea')
            ->get();

        // Estructura amigable para Flutter
        $payload = $camiones->map(function ($c) {
            return [
                'id'        => $c->id,
                'nombre'    => $c->nom_linea,                    // lo que verá el usuario
                'turno_id'  => $c->id_turno,
                'turno'     => optional($c->turno)->nombre_turno // opcional, por si lo usas
            ];
        });

        return response()->json($payload->values());
    }



}
