<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\AdultoMayor;
use App\Models\Voluntario;
use App\Models\Visita;
use Carbon\Carbon;

class AIController extends Controller
{
    public function chat(Request $request)
    {
        $mensaje = $request->input('message');
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

            $datosDelSistema = "
            EXPEDIENTE DEL PACIENTE: {$adultoEncontrado->nombres} {$adultoEncontrado->apellidos}
            Edad: {$adultoEncontrado->edad} años
            Nivel de Riesgo Actual: {$adultoEncontrado->nivel_riesgo}
            
            HISTORIAL CLÍNICO RECIENTE (Últimas visitas):
            {$historial}
            ";

            $systemInstruction = "Eres el Consultor Clínico de WasiQhari. 
            El usuario está preguntando específicamente por el paciente {$adultoEncontrado->nombres}.
            Usa el historial clínico proporcionado para responder. 
            Si ves alertas del 'DR. IA' en el historial, menciónalas como puntos de atención.
            Sé empático, profesional y basa tu respuesta SOLO en los datos mostrados.";

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
}