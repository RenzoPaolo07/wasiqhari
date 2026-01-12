<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            
            // 1. Balance
            if (!Schema::hasColumn('vgi_evaluaciones', 'sppb_bal_lado')) {
                $table->boolean('sppb_bal_lado')->default(0);
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'sppb_bal_semi')) {
                $table->boolean('sppb_bal_semi')->default(0);
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'sppb_bal_tandem_tiempo')) {
                $table->decimal('sppb_bal_tandem_tiempo', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'sppb_score_balance')) {
                $table->integer('sppb_score_balance')->default(0);
            }

            // 2. Velocidad de Marcha
            if (!Schema::hasColumn('vgi_evaluaciones', 'sppb_marcha_t1')) {
                $table->decimal('sppb_marcha_t1', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'sppb_marcha_t2')) {
                $table->decimal('sppb_marcha_t2', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'sppb_score_marcha')) {
                $table->integer('sppb_score_marcha')->default(0);
            }

            // 3. Levantarse de silla
            if (!Schema::hasColumn('vgi_evaluaciones', 'sppb_silla_pre')) {
                $table->boolean('sppb_silla_pre')->default(0);
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'sppb_silla_tiempo')) {
                $table->decimal('sppb_silla_tiempo', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'sppb_score_silla')) {
                $table->integer('sppb_score_silla')->default(0);
            }

            // Totales
            if (!Schema::hasColumn('vgi_evaluaciones', 'sppb_total')) {
                $table->integer('sppb_total')->default(0);
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'sppb_valoracion')) {
                $table->string('sppb_valoracion')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('vgi_evaluaciones', function (Blueprint $table) {
            // Aquí no necesitamos IFs porque dropColumn no suele fallar igual
            $columns = [
                'sppb_bal_lado', 'sppb_bal_semi', 'sppb_bal_tandem_tiempo', 'sppb_score_balance',
                'sppb_marcha_t1', 'sppb_marcha_t2', 'sppb_score_marcha',
                'sppb_silla_pre', 'sppb_silla_tiempo', 'sppb_score_silla',
                'sppb_total', 'sppb_valoracion'
            ];
            $table->dropColumn($columns);
        });
    }
};