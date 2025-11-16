<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('adultos_mayores', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_registro');
            $table->string('dni', 8)->nullable()->unique();
            $table->string('apellidos', 100);
            $table->string('nombres', 100);
            $table->enum('sexo', ['M', 'F']);
            $table->date('fecha_nacimiento');
            $table->integer('edad');
            $table->text('direccion')->nullable();
            $table->string('distrito', 50);
            $table->string('zona_ubicacion', 100);
            $table->enum('lee_escribe', ['Si', 'No', 'Poco']);
            $table->enum('nivel_estudio', ['Ninguno', 'Primaria', 'Secundaria']);
            $table->enum('apoyo_familiar', ['Ninguno', 'Poco', 'Ocasional']);
            $table->enum('estado_abandono', ['Total', 'Parcial', 'Situación Calle']);
            $table->string('telefono', 9)->nullable();
            $table->enum('estado_salud', ['Bueno', 'Regular', 'Malo', 'Critico']);
            $table->enum('actividad_calle', [
                'Vende dulces', 
                'Pide limosna', 
                'Recicla', 
                'Vende artesanías', 
                'Vende periódicos',
                'Vende frutas',
                'Vende flores',
                'Vende empanadas',
                'Vende verduras',
                'Otro'
            ]);
            $table->text('necesidades')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('adultos_mayores');
    }
};