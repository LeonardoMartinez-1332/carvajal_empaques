<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductosSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 50; $i++) {

            $codigo = 'P-' . Str::upper(Str::random(6));

            DB::table('productos')->insert([
                'codigo' => $codigo,
                'descripcion' => 'Producto de prueba ' . $i,
                'camas' => rand(1, 5),
                'cajas_por_cama' => rand(5, 20),
                'cajas_por_tarima' => rand(10, 50),

                'pz_x_pt' => rand(10, 200),
                'vol' => rand(1, 50) / 10,
                'w' => rand(1, 50) / 10,
                'tipo' => ['FOAM', 'PLASTICO', 'CARTON'][array_rand(['FOAM','PLASTICO','CARTON'])],
                'udm' => 'PZ',

                'volumen_unitario' => rand(1, 20) / 10,
                'costo_pac_unitario' => rand(10, 200) / 10,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
