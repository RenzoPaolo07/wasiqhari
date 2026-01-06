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
        // Aseguramos los 10 parámetros exactos de Barthel
        // Usamos decimal o integer. Integer es mejor para 0, 5, 10, 15.
        if (!Schema::hasColumn('vgi_evaluaciones', 'barthel_aseo')) $table->integer('barthel_aseo')->default(0); // Aseo personal
        if (!Schema::hasColumn('vgi_evaluaciones', 'barthel_banarse')) $table->integer('barthel_banarse')->default(0); // Baño/Ducha
        if (!Schema::hasColumn('vgi_evaluaciones', 'barthel_heces')) $table->integer('barthel_heces')->default(0); // Control Heces
        if (!Schema::hasColumn('vgi_evaluaciones', 'barthel_orina')) $table->integer('barthel_orina')->default(0); // Control Orina
        if (!Schema::hasColumn('vgi_evaluaciones', 'barthel_retrete')) $table->integer('barthel_retrete')->default(0); // Uso retrete
        // Los otros (comer, vestirse, traslado, deambulacion, escaleras) ya solían estar, pero si no, agrégalos aquí.
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
