<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {

            // ID
            $table->id();

            // Código único del producto
            $table->string('codigo', 191)->unique();

            // Descripción
            $table->string('descripcion')->nullable();

            // Datos operativos
            $table->integer('camas')->default(0);
            $table->integer('cajas_por_cama')->default(0);

            // Total real de cajas por tarima
            $table->integer('cajas_por_tarima')->nullable();

            // Base millares / volumetría
            $table->integer('pz_x_pt')->nullable();
            $table->decimal('vol', 12, 4)->nullable();
            $table->decimal('w', 12, 4)->nullable();
            $table->string('tipo')->nullable();

            // Unidad de medida
            $table->string('udm')->nullable();

            // Datos complementarios
            $table->decimal('volumen_unitario', 12, 4)->nullable();
            $table->decimal('costo_pac_unitario', 12, 4)->nullable();

            // Laravel timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
