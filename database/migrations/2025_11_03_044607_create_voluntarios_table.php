<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('voluntarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('telefono', 15)->nullable();
            $table->text('direccion')->nullable();
            $table->string('distrito', 50)->nullable();
            $table->text('habilidades')->nullable();
            $table->enum('disponibilidad', [
                'Mañanas', 
                'Tardes', 
                'Noches', 
                'Fines de semana', 
                'Flexible'
            ]);
            $table->text('zona_cobertura')->nullable();
            $table->enum('estado', ['Activo', 'Inactivo', 'Suspendido'])->default('Activo');
            $table->date('fecha_registro')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('voluntarios');
    }
};