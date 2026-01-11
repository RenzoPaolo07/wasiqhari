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
            // Detalle SARC-F (0-2 puntos cada uno)
            $table->integer('sarcf_fuerza')->default(0);      // Strength
            $table->integer('sarcf_asistencia')->default(0);  // Assistance
            $table->integer('sarcf_levantarse')->default(0);  // Rise
            $table->integer('sarcf_escaleras')->default(0);   // Climb
            $table->integer('sarcf_caidas')->default(0);      // Falls

            // Total y Valoración
            $table->integer('sarcf_total')->default(0);
            $table->string('sarcf_valoracion')->nullable();
        });
    }

public function down()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            $table->dropColumn([
                'sarcf_fuerza', 'sarcf_asistencia', 'sarcf_levantarse', 
                'sarcf_escaleras', 'sarcf_caidas', 'sarcf_total', 'sarcf_valoracion'
            ]);
        });
    }
};
