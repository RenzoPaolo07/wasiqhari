<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    public function chat(Request $request)
    {
        $mensajeUsuario = $request->input('message');
        $apiKey = env('GEMINI_API_KEY');

        // Prompt del sistema para darle personalidad a la IA
        $contexto = "Eres el asistente virtual de WasiQhari, una plataforma de ayuda social en Cusco, Perú. 
                     Tu misión es ayudar a voluntarios y coordinadores. 
                     Responde de forma empática, breve y útil. 
                     Si te preguntan por emergencias médicas, sugiere llamar al 106 (SAMU) o 116 (Bomberos).";

        $prompt = $contexto . "\n\nUsuario: " . $mensajeUsuario . "\nAsistente:";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            $data = $response->json();
            
            // Extraemos la respuesta de Gemini
            $respuestaIA = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Lo siento, estoy teniendo problemas de conexión. Intenta de nuevo.';

            return response()->json(['response' => $respuestaIA]);

        } catch (\Exception $e) {
            return response()->json(['response' => 'Error al conectar con la IA: ' . $e->getMessage()], 500);
        }
    }
}