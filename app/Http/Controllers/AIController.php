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

        // --- 1. RECOPILAR DATOS (CONTEXTO) ---
        
        $totalAdultos = AdultoMayor::count();
        $totalVoluntarios = Voluntario::count();
        $voluntariosActivos = Voluntario::where('estado', 'Activo')->count();
        
        // Obtenemos casos críticos reales
        $criticos = AdultoMayor::where('nivel_riesgo', 'Alto')
                                ->get(['nombres', 'apellidos', 'distrito', 'edad'])
                                ->map(function($a) {
                                    return "{$a->nombres} {$a->apellidos} ({$a->edad} años, {$a->distrito})";
                                })->implode(', ');

        $visitasSemana = Visita::where('fecha_visita', '>=', Carbon::now()->subDays(7))->count();

        $datosDelSistema = "
        DATOS ACTUALES DEL SISTEMA:
        - Beneficiarios: {$totalAdultos}
        - Voluntarios: {$totalVoluntarios} ({$voluntariosActivos} activos)
        - Visitas esta semana: {$visitasSemana}
        - CASOS CRÍTICOS (Alto Riesgo): {$criticos}
        ";

        // --- 2. PREPARAR EL PROMPT ---

        $systemInstruction = "Eres el asistente experto de WasiQhari.
                   Usa los siguientes datos reales para responder.
                   Si preguntan por casos urgentes, menciona los de alto riesgo.
                   Responde breve y profesionalmente.
                   
                   {$datosDelSistema}";

        $prompt = $systemInstruction . "\n\nUsuario: " . $mensaje . "\nAsistente:";

        try {
            // --- 3. LLAMADA A LA API (CORREGIDA A GEMINI 2.0) ---
            // Usamos 'gemini-2.0-flash' que es el que aparece en tu lista de modelos disponibles
            $model = "gemini-2.0-flash";
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ]);

            $data = $response->json();

            // Si falla el 2.0, intentamos con el "latest" que suele ser un comodín seguro
            if (isset($data['error'])) {
                Log::warning('Falló Gemini 2.0, intentando con gemini-1.5-flash-latest...');
                
                $modelFallback = "gemini-1.5-flash-latest"; 
                $urlFallback = "https://generativelanguage.googleapis.com/v1beta/models/{$modelFallback}:generateContent?key={$apiKey}";
                
                $response = Http::withHeaders(['Content-Type' => 'application/json'])
                    ->post($urlFallback, [
                        'contents' => [['parts' => [['text' => $prompt]]]]
                    ]);
                $data = $response->json();
            }

            // Verificar errores finales
            if (isset($data['error'])) {
                $errorMsg = $data['error']['message'] ?? 'Error desconocido';
                return response()->json(['response' => "Error de IA: $errorMsg"]);
            }

            $texto = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($texto) {
                return response()->json(['response' => $texto]);
            } else {
                return response()->json(['response' => 'La IA no devolvió una respuesta válida.']);
            }

        } catch (\Exception $e) {
            Log::error('Error IA: ' . $e->getMessage());
            return response()->json(['response' => 'Error interno del servidor.'], 500);
        }
    }
}