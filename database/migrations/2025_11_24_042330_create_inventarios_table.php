<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();

            // 🔗 Producto
            $table->unsignedBigInteger('producto_id');

            // 🔹 Almacén (EMMX019, EMMX023, etc.)
            $table->string('almacen', 20);

            // 🔢 Cantidades
            $table->integer('tarimas')->default(0);
            $table->integer('cajas')->default(0);
            $table->integer('piezas')->default(0);

            $table->timestamps();

            // Clave foránea
            $table->foreign('producto_id')
                ->references('id')->on('productos')
                ->onDelete('cascade');

            // Un inventario por producto+almacén
            $table->unique(['producto_id', 'almacen']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
