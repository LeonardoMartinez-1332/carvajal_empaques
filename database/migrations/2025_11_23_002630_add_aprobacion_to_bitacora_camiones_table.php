<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bitacora_camiones', function (Blueprint $t) {
            $t->string('npi')->nullable()->after('id_supervisor');
            $t->string('estatus_aprobacion')->default('Aprobado')->after('npi');
            $t->unsignedBigInteger('aprobado_por')->nullable()->after('estatus_aprobacion');
            $t->timestamp('aprobado_at')->nullable()->after('aprobado_por');

            $t->foreign('aprobado_por')->references('id')->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::table('bitacora_camiones', function (Blueprint $t) {
            $t->dropForeign(['aprobado_por']);

            $t->dropColumn([
                'npi',
                'estatus_aprobacion',
                'aprobado_por',
                'aprobado_at'
            ]);
        });
    }
};
