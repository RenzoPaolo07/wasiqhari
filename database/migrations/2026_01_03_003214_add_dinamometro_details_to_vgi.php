<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            // Campos específicos para el Dinamómetro
            $table->string('mano_dominante')->nullable(); // Derecha / Izquierda
            $table->decimal('dinam_derecha_1', 5, 2)->nullable();
            $table->decimal('dinam_derecha_2', 5, 2)->nullable();
            $table->decimal('dinam_derecha_3', 5, 2)->nullable();
            $table->decimal('dinam_izquierda_1', 5, 2)->nullable();
            $table->decimal('dinam_izquierda_2', 5, 2)->nullable();
            $table->decimal('dinam_izquierda_3', 5, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            $table->dropColumn([
                'mano_dominante', 
                'dinam_derecha_1', 'dinam_derecha_2', 'dinam_derecha_3',
                'dinam_izquierda_1', 'dinam_izquierda_2', 'dinam_izquierda_3'
            ]);
        });
    }
};