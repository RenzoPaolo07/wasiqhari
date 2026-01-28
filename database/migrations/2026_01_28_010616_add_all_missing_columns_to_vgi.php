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
            
            // --- MEDICACIÓN Y SOCIAL ---
            if (!Schema::hasColumn('vgi_evaluaciones', 'toma_medicacion')) $table->boolean('toma_medicacion')->default(0)->nullable();
            if (!Schema::hasColumn('vgi_evaluaciones', 'num_medicamentos')) $table->integer('num_medicamentos')->default(0)->nullable();
            if (!Schema::hasColumn('vgi_evaluaciones', 'realiza_ejercicio')) $table->boolean('realiza_ejercicio')->default(0)->nullable();
            if (!Schema::hasColumn('vgi_evaluaciones', 'acude_centro_social')) $table->boolean('acude_centro_social')->default(0)->nullable();
            if (!Schema::hasColumn('vgi_evaluaciones', 'actividad_centro_social')) $table->string('actividad_centro_social')->nullable();

            // --- GIJÓN (Desglose) ---
            if (!Schema::hasColumn('vgi_evaluaciones', 'gijon_familiar')) $table->integer('gijon_familiar')->default(0)->nullable();
            if (!Schema::hasColumn('vgi_evaluaciones', 'gijon_economica')) $table->integer('gijon_economica')->default(0)->nullable();
            if (!Schema::hasColumn('vgi_evaluaciones', 'gijon_vivienda')) $table->integer('gijon_vivienda')->default(0)->nullable();
            if (!Schema::hasColumn('vgi_evaluaciones', 'gijon_relaciones')) $table->integer('gijon_relaciones')->default(0)->nullable();
            if (!Schema::hasColumn('vgi_evaluaciones', 'gijon_apoyo')) $table->integer('gijon_apoyo')->default(0)->nullable();

            // --- BARTHEL (Desglose completo) ---
            $barthelFields = ['barthel_comer', 'barthel_aseo', 'barthel_vestirse', 'barthel_banarse', 'barthel_heces', 'barthel_orina', 'barthel_retrete', 'barthel_traslado', 'barthel_deambulacion', 'barthel_escaleras'];
            foreach ($barthelFields as $field) {
                if (!Schema::hasColumn('vgi_evaluaciones', $field)) $table->integer($field)->default(0)->nullable();
            }

            // --- LAWTON (Desglose completo) ---
            $lawtonFields = ['lawton_telefono', 'lawton_comida', 'lawton_medicacion', 'lawton_casa', 'lawton_compras', 'lawton_ropa', 'lawton_transporte', 'lawton_finanzas'];
            foreach ($lawtonFields as $field) {
                if (!Schema::hasColumn('vgi_evaluaciones', $field)) $table->integer($field)->default(0)->nullable();
            }

            // --- PFEIFFER (Desglose) ---
            $pfeifferFields = ['pf_fecha', 'pf_dia', 'pf_lugar', 'pf_telefono', 'pf_direccion', 'pf_edad', 'pf_nacer', 'pf_pres_act', 'pf_pres_ant', 'pf_madre', 'pf_resta'];
            foreach ($pfeifferFields as $field) {
                if (!Schema::hasColumn('vgi_evaluaciones', $field)) $table->boolean($field)->default(0)->nullable();
            }

            // --- RUDAS ---
            $rudasFields = ['rudas_orientacion', 'rudas_praxis', 'rudas_visoconstructiva', 'rudas_juicio', 'rudas_lenguaje', 'rudas_memoria'];
            foreach ($rudasFields as $field) {
                if (!Schema::hasColumn('vgi_evaluaciones', $field)) $table->integer($field)->default(0)->nullable();
            }

            // --- MMSE ---
            $mmseFields = ['mmse_mem_intentos', 'mmse_resta_93', 'mmse_resta_86', 'mmse_resta_79', 'mmse_resta_72', 'mmse_resta_65', 'mmse_mundo_o', 'mmse_mundo_d', 'mmse_mundo_n', 'mmse_mundo_u', 'mmse_mundo_m', 'mmse_nom_lapiz', 'mmse_nom_reloj', 'mmse_repeticion', 'mmse_orden_mano', 'mmse_orden_doblar', 'mmse_orden_suelo', 'mmse_leer', 'mmse_escribir', 'mmse_copiar', 'mmse_tiempo_anio', 'mmse_tiempo_estacion', 'mmse_tiempo_fecha', 'mmse_tiempo_dia', 'mmse_tiempo_mes', 'mmse_lugar_pais', 'mmse_lugar_dep', 'mmse_lugar_dist', 'mmse_lugar_hosp', 'mmse_lugar_piso', 'mmse_mem_arbol', 'mmse_mem_puente', 'mmse_mem_farol'];
            foreach ($mmseFields as $field) {
                if (!Schema::hasColumn('vgi_evaluaciones', $field)) $table->integer($field)->default(0)->nullable();
            }

            // --- MINI-COG ---
            $minicogFields = ['minicog_palabra_mesa', 'minicog_palabra_llave', 'minicog_palabra_libro', 'minicog_reloj_puntaje'];
            foreach ($minicogFields as $field) {
                if (!Schema::hasColumn('vgi_evaluaciones', $field)) $table->integer($field)->default(0)->nullable();
            }
            if (!Schema::hasColumn('vgi_evaluaciones', 'minicog_reloj_imagen_hidden')) $table->string('minicog_reloj_imagen_hidden')->nullable(); // Ojo con este auxiliar

            // --- GDS / YESAVAGE (Desglose) ---
            $gdsFields = ['gds_insatisfecho', 'gds_impotente', 'gds_memoria', 'gds_desgano', 'ysg_1', 'ysg_2', 'ysg_3', 'ysg_4', 'ysg_5', 'ysg_6', 'ysg_7', 'ysg_8', 'ysg_9', 'ysg_10', 'ysg_11', 'ysg_12', 'ysg_13', 'ysg_14', 'ysg_15'];
            foreach ($gdsFields as $field) {
                if (!Schema::hasColumn('vgi_evaluaciones', $field)) $table->integer($field)->default(0)->nullable();
            }

            // --- MNA (Nutrición) ---
            $mnaFields = ['mna_a', 'mna_b', 'mna_c', 'mna_d', 'mna_e', 'mna_f'];
            foreach ($mnaFields as $field) {
                if (!Schema::hasColumn('vgi_evaluaciones', $field)) $table->integer($field)->default(0)->nullable();
            }

            // --- SARC-F ---
            $sarcfFields = ['sarcf_fuerza', 'sarcf_asistencia', 'sarcf_levantarse', 'sarcf_escaleras', 'sarcf_caidas'];
            foreach ($sarcfFields as $field) {
                if (!Schema::hasColumn('vgi_evaluaciones', $field)) $table->integer($field)->default(0)->nullable();
            }

            // --- FRAIL ---
            $frailFields = ['frail_fatiga', 'frail_resistencia', 'frail_ambulacion', 'frail_enfermedades', 'frail_peso', 'frail_valoracion_texto'];
            foreach ($frailFields as $field) {
                if ($field == 'frail_valoracion_texto') {
                    if (!Schema::hasColumn('vgi_evaluaciones', $field)) $table->string($field)->nullable();
                } else {
                    if (!Schema::hasColumn('vgi_evaluaciones', $field)) $table->integer($field)->default(0)->nullable();
                }
            }

            // --- MARCHA / TUG / CFS / SPPB ---
            if (!Schema::hasColumn('vgi_evaluaciones', 'marcha_segundos')) $table->decimal('marcha_segundos', 8, 2)->nullable();
            if (!Schema::hasColumn('vgi_evaluaciones', 'marcha_velocidad')) $table->decimal('marcha_velocidad', 8, 2)->nullable();
            if (!Schema::hasColumn('vgi_evaluaciones', 'tug_segundos')) $table->decimal('tug_segundos', 8, 2)->nullable();
            
            if (!Schema::hasColumn('vgi_evaluaciones', 'cfs_puntaje')) $table->integer('cfs_puntaje')->default(0)->nullable();
            if (!Schema::hasColumn('vgi_evaluaciones', 'cfs_valoracion')) $table->string('cfs_valoracion')->nullable();

            $sppbFields = ['sppb_bal_lado', 'sppb_bal_semi', 'sppb_bal_tandem_tiempo', 'sppb_bal_tandem_puntos', 'sppb_score_balance', 'sppb_marcha_t1', 'sppb_marcha_t2', 'sppb_marcha_puntos', 'sppb_score_marcha', 'sppb_silla_pre', 'sppb_silla_tiempo', 'sppb_silla_puntos', 'sppb_score_silla', 'sppb_valoracion'];
            foreach ($sppbFields as $field) {
                // Tiempos como decimales, puntos como enteros, textos como strings
                if (str_contains($field, 'tiempo') || str_contains($field, '_t1') || str_contains($field, '_t2')) {
                    if (!Schema::hasColumn('vgi_evaluaciones', $field)) $table->decimal($field, 8, 2)->nullable();
                } elseif (str_contains($field, 'valoracion') || str_contains($field, 'lado') || str_contains($field, 'semi') || str_contains($field, 'pre')) {
                    // Algunos campos SPPB tienen valores como 'rehusa' (string)
                    if (!Schema::hasColumn('vgi_evaluaciones', $field)) $table->string($field)->nullable();
                } else {
                    if (!Schema::hasColumn('vgi_evaluaciones', $field)) $table->integer($field)->default(0)->nullable();
                }
            }

            // --- PLAN DE CUIDADOS ---
            if (!Schema::hasColumn('vgi_evaluaciones', 'plan_cuidados_final')) $table->text('plan_cuidados_final')->nullable();
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
