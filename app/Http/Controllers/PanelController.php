<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Log;

class PanelController extends Controller
{
    public function vistaSuperusuario()
{
    $dias = collect([]);
    $registros = collect([]);

    for ($i = 6; $i >= 0; $i--) {
        $fecha = Carbon::today()->subDays($i)->toDateString();

        $cantidad = DB::table('bitacora_camiones')
            ->whereDate('fecha', $fecha)
            ->count();

        $dias->push(Carbon::parse($fecha)->format('d M'));
        $registros->push($cantidad);
    }

    $logsRecientes = Log::orderBy('created_at', 'desc')->take(5)->get();

    return view('panel_superusuario', [
        'dias' => $dias,
        'registros' => $registros,
        'logsRecientes' => $logsRecientes,
    ]);
}
}
