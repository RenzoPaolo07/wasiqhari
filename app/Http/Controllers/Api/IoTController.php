<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdultoMayor;
use App\Models\ActivityLog;

class IoTController extends Controller
{
    public function obtenerEstado($pacienteId)
    {
        // Buscar por DNI (cédula) o por ID
        $paciente = AdultoMayor::where('dni', $pacienteId)
            ->orWhere('id', $pacienteId)
            ->first();

        if (!$paciente) {
            return response()->json([
                'success' => false,
                'error' => 'Paciente no encontrado',
                'dni_buscado' => $pacienteId
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $paciente->id,
                'nombres' => $paciente->nombres,
                'apellidos' => $paciente->apellidos,
                'dni' => $paciente->dni,
                'telefono' => $paciente->telefono,
                'direccion' => $paciente->direccion,
                'nivel_riesgo' => $paciente->nivel_riesgo ?? 'No definido'
            ]
        ]);
    }

    public function recibirAlerta(Request $request)
    {
        // Validar datos
        $request->validate([
            'paciente_id' => 'required|string',
            'tipo_alerta' => 'required|string',
            'fuerza_g' => 'nullable|numeric'
        ]);

        // Buscar por DNI o ID
        $paciente = AdultoMayor::where('dni', $request->paciente_id)
            ->orWhere('id', $request->paciente_id)
            ->first();

        if (!$paciente) {
            return response()->json([
                'success' => false,
                'error' => 'Paciente no encontrado',
                'dni_buscado' => $request->paciente_id
            ], 404);
        }

        // Registrar en activity_logs usando las columnas correctas
        ActivityLog::create([
            'user_id' => null,  // o 1 si tienes un usuario admin
            'accion' => 'EMERGENCIA_IOT',
            'modulo' => 'IoT',
            'descripcion' => json_encode([
                'paciente_id' => $paciente->id,
                'paciente_nombre' => $paciente->nombres . ' ' . $paciente->apellidos,
                'tipo_alerta' => $request->tipo_alerta,
                'fuerza_g' => $request->fuerza_g,
                'timestamp' => now()->toISOString()
            ])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alerta registrada exitosamente',
            'paciente' => $paciente->nombres . ' ' . $paciente->apellidos,
            'tipo_alerta' => $request->tipo_alerta
        ], 200);
    }
};