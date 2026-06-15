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
            'timestamp' => 'nullable|date'
        ]);

        // Buscar adulto mayor por código o ID
        $adulto = AdultoMayor::where('codigo', $datos['paciente_id'])
            ->orWhere('id', $datos['paciente_id'])
            ->first();

        if (!$adulto) {
            return response()->json(['error' => 'Paciente no encontrado'], 404);
        }

        // Detectar emergencia
        $esEmergencia = false;
        $motivo = [];

        if (isset($datos['sos']) && $datos['sos'] === true) {
            $esEmergencia = true;
            $motivo[] = 'Botón SOS presionado';
        }

        if (isset($datos['caida']) && $datos['caida'] === true) {
            $esEmergencia = true;
            $motivo[] = 'Detección de caída';
        }

        if (isset($datos['pulso']) && ($datos['pulso'] > 120 || $datos['pulso'] < 50)) {
            $esEmergencia = true;
            $motivo[] = 'Pulso anómalo: ' . $datos['pulso'] . ' bpm';
        }

        if (isset($datos['oxigeno']) && $datos['oxigeno'] < 90) {
            $esEmergencia = true;
            $motivo[] = 'Oxígeno bajo: ' . $datos['oxigeno'] . '%';
        }

        // Registrar en Activity Log
        $log = ActivityLog::create([
            'user_id' => null,
            'adulto_mayor_id' => $adulto->id,
            'accion' => $esEmergencia ? 'EMERGENCIA_IOT' : 'LECTURA_SENSOR',
            'detalles' => json_encode([
                'datos' => $datos,
                'motivo_emergencia' => $motivo,
                'fuente' => 'ESP32/Wokwi'
            ]),
            'ip' => $request->ip(),
            'created_at' => now()
        ]);

        // Si es emergencia, enviar notificaciones
        if ($esEmergencia) {
            // Obtener voluntarios y admin cercanos
            $usuariosNotificar = User::whereIn('role', ['admin', 'voluntario'])
                ->orWhere('role', 'medico')
                ->get();

            // Enviar notificación a todos
            Notification::send($usuariosNotificar, new NuevaEmergencia($adulto, $motivo, $datos));

            // También crear una visita de emergencia automática
            $visita = Visita::create([
                'adulto_mayor_id' => $adulto->id,
                'voluntario_id' => null,
                'tipo' => 'emergencia',
                'fecha_programada' => now(),
                'estado' => 'pendiente',
                'notas' => 'Emergencia IoT: ' . implode(', ', $motivo),
                'ubicacion' => $datos['ubicacion'] ?? $adulto->direccion
            ]);

            return response()->json([
                'status' => 'emergencia',
                'mensaje' => 'Alerta de emergencia registrada',
                'motivos' => $motivo,
                'visita_id' => $visita->id
            ], 201);
        }

        // Si no es emergencia, solo guardar lectura
        return response()->json([
            'status' => 'ok',
            'mensaje' => 'Lectura registrada exitosamente',
            'paciente' => $adulto->nombre
        ], 200);
    }

    // Endpoint para obtener estado actual del paciente (para el ESP32)
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
            'riesgo' => $adulto->nivel_riesgo ?? 'desconocido',
            'alertas_activas' => $adulto->alertas_activas ?? false,
            'ultima_visita' => $adulto->visitas()->latest()->first()?->created_at
        ]);
    }

    // En IoTController.php
public function mostrarPaciente($dni)
    {
        $paciente = AdultoMayor::where('dni', $dni)->first();
        $alertas = ActivityLog::where('accion', 'EMERGENCIA_IOT')
            ->where('descripcion', 'LIKE', '%' . $paciente->id . '%')
            ->latest()
            ->take(10)
            ->get();
        
        return view('iot.paciente', compact('paciente', 'alertas'));
    }
}