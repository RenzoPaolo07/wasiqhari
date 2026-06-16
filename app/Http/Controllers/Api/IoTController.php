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
                    'tipo_alerta' => $detalles['tipo_alerta'] ?? 'Desconocido',
                    'fuerza_g' => $detalles['fuerza_g'] ?? 0,
                    'paciente' => $paciente ? $paciente->nombres . ' ' . $paciente->apellidos : 'Desconocido',
                    'timestamp' => $log->created_at
                ];
            });
        
        return response()->json($alertas);
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
                $detalles = json_decode($log->descripcion, true);
                return [
                    'id' => $log->id,
                    'tipo_alerta' => $detalles['tipo_alerta'] ?? 'Desconocido',
                    'fuerza_g' => $detalles['fuerza_g'] ?? 0,
                    'paciente' => $log->adultoMayor ? $log->adultoMayor->nombres . ' ' . $log->adultoMayor->apellidos : 'Desconocido',
                    'dispositivo_id' => $log->adultoMayor ? $log->adultoMayor->dispositivo_id : 'N/A',
                    'timestamp' => $log->created_at,
                    'es_nueva' => $log->created_at > now()->subMinutes(1)
                ];
            });
        
        return response()->json($alertas);
    }

    public function pacientesConDispositivos()
    {
        $pacientes = AdultoMayor::whereNotNull('dispositivo_id')
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
        
        $labels = [];
        $valores = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d/m');
            $valores[] = $alertasPorDia->firstWhere('fecha', $fecha)->total ?? 0;
        }
        
        return response()->json([
            'labels' => $labels,
            'valores' => $valores
        ]);
    }

    public function ubicacionesPacientes()
    {
        $pacientes = AdultoMayor::whereNotNull('lat')
            ->whereNotNull('lon')
            ->get(['id', 'nombres', 'apellidos', 'dni', 'lat', 'lon', 'nivel_riesgo']);
        
        return response()->json($pacientes);
    }

    public function mostrarPaciente($id)
    {
        $paciente = AdultoMayor::findOrFail($id);
        $alertas = ActivityLog::where('accion', 'EMERGENCIA_IOT')
            ->where('descripcion', 'LIKE', '%' . $paciente->id . '%')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('iot.paciente', compact('paciente', 'alertas'));
    }

    public function dashboard()
    {
        // Obtener estadísticas para mostrar en el dashboard IoT
        $totalDispositivos = AdultoMayor::whereNotNull('dispositivo_id')->count();
        $alertasHoy = ActivityLog::where('accion', 'EMERGENCIA_IOT')
            ->whereDate('created_at', today())->count();
        $alertasTotales = ActivityLog::where('accion', 'EMERGENCIA_IOT')->count();
        
        return view('dashboard.iot-dashboard', compact('totalDispositivos', 'alertasHoy', 'alertasTotales'));
    }
    
    public function exportarExcel()
    {
        $pacientes = AdultoMayor::whereNotNull('dispositivo_id')->get();
        
        $csv = "ID,Nombre Completo,DNI,Dispositivo,Estado,Riesgo,Último Contacto\n";
        foreach ($pacientes as $p) {
            $csv .= "{$p->id},{$p->nombres} {$p->apellidos},{$p->dni},{$p->dispositivo_id},{$p->alertas_activas},{$p->nivel_riesgo},{$p->ultimo_contacto_iot}\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="pacientes_iot.csv"');
    }
};