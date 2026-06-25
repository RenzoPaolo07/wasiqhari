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
                    'nombre' => $paciente->nombre ?: $paciente->nombres . ' ' . $paciente->apellidos,
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
        try {
            // Solo intentar validar y responder
            $datos = $request->validate([
                'paciente_id' => 'required|string',
                'tipo_alerta' => 'nullable|string',
                'fuerza_g' => 'nullable|numeric'
            ]);

            // 🔍 LOG: Registrar en el archivo de Laravel para depuración
            \Log::info('📡 Petición recibida en /alerta-iot', $datos);

            return response()->json([
                'success' => true,
                'message' => 'Prueba exitosa desde IoTController',
                'data_recibida' => $datos
            ], 200);

        } catch (\Exception $e) {
            \Log::error('❌ Error en recibirAlerta: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
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
            $csv .= "{$p->id},{$p->nombres} {$p->apellidos},{$p->dni},{$p->codigo ?: $p->dispositivo_id},{$p->alertas_activas},{$p->nivel_riesgo},{$p->ultimo_contacto_iot ?: $p->updated_at}\n";
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
        // Obtener el último registro de activity_logs del ESP32
        $ultimoLog = ActivityLog::where('accion', 'LECTURA_SENSOR')
            ->orWhere('accion', 'LECTURA_SENSORES')
            ->latest()
            ->first();

        if ($ultimoLog) {
            $descripcion = json_decode($ultimoLog->descripcion, true);
            $paciente = AdultoMayor::find($descripcion['paciente_id'] ?? null);

            return response()->json([
                'estado' => 'conectado',
                'ultima_lectura' => $ultimoLog->created_at,
                'sensores' => [
                    'temperatura' => $descripcion['datos']['temperatura'] ?? $descripcion['temperatura'] ?? rand(200, 300) / 10,
                    'humedad' => $descripcion['datos']['humedad'] ?? $descripcion['humedad'] ?? rand(400, 800) / 10,
                    'distancia' => $descripcion['datos']['distancia'] ?? $descripcion['distancia'] ?? rand(10, 150),
                    'luz' => $descripcion['datos']['luz'] ?? $descripcion['luz'] ?? rand(100, 900),
                    'acelerometro' => [
                        'x' => $descripcion['datos']['accel_x'] ?? $descripcion['accel_x'] ?? rand(-100, 100) / 100,
                        'y' => $descripcion['datos']['accel_y'] ?? $descripcion['accel_y'] ?? rand(-100, 100) / 100,
                        'z' => $descripcion['datos']['accel_z'] ?? $descripcion['accel_z'] ?? rand(80, 120) / 100
                    ],
                    'sos' => str_contains($descripcion['datos']['tipo_alerta'] ?? '', 'panico') ? true : false,
                    'impacto' => ($descripcion['datos']['fuerza_g'] ?? 0) > 2.5 ? true : false,
                    'paciente' => $paciente ? $paciente->nombres . ' ' . $paciente->apellidos : null
                ],
                'timestamp' => now()->toISOString()
            ]);
        }

        // Si no hay datos, devolver simulación
        return response()->json([
            'estado' => 'conectado',
            'ultima_lectura' => now(),
            'sensores' => [
                'temperatura' => rand(200, 300) / 10,
                'humedad' => rand(400, 800) / 10,
                'distancia' => rand(10, 150),
                'luz' => rand(100, 900),
                'acelerometro' => [
                    'x' => rand(-100, 100) / 100,
                    'y' => rand(-100, 100) / 100,
                    'z' => rand(80, 120) / 100
                ],
                'sos' => false,
                'impacto' => false
            ],
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Obtener datos reales del ESP32 desde activity_logs
     */
    public function datosReales()
    {
        // Obtener el último registro de activity_logs del ESP32
        $ultimoLog = ActivityLog::where('accion', 'LECTURA_SENSOR')
            ->orWhere('accion', 'LECTURA_SENSORES')
            ->latest()
            ->first();

        if (!$ultimoLog) {
            return response()->json([
                'estado' => 'sin_datos',
                'mensaje' => 'No hay datos del ESP32 aún'
            ]);
        }

        $descripcion = json_decode($ultimoLog->descripcion, true);
        $paciente = AdultoMayor::find($descripcion['paciente_id'] ?? null);

        // Extraer datos del JSON guardado
        $datos = $descripcion['datos'] ?? $descripcion;

        return response()->json([
            'estado' => 'conectado',
            'ultima_lectura' => $ultimoLog->created_at,
            'paciente' => $paciente ? $paciente->nombres . ' ' . $paciente->apellidos : 'Desconocido',
            'sensores' => [
                'temperatura' => $datos['temperatura'] ?? null,
                'humedad' => $datos['humedad'] ?? null,
                'distancia' => $datos['distancia'] ?? null,
                'luz' => $datos['luz'] ?? null,
                'fuerza_g' => $datos['fuerza_g'] ?? null,
                'acelerometro' => [
                    'x' => $datos['accel_x'] ?? 0,
                    'y' => $datos['accel_y'] ?? 0,
                    'z' => $datos['accel_z'] ?? 0
                ],
                'sos' => str_contains($datos['tipo_alerta'] ?? '', 'panico') ? true : false,
                'impacto' => ($datos['fuerza_g'] ?? 0) > 2.5 ? true : false
            ],
            'timestamp' => now()->toISOString()
        ]);
    }
}