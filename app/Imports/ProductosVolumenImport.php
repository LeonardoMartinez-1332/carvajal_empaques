<?php

namespace App\Imports;

use App\Models\Producto;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductosVolumenImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $batch = [];

        foreach ($rows as $r) {
            // "ARTICULO", "DESCRIPCION DE ARTICULO", "VOLUMEN UNITARIO", "COSTO PAC UNITARIO"
            $codigo = trim((string)($r['articulo'] ?? ''));
            if ($codigo === '') continue;

            $batch[] = [
                'codigo'             => $codigo,
                'descripcion'        => (string)($r['descripcion_de_articulo'] ?? null) ?: null,
                'volumen_unitario'   => isset($r['volumen_unitario']) ? (float)$r['volumen_unitario'] : null,
                'costo_pac_unitario' => isset($r['costo_pac_unitario']) ? (float)$r['costo_pac_unitario'] : null,
                'created_at'         => now(),
                'updated_at'         => now(),
            ];
        }

        if ($batch) {
            Producto::upsert($batch, ['codigo'], ['descripcion','volumen_unitario','costo_pac_unitario','updated_at']);
        }
    }
}
