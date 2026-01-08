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
            // Orientación (10 pts)
            $table->boolean('mmse_tiempo_anio')->default(0);
            $table->boolean('mmse_tiempo_estacion')->default(0);
            $table->boolean('mmse_tiempo_fecha')->default(0);
            $table->boolean('mmse_tiempo_dia')->default(0);
            $table->boolean('mmse_tiempo_mes')->default(0);

            $table->boolean('mmse_lugar_pais')->default(0);
            $table->boolean('mmse_lugar_dep')->default(0);
            $table->boolean('mmse_lugar_dist')->default(0);
            $table->boolean('mmse_lugar_hosp')->default(0);
            $table->boolean('mmse_lugar_piso')->default(0);

            // Memoria Inmediata (3 pts)
            $table->boolean('mmse_mem_arbol')->default(0);
            $table->boolean('mmse_mem_puente')->default(0);
            $table->boolean('mmse_mem_farol')->default(0);
            $table->integer('mmse_mem_intentos')->nullable();

            // Atención y Cálculo (5 pts)
            $table->boolean('mmse_atencion_1')->default(0); // 93 u O
            $table->boolean('mmse_atencion_2')->default(0); // 86 u D
            $table->boolean('mmse_atencion_3')->default(0); // 79 u N
            $table->boolean('mmse_atencion_4')->default(0); // 72 u U
            $table->boolean('mmse_atencion_5')->default(0); // 65 u M

            // Recuerdo Diferido (3 pts)
            $table->boolean('mmse_rec_arbol')->default(0);
            $table->boolean('mmse_rec_puente')->default(0);
            $table->boolean('mmse_rec_farol')->default(0);

            // Lenguaje (9 pts)
            $table->boolean('mmse_nom_lapiz')->default(0);
            $table->boolean('mmse_nom_reloj')->default(0);
            $table->boolean('mmse_repeticion')->default(0); // Trigal
            $table->boolean('mmse_orden_mano')->default(0);
            $table->boolean('mmse_orden_doblar')->default(0);
            $table->boolean('mmse_orden_suelo')->default(0);
            $table->boolean('mmse_leer')->default(0); // Cierre los ojos
            $table->boolean('mmse_escribir')->default(0); // Frase
            $table->boolean('mmse_copiar')->default(0); // Dibujo

            // Totales
            $table->integer('mmse_total_final')->default(0);
            $table->string('mmse_valoracion_final')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            $table->dropColumn([
                'mmse_tiempo_anio', 'mmse_tiempo_estacion', 'mmse_tiempo_fecha',
                'mmse_tiempo_dia', 'mmse_tiempo_mes', 'mmse_lugar_pais',
                'mmse_lugar_dep', 'mmse_lugar_dist', 'mmse_lugar_hosp',
                'mmse_lugar_piso', 'mmse_mem_arbol', 'mmse_mem_puente',
                'mmse_mem_farol', 'mmse_mem_intentos', 'mmse_atencion_1',
                'mmse_atencion_2', 'mmse_atencion_3', 'mmse_atencion_4',
                'mmse_atencion_5', 'mmse_rec_arbol', 'mmse_rec_puente',
                'mmse_rec_farol', 'mmse_nom_lapiz', 'mmse_nom_reloj',
                'mmse_repeticion', 'mmse_orden_mano', 'mmse_orden_doblar',
                'mmse_orden_suelo', 'mmse_leer', 'mmse_escribir',
                'mmse_copiar', 'mmse_total_final', 'mmse_valoracion_final'
            ]);
        });
        //
    }
};
