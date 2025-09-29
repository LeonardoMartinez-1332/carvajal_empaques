<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BitacoraCamion;
use App\Models\Turno;
use App\Models\Camion;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BitacoraCamionController extends Controller
{
    public function vistaFormulario()
    {
        $camiones = Camion::all();
        $registros = BitacoraCamion::with(['turno', 'camion', 'supervisor'])->latest()->get();

        return view('Bitacora_camiones', compact('camiones', 'registros'));
    }

    public function guardarRegistro(Request $request)
    {
        $request->validate([
            'id_transp' => 'required|exists:camiones,id',
            'logistica' => 'required|string|max:255',
            'num_asn' => 'required|string|max:255',
            'cantidad_tarimas' => 'required|string|max:255',
            'hora_salida' => 'required'
        ]);

        $horaActual = Carbon::now();
        $hora = $horaActual->format('H:i');

        $turno = $this->determinarTurno($hora);

        BitacoraCamion::create([
            'fecha' => $horaActual->toDateString(),
            'hora_llegada' => $horaActual->toTimeString(),
            'id_turno' => $turno->id,
            'id_transp' => $request->id_transp,
            'logistica' => $request->logistica,
            'num_asn' => $request->num_asn,
            'id_supervisor' => Auth::user()->id,
            'cantidad_tarimas' => $request->cantidad_tarimas,
            'hora_salida' => $request->hora_salida
        ]);

        return redirect()->back()->with('success', 'Registro guardado correctamente.');
    }

    private function determinarTurno($hora)
    {
        if ($hora >= '22:30' || $hora < '06:30') {
            return Turno::where('nombre_turno', 'Nocturno')->first();
        } elseif ($hora >= '06:30' && $hora < '14:30') {
            return Turno::where('nombre_turno', 'Matutino')->first();
        } else {
            return Turno::where('nombre_turno', 'Vespertino')->first();
        }
    }
}
