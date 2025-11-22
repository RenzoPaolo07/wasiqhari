<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIController extends Controller
{
    public function chat(Request $request)
    {
        $mensaje = $request->input('message');
        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            return response()->json(['response' => 'Error: No se encontró la API KEY en el archivo .env']);
        }

        $prompt = "Eres el asistente experto de WasiQhari, una ONG de ayuda social en Cusco. 
                   Ayudas a coordinadores y voluntarios. Responde brevemente, con empatía y en español.
                   Pregunta del usuario: " . $mensaje;

        try {
            // CAMBIO CLAVE: Usamos el modelo que vimos en tu lista
            // "name": "models/gemini-2.0-flash-001"
            // Y usamos la versión v1beta que es donde viven estos modelos nuevos.
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-001:generateContent?key={$apiKey}";

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ]);

            $data = $response->json();

            // Debugging
            Log::info('Respuesta de Gemini:', $data);

            if (isset($data['error'])) {
                $errorMsg = $data['error']['message'] ?? 'Error desconocido de Google';
                // Si falla este, intentamos un fallback al lite
                if (strpos($errorMsg, 'not found') !== false) {
                     return $this->tryFallbackModel($apiKey, $prompt);
                }
                return response()->json(['response' => "Error de IA: $errorMsg"]);
            }

            $texto = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($texto) {
                return response()->json(['response' => $texto]);
            } else {
                return response()->json(['response' => 'La IA respondió vacío. Intenta preguntar de otra forma.']);
            }

        } catch (\Exception $e) {
            Log::error('Error de conexión IA: ' . $e->getMessage());
            return response()->json(['response' => 'Error interno del servidor: ' . $e->getMessage()], 500);
        }
    }

    // Función de respaldo por si acaso
    private function tryFallbackModel($apiKey, $prompt) {
        try {
            // Intentamos con la versión lite que también tienes
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-lite-001:generateContent?key={$apiKey}";
            
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ]);
                
            $data = $response->json();
            $texto = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            
            if ($texto) return response()->json(['response' => $texto]);
            
        } catch (\Exception $e) {}
        
        return response()->json(['response' => 'Lo siento, no pude conectar con ningún modelo de IA disponible.']);
    }
}