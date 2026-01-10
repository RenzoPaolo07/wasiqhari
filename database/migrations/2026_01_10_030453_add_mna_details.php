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
            // Detalle MNA (Letras A-F)
            $table->integer('mna_a')->default(0); // Apetito
            $table->integer('mna_b')->default(0); // Peso
            $table->integer('mna_c')->default(0); // Movilidad
            $table->integer('mna_d')->default(0); // Estrés
            $table->integer('mna_e')->default(0); // Demencia/Depresión
            $table->integer('mna_f')->default(0); // IMC
            // mna_puntaje ya existe de la primera migración, lo usaremos como total.
        });
    }

public function down()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            $table->dropColumn(['mna_a', 'mna_b', 'mna_c', 'mna_d', 'mna_e', 'mna_f']);
        });
    }
};
