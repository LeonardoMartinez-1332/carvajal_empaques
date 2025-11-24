<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bitacora_camiones', function (Blueprint $t) {

            $t->id();

            // Fecha + horas
            $t->date('fecha');
            $t->time('hora_llegada')->nullable();
            $t->time('hora_salida')->nullable();

            // Relaciones
            $t->unsignedBigInteger('id_turno');
            $t->unsignedBigInteger('id_transp');      // FK camion
            $t->unsignedBigInteger('id_supervisor');  // FK usuario (supervisor)

            // Datos nuevos
            $t->string('origen');        // antes logistica
            $t->string('destino');       // ya existía en tu vista
            $t->string('num_fi_ti');     // antes num_asn
            $t->integer('cantidad_tarimas')->default(0);

            // Nuevos campos clave
            $t->string('estado')->default('EN ESPERA');  
            // Ejemplos: EN ESPERA, DESCARGA, COMPLETADO

            $t->string('rampa')->nullable(); // solo se llena cuando estado = DESCARGA

            $t->timestamps();

            // Foreign Keys
            $t->foreign('id_turno')->references('id')->on('turnos');
            $t->foreign('id_transp')->references('id')->on('camiones');
            $t->foreign('id_supervisor')->references('id')->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacora_camiones');
    }
};
