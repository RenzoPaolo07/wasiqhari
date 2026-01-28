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
            // Agregamos las columnas que podrían faltar. 
            // Si alguna ya existe, Laravel podría quejarse, pero mejor prevenir.

            if (!Schema::hasColumn('vgi_evaluaciones', 'sindrome_caidas')) {
                $table->boolean('sindrome_caidas')->default(0)->nullable();
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'sindrome_incontinencia')) {
                $table->boolean('sindrome_incontinencia')->default(0)->nullable();
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'sindrome_delirio')) {
                $table->boolean('sindrome_delirio')->default(0)->nullable();
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'problema_dental')) {
                $table->boolean('problema_dental')->default(0)->nullable();
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'usa_protesis')) {
                $table->boolean('usa_protesis')->default(0)->nullable();
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'vision_conservada')) {
                $table->boolean('vision_conservada')->default(0)->nullable();
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'audicion_conservada')) {
                $table->boolean('audicion_conservada')->default(0)->nullable();
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'problema_estrenimiento')) {
                $table->boolean('problema_estrenimiento')->default(0)->nullable();
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'problema_insomnio')) {
                $table->boolean('problema_insomnio')->default(0)->nullable();
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'problema_nocturia')) {
                $table->boolean('problema_nocturia')->default(0)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            //
        });
    }
};
