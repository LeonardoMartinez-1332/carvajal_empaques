<<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Asegura que 'codigo' NO sea nulo
        Schema::table('productos', function (Blueprint $t) {
            // ajusta la longitud si lo necesitas
            $t->string('codigo', 191)->nullable(false)->change();
        });

        // 2) Crea índice único si NO existe
        $exists = DB::table('information_schema.statistics')
            ->whereRaw('table_schema = DATABASE()')
            ->where('table_name', 'productos')
            ->where('index_name', 'productos_codigo_unique')
            ->exists();

        if (!$exists) {
            DB::statement('CREATE UNIQUE INDEX productos_codigo_unique ON productos (codigo)');
        }

        // (Opcional) asegúrate de que num_caja tenga un índice normal:
        // $hasNumCajaIdx = DB::table('information_schema.statistics')
        //     ->whereRaw('table_schema = DATABASE()')
        //     ->where('table_name', 'productos')
        //     ->where('index_name', 'productos_num_caja_index')
        //     ->exists();
        // if (!$hasNumCajaIdx) {
        //     Schema::table('productos', fn (Blueprint $t) => $t->index('num_caja', 'productos_num_caja_index'));
        // }
    }

    public function down(): void
    {
        // Quita el índice único si existe
        DB::statement('ALTER TABLE productos DROP INDEX IF EXISTS productos_codigo_unique');

        // (Si quieres revertir a nullable)
        // Schema::table('productos', function (Blueprint $t) {
        //     $t->string('codigo', 191)->nullable()->change();
        // });
    }
};
