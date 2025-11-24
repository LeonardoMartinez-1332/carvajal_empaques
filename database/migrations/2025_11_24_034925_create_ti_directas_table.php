<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ti_directas', function (Blueprint $table) {
            $table->id();

            // Folios
            $table->string('npi')->unique();        // NPI interno
            $table->string('num_ti')->unique();     // Ej: TI-000001

            // Relación con producto
            $table->unsignedBigInteger('producto_id');
            $table->string('codigo_producto');      // respaldo para reportes
            $table->string('descripcion_producto'); // respaldo para reportes

            // Movimiento
            $table->unsignedInteger('cantidad');
            $table->string('unidad', 20)->default('CJ');

            $table->string('almacen_origen', 50);
            $table->string('almacen_destino', 50)->nullable();

            // Quién la generó
            $table->unsignedBigInteger('creado_por');

            // Estado de la TI
            $table->enum('estatus', ['emitida', 'cancelada'])->default('emitida');

            // Para futuro PDF
            $table->string('pdf_path')->nullable();

            $table->timestamps();

            // 🔗 Foreign keys (ajusta nombres si cambia algo en tu esquema)
            $table->foreign('producto_id')
                ->references('id')
                ->on('productos');

            $table->foreign('creado_por')
                ->references('id')
                ->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ti_directas');
    }
};
