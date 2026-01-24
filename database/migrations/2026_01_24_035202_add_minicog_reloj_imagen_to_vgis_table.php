<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    // CAMBIA 'vgis' POR EL NOMBRE REAL DE TU TABLA
    Schema::table('vgi_evaluaciones', function (Blueprint $table) { 
        // Nota: Si 'minicog_valoracion' no existe, ponle after('id') o quita el ->after(...)
        $table->string('minicog_reloj_imagen')->nullable()->after('minicog_valoracion');
    });
}

public function down(): void
{
    // AQUÍ TAMBIÉN CAMBIA EL NOMBRE
    Schema::table('vgi_evaluaciones', function (Blueprint $table) {
        $table->dropColumn('minicog_reloj_imagen');
    });
}
};