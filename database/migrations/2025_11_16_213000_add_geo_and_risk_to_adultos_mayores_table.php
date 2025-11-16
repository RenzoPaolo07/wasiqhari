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
            // Columna para el "Caso Crítico" que busca tu controlador
            $table->enum('nivel_riesgo', ['Bajo', 'Medio', 'Alto'])
                  ->default('Bajo')
                  ->after('observaciones'); // La pone después de 'observaciones'

            // Columnas para el MAPA (Latitud y Longitud)
            // Usamos decimal para alta precisión. Nullable por si no tienes el dato.
            $table->decimal('lat', 10, 7)->nullable()->after('nivel_riesgo');
            $table->decimal('lon', 10, 7)->nullable()->after('lat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adultos_mayores', function (Blueprint $table) {
            $table->dropColumn('nivel_riesgo');
            $table->dropColumn('lat');
            $table->dropColumn('lon');
        });
    }
};