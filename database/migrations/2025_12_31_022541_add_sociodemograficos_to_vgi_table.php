<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            // Campos que faltaban según el documento VGI
            $table->string('lugar_nacimiento')->nullable();
            $table->string('procedencia')->nullable();
            $table->string('religion')->nullable();
            $table->string('ocupacion')->nullable();
            $table->string('grupo_sanguineo')->nullable(); // Incluye FRH
            $table->string('telefonos_referencia')->nullable(); // Casa/Celulares
            
            // Instrucción detallada
            $table->string('grado_instruccion')->nullable(); // "Primaria Incompleta", etc.
            $table->integer('anios_estudio')->nullable();
            
            // Estado Civil
            $table->string('estado_civil')->nullable();

            // Cuidador (Detalles extra)
            $table->boolean('cuidador_aplica')->default(0); // Si/No
            $table->string('cuidador_sexo')->nullable();
            $table->integer('cuidador_edad')->nullable();
        });
    }

    public function down()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            // Por si hay que revertir
            $table->dropColumn([
                'lugar_nacimiento', 'procedencia', 'religion', 'ocupacion', 
                'grupo_sanguineo', 'telefonos_referencia', 'grado_instruccion', 
                'anios_estudio', 'estado_civil', 'cuidador_aplica', 
                'cuidador_sexo', 'cuidador_edad'
            ]);
        });
    }
};