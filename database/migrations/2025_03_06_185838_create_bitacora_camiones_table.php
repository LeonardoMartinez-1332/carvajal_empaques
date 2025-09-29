<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('bitacora_camiones', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('hora_llegada');
            $table->unsignedBigInteger('id_turno');
            $table->unsignedBigInteger('id_transp');
            $table->string('logistica');
            $table->string('num_asn');
            $table->unsignedBigInteger('id_supervisor');
            $table->string('cantidad_tarimas'); // Modificado de integer a string
            $table->time('hora_salida')->nullable();
            $table->timestamps();

            $table->foreign('id_turno')->references('id')->on('turnos')->onDelete('cascade');
            $table->foreign('id_transp')->references('id')->on('camiones')->onDelete('cascade');
            $table->foreign('id_supervisor')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bitacora_camiones');
    }
};
