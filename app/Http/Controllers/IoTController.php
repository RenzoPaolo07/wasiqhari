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

    public function mostrarPaciente($dni)
    {
        $paciente = AdultoMayor::where('dni', $dni)->first();
        
        if (!$paciente) {
            abort(404, 'Paciente no encontrado');
        }
        
        $alertas = ActivityLog::where('accion', 'EMERGENCIA_IOT')
            ->where('adulto_mayor_id', $paciente->id)
            ->latest()
            ->take(10)
            ->get();
        
        return view('iot.paciente', compact('paciente', 'alertas'));
    }

    public function resumenIoT()
    {
        $hoy = now()->startOfDay();
        
        return response()->json([
            'dispositivos_activos' => AdultoMayor::where('alertas_activas', true)->count(),
            'alertas_hoy' => ActivityLog::where('accion', 'EMERGENCIA_IOT')
                ->where('created_at', '>=', $hoy)->count(),
            'pacientes_riesgo' => AdultoMayor::where('nivel_riesgo', 'Alto')->count(),
            'total_pacientes' => AdultoMayor::count()
        ]);
    }

    public function alertasRecientes()
    {
        $alertas = ActivityLog::where('accion', 'EMERGENCIA_IOT')
            ->with('adultoMayor')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function($log) {
                $detalles = json_decode($log->detalles, true);
                $datos = $detalles['datos'] ?? [];
                
                return [
                    'id' => $log->id,
                    'tipo_alerta' => $datos['sos'] ?? false ? 'SOS' : ($datos['caida'] ?? false ? 'CAÍDA' : 'SENSOR'),
                    'fuerza_g' => $datos['fuerza_g'] ?? 0,
                    'paciente' => $log->adultoMayor ? $log->adultoMayor->nombre : 'Desconocido',
                    'dispositivo_id' => $log->adultoMayor ? $log->adultoMayor->codigo : 'N/A',
                    'timestamp' => $log->created_at,
                    'es_nueva' => $log->created_at > now()->subMinutes(1)
                ];
            });
        
        return response()->json($alertas);
    }

    public function pacientesConDispositivos()
    {
        $pacientes = AdultoMayor::whereNotNull('codigo')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($pacientes);
    }

    public function estadisticasAlertas()
    {
        $alertasPorDia = ActivityLog::where('accion', 'EMERGENCIA_IOT')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as fecha, COUNT(*) as total')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();
        
        // Estadísticas adicionales
        $alertasUltimaSemana = ActivityLog::where('accion', 'EMERGENCIA_IOT')
            ->where('created_at', '>=', now()->subDays(7))
            ->get();
        
        $caidas = $alertasUltimaSemana->filter(function($alerta) {
            $detalles = json_decode($alerta->detalles, true);
            return isset($detalles['datos']['caida']) && $detalles['datos']['caida'] === true;
        })->count();
        
        $sos = $alertasUltimaSemana->filter(function($alerta) {
            $detalles = json_decode($alerta->detalles, true);
            return isset($detalles['datos']['sos']) && $detalles['datos']['sos'] === true;
        })->count();
        
        $detecciones = $alertasUltimaSemana->count() - $caidas - $sos;
        
        $labels = [];
        $valores = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d/m');
            $valores[] = $alertasPorDia->firstWhere('fecha', $fecha)->total ?? 0;
        }
        
        return response()->json([
            'labels' => $labels,
            'valores' => $valores,
            'caidas' => $caidas,
            'sos' => $sos,
            'detecciones' => $detecciones
        ]);
    }
    
    public function ubicacionesPacientes()
    {
        // Si no tienes columnas 'lat' y 'lon', usa dirección
        $pacientes = AdultoMayor::whereNotNull('lat')
            ->whereNotNull('lon')
            ->get(['id', 'nombre', 'dni', 'lat', 'lon', 'nivel_riesgo']);
        
        return response()->json($pacientes);
    }
    
    public function dashboard()
    {
        $totalDispositivos = AdultoMayor::whereNotNull('codigo')->count();
        $alertasHoy = ActivityLog::where('accion', 'EMERGENCIA_IOT')
            ->whereDate('created_at', today())->count();
        $alertasTotales = ActivityLog::where('accion', 'EMERGENCIA_IOT')->count();
        
        return view('dashboard.iot-dashboard', compact('totalDispositivos', 'alertasHoy', 'alertasTotales'));
    }

    public function exportarExcel()
    {
        $pacientes = AdultoMayor::whereNotNull('codigo')->get();
        
        $csv = "ID,Nombre,DNI,Dispositivo,Estado,Riesgo,Último Contacto\n";
        foreach ($pacientes as $p) {
            $csv .= "{$p->id},{$p->nombre},{$p->dni},{$p->codigo},{$p->alertas_activas},{$p->nivel_riesgo},{$p->updated_at}\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="pacientes_iot.csv"');
    }

    public function datosSensores()
    {
        return response()->json([
            'acelerometro' => ['x' => 0.1, 'y' => 0.2, 'z' => 0.95, 'fuerza' => 1.0],
            'temperatura' => 24.5,
            'humedad' => 55,
            'distancia' => 120,
            'luz' => 450,
            'timestamp' => now()
        ]);
    }

    public function simularArduino()
    {
        // Datos simulados como si vinieran del Arduino
        $datos = [
            'estado' => 'conectado',
            'ultima_lectura' => now(),
            'sensores' => [
                'temperatura' => rand(200, 300) / 10, // 20-30°C
                'humedad' => rand(400, 800) / 10, // 40-80%
                'distancia' => rand(10, 150), // cm
                'luz' => rand(100, 900), // LDR
                'acelerometro' => [
                    'x' => rand(-100, 100) / 100,
                    'y' => rand(-100, 100) / 100,
                    'z' => rand(80, 120) / 100
                ],
                'sos' => false,
                'impacto' => false
            ],
            'timestamp' => now()->toISOString()
        ];
        
        return response()->json($datos);
    }

    /**
     * Recibe datos de sensores del dispositivo IoT
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recibirDatosSensores(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|string',
            'temperatura' => 'nullable|numeric',
            'humedad' => 'nullable|numeric',
            'distancia' => 'nullable|numeric',
            'luz' => 'nullable|numeric',
            'fuerza_g' => 'nullable|numeric',
            'accel_x' => 'nullable|numeric',
            'accel_y' => 'nullable|numeric',
            'accel_z' => 'nullable|numeric'
        ]);
        
        // Buscar el paciente por DNI o código
        $paciente = AdultoMayor::where('dni', $request->paciente_id)
            ->orWhere('codigo', $request->paciente_id)
            ->first();
        
        if (!$paciente) {
            return response()->json(['error' => 'Paciente no encontrado'], 404);
        }
        
        // Actualizar los datos del paciente
        $paciente->ultima_lectura_iot = json_encode([
            'temperatura' => $request->temperatura,
            'humedad' => $request->humedad,
            'distancia' => $request->distancia,
            'luz' => $request->luz,
            'fuerza_g' => $request->fuerza_g,
            'acelerometro' => [
                'x' => $request->accel_x,
                'y' => $request->accel_y,
                'z' => $request->accel_z
            ],
            'timestamp' => now()->toISOString()
        ]);
        $paciente->ultimo_contacto_iot = now();
        $paciente->save();
        
        // Registrar en activity_logs
        ActivityLog::create([
            'user_id' => null,
            'adulto_mayor_id' => $paciente->id,
            'accion' => 'LECTURA_SENSORES',
            'detalles' => json_encode([
                'paciente_id' => $paciente->id,
                'paciente' => $paciente->nombres . ' ' . $paciente->apellidos,
                'sensores' => $request->all()
            ])
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Datos recibidos correctamente'
        ]);
    }
}