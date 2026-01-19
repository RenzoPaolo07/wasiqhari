<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\AdultoMayor;
use App\Models\Voluntario;
use App\Models\Visita;
use App\Models\VgiEvaluacion; // Importación del modelo VGI
use Carbon\Carbon;

class AIController extends Controller
{
    public function chat(Request $request)
    {
        $mensaje = $request->input('message');
        $adultoId = $request->input('adulto_id'); // Nuevo parámetro para contexto clínico
        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            return response()->json(['response' => 'Error: No se encontró la API KEY en el archivo .env']);
        }

        // --- 1. DETECTAR SI PREGUNTA POR ALGUIEN ESPECÍFICO ---
        $adultoEncontrado = null;
        // Obtenemos solo nombres para no cargar todo
        $adultos = AdultoMayor::all(['id', 'nombres', 'apellidos', 'edad', 'nivel_riesgo']);

        foreach ($adultos as $adulto) {
            // Buscamos si el nombre O el apellido aparece en el mensaje (ignorando mayúsculas)
            if (str_contains(strtolower($mensaje), strtolower($adulto->nombres)) || 
                str_contains(strtolower($mensaje), strtolower($adulto->apellidos))) {
                $adultoEncontrado = $adulto;
                break; // Encontramos uno, nos enfocamos en él
            }
        }

        // --- 2. CONSTRUIR EL CONTEXTO (Historial o General) ---
        
        if ($adultoEncontrado) {
            // === MODO CONSULTOR DE CASO ===
            
            // Traemos las últimas 5 visitas con el voluntario y la recomendación del Doctor IA
            $visitas = $adultoEncontrado->visitas()
                        ->with('voluntario.user')
                        ->latest('fecha_visita')
                        ->take(5)
                        ->get();
            
            $historial = "";
            if ($visitas->count() > 0) {
                foreach($visitas as $v) {
                    $fecha = $v->fecha_visita->format('d/m/Y');
                    $voluntario = $v->voluntario->user->name ?? 'Voluntario';
                    
                    // Aquí está la magia: Incluimos lo que opinó el Doctor IA
                    $alertaIA = $v->recomendacion_ia ? "[{$v->recomendacion_ia}]" : "";
                    
                    $historial .= "- {$fecha} ({$voluntario}): Físico {$v->estado_fisico}, Ánimo {$v->estado_emocional}. Obs: \"{$v->observaciones}\" {$alertaIA}\n";
                }
            } else {
                $historial = "No hay visitas registradas recientemente.";
            }

            // OBTENER CONTEXTO CLÍNICO VGI (si tenemos adulto_id)
            $contextoMedico = "";
            if ($adultoId) {
                $contextoMedico = $this->obtenerContextoClinico($adultoId);
            } else {
                // Si no hay adulto_id pero encontramos por nombre, usamos su ID
                $contextoMedico = $this->obtenerContextoClinico($adultoEncontrado->id);
            }

            $datosDelSistema = "
            EXPEDIENTE DEL PACIENTE: {$adultoEncontrado->nombres} {$adultoEncontrado->apellidos}
            Edad: {$adultoEncontrado->edad} años
            Nivel de Riesgo Actual: {$adultoEncontrado->nivel_riesgo}
            
            HISTORIAL CLÍNICO RECIENTE (Últimas visitas):
            {$historial}
            
            {$contextoMedico}
            ";

            $systemInstruction = "Eres el Consultor Clínico de WasiQhari. 
            El usuario está preguntando específicamente por el paciente {$adultoEncontrado->nombres}.
            Usa el historial clínico proporcionado para responder. 
            Si ves alertas del 'DR. IA' en el historial, menciónalas como puntos de atención.
            
            INFORMACIÓN CLÍNICA VGI: Usa esta información para dar consejos personalizados. 
            Si el paciente tiene riesgo de caídas (SPPB bajo o TUG alto), sugiere precauciones.
            Si tiene desnutrición (MNA bajo), sugiere dietas blandas o ricas en proteínas.
            
            Sé empático, profesional y basa tu respuesta en los datos mostrados.";

        } else {
            // === MODO ASISTENTE GENERAL (Estadísticas) ===
            
            $totalAdultos = AdultoMayor::count();
            $totalVoluntarios = Voluntario::count();
            $criticos = AdultoMayor::where('nivel_riesgo', 'Alto')->count();
            // Nombres de los casos críticos para referencia rápida
            $listaCriticos = AdultoMayor::where('nivel_riesgo', 'Alto')->pluck('nombres')->implode(', ');
            
            $datosDelSistema = "
            ESTADÍSTICAS GENERALES DE WASIQHARI:
            - Beneficiarios totales: {$totalAdultos}
            - Casos de ALTO RIESGO: {$criticos} (Nombres: {$listaCriticos})
            - Voluntarios registrados: {$totalVoluntarios}
            ";

            $systemInstruction = "Eres el asistente general de WasiQhari.
            Responde preguntas sobre el estado general del albergue usando estos datos.
            Si te preguntan por una persona específica, pídeles que escriban su nombre completo.";
        }

        $prompt = $systemInstruction . "\n\nDATOS DE CONTEXTO:\n" . $datosDelSistema . "\n\nPREGUNTA USUARIO: " . $mensaje . "\nRESPUESTA:";

        try {
            // Usamos el modelo 'gemini-flash-latest' que sabemos que funciona bien con tu llave nueva
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}";

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ]);

            $data = $response->json();

            if (isset($data['error'])) {
                return response()->json(['response' => "Error de IA: " . ($data['error']['message'] ?? 'Desconocido')]);
            }

            $texto = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Lo siento, no pude analizar los datos en este momento.';
            
            return response()->json(['response' => $texto]);

        } catch (\Exception $e) {
            Log::error('Error Chat IA: ' . $e->getMessage());
            return response()->json(['response' => 'Error de conexión con el servidor.'], 500);
        }
    }

    // NUEVA FUNCIÓN: Obtener contexto clínico del paciente
    private function obtenerContextoClinico($adultoId)
    {
        // 1. Buscar la última evaluación VGI del paciente
        $vgi = VgiEvaluacion::where('adulto_mayor_id', $adultoId)
                            ->latest('fecha_evaluacion')
                            ->first();

        // 2. Si no hay evaluación, devolvemos vacío
        if (!$vgi) {
            return "Nota: Este paciente aún no tiene una Valoración Geriátrica Integral (VGI) registrada.";
        }

        // 3. Si existe, construimos el "Resumen para el Doctor Gemini"
        $resumen = "INFORMACIÓN CLÍNICA DEL PACIENTE (Basada en VGI del {$vgi->fecha_evaluacion}):\n";
        
        // Agregamos datos clave
        $resumen .= "- Estado Nutricional (MNA): {$vgi->mna_puntaje} pts ({$vgi->mna_valoracion}). IMC: {$vgi->imc}.\n";
        $resumen .= "- Funcionalidad (Barthel): {$vgi->barthel_total} pts ({$vgi->barthel_valoracion}).\n";
        $resumen .= "- Estado Cognitivo (MMSE): {$vgi->mmse_total_final}/30. (Pfeiffer: {$vgi->pfeiffer_errores} errores).\n";
        $resumen .= "- Estado Afectivo (Yesavage): {$vgi->yesavage_total} pts.\n";
        $resumen .= "- Desempeño Físico (SPPB): {$vgi->sppb_total}/12 ({$vgi->sppb_valoracion}).\n";
        $resumen .= "- Riesgo de Caídas (TUG): {$vgi->tug_segundos} segundos.\n";
        $resumen .= "- Fragilidad (FRAIL): {$vgi->frail_valoracion_texto}.\n";
        
        // Agregamos comorbilidades importantes (solo si las tiene)
        $patologias = [];
        if ($vgi->tiene_hta) $patologias[] = "Hipertensión";
        if ($vgi->tiene_diabetes) $patologias[] = "Diabetes";
        if ($vgi->tiene_demencia) $patologias[] = "Demencia";
        if ($vgi->sindrome_caidas) $patologias[] = "Síndrome de Caídas Recientes";
        
        if (!empty($patologias)) {
            $resumen .= "- Patologías/Síndromes: " . implode(', ', $patologias) . ".\n";
        }

        $resumen .= "- Plan de Cuidados Actual: " . ($vgi->plan_cuidados ?? 'No especificado') . "\n";

        return $resumen;
    }
}