<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('camiones', function (Blueprint $table) {
            $table->id();
            $table->string('nom_linea');
            $table->foreignId('id_turno')->constrained('turnos')->onDelete('cascade');
            $table->foreignId('id_usuario')->nullable()->constrained('usuarios')->onDelete('set null'); // Si un usuario es eliminado, el campo se vuelve NULL
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('camiones');
    }
};
