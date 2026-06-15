<?php

namespace App\Http\Controllers;

use App\Models\AdultoMayor;
use App\Models\ActivityLog;
use App\Models\Visita;
use App\Notifications\NuevaEmergencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

class IoTController extends Controller
{
    public function recibirAlerta(Request $request)
    {
        // Validar datos del ESP32
        $datos = $request->validate([
            'paciente_id' => 'required|string',
            'pulso' => 'nullable|integer',
            'oxigeno' => 'nullable|integer',
            'temperatura' => 'nullable|numeric',
            'sos' => 'boolean',
            'caida' => 'boolean',
            'ubicacion' => 'nullable|string',
        ]);

        // Buscar adulto mayor
        $adulto = AdultoMayor::where('codigo', $datos['paciente_id'])
            ->orWhere('id', $datos['paciente_id'])
            ->first();

        if (!$adulto) {
            return response()->json(['error' => 'Paciente no encontrado'], 404);
        }

        // Registrar en Activity Log
        ActivityLog::create([
            'user_id' => null,
            'adulto_mayor_id' => $adulto->id,
            'accion' => 'LECTURA_SENSOR',
            'detalles' => json_encode($datos),
            'ip' => $request->ip(),
        ]);

        return response()->json([
            'status' => 'ok',
            'mensaje' => 'Lectura registrada',
            'paciente' => $adulto->nombre
        ], 200);
    }

    public function obtenerEstado($pacienteId)
    {
        $adulto = AdultoMayor::where('codigo', $pacienteId)
            ->orWhere('id', $pacienteId)
            ->first();

        if (!$adulto) {
            return response()->json(['error' => 'No encontrado'], 404);
        }

        return response()->json([
            'paciente_id' => $adulto->codigo ?? $adulto->id,
            'nombre' => $adulto->nombre,
            'riesgo' => $adulto->nivel_riesgo ?? 'desconocido'
        ]);
    }
}