<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('adultos_mayores', function (Blueprint $table) {
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
            ])->change();
        });
    }

    public function down()
    {
        Schema::table('adultos_mayores', function (Blueprint $table) {
            $table->enum('actividad_calle', [
                'Vende dulces', 
                'Pide limosna', 
                'Recicla', 
                'Vende artesanías', 
                'Vende periódicos',
                'Otro'
            ])->change();
        });
    }
};