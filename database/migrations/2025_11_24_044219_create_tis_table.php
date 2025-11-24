<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tis', function (Blueprint $table) {
            $table->id();

            // Número de TI (ej: TI-000123)
            $table->string('numero', 30)->unique();

            // Almacenes
            $table->string('almacen_origen', 20);
            $table->string('almacen_destino', 20);

            // Usuario Jobs que la generó
            $table->unsignedBigInteger('usuario_id');

            $table->date('fecha');

            // Totales por control (opcional)
            $table->integer('total_tarimas')->default(0);
            $table->integer('total_cajas')->default(0);
            $table->integer('total_piezas')->default(0);

            $table->timestamps();

            $table->foreign('usuario_id')
                ->references('id')->on('usuarios')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tis');
    }
};

