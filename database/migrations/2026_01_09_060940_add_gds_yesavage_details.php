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
            // GDS-4
            $table->boolean('gds_insatisfecho')->default(0);
            $table->boolean('gds_impotente')->default(0);
            $table->boolean('gds_memoria')->default(0);
            $table->boolean('gds_desgano')->default(0);
            $table->integer('gds_total')->default(0);

            // Yesavage (Detalle de las 15 preguntas)
            // Usaremos nombres cortos: ysg_1, ysg_2... para no hacer la tabla gigante
            for ($i = 1; $i <= 15; $i++) {
                $table->boolean("ysg_{$i}")->default(0);
            }
            // El 'yesavage_total' ya existía de migraciones pasadas, así que no lo creamos de nuevo.
        });
    }

public function down()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            $table->dropColumn(['gds_insatisfecho', 'gds_impotente', 'gds_memoria', 'gds_desgano', 'gds_total']);
            for ($i = 1; $i <= 15; $i++) {
                $table->dropColumn("ysg_{$i}");
            }
        });
    }
};
