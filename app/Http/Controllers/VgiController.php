<?php

namespace App\Http\Controllers;

use App\Models\AdultoMayor;
use App\Models\VgiEvaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // <--- 1. IMPORTANTE PARA SUBIR FOTOS
use Barryvdh\DomPDF\Facade\Pdf;

class VgiController extends Controller
{
    // Muestra la historia clínica (Si existe una previa, la carga)
    public function show($adultoId)
    {
        $adulto = AdultoMayor::findOrFail($adultoId);
        
        // Buscamos la última evaluación VGI realizada
        $vgi = VgiEvaluacion::where('adulto_mayor_id', $adultoId)
                            ->latest('fecha_evaluacion')
                            ->first();

        return view('dashboard.vgi.form', compact('adulto', 'vgi'));
    }

    // Guarda una NUEVA evaluación
    public function store(Request $request, $adultoId)
    {
        // 1. ACTUALIZAR DATOS DEL ADULTO MAYOR (Opcional, pero recomendado)
        // Si cambiaste algo en "Datos Personales", actualizamos la ficha del abuelito
        $adulto = AdultoMayor::findOrFail($adultoId);
        $adulto->update($request->only([
            'fecha_nacimiento', 
            'lugar_nacimiento', 
            'grupo_sanguineo', 
            'religion', 
            'ocupacion', 
            'telefonos_referencia', 
            'grado_instruccion', // Asegúrate que tu tabla adultos tenga estos campos
            'estado_civil',
            'anios_estudio',      // <--- Agregado
            'vive_con',           // <--- Agregado (El del error actual)
            'visitas_familiares', // <--- Agregado
            'actividades_sociales'// <--- Agregado
        ]));

        // 2. PREPARAR DATOS PARA VGI (Limpieza)
        // Quitamos todo lo que NO pertenece a la tabla 'vgi_evaluaciones'
        $data = $request->except([
            '_token', 
            'generate_pdf', 
            'minicog_reloj_imagen', 
            // Datos que no existen en la tabla vgi_evaluaciones
            'url', // <--- ¡AGREGA ESTO AQUÍ! (Esto es lo que falla)
            'hora_evaluacion', 
            'fecha_nacimiento',
            'lugar_nacimiento',
            'grupo_sanguineo',
            'procedencia',       // Ojo con este
            'religion',
            'ocupacion',
            'telefonos_referencia',
            'grado_instruccion',
            'anios_estudio',
            'estado_civil',
            'vive_con',           // <--- El culpable actual
            'visitas_familiares',
            'actividades_sociales',
            'nombres', 
            'apellidos', 
            'dni'
        ]);
        
        // 3. Definir campos numéricos que deben ser 0 si vienen vacíos
        $camposNumericos = [
            'cuidador_aplica', 'peso', 'talla', 'imc', 'perimetro_abdominal', 'perimetro_pantorrilla',
            'dinam_derecha_1', 'dinam_derecha_2', 'dinam_derecha_3',
            'dinam_izquierda_1', 'dinam_izquierda_2', 'dinam_izquierda_3',
            'barthel_comer', 'barthel_lavarse', 'barthel_vestirse', 'barthel_arreglarse',
            'barthel_deposicion', 'barthel_miccion', 'barthel_ir_bano', 'barthel_traslado',
            'barthel_deambulacion', 'barthel_escaleras', 'barthel_total',
            'lawton_telefono', 'lawton_compras', 'lawton_comida', 'lawton_casa',
            'lawton_ropa', 'lawton_transporte', 'lawton_medicacion', 'lawton_finanzas', 'lawton_total',
            'pfeiffer_errores', 'yesavage_total', 'mna_puntaje', 'frail_puntaje', 'sarcf_puntaje',
            'sppb_balance', 'sppb_velocidad', 'sppb_silla', 'sppb_total',
            // Comorbilidades
            'tiene_hta', 'tiene_diabetes', 'tiene_epoc', 'tiene_epid', 'tiene_fa', 'tiene_icc',
            'tiene_coronaria', 'tiene_demencia', 'tiene_hipotiroidismo', 'tiene_depresion',
            'tiene_osteoporosis', 'tiene_artrosis', 'tiene_parkinson', 'tiene_cancer',
            // Síndromes
            'sindrome_caidas', 'sindrome_incontinencia', 'sindrome_delirio',
            'problema_dental', 'usa_protesis', 'vision_conservada', 'audicion_conservada',
            'problema_estrenimiento', 'problema_insomnio', 'problema_nocturia',
            'realiza_ejercicio', 'acude_centro_social',
            'gijon_familiar', 'gijon_economica', 'gijon_vivienda', 
            'gijon_relaciones', 'gijon_apoyo', 'gijon_total',
            'toma_medicacion', 'num_medicamentos',
            // Barthel Desglosado
            'barthel_comer', 'barthel_aseo', 'barthel_vestirse', 'barthel_banarse',
            'barthel_heces', 'barthel_orina', 'barthel_retrete', 'barthel_escaleras',
            'barthel_traslado', 'barthel_deambulacion', 'barthel_total',
            // Pfeiffer Desglosado
            'pf_fecha', 'pf_dia', 'pf_lugar', 'pf_telefono', 'pf_direccion', 
            'pf_edad', 'pf_nacer', 'pf_pres_act', 'pf_pres_ant', 'pf_madre', 'pf_resta',
            // Rudas
            'rudas_orientacion', 'rudas_praxis', 'rudas_visoconstructiva',
            'rudas_juicio', 'rudas_lenguaje', 'rudas_memoria', 'rudas_total',
            // MMSE
            'mmse_tiempo_anio', 'mmse_tiempo_estacion', 'mmse_tiempo_fecha', 'mmse_tiempo_dia', 'mmse_tiempo_mes',
            'mmse_lugar_pais', 'mmse_lugar_dep', 'mmse_lugar_dist', 'mmse_lugar_hosp', 'mmse_lugar_piso',
            'mmse_mem_arbol', 'mmse_mem_puente', 'mmse_mem_farol',
            'mmse_atencion_1', 'mmse_atencion_2', 'mmse_atencion_3', 'mmse_atencion_4', 'mmse_atencion_5',
            'mmse_rec_arbol', 'mmse_rec_puente', 'mmse_rec_farol',
            'mmse_nom_lapiz', 'mmse_nom_reloj', 'mmse_repeticion',
            'mmse_orden_mano', 'mmse_orden_doblar', 'mmse_orden_suelo',
            'mmse_leer', 'mmse_escribir', 'mmse_copiar', 'mmse_total_final',
            // MiniCog
            'minicog_palabra_mesa', 'minicog_palabra_llave', 'minicog_palabra_libro',
            'minicog_reloj_puntaje', 'minicog_total',
            // GDS / Yesavage
            'gds_insatisfecho', 'gds_impotente', 'gds_memoria', 'gds_desgano', 'gds_total',
            'ysg_1', 'ysg_2', 'ysg_3', 'ysg_4', 'ysg_5', 
            'ysg_6', 'ysg_7', 'ysg_8', 'ysg_9', 'ysg_10', 
            'ysg_11', 'ysg_12', 'ysg_13', 'ysg_14', 'ysg_15',
            // MNA / SARC-F
            'mna_a', 'mna_b', 'mna_c', 'mna_d', 'mna_e', 'mna_f',
            'sarcf_fuerza', 'sarcf_asistencia', 'sarcf_levantarse', 
            'sarcf_escaleras', 'sarcf_caidas', 'sarcf_total',
            // Marcha / TUG / Frail / CFS / SPPB
            'marcha_segundos', 'marcha_velocidad', 'tug_segundos',
            'frail_fatiga', 'frail_resistencia', 'frail_ambulacion', 'frail_enfermedades', 'frail_peso',
            'cfs_puntaje', 
            'sppb_bal_lado', 'sppb_bal_semi', 'sppb_bal_tandem_tiempo', 'sppb_score_balance',
            'sppb_marcha_t1', 'sppb_marcha_t2', 'sppb_score_marcha',
            'sppb_silla_pre', 'sppb_silla_tiempo', 'sppb_score_silla', 'sppb_total', 
            // MMSE Cálculo
            'mmse_resta_93', 'mmse_resta_86', 'mmse_resta_79', 'mmse_resta_72', 'mmse_resta_65',
            'mmse_mundo_o', 'mmse_mundo_d', 'mmse_mundo_n', 'mmse_mundo_u', 'mmse_mundo_m',
        ];

        foreach ($camposNumericos as $campo) {
            if (!isset($data[$campo]) || $data[$campo] === null) {
                $data[$campo] = 0; // Asigna 0 si está vacío
            }
        }

        // =========================================================
        // 4. PROCESAMIENTO DE IMAGEN (MINI-COG RELOJ)
        // =========================================================
        if ($request->hasFile('minicog_reloj_imagen')) {
            $request->validate([
                'minicog_reloj_imagen' => 'image|mimes:jpeg,png,jpg|max:5120',
            ]);
            $path = $request->file('minicog_reloj_imagen')->store('relojes', 'public');
            $data['minicog_reloj_imagen'] = $path;
        } elseif ($request->minicog_reloj_imagen_hidden) {
             // Si no subió foto nueva, pero ya existía una (caso editar), la mantenemos
             // No hacemos nada, el valor no se sobrescribe
        }

        // 5. Asignar IDs y Fechas
        $data['adulto_mayor_id'] = $adultoId;
        $data['user_id'] = Auth::id();
        
        if (empty($data['fecha_evaluacion'])) {
            $data['fecha_evaluacion'] = now();
        }

        // 6. GUARDAR EN BASE DE DATOS
        $vgi = VgiEvaluacion::updateOrCreate(
            ['adulto_mayor_id' => $adultoId, 'fecha_evaluacion' => $data['fecha_evaluacion']],
            $data
        );

        // 7. GENERAR PDF (Si se presionó el botón rojo)
        if ($request->input('generate_pdf') == '1') {
            $adulto = AdultoMayor::find($adultoId);
            $pdf = Pdf::loadView('dashboard.vgi.pdf_export', compact('adulto', 'vgi'));
            return $pdf->download('VGI_' . $adulto->dni . '_' . date('Ymd') . '.pdf');
        }

        // Si fue solo guardar normal
        return redirect()->route('adultos.vgi', $adultoId)
                         ->with('success', 'Historia Clínica guardada correctamente.');
    }
}