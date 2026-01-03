<?php

namespace App\Http\Controllers;

use App\Models\AdultoMayor;
use App\Models\VgiEvaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Retornamos la vista (que crearemos en el Paso 3)
        // Enviamos los datos del adulto y su evaluación (si tiene)
        return view('dashboard.vgi.form', compact('adulto', 'vgi'));
    }

    // Guarda una NUEVA evaluación (Cada vez que guardan, es una foto médica nueva en el tiempo)
    public function store(Request $request, $adultoId)
    {
        // 1. Obtener todos los datos
        $data = $request->except(['_token']);
        
        // 2. Definir campos que deben ser 0 si vienen vacíos (Scores y Checkboxes)
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
            // Comorbilidades (Booleanos)
            'tiene_hta', 'tiene_diabetes', 'tiene_epoc', 'tiene_epid', 'tiene_fa', 'tiene_icc',
            'tiene_coronaria', 'tiene_demencia', 'tiene_hipotiroidismo', 'tiene_depresion',
            'tiene_osteoporosis', 'tiene_artrosis', 'tiene_parkinson', 'tiene_cancer',
            // AGREGA ESTOS NUEVOS AL FINAL DE LA LISTA:
            'sindrome_caidas', 'sindrome_incontinencia', 'sindrome_delirio',
            'problema_dental', 'usa_protesis', 'vision_conservada', 'audicion_conservada',
            'problema_estrenimiento', 'problema_insomnio', 'problema_nocturia',
            'realiza_ejercicio', 'acude_centro_social',
            'gijon_familiar', 'gijon_economica', 'gijon_vivienda', 
            'gijon_relaciones', 'gijon_apoyo', 'gijon_total',
            'toma_medicacion', 'num_medicamentos'
        ];

        foreach ($camposNumericos as $campo) {
            if (!isset($data[$campo]) || $data[$campo] === null) {
                $data[$campo] = 0; // Asigna 0 si está vacío
            }
        }

        // 3. Asignar IDs y Fechas
        $data['adulto_mayor_id'] = $adultoId;
        $data['user_id'] = Auth::id();
        
        // Si no mandan fecha, ponemos la de hoy
        if (empty($data['fecha_evaluacion'])) {
            $data['fecha_evaluacion'] = now();
        }

        // 4. Guardar
        VgiEvaluacion::create($data);

        return redirect()->route('adultos.vgi', $adultoId)
                         ->with('success', 'Historia Clínica VGI guardada correctamente.');
    }
}