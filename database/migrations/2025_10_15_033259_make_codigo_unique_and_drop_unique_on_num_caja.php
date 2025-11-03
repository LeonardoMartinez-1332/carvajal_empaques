<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1) Quitar UNIQUE de num_caja (el nombre puede variar; probamos ambos)
        Schema::table('productos', function (Blueprint $t) {
            try { $t->dropUnique('productos_num_caja_unique'); } catch (\Throwable $e) {}
            try { $t->dropUnique(['num_caja']); } catch (\Throwable $e) {}
        });

        // 2) (Opcional) dejar index normal en num_caja para búsquedas
        Schema::table('productos', function (Blueprint $t) {
            try { $t->index('num_caja'); } catch (\Throwable $e) {}
        });

        // 3) Hacer codigo NOT NULL + UNIQUE
        Schema::table('productos', function (Blueprint $t) {
            // Asegura que no sea null (MySQL permite múltiples NULL en UNIQUE)
            $t->string('codigo', 100)->nullable(false)->change();

            // Si había un index normal, lo quitamos y ponemos UNIQUE
            try { $t->dropIndex(['codigo']); } catch (\Throwable $e) {}
            try { $t->dropIndex('productos_codigo_index'); } catch (\Throwable $e) {}

            $t->unique('codigo');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $t) {
            // revertir UNIQUE en codigo -> index normal y permitir null si lo prefieres
            try { $t->dropUnique('productos_codigo_unique'); } catch (\Throwable $e) {}
            try { $t->dropUnique(['codigo']); } catch (\Throwable $e) {}

            $t->string('codigo', 100)->nullable()->change();
            try { $t->index('codigo'); } catch (\Throwable $e) {}

            // volver a UNIQUE en num_caja si quisieras
            try { $t->dropIndex(['num_caja']); } catch (\Throwable $e) {}
            $t->unique('num_caja');
        });
    }
};
