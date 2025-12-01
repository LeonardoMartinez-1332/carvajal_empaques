<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ti_detalles', function (Blueprint $table) {
            // 1) Quitar la FK vieja que apunta a 'tis'
            $table->dropForeign('ti_detalles_ti_id_foreign'); 
            //   ^^^ si el nombre es otro, lo cambias (lo ves en Workbench o con SHOW CREATE TABLE)

            // 2) Crear la FK correcta hacia 'ti_directas'
            $table->foreign('ti_id')
                ->references('id')
                ->on('ti_directas')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('ti_detalles', function (Blueprint $table) {
            $table->dropForeign(['ti_id']);

            // Volver a la FK anterior si quisieras (opcional)
            $table->foreign('ti_id')
                ->references('id')
                ->on('tis')
                ->onDelete('cascade');
        });
    }
};
