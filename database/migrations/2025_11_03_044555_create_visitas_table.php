<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('visitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adulto_id')->constrained('adultos_mayores')->onDelete('cascade');
            $table->foreignId('voluntario_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('fecha_visita');
            $table->enum('tipo_visita', [
                'Acompañamiento', 
                'Entrega de alimentos', 
                'Atención médica', 
                'Apoyo emocional', 
                'Otro'
            ]);
            $table->text('observaciones')->nullable();
            $table->enum('estado_emocional', [
                'Estable', 
                'Triste', 
                'Ansioso', 
                'Eufórico', 
                'Deprimido'
            ])->default('Estable');
            $table->enum('estado_fisico', [
                'Bueno', 
                'Regular', 
                'Malo', 
                'Crítico'
            ])->default('Regular');
            $table->text('necesidades_detectadas')->nullable();
            $table->boolean('emergencia')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('visitas');
    }
};