<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    public function chat(Request $request)
    {
        $mensaje = $request->input('message');
        // REEMPLAZA ESTO CON TU API KEY REAL DE GOOGLE GEMINI
        $apiKey = env('GEMINI_API_KEY', 'AIzaSyB8s0JEP7Zi7yw6HD3g8AmdXf5XaLDH1TI'); 

        $prompt = "Eres el asistente experto de WasiQhari, una ONG de ayuda social en Cusco. 
                   Ayudas a coordinadores y voluntarios. Responde brevemente y con empatía.
                   Pregunta del usuario: " . $mensaje;

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ]);

            $data = $response->json();
            $texto = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Lo siento, no pude procesar eso.';

            return response()->json(['response' => $texto]);
        } catch (\Exception $e) {
            return response()->json(['response' => 'Error de conexión con la IA.'], 500);
        }
    }
}