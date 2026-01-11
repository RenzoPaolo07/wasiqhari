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
            $table->integer('cfs_puntaje')->default(0); // 1 a 9
            $table->string('cfs_valoracion')->nullable(); // "Muy en forma", etc.
        });
    }

public function down()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            $table->dropColumn(['cfs_puntaje', 'cfs_valoracion']);
        });
    }
};
