<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bitacora_camiones', function (Blueprint $t) {
            // 1) TI/FI puede ser null cuando crea el SUPERVISOR
            $t->string('num_fi_ti')->nullable()->change();

            // 2) Default del estatus de aprobación → "Pendiente"
            $t->string('estatus_aprobacion')
                ->default('Pendiente')
                ->change();

                // 3) Opcional, para que coincida con tu controlador
                $t->string('estado')
                ->default('Programado')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('bitacora_camiones', function (Blueprint $t) {
            // Revertir lo mínimo (si quieres)
            $t->string('num_fi_ti')->nullable(false)->change();
            $t->string('estatus_aprobacion')->default('Aprobado')->change();
            $t->string('estado')->default('EN ESPERA')->change();
        });
    }
};
