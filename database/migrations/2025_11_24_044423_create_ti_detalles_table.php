<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ti_detalles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ti_id');
            $table->unsignedBigInteger('producto_id');

            $table->integer('tarimas')->default(0);
            $table->integer('cajas')->default(0);
            $table->integer('piezas')->default(0);

            $table->timestamps();

            $table->foreign('ti_id')
                ->references('id')->on('tis')
                ->onDelete('cascade');

            $table->foreign('producto_id')
                ->references('id')->on('productos')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ti_detalles');
    }
};