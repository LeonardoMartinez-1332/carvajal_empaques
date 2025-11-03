<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $t) {
            // num_caja alfanumérico + unique
            $t->string('num_caja', 50)->change();
            $t->unique('num_caja');

            // defaults en enteros
            $t->integer('camas')->default(0)->change();
            $t->integer('cajas_por_cama')->default(0)->change();
            $t->integer('total_cajas')->default(0)->change();

            // codigo nullable (si quieres quitar el unique hazlo con otra migración o via SQL)
            $t->string('codigo')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $t) {
            $t->dropUnique(['num_caja']); // nombre típico: productos_num_caja_unique
            // aquí podrías revertir tipos si lo necesitas
        });
    }
};
