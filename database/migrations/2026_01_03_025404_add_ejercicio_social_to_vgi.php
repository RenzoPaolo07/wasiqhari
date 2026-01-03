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
            $table->boolean('realiza_ejercicio')->default(0);
            $table->boolean('acude_centro_social')->default(0); // CAM/Parroquial/Municipal
            $table->string('actividad_centro_social')->nullable(); // Manualidades, Danzas, etc.
        });
    }

    public function down()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            $table->dropColumn(['realiza_ejercicio', 'acude_centro_social', 'actividad_centro_social']);
        });
    }
};
