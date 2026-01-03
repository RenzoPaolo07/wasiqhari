<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            // Nuevas enfermedades del documento VGI
            $table->boolean('tiene_epid')->default(0); // Pulmonar intersticial difusa
            $table->boolean('tiene_fa')->default(0); // Fibrilación auricular
            $table->boolean('tiene_coronaria')->default(0); // Coronaria crónica
            $table->boolean('tiene_hipotiroidismo')->default(0);
            $table->boolean('tiene_depresion')->default(0); // Depresión en tto
            $table->boolean('tiene_osteoporosis')->default(0);
            $table->boolean('tiene_parkinson')->default(0);
            $table->boolean('tiene_cancer')->default(0);
            $table->string('cancer_info')->nullable(); // Órgano y estadio
        });
    }

    public function down()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            $table->dropColumn([
                'tiene_epid', 'tiene_fa', 'tiene_coronaria', 'tiene_hipotiroidismo',
                'tiene_depresion', 'tiene_osteoporosis', 'tiene_parkinson', 
                'tiene_cancer', 'cancer_info'
            ]);
        });
    }
};