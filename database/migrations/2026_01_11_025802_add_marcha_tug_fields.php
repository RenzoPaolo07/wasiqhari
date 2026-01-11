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
            // Velocidad de Marcha (8 metros)
            $table->decimal('marcha_segundos', 5, 2)->nullable();
            $table->decimal('marcha_velocidad', 5, 2)->nullable(); // m/s (Calculado)

            // Get Up and Go (TUG)
            $table->decimal('tug_segundos', 5, 2)->nullable();

            // Valoraciones automáticas (Texto)
            $table->string('fisica_valoracion')->nullable();
        });
    }

public function down()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            $table->dropColumn(['marcha_segundos', 'marcha_velocidad', 'tug_segundos', 'fisica_valoracion']);
        });
    }
};
