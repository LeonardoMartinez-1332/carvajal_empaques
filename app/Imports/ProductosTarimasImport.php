<?php

namespace App\Imports;

use App\Models\Producto;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductosTarimasImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $batch = [];

        foreach ($rows as $r) {
            // "articulo", "descripcion", "udm", "cajas por tarima" -> "cajas_por_tarima"
            $codigo = trim((string)($r['articulo'] ?? ''));
            if ($codigo === '') continue;

            $batch[] = [
                'codigo'           => $codigo,
                'descripcion'      => (string)($r['descripcion'] ?? null) ?: null,
                'udm'              => (string)($r['udm'] ?? null) ?: null,
                'cajas_por_tarima' => isset($r['cajas_por_tarima']) ? (int)$r['cajas_por_tarima'] : null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ];
        }

        if ($batch) {
            Producto::upsert($batch, ['codigo'], ['descripcion','udm','cajas_por_tarima','updated_at']);
        }
    }
}
