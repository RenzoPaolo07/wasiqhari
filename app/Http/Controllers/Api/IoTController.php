<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdultoMayor;
use App\Models\ActivityLog;
use App\Models\Visita;
use App\Notifications\NuevaEmergencia;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

class IoTController extends Controller
{
    /**
     * Obtener estado del paciente (para ESP32)
     */
    public function obtenerEstado($pacienteId)
    {
        // Buscar por DNI (cédula) o por ID o código
        $paciente = AdultoMayor::where('dni', $pacienteId)
            ->orWhere('id', $pacienteId)
            ->orWhere('codigo', $pacienteId)
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
                'paciente_id' => $paciente->codigo ?? $paciente->id,
                'nombres' => $paciente->nombres,
                'apellidos' => $paciente->apellidos,
                'nombre' => $paciente->nombre ?? $paciente->nombres . ' ' . $paciente->apellidos,
                'dni' => $paciente->dni,
                'telefono' => $paciente->telefono,
                'direccion' => $paciente->direccion,
                'nivel_riesgo' => $paciente->nivel_riesgo ?? 'desconocido',
                'alertas_activas' => $paciente->alertas_activas ?? false,
                'ultima_visita' => $paciente->visitas()->latest()->first()?->created_at
            ]
        ]);
    }

    /**
     * Recibir alerta del ESP32 (unificado con lógica de emergencia)
     */
    public function recibirAlerta(Request $request)
    {
        // Validar datos del ESP32 (unificando validaciones)
        $datos = $request->validate([
            'paciente_id' => 'required|string',
            'tipo_alerta' => 'nullable|string',
            'pulso' => 'nullable|integer',
            'oxigeno' => 'nullable|integer',
            'temperatura' => 'nullable|numeric',
            'sos' => 'boolean',
            'caida' => 'boolean',
            'ubicacion' => 'nullable|string',
            'timestamp' => 'nullable|date',
            'fuerza_g' => 'nullable|numeric',
            'accel_x' => 'nullable|numeric',
            'accel_y' => 'nullable|numeric',
            'accel_z' => 'nullable|numeric'
        ]);

        // Buscar por DNI o ID o código
        $paciente = AdultoMayor::where('dni', $datos['paciente_id'])
            ->orWhere('id', $datos['paciente_id'])
            ->orWhere('codigo', $datos['paciente_id'])
            ->first();

        if (!$paciente) {
            return response()->json([
                'success' => false,
                'error' => 'Paciente no encontrado',
                'dni_buscado' => $datos['paciente_id']
            ], 404);
        }

        // Detectar emergencia
        $esEmergencia = false;
        $motivo = [];

        // Verificar SOS
        if (isset($datos['sos']) && $datos['sos'] === true) {
            $esEmergencia = true;
            $motivo[] = 'Botón SOS presionado';
        }

        // Verificar caída
        if (isset($datos['caida']) && $datos['caida'] === true) {
            $esEmergencia = true;
            $motivo[] = 'Detección de caída';
        }

        // Verificar pulso anómalo
        if (isset($datos['pulso']) && ($datos['pulso'] > 120 || $datos['pulso'] < 50)) {
            $esEmergencia = true;
            $motivo[] = 'Pulso anómalo: ' . $datos['pulso'] . ' bpm';
        }

        // Verificar oxígeno bajo
        if (isset($datos['oxigeno']) && $datos['oxigeno'] < 90) {
            $esEmergencia = true;
            $motivo[] = 'Oxígeno bajo: ' . $datos['oxigeno'] . '%';
        }

        // Si hay tipo_alerta, también consideramos emergencia
        if (isset($datos['tipo_alerta']) && in_array($datos['tipo_alerta'], ['caida', 'panico', 'sos', 'emergencia'])) {
            $esEmergencia = true;
            if (empty($motivo)) {
                $motivo[] = 'Alerta tipo: ' . $datos['tipo_alerta'];
            }
        }

        // Registrar en Activity Log (usando estructura unificada)
        $log = ActivityLog::create([
            'user_id' => null,
            'adulto_mayor_id' => $paciente->id,
            'accion' => $esEmergencia ? 'EMERGENCIA_IOT' : 'LECTURA_SENSOR',
            'modulo' => 'IoT',
            'descripcion' => json_encode([
                'paciente_id' => $paciente->id,
                'paciente_nombre' => $paciente->nombres . ' ' . $paciente->apellidos,
                'datos' => $datos,
                'motivo_emergencia' => $motivo,
                'tipo_alerta' => $datos['tipo_alerta'] ?? null,
                'fuerza_g' => $datos['fuerza_g'] ?? null,
                'fuente' => 'ESP32/Wokwi',
                'timestamp' => now()->toISOString()
            ]),
            'ip' => $request->ip(),
            'created_at' => now()
        ]);

        // Si es emergencia, enviar notificaciones y crear visita
        if ($esEmergencia) {
            // Obtener usuarios a notificar
            $usuariosNotificar = User::whereIn('role', ['admin', 'voluntario', 'medico'])->get();

            // Enviar notificación
            Notification::send($usuariosNotificar, new NuevaEmergencia($paciente, $motivo, $datos));

            // Crear visita de emergencia automática
            $visita = Visita::create([
                'adulto_mayor_id' => $paciente->id,
                'voluntario_id' => null,
                'tipo' => 'emergencia',
                'fecha_programada' => now(),
                'estado' => 'pendiente',
                'notas' => 'Emergencia IoT: ' . implode(', ', $motivo),
                'ubicacion' => $datos['ubicacion'] ?? $paciente->direccion
            ]);

            return response()->json([
                'success' => true,
                'status' => 'emergencia',
                'mensaje' => 'Alerta de emergencia registrada',
                'motivos' => $motivo,
                'paciente' => $paciente->nombres . ' ' . $paciente->apellidos,
                'visita_id' => $visita->id
            ], 201);
        }

        // Si no es emergencia, solo guardar lectura
        return response()->json([
            'success' => true,
            'status' => 'ok',
            'mensaje' => 'Lectura registrada exitosamente',
            'paciente' => $paciente->nombres . ' ' . $paciente->apellidos
        ], 200);
    }

    /**
     * Recibir datos de sensores del dispositivo IoT
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
            'modulo' => 'IoT',
            'descripcion' => json_encode([
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

    /**
     * Obtener últimas alertas
     */
    public function ultimasAlertas()
    {
        $alertas = ActivityLog::where('accion', 'EMERGENCIA_IOT')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function($log) {
                $detalles = json_decode($log->descripcion, true);
                $paciente = AdultoMayor::find($detalles['paciente_id'] ?? null);
                
                return [
                    'id' => $log->id,
                    'tipo_alerta' => $detalles['tipo_alerta'] ?? ($detalles['datos']['sos'] ?? false ? 'SOS' : ($detalles['datos']['caida'] ?? false ? 'CAÍDA' : 'SENSOR')),
                    'fuerza_g' => $detalles['fuerza_g'] ?? $detalles['datos']['fuerza_g'] ?? 0,
                    'paciente' => $paciente ? $paciente->nombres . ' ' . $paciente->apellidos : 'Desconocido',
                    'timestamp' => $log->created_at
                ];
            });
        
        return response()->json($alertas);
    }

    /**
     * Resumen IoT
     */
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

    /**
     * Alertas recientes (con datos completos)
     */
    public function alertasRecientes()
    {
        $alertas = ActivityLog::where('accion', 'EMERGENCIA_IOT')
            ->with('adultoMayor')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function($log) {
                $detalles = json_decode($log->descripcion, true);
                $datos = $detalles['datos'] ?? [];
                $motivo = $detalles['motivo_emergencia'] ?? [];
                
                return [
                    'id' => $log->id,
                    'tipo_alerta' => $datos['sos'] ?? false ? 'SOS' : ($datos['caida'] ?? false ? 'CAÍDA' : ($detalles['tipo_alerta'] ?? 'SENSOR')),
                    'fuerza_g' => $datos['fuerza_g'] ?? $detalles['fuerza_g'] ?? 0,
                    'motivos' => $motivo,
                    'paciente' => $log->adultoMayor ? $log->adultoMayor->nombres . ' ' . $log->adultoMayor->apellidos : 'Desconocido',
                    'dispositivo_id' => $log->adultoMayor ? ($log->adultoMayor->codigo ?? $log->adultoMayor->id) : 'N/A',
                    'timestamp' => $log->created_at,
                    'es_nueva' => $log->created_at > now()->subMinutes(1)
                ];
            });
        
        return response()->json($alertas);
    }

    /**
     * Pacientes con dispositivos
     */
    public function pacientesConDispositivos()
    {
        $pacientes = AdultoMayor::whereNotNull('codigo')
            ->orWhereNotNull('dispositivo_id')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($pacientes);
    }

    /**
     * Estadísticas de alertas (última semana)
     */
    public function estadisticasAlertas()
    {
        $alertasPorDia = ActivityLog::where('accion', 'EMERGENCIA_IOT')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as fecha, COUNT(*) as total')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();
        
        // Obtener todas las alertas para estadísticas adicionales
        $alertas = ActivityLog::where('accion', 'EMERGENCIA_IOT')
            ->where('created_at', '>=', now()->subDays(7))
            ->get();
        
        // Contar tipos de alertas
        $caidas = 0;
        $sos = 0;
        $detecciones = 0;
        
        foreach ($alertas as $alerta) {
            $detalles = json_decode($alerta->descripcion, true);
            $datos = $detalles['datos'] ?? [];
            
            if (isset($datos['caida']) && $datos['caida'] === true) {
                $caidas++;
            } elseif (isset($datos['sos']) && $datos['sos'] === true) {
                $sos++;
            } elseif (isset($detalles['tipo_alerta']) && $detalles['tipo_alerta'] === 'caida') {
                $caidas++;
            } elseif (isset($detalles['tipo_alerta']) && in_array($detalles['tipo_alerta'], ['panico', 'sos'])) {
                $sos++;
            } else {
                $detecciones++;
            }
        }
        
        // Si no hay categorías específicas, distribuir equitativamente
        if ($caidas == 0 && $sos == 0 && $detecciones > 0) {
            $detecciones = $alertas->count();
        }
        
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
            'detecciones' => $detecciones,
            'total_semana' => $alertas->count()
        ]);
    }

    /**
     * Ubicaciones de pacientes
     */
    public function ubicacionesPacientes()
    {
        // Si no tienes columnas 'lat' y 'lon', usa dirección
        $pacientes = AdultoMayor::whereNotNull('lat')
            ->whereNotNull('lon')
            ->get(['id', 'nombres', 'apellidos', 'dni', 'lat', 'lon', 'nivel_riesgo', 'direccion']);
        
        return response()->json($pacientes);
    }

    /**
     * Mostrar paciente en vista web
     */
    public function mostrarPaciente($id)
    {
        $paciente = AdultoMayor::findOrFail($id);
        $alertas = ActivityLog::where('accion', 'EMERGENCIA_IOT')
            ->where('adulto_mayor_id', $paciente->id)
            ->latest()
            ->take(10)
            ->get();
        
        return view('iot.paciente', compact('paciente', 'alertas'));
    }

    /**
     * Dashboard IoT
     */
    public function dashboard()
    {
        $totalDispositivos = AdultoMayor::whereNotNull('codigo')
            ->orWhereNotNull('dispositivo_id')
            ->count();
        $alertasHoy = ActivityLog::where('accion', 'EMERGENCIA_IOT')
            ->whereDate('created_at', today())->count();
        $alertasTotales = ActivityLog::where('accion', 'EMERGENCIA_IOT')->count();
        
        return view('dashboard.iot-dashboard', compact('totalDispositivos', 'alertasHoy', 'alertasTotales'));
    }

    /**
     * Exportar a Excel (CSV)
     */
    public function exportarExcel()
    {
        $pacientes = AdultoMayor::whereNotNull('codigo')
            ->orWhereNotNull('dispositivo_id')
            ->get();
        
        $csv = "ID,Nombre Completo,DNI,Dispositivo,Estado,Riesgo,Último Contacto\n";
        foreach ($pacientes as $p) {
            $csv .= "{$p->id},{$p->nombres} {$p->apellidos},{$p->dni},{$p->codigo ?? $p->dispositivo_id},{$p->alertas_activas},{$p->nivel_riesgo},{$p->ultimo_contacto_iot ?? $p->updated_at}\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="pacientes_iot.csv"');
    }

    /**
     * Datos de sensores en tiempo real (simulación)
     */
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

    /**
     * Simular datos del Arduino
     */
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
}