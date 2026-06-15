<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('adultos_mayores', function (Blueprint $table) {
            $table->string('dispositivo_id')->nullable()->unique();
            $table->boolean('alertas_activas')->default(true);
            $table->json('ultima_lectura_iot')->nullable();
            $table->timestamp('ultimo_contacto_iot')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adultos_mayores', function (Blueprint $table) {
            //
        });
    }
};
