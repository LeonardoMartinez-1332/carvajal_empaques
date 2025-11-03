<?php

namespace App\Imports;

use App\Models\Producto;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ProductsImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    // Si tu encabezado real está en otra fila, cámbiala aquí
    public function headingRow(): int
    {
        return 1;
    }

    public function collection(Collection $rows)
    {
        $toUpsert = [];

        foreach ($rows as $row) {
            $map = $row->toArray();

            // num_caja como STRING alfanumérico normalizado (no lo hagas numérico)
            $numCajaRaw = $this->v($map,
                'num_caja', 'num caja', 'n° caja', 'no caja', 'numero', 'numero caja', 'nro caja', 'nro', 'num'
            );
            $numCaja = $this->strId($numCajaRaw); // <- normaliza y elimina espacios

            // codigo como string (nullable)
            $codigo = $this->str($this->v($map,
                'codigo', 'código', 'sku', 'clave', 'codigo producto', 'producto', 'cod'
            ));

            // numéricos (nullable -> luego default 0)
            $camas      = $this->num($this->v($map, 'camas', 'cama'));
            $cajasXCama = $this->num($this->v($map,
                'cajas_por_cama', 'cajas x cama', 'cajas_x_cama', 'cajasxcama', 'cajas por cama'
            ));
            $totalCajas = $this->num($this->v($map,
                'total_cajas', 'total', 'millares', 'millar', 'total millares', 'cantidad de cajas', 'cantidadcajas'
            ));

            // Calcula total si faltó pero hay factores
            if ($totalCajas === null && $camas !== null && $cajasXCama !== null) {
                $totalCajas = $camas * $cajasXCama;
            }

            // Reglas mínimas: si no hay num_caja, saltar fila
            if ($numCaja === null) {
                continue;
            }

            // Defaults seguros para numéricos opcionales
            $camas      = $camas      ?? 0;
            $cajasXCama = $cajasXCama ?? 0;
            $totalCajas = $totalCajas ?? 0;

            $toUpsert[] = [
                'num_caja'       => $numCaja,
                'codigo'         => $codigo,          // puede ir null
                'camas'          => $camas,
                'cajas_por_cama' => $cajasXCama,
                'total_cajas'    => $totalCajas,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        if (!empty($toUpsert)) {
            // Upsert por codigo (clave alfanumérica)
            Producto::upsert(
            $toUpsert,
            ['codigo'],                                  // clave única
            ['num_caja','camas','cajas_por_cama','total_cajas','updated_at']
            );

        }
    }

    /** Busca un valor por candidatos de encabezado con normalización */
    private function v(array $row, string ...$candidates)
    {
        $lower = [];
        foreach ($row as $k => $v) {
            $key = $this->normKey($k);
            $lower[$key] = $v;
        }

        foreach ($candidates as $c) {
            $ck = $this->normKey($c);
            if (array_key_exists($ck, $lower)) {
                return $lower[$ck];
            }
        }
        return null;
    }

    /** Normaliza claves: minúsculas, sin acentos; símbolos → espacio → '_' */
    private function normKey(?string $s): string
    {
        return Str::of((string)$s)
            ->lower()
            ->replace(['á','é','í','ó','ú','ä','ë','ï','ö','ü'], ['a','e','i','o','u','a','e','i','o','u'])
            ->replace(['º','°','.',',','/','\\','-'], ' ')
            ->replaceMatches('/\s+/', ' ')
            ->trim()
            ->replace(' ', '_')
            ->toString();
    }

    /** A entero; limpia “1,234”, “ 12 pzs ”, etc. */
    private function num($v): ?int
    {
        if ($v === null || $v === '') return null;
        if (is_numeric($v)) return (int)$v;

        $clean = preg_replace('/[^\d\-]/', '', (string)$v);
        if ($clean === '' || $clean === '-') return null;
        return (int)$clean;
    }

    /** A string (trim) o null si queda vacío */
    private function str($v): ?string
    {
        if ($v === null) return null;
        $s = trim((string)$v);
        return $s === '' ? null : $s;
    }

    /**
     * Normalizador para IDs alfanuméricos (num_caja):
     * - quita NBSP (0xC2 0xA0) y espacios
     * - trim, mayúsculas opcional
     * - si queda vacío, null
     */
    private function strId($v): ?string
    {
        if ($v === null) return null;

        $s = (string)$v;

        // reemplaza NBSP por espacio normal
        $s = str_replace("\xC2\xA0", ' ', $s);

        // trim y colapsa espacios -> luego elimina todos los espacios
        $s = Str::of($s)->trim()->replaceMatches('/\s+/', ' ')->replace(' ', '')->toString();

        // opcional: normalizar a mayúsculas
        $s = Str::of($s)->upper()->toString();

        return $s === '' ? null : $s;
    }
}
