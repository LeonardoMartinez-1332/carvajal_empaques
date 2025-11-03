<?php

namespace App\Imports;

use App\Models\Producto;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductosMillaresImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $batch = [];

        foreach ($rows as $r) {
            // Con WithHeadingRow, los headers se “slugean”: "Descripcion Articulo" -> "descripcion_articulo"
            $codigo = trim((string)($r['sku'] ?? ''));
            if ($codigo === '') continue;

            $batch[] = [
                'codigo'      => $codigo,
                'descripcion' => (string)($r['descripcion_articulo'] ?? null) ?: null,
                'pz_x_pt'     => isset($r['pz_x_pt']) ? (int)$r['pz_x_pt'] : null,
                'vol'         => isset($r['vol']) ? (float)$r['vol'] : null,
                'w'           => isset($r['w']) ? (float)$r['w'] : null,
                'tipo'        => (string)($r['tipo'] ?? null) ?: null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        if ($batch) {
            Producto::upsert($batch, ['codigo'], ['descripcion','pz_x_pt','vol','w','tipo','updated_at']);
        }
    }
}
