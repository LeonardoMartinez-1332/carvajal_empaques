<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Asegura que 'codigo' sea NOT NULL (ajusta longitud si quieres)
        Schema::table('productos', function (Blueprint $t) {
            $t->string('codigo', 191)->nullable(false)->change();
        });

        // ❌ Solo intenta dropear el UNIQUE en num_caja si realmente existe
        $hasUniqueNumCaja = DB::table('information_schema.statistics')
            ->whereRaw('table_schema = DATABASE()')
            ->where('table_name', 'productos')
            ->where('index_name', 'productos_num_caja_unique')
            ->exists();

        if ($hasUniqueNumCaja) {
            DB::statement('ALTER TABLE productos DROP INDEX productos_num_caja_unique');
        }

        // ✅ Crea el UNIQUE en codigo si no existe
        $hasCodigoUnique = DB::table('information_schema.statistics')
            ->whereRaw('table_schema = DATABASE()')
            ->where('table_name', 'productos')
            ->where('index_name', 'productos_codigo_unique')
            ->exists();

        if (!$hasCodigoUnique) {
            DB::statement('CREATE UNIQUE INDEX productos_codigo_unique ON productos (codigo)');
        }
    }

    public function down(): void
    {
        // Quita el UNIQUE en codigo si existe (por si haces rollback)
        $hasCodigoUnique = DB::table('information_schema.statistics')
            ->whereRaw('table_schema = DATABASE()')
            ->where('table_name', 'productos')
            ->where('index_name', 'productos_codigo_unique')
            ->exists();

        if ($hasCodigoUnique) {
            DB::statement('ALTER TABLE productos DROP INDEX productos_codigo_unique');
        }

        // (Opcional) podrías volver a crear el unique en num_caja si alguna vez existió,
        // pero como en tu esquema actual no lo usas, lo dejo sin recrear.
    }
};
