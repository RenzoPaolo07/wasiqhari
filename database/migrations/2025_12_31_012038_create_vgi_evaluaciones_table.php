<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vgi_evaluaciones', function (Blueprint $table) {
            $table->id();
            
            // RELACIONES
            $table->foreignId('adulto_mayor_id')->constrained('adultos_mayores')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // Profesional que llenó la ficha
            
            // DATOS GENERALES DE LA VISITA
            $table->dateTime('fecha_evaluacion');
            $table->string('hcl')->nullable(); // Número de Historia Clínica
            
            // I. DATOS SOCIODEMOGRÁFICOS Y CUIDADOR
            $table->string('nombre_cuidador')->nullable();
            $table->string('parentesco_cuidador')->nullable();
            $table->string('dni_cuidador')->nullable();
            $table->boolean('cuidador_colapso')->default(0); 

            // VALORACIÓN SOCIAL (ESCALA GIJÓN)
            $table->integer('gijon_familiar')->nullable(); 
            $table->integer('gijon_economica')->nullable(); 
            $table->integer('gijon_vivienda')->nullable(); 
            $table->integer('gijon_relaciones')->nullable(); 
            $table->integer('gijon_apoyo')->nullable(); 
            $table->integer('gijon_total')->nullable(); 
            $table->string('gijon_valoracion')->nullable(); 

            // II. VALORACIÓN CLÍNICA / BIOMÉDICA
            // Antropometría
            $table->decimal('peso', 5, 2)->nullable();
            $table->decimal('talla', 5, 2)->nullable();
            $table->decimal('imc', 5, 2)->nullable();
            $table->decimal('perimetro_abdominal', 5, 2)->nullable();
            $table->decimal('perimetro_pantorrilla', 5, 2)->nullable();
            $table->decimal('dinamometro_mano', 5, 2)->nullable();
            
            // Comorbilidades
            $table->boolean('tiene_hta')->default(0);
            $table->boolean('tiene_diabetes')->default(0);
            $table->boolean('tiene_epoc')->default(0); 
            $table->boolean('tiene_icc')->default(0); 
            $table->boolean('tiene_demencia')->default(0);
            $table->boolean('tiene_artrosis')->default(0);
            $table->boolean('tiene_audicion_baja')->default(0); 
            $table->boolean('tiene_vision_baja')->default(0); 
            $table->boolean('tiene_incontinencia')->default(0); 
            $table->boolean('caidas_recientes')->default(0); 
            $table->text('otras_enfermedades')->nullable();

            // Laboratorios
            $table->string('lab_hemoglobina')->nullable();
            $table->string('lab_glucosa')->nullable();
            $table->string('lab_creatinina')->nullable();
            $table->string('lab_albumina')->nullable();
            $table->string('lab_tsh')->nullable();
            $table->string('lab_b12')->nullable();

            // III. VALORACIÓN FUNCIONAL
            // BARTHEL
            $table->integer('barthel_comer')->default(0);
            $table->integer('barthel_lavarse')->default(0);
            $table->integer('barthel_vestirse')->default(0);
            $table->integer('barthel_arreglarse')->default(0);
            $table->integer('barthel_deposicion')->default(0);
            $table->integer('barthel_miccion')->default(0);
            $table->integer('barthel_ir_bano')->default(0);
            $table->integer('barthel_traslado')->default(0);
            $table->integer('barthel_deambulacion')->default(0);
            $table->integer('barthel_escaleras')->default(0);
            $table->integer('barthel_total')->default(0);
            $table->string('barthel_valoracion')->nullable(); 

            // LAWTON & BRODY
            $table->integer('lawton_telefono')->default(0);
            $table->integer('lawton_compras')->default(0);
            $table->integer('lawton_comida')->default(0);
            $table->integer('lawton_casa')->default(0);
            $table->integer('lawton_ropa')->default(0);
            $table->integer('lawton_transporte')->default(0);
            $table->integer('lawton_medicacion')->default(0);
            $table->integer('lawton_finanzas')->default(0);
            $table->integer('lawton_total')->default(0);

            // IV. VALORACIÓN MENTAL / COGNITIVA
            // PFEIFFER
            $table->integer('pfeiffer_errores')->nullable();
            $table->string('pfeiffer_valoracion')->nullable(); 

            // MINIMENTAL (MMSE)
            $table->integer('mmse_orientacion')->default(0);
            $table->integer('mmse_memoria')->default(0);
            $table->integer('mmse_atencion')->default(0);
            $table->integer('mmse_recuerdo')->default(0);
            $table->integer('mmse_lenguaje')->default(0);
            $table->integer('mmse_total')->default(0);

            // TEST DEL RELOJ
            $table->boolean('test_reloj_anomalo')->default(0);

            // YESAVAGE
            $table->integer('yesavage_total')->nullable();
            $table->string('yesavage_valoracion')->nullable(); 

            // V. VALORACIÓN FÍSICA / NUTRICIONAL
            // MNA
            $table->integer('mna_puntaje')->nullable();
            $table->string('mna_valoracion')->nullable(); 

            // FRAIL
            $table->integer('frail_puntaje')->nullable();
            $table->string('frail_valoracion')->nullable(); 

            // SARC-F
            $table->integer('sarcf_puntaje')->nullable();

            // SPPB
            $table->integer('sppb_balance')->default(0);
            $table->integer('sppb_velocidad')->default(0);
            $table->integer('sppb_silla')->default(0);
            $table->integer('sppb_total')->default(0);

            // VI. PLAN DE TRABAJO
            $table->text('plan_cuidados')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vgi_evaluaciones');
    }
};