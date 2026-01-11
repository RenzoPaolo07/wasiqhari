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
            // Detalle FRAIL (1 = Punto/Riesgo, 0 = Normal)
            // Ojo con la lógica: En Resistencia/Ambulación, NO poder = 1 punto.
            $table->boolean('frail_fatiga')->default(0);      // F: ¿Fatiga? Sí=1
            $table->boolean('frail_resistencia')->default(0); // R: ¿Sube escaleras? No=1
            $table->boolean('frail_ambulacion')->default(0);  // A: ¿Camina 1 cuadra? No=1
            $table->boolean('frail_enfermedades')->default(0);// I: ¿>5 enfermedades? Sí=1
            $table->boolean('frail_peso')->default(0);        // L: ¿Pérdida peso >5%? Sí=1

            // Valoración texto
            $table->string('frail_valoracion_texto')->nullable(); // Robusto, Pre-frágil, Frágil
        });
    }

public function down()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            $table->dropColumn([
                'frail_fatiga', 'frail_resistencia', 'frail_ambulacion', 
                'frail_enfermedades', 'frail_peso', 'frail_valoracion_texto'
            ]);
        });
    }
};
