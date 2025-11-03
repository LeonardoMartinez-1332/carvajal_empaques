<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductosMasterImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'BASE DE MILLARES' => new ProductosMillaresImport,
            'BASE DE TARIMAS'  => new ProductosTarimasImport,
            'BASE DE VOLUMEN'  => new ProductosVolumenImport,
        ];
    }
}
