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
            // Palabras (1 si recuerda, 0 si no)
            $table->boolean('minicog_palabra_mesa')->default(0);
            $table->boolean('minicog_palabra_llave')->default(0);
            $table->boolean('minicog_palabra_libro')->default(0);

            // Reloj (2 = Normal, 0 = Anormal)
            $table->integer('minicog_reloj_puntaje')->default(0);

            // Resultados
            $table->integer('minicog_total')->default(0);
            $table->string('minicog_valoracion')->nullable();
        });
    }

public function down()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            $table->dropColumn([
                'minicog_palabra_mesa', 'minicog_palabra_llave', 'minicog_palabra_libro',
                'minicog_reloj_puntaje', 'minicog_total', 'minicog_valoracion'
            ]);
        });
    }
};
