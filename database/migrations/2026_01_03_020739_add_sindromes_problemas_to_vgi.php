<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            // Síndromes Geriátricos
            $table->boolean('sindrome_caidas')->default(0); // 2 o más caídas
            $table->boolean('sindrome_incontinencia')->default(0); // Problemas para contener orina/heces
            $table->boolean('sindrome_delirio')->default(0); // Se desorientó hospitalizado

            // Problemas Geriátricos
            $table->boolean('problema_dental')->default(0); // Le faltan piezas
            $table->boolean('usa_protesis')->default(0);
            $table->boolean('vision_conservada')->default(1); // ¿Ve bien? (1=Si, 0=No)
            $table->boolean('audicion_conservada')->default(1); // ¿Escucha bien?
            $table->boolean('problema_estrenimiento')->default(0);
            $table->boolean('problema_insomnio')->default(0);
            $table->boolean('problema_nocturia')->default(0); // Se levanta de noche (varones)

            // Medicación
            $table->boolean('toma_medicacion')->default(0);
            $table->integer('num_medicamentos')->nullable();
        });
    }

    public function down()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            $table->dropColumn([
                'sindrome_caidas', 'sindrome_incontinencia', 'sindrome_delirio',
                'problema_dental', 'usa_protesis', 'vision_conservada', 'audicion_conservada',
                'problema_estrenimiento', 'problema_insomnio', 'problema_nocturia',
                'toma_medicacion', 'num_medicamentos'
            ]);
        });
    }
};