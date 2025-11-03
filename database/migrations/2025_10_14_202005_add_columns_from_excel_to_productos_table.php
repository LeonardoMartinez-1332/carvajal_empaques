<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('productos', function (Blueprint $t) {
            // Si no existe, asegúrate de tener 'codigo' como índice/único según tu negocio
            if (!Schema::hasColumn('productos', 'codigo')) {
                $t->string('codigo')->nullable()->index();
            }

            $t->string('descripcion')->nullable();

            // BASE DE MILLARES
            $t->integer('pz_x_pt')->nullable();
            $t->decimal('vol', 12, 4)->nullable();
            $t->decimal('w', 12, 4)->nullable();
            $t->string('tipo')->nullable();

            // BASE DE TARIMAS
            $t->string('udm')->nullable();
            $t->integer('cajas_por_tarima')->nullable();

            // BASE DE VOLUMEN
            $t->decimal('volumen_unitario', 12, 4)->nullable();
            $t->decimal('costo_pac_unitario', 12, 4)->nullable();
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $t) {
            $t->dropColumn([
                'descripcion',
                'pz_x_pt','vol','w','tipo',
                'udm','cajas_por_tarima',
                'volumen_unitario','costo_pac_unitario',
            ]);
        });
    }

};
