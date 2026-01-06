<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            // Solo agregamos las columnas de detalle (1 = Error, 0 = Correcto)
            // Usamos nombres cortos "pf_"
            if (!Schema::hasColumn('vgi_evaluaciones', 'pf_fecha')) {
                $table->boolean('pf_fecha')->default(0);
                $table->boolean('pf_dia')->default(0);
                $table->boolean('pf_lugar')->default(0);
                $table->boolean('pf_telefono')->default(0);
                $table->boolean('pf_direccion')->default(0);
                $table->boolean('pf_edad')->default(0);
                $table->boolean('pf_nacer')->default(0);
                $table->boolean('pf_pres_act')->default(0);
                $table->boolean('pf_pres_ant')->default(0);
                $table->boolean('pf_madre')->default(0);
                $table->boolean('pf_resta')->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            $table->dropColumn([
                'pf_fecha', 'pf_dia', 'pf_lugar', 'pf_telefono', 'pf_direccion',
                'pf_edad', 'pf_nacer', 'pf_pres_act', 'pf_pres_ant', 'pf_madre', 'pf_resta'
            ]);
        });
    }
};