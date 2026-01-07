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
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            // Puntajes por dominio
            $table->integer('rudas_orientacion')->default(0); // Max 8
            $table->integer('rudas_praxis')->default(0);      // Max 2
            $table->integer('rudas_visoconstructiva')->default(0); // Max 4
            $table->integer('rudas_juicio')->default(0);      // Max 4
            $table->integer('rudas_lenguaje')->default(0);    // Max 6
            $table->integer('rudas_memoria')->default(0);     // Max 6

            // Total y Valoración
            $table->integer('rudas_total')->default(0);       // Max 30
            $table->string('rudas_valoracion')->nullable();
        });
    }

public function down()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            $table->dropColumn([
                'rudas_orientacion', 'rudas_praxis', 'rudas_visoconstructiva',
                'rudas_juicio', 'rudas_lenguaje', 'rudas_memoria',
                'rudas_total', 'rudas_valoracion'
            ]);
        });
    }
};
