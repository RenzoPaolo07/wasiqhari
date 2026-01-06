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
            // Aseguramos que existan
            if (!Schema::hasColumn('vgi_evaluaciones', 'lawton_total')) $table->integer('lawton_total')->default(0);
            if (!Schema::hasColumn('vgi_evaluaciones', 'lawton_valoracion')) $table->string('lawton_valoracion')->nullable();
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
