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
            // Usamos 'after' para ordenarlo un poco, si es posible
            if (!Schema::hasColumn('vgi_evaluaciones', 'gijon_familiar')) {
                $table->integer('gijon_familiar')->default(0);
                $table->integer('gijon_economica')->default(0);
                $table->integer('gijon_vivienda')->default(0);
                $table->integer('gijon_relaciones')->default(0);
                $table->integer('gijon_apoyo')->default(0);
                $table->integer('gijon_total')->default(0);
                $table->string('gijon_valoracion')->nullable();
            }
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
