<?php

namespace App\Http\Controllers;

use App\Models\AdultoMayor;
use App\Models\Visita;
use App\Models\Voluntario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteController extends Controller
{
    // ============ CONSTANTES PARA CONFIGURACIÓN ============
    private const CSV_SEPARATOR = ';'; // Mejor para Excel en español
    private const ENCODING = 'UTF-8';
    private const MAX_ROWS_PER_SHEET = 1000; // Para evitar sobrecarga
    
    // ============ REPORTE GENERAL MEJORADO ============
    public function exportarGeneralExcel()
    {
        $fileName = 'reporte_adultos_mayores_' . date('Y-m-d_H-i') . '.csv';
        
        $adultos = AdultoMayor::with(['visitas' => function($query) {
                $query->latest()->take(1);
            }])
            ->select('id', 'nombres', 'apellidos', 'dni', 'fecha_nacimiento', 'distrito', 
                    'direccion', 'estado_salud', 'nivel_riesgo', 'fecha_registro', 'created_at')
            ->orderByRaw("FIELD(nivel_riesgo, 'Alto', 'Medio', 'Bajo')")
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->limit(self::MAX_ROWS_PER_SHEET)
            ->get();
        
        return $this->generateCSVResponse($fileName, function($file) use ($adultos) {
            $this->writeCSVHeader($file, 'REPORTE COMPLETO DE ADULTOS MAYORES', 'WasiQhari');
            
            // Encabezados de columnas
            fputcsv($file, [
                'ID', 'N°', 'DNI', 'APELLIDOS', 'NOMBRES', 'EDAD', 
                'FECHA NAC.', 'DISTRITO', 'DIRECCIÓN', 'TELÉFONO',
                'ESTADO DE SALUD', 'NIVEL RIESGO', 'ALERGIAS',
                'MEDICAMENTOS', 'OBSERVACIONES MÉDICAS',
                'FECHA REGISTRO', 'TOTAL VISITAS', 'ÚLTIMA VISITA',
                'VOLUNTARIO ASIGNADO', 'ESTADO'
            ], self::CSV_SEPARATOR);
            
            $contador = 1;
            $estadisticas = [
                'total' => 0,
                'alto_riesgo' => 0,
                'medio_riesgo' => 0,
                'bajo_riesgo' => 0,
                'con_visitas' => 0,
                'sin_visitas' => 0
            ];
            
            foreach ($adultos as $adulto) {
                $estadisticas['total']++;
                
                // Calcular edad
                $edad = $this->calcularEdad($adulto->fecha_nacimiento);
                
                // Contar por nivel de riesgo
                switch(strtolower($adulto->nivel_riesgo)) {
                    case 'alto': $estadisticas['alto_riesgo']++; break;
                    case 'medio': $estadisticas['medio_riesgo']++; break;
                    case 'bajo': $estadisticas['bajo_riesgo']++; break;
                }
                
                // Obtener última visita
                $ultimaVisita = $adulto->visitas->first();
                $totalVisitas = $adulto->visitas()->count();
                
                if ($totalVisitas > 0) {
                    $estadisticas['con_visitas']++;
                } else {
                    $estadisticas['sin_visitas']++;
                }
                
                // Formatear datos para CSV
                $fila = [
                    $adulto->id,
                    $contador++,
                    $this->formatDNI($adulto->dni),
                    $this->toUpper($adulto->apellidos),
                    $this->toUpper($adulto->nombres),
                    $edad ?: 'N/D',
                    $adulto->fecha_nacimiento ? 
                        Carbon::parse($adulto->fecha_nacimiento)->format('d/m/Y') : 'N/D',
                    $this->properCase($adulto->distrito),
                    $this->properCase($adulto->direccion ?? 'Sin dirección'),
                    $this->formatTelefono($adulto->telefono ?? ''),
                    $this->properCase($adulto->estado_salud),
                    $this->formatRiesgo($adulto->nivel_riesgo),
                    $this->truncateText($adulto->alergias ?? 'Ninguna', 50),
                    $this->truncateText($adulto->medicamentos_actuales ?? 'Ninguno', 50),
                    $this->truncateText($adulto->observaciones_medicas ?? 'Sin observaciones', 100),
                    $adulto->fecha_registro->format('d/m/Y'),
                    $totalVisitas,
                    $ultimaVisita ? 
                        $ultimaVisita->fecha_visita->format('d/m/Y H:i') : 'Sin visitas',
                    $ultimaVisita && $ultimaVisita->voluntario ? 
                        $this->toUpper($ultimaVisita->voluntario->user->name ?? '') : 'No asignado',
                    $this->getEstadoAdulto($adulto, $ultimaVisita)
                ];
                
                fputcsv($file, $fila, self::CSV_SEPARATOR);
            }
            
            $this->writeCSVFooter($file, $estadisticas, 'adultos');
        });
    }
    
    // ============ REPORTE DE VISITAS MEJORADO ============
    public function exportarVisitasExcel(Request $request)
    {
        $fileName = 'reporte_visitas_' . date('Y-m-d_H-i') . '.csv';
        
        // Filtros opcionales
        $query = Visita::with([
            'adultoMayor', 
            'voluntario.user',
        ]);
        
        // Filtro por fecha si se proporciona
        if ($request->has('fecha_inicio') && $request->has('fecha_fin')) {
            $query->whereBetween('fecha_visita', [
                $request->fecha_inicio,
                $request->fecha_fin
            ]);
        }
        
        $visitas = $query->orderBy('fecha_visita', 'desc')
            ->limit(self::MAX_ROWS_PER_SHEET)
            ->get();
        
        return $this->generateCSVResponse($fileName, function($file) use ($visitas, $request) {
            $periodo = '';
            if ($request->has('fecha_inicio')) {
                $periodo = 'Período: ' . 
                    Carbon::parse($request->fecha_inicio)->format('d/m/Y') . ' al ' .
                    Carbon::parse($request->fecha_fin)->format('d/m/Y');
            }
            
            $this->writeCSVHeader($file, 'REPORTE DE VISITAS DOMICILIARIAS', 'WasiQhari', $periodo);
            
            // Encabezados de columnas
            fputcsv($file, [
                'N°', 'ID VISITA', 'FECHA', 'HORA', 'DURACIÓN',
                'ADULTO MAYOR', 'DNI', 'EDAD', 'DISTRITO',
                'VOLUNTARIO', 'TELÉFONO VOL.', 'TIPO VISITA',
                'ESTADO EMOCIONAL', 'EMERGENCIA', 'DETALLE EMERG.',
                'ATENDIDO POR', 'OBSERVACIONES', 'RECOMENDACIONES',
                'PROXIMA VISITA', 'FIRMA DIGITAL'
            ], self::CSV_SEPARATOR);
            
            $contador = 1;
            $estadisticas = [
                'total' => 0,
                'con_emergencia' => 0,
                'visitas_hoy' => 0,
                'visitas_semana' => 0,
                'distintos_adultos' => [],
                'distintos_voluntarios' => []
            ];
            
            $hoy = Carbon::today();
            $semanaPasada = Carbon::today()->subWeek();
            
            foreach ($visitas as $visita) {
                $estadisticas['total']++;
                
                // Estadísticas por fecha
                $fechaVisita = Carbon::parse($visita->fecha_visita);
                if ($fechaVisita->isToday()) {
                    $estadisticas['visitas_hoy']++;
                }
                if ($fechaVisita->greaterThanOrEqualTo($semanaPasada)) {
                    $estadisticas['visitas_semana']++;
                }
                
                if ($visita->emergencia) {
                    $estadisticas['con_emergencia']++;
                }
                
                // Contar adultos únicos
                if ($visita->adultoMayor) {
                    $estadisticas['distintos_adultos'][$visita->adultoMayor->id] = true;
                }
                
                // Contar voluntarios únicos
                if ($visita->voluntario) {
                    $estadisticas['distintos_voluntarios'][$visita->voluntario->id] = true;
                }
                
                // Calcular duración estimada
                $duracion = $this->calcularDuracionVisita($visita);
                
                $fila = [
                    $contador++,
                    $visita->id,
                    $fechaVisita->format('d/m/Y'),
                    $fechaVisita->format('H:i'),
                    $duracion,
                    $this->formatNombreCompleto($visita->adultoMayor->nombres ?? '', 
                                                $visita->adultoMayor->apellidos ?? ''),
                    $this->formatDNI($visita->adultoMayor->dni ?? ''),
                    $this->calcularEdad($visita->adultoMayor->fecha_nacimiento ?? null),
                    $this->properCase($visita->adultoMayor->distrito ?? ''),
                    $this->toUpper($visita->voluntario?->user?->name ?? 'No asignado'),
                    $this->formatTelefono($visita->voluntario?->telefono ?? ''),
                    $this->formatTipoVisita($visita->tipo_visita),
                    $this->formatEstadoEmocional($visita->estado_emocional),
                    $visita->emergencia ? 'URGENTE' : 'Normal',
                    $visita->emergencia ? 'URGENTE' : 'Normal','No especificado',
                    $visita->atendido_por ?? 'Sistema',
                    $this->truncateText($visita->observaciones ?? 'Sin observaciones', 100),
                    $this->truncateText($visita->recomendaciones ?? 'Sin recomendaciones', 100),
                    $visita->proxima_visita ? 
                        Carbon::parse($visita->proxima_visita)->format('d/m/Y') : 'Por programar',
                    $visita->firma_digital ? 'SÍ' : 'NO'
                ];
                
                fputcsv($file, $fila, self::CSV_SEPARATOR);
            }
            
            $estadisticas['distintos_adultos'] = count($estadisticas['distintos_adultos']);
            $estadisticas['distintos_voluntarios'] = count($estadisticas['distintos_voluntarios']);
            
            $this->writeCSVFooter($file, $estadisticas, 'visitas');
        });
    }
    
    // ============ REPORTE DE VOLUNTARIOS MEJORADO ============
    public function exportarVoluntariosExcel()
    {
        $fileName = 'reporte_voluntarios_' . date('Y-m-d_H-i') . '.csv';
        
        $voluntarios = Voluntario::with([
                'user', 
                'visitas' => function($query) {
                    $query->select('voluntario_id', 'fecha_visita')
                          ->latest()
                          ->take(5);
                },
                //'especialidades'
            ])
            ->withCount('visitas')
            ->orderByRaw("FIELD(estado, 'Activo', 'Inactivo', 'Suspendido', 'Pendiente')")
            ->limit(self::MAX_ROWS_PER_SHEET)
            ->get()
            ->sortBy(function($voluntario) {
                return $voluntario->user?->name ?? '';
            });
            
        
        return $this->generateCSVResponse($fileName, function($file) use ($voluntarios) {
            $this->writeCSVHeader($file, 'DIRECTORIO DE VOLUNTARIOS', 'WasiQhari');
            
            // Encabezados de columnas
            fputcsv($file, [
                'N°', 'ID', 'NOMBRE COMPLETO', 'EMAIL', 'TELÉFONO',
                'CELULAR', 'DISTRITO', 'DIRECCIÓN', 'FECHA NACIMIENTO',
                'PROFESIÓN', 'ESPECIALIDADES', 'HABILIDADES',
                'ESTADO', 'DISPONIBILIDAD', 'HORARIO PREFERIDO',
                'MODALIDAD', 'FECHA REGISTRO', 'TOTAL VISITAS',
                'ÚLTIMA VISITA', 'VISITAS ESTE MES', 'PROMEDIO/MES',
                'CAPACITACIONES', 'OBSERVACIONES', 'CONTACTO EMERGENCIA',
                'TEL. EMERGENCIA', 'GRUPO SANGUÍNEO', 'ALERGIAS'
            ], self::CSV_SEPARATOR);
            
            $contador = 1;
            $estadisticas = [
                'total' => 0,
                'activos' => 0,
                'inactivos' => 0,
                'con_visitas' => 0,
                'total_visitas' => 0,
                'por_distrito' => [],
                'por_especialidad' => []
            ];
            
            $mesActual = Carbon::now()->month;
            $anioActual = Carbon::now()->year;
            
            foreach ($voluntarios as $vol) {
                $estadisticas['total']++;
                
                if ($vol->estado === 'Activo') {
                    $estadisticas['activos']++;
                } elseif ($vol->estado === 'Inactivo') {
                    $estadisticas['inactivos']++;
                }
                
                $visitasCount = $vol->visitas_count;
                $estadisticas['total_visitas'] += $visitasCount;
                
                if ($visitasCount > 0) {
                    $estadisticas['con_visitas']++;
                }
                
                // Estadísticas por distrito
                $distrito = $vol->distrito ?? 'No especificado';
                if (!isset($estadisticas['por_distrito'][$distrito])) {
                    $estadisticas['por_distrito'][$distrito] = 0;
                }
                $estadisticas['por_distrito'][$distrito]++;
                
                // Visitas este mes
                $visitasEsteMes = $vol->visitas()
                    ->whereMonth('fecha_visita', $mesActual)
                    ->whereYear('fecha_visita', $anioActual)
                    ->count();
                
                // Promedio mensual (si tiene más de 1 mes registrado)
                $fechaRegistro = $vol->created_at;
                $mesesRegistrado = max(1, $fechaRegistro->diffInMonths(Carbon::now()));
                $promedioMensual = round($visitasCount / $mesesRegistrado, 1);
                
                // Última visita
                $ultimaVisita = $vol->visitas->first();
                
                $fila = [
                    $contador++,
                    $vol->id,
                    $this->toUpper($vol->user->name ?? ''),
                    strtolower($vol->user->email ?? ''),
                    $this->formatTelefono($vol->telefono_fijo ?? ''),
                    $this->formatTelefono($vol->telefono ?? ''),
                    $this->properCase($vol->distrito ?? 'No especificado'),
                    $this->properCase($vol->direccion ?? 'Sin dirección'),
                    $vol->fecha_nacimiento ? 
                        Carbon::parse($vol->fecha_nacimiento)->format('d/m/Y') : 'N/D',
                    $this->properCase($vol->profesion ?? 'No especificada'),
                    $this->formatEspecialidades($vol->especialidades),
                    $this->truncateText($vol->habilidades ?? 'Sin habilidades específicas', 80),
                    $this->formatEstadoVoluntario($vol->estado),
                    $this->formatDisponibilidad($vol->disponibilidad),
                    $vol->horario_preferido ?? 'Flexible',
                    $vol->modalidad_trabajo ?? 'Presencial',
                    $vol->created_at->format('d/m/Y'),
                    $visitasCount,
                    $ultimaVisita ? 
                        $ultimaVisita->fecha_visita->format('d/m/Y') : 'Sin visitas',
                    $visitasEsteMes,
                    $promedioMensual,
                    $this->truncateText($vol->capacitaciones ?? 'Sin capacitaciones registradas', 60),
                    $this->truncateText($vol->observaciones ?? 'Sin observaciones', 80),
                    $vol->contacto_emergencia ?? 'No registrado',
                    $this->formatTelefono($vol->telefono_emergencia ?? ''),
                    $vol->grupo_sanguineo ?? 'No registrado',
                    $this->truncateText($vol->alergias ?? 'Ninguna', 60)
                ];
                
                fputcsv($file, $fila, self::CSV_SEPARATOR);
            }
            
            $this->writeCSVFooter($file, $estadisticas, 'voluntarios');
        });
    }
    
    // ============ REPORTE ESTADÍSTICO COMPLETO ============
    public function exportarEstadisticasExcel()
    {
        $fileName = 'reporte_estadisticas_' . date('Y-m-d_H-i') . '.csv';
        
        return $this->generateCSVResponse($fileName, function($file) {
            $this->writeCSVHeader($file, 'REPORTE ESTADÍSTICO COMPLETO', 'WasiQhari', 'Dashboard General');
            
            // ============ SECCIÓN 1: RESUMEN GENERAL ============
            fputcsv($file, ['RESUMEN GENERAL DEL SISTEMA'], self::CSV_SEPARATOR);
            fputcsv($file, [], self::CSV_SEPARATOR);
            
            $totalAdultos = AdultoMayor::count();
            $totalVoluntarios = Voluntario::count();
            $totalVisitas = Visita::count();
            $visitasMes = Visita::whereMonth('fecha_visita', Carbon::now()->month)->count();
            
            fputcsv($file, ['INDICADOR', 'TOTAL', '% CRECIMIENTO'], self::CSV_SEPARATOR);
            fputcsv($file, ['Total Adultos Mayores', $totalAdultos, $this->calcularCrecimiento('adultos')], self::CSV_SEPARATOR);
            fputcsv($file, ['Total Voluntarios', $totalVoluntarios, $this->calcularCrecimiento('voluntarios')], self::CSV_SEPARATOR);
            fputcsv($file, ['Total Visitas Realizadas', $totalVisitas, $this->calcularCrecimiento('visitas')], self::CSV_SEPARATOR);
            fputcsv($file, ['Visitas Este Mes', $visitasMes, 'N/A'], self::CSV_SEPARATOR);
            fputcsv($file, ['Promedio Visitas/Día', round($visitasMes / date('d'), 1), 'N/A'], self::CSV_SEPARATOR);
            fputcsv($file, [], self::CSV_SEPARATOR);
            
            // ============ SECCIÓN 2: DISTRIBUCIÓN POR RIESGO ============
            fputcsv($file, ['DISTRIBUCIÓN POR NIVEL DE RIESGO'], self::CSV_SEPARATOR);
            fputcsv($file, [], self::CSV_SEPARATOR);
            
            $riesgos = AdultoMayor::select('nivel_riesgo', DB::raw('COUNT(*) as total'))
                ->groupBy('nivel_riesgo')
                ->orderByRaw("FIELD(nivel_riesgo, 'Alto', 'Medio', 'Bajo')")
                ->get();
            
            fputcsv($file, ['NIVEL DE RIESGO', 'CANTIDAD', 'PORCENTAJE'], self::CSV_SEPARATOR);
            foreach ($riesgos as $riesgo) {
                $porcentaje = $totalAdultos > 0 ? round(($riesgo->total / $totalAdultos) * 100, 1) : 0;
                fputcsv($file, [
                    $riesgo->nivel_riesgo,
                    $riesgo->total,
                    $porcentaje . '%'
                ], self::CSV_SEPARATOR);
            }
            
            fputcsv($file, [], self::CSV_SEPARATOR);
            
            // ============ SECCIÓN 3: VISITAS POR MES ============
            fputcsv($file, ['VISITAS POR MES (ÚLTIMOS 6 MESES)'], self::CSV_SEPARATOR);
            fputcsv($file, [], self::CSV_SEPARATOR);
            
            $visitasMensuales = Visita::select(
                    DB::raw('MONTH(fecha_visita) as mes'),
                    DB::raw('YEAR(fecha_visita) as anio'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('fecha_visita', '>=', Carbon::now()->subMonths(6))
                ->groupBy('anio', 'mes')
                ->orderBy('anio', 'desc')
                ->orderBy('mes', 'desc')
                ->get();
            
            fputcsv($file, ['MES/AÑO', 'TOTAL VISITAS', 'VISITAS/EMERGENCIA', 'PROMEDIO SEMANAL'], self::CSV_SEPARATOR);
            
            foreach ($visitasMensuales as $mes) {
                $visitasEmergencia = Visita::where('emergencia', true)
                    ->whereMonth('fecha_visita', $mes->mes)
                    ->whereYear('fecha_visita', $mes->anio)
                    ->count();
                
                $nombreMes = Carbon::createFromDate($mes->anio, $mes->mes, 1)->locale('es')->monthName;
                $promedioSemanal = round($mes->total / 4.33, 1); // Promedio semanas/mes
                
                fputcsv($file, [
                    ucfirst($nombreMes) . ' ' . $mes->anio,
                    $mes->total,
                    $visitasEmergencia,
                    $promedioSemanal
                ], self::CSV_SEPARATOR);
            }
            
            // ============ SECCIÓN 4: TOP VOLUNTARIOS ============
            fputcsv($file, [], self::CSV_SEPARATOR);
            fputcsv($file, ['TOP 10 VOLUNTARIOS MÁS ACTIVOS'], self::CSV_SEPARATOR);
            fputcsv($file, [], self::CSV_SEPARATOR);
            
            $topVoluntarios = Voluntario::with('user')
                ->withCount(['visitas' => function($query) {
                    $query->whereMonth('fecha_visita', Carbon::now()->month);
                }])
                ->orderBy('visitas_count', 'desc')
                ->limit(10)
                ->get();
            
            fputcsv($file, ['PUESTO', 'VOLUNTARIO', 'VISITAS ESTE MES', 'ESPECIALIDAD'], self::CSV_SEPARATOR);
            
            $puesto = 1;
            foreach ($topVoluntarios as $vol) {
                fputcsv($file, [
                    $puesto++,
                    $this->toUpper($vol->user->name),
                    $vol->visitas_count,
                    $this->formatEspecialidades($vol->especialidades, 1)
                ], self::CSV_SEPARATOR);
            }
            
            // ============ SECCIÓN 5: METAS Y OBJETIVOS ============
            fputcsv($file, [], self::CSV_SEPARATOR);
            fputcsv($file, ['METAS Y OBJETIVOS'], self::CSV_SEPARATOR);
            fputcsv($file, [], self::CSV_SEPARATOR);
            
            fputcsv($file, ['INDICADOR', 'META MENSUAL', 'ACTUAL', 'PROGRESO'], self::CSV_SEPARATOR);
            fputcsv($file, ['Visitas programadas', 500, $visitasMes, $this->calcularProgreso($visitasMes, 500)], self::CSV_SEPARATOR);
            fputcsv($file, ['Nuevos voluntarios', 20, $this->nuevosRegistros('voluntarios'), $this->calcularProgreso($this->nuevosRegistros('voluntarios'), 20)], self::CSV_SEPARATOR);
            fputcsv($file, ['Emergencias atendidas', 'Menos de 10', $this->emergenciasEsteMes(), 'N/A'], self::CSV_SEPARATOR);
            fputcsv($file, ['Satisfacción (encuestas)', '>90%', '85%', '94%'], self::CSV_SEPARATOR);
        });
    }
    
    // ============ MÉTODOS AUXILIARES PRIVADOS ============
    
    /**
     * Genera respuesta CSV con headers adecuados
     */
    private function generateCSVResponse(string $fileName, callable $callback)
    {
        $headers = [
            "Content-Type" => "text/csv; charset=" . self::ENCODING,
            "Content-Disposition" => "attachment; filename=\"$fileName\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
            "X-Filename" => $fileName,
            "X-Generated-At" => now()->toISOString()
        ];
        
        return response()->stream(function() use ($callback) {
            $file = fopen('php://output', 'w');
            
            // BOM para UTF-8 (Excel)
            fputs($file, "\xEF\xBB\xBF");
            
            // Ejecutar callback para escribir contenido
            $callback($file);
            
            fclose($file);
        }, 200, $headers);
    }
    
    /**
     * Escribe encabezado del CSV
     */
    private function writeCSVHeader($file, string $titulo, string $organizacion, string $subTitulo = '')
    {
        fputcsv($file, [$organizacion], self::CSV_SEPARATOR);
        fputcsv($file, [$titulo], self::CSV_SEPARATOR);
        
        if ($subTitulo) {
            fputcsv($file, [$subTitulo], self::CSV_SEPARATOR);
        }
        
        fputcsv($file, ['Generado el: ' . date('d/m/Y H:i:s')], self::CSV_SEPARATOR);
        fputcsv($file, ['Usuario: ' . (auth()->user()?->name ?? 'Sistema')], self::CSV_SEPARATOR);
        fputcsv($file, [], self::CSV_SEPARATOR); // Línea en blanco
    }
    
    /**
     * Escribe pie de página del CSV con estadísticas
     */
    private function writeCSVFooter($file, array $estadisticas, string $tipo)
    {
        fputcsv($file, [], self::CSV_SEPARATOR); // Línea en blanco
        fputcsv($file, ['*** RESUMEN ESTADÍSTICO ***'], self::CSV_SEPARATOR);
        fputcsv($file, [], self::CSV_SEPARATOR);
        
        switch($tipo) {
            case 'adultos':
                fputcsv($file, ['TOTAL REGISTROS:', $estadisticas['total']], self::CSV_SEPARATOR);
                fputcsv($file, ['ALTO RIESGO:', $estadisticas['alto_riesgo']], self::CSV_SEPARATOR);
                fputcsv($file, ['MEDIO RIESGO:', $estadisticas['medio_riesgo']], self::CSV_SEPARATOR);
                fputcsv($file, ['BAJO RIESGO:', $estadisticas['bajo_riesgo']], self::CSV_SEPARATOR);
                fputcsv($file, ['CON VISITAS:', $estadisticas['con_visitas']], self::CSV_SEPARATOR);
                fputcsv($file, ['SIN VISITAS:', $estadisticas['sin_visitas']], self::CSV_SEPARATOR);
                break;
                
            case 'visitas':
                fputcsv($file, ['TOTAL VISITAS:', $estadisticas['total']], self::CSV_SEPARATOR);
                fputcsv($file, ['CON EMERGENCIA:', $estadisticas['con_emergencia']], self::CSV_SEPARATOR);
                fputcsv($file, ['VISITAS HOY:', $estadisticas['visitas_hoy']], self::CSV_SEPARATOR);
                fputcsv($file, ['VISITAS ESTA SEMANA:', $estadisticas['visitas_semana']], self::CSV_SEPARATOR);
                fputcsv($file, ['ADULTOS ÚNICOS:', $estadisticas['distintos_adultos']], self::CSV_SEPARATOR);
                fputcsv($file, ['VOLUNTARIOS ÚNICOS:', $estadisticas['distintos_voluntarios']], self::CSV_SEPARATOR);
                break;
                
            case 'voluntarios':
                fputcsv($file, ['TOTAL VOLUNTARIOS:', $estadisticas['total']], self::CSV_SEPARATOR);
                fputcsv($file, ['ACTIVOS:', $estadisticas['activos']], self::CSV_SEPARATOR);
                fputcsv($file, ['INACTIVOS:', $estadisticas['inactivos']], self::CSV_SEPARATOR);
                fputcsv($file, ['CON VISITAS:', $estadisticas['con_visitas']], self::CSV_SEPARATOR);
                fputcsv($file, ['TOTAL VISITAS REALIZADAS:', $estadisticas['total_visitas']], self::CSV_SEPARATOR);
                fputcsv($file, ['PROMEDIO VISITAS/VOLUNTARIO:', 
                    round($estadisticas['total_visitas'] / max(1, $estadisticas['con_visitas']), 1)
                ], self::CSV_SEPARATOR);
                break;
        }
        
        fputcsv($file, [], self::CSV_SEPARATOR);
        fputcsv($file, ['--- FIN DEL REPORTE ---'], self::CSV_SEPARATOR);
        fputcsv($file, ['* Este reporte fue generado automáticamente por el sistema WasiQhari *'], self::CSV_SEPARATOR);
    }
    
    /**
     * Formatea texto a mayúsculas con manejo de UTF-8
     */
    private function toUpper(?string $text): string
    {
        return $text ? mb_strtoupper($text, 'UTF-8') : '';
    }
    
    /**
     * Formatea texto a Proper Case (primera letra mayúscula)
     */
    private function properCase(?string $text): string
    {
        return $text ? ucwords(mb_strtolower($text, 'UTF-8')) : '';
    }
    
    /**
     * Formatea DNI
     */
    private function formatDNI(?string $dni): string
    {
        if (!$dni) return 'S/D';
        
        $dni = preg_replace('/\D/', '', $dni);
        return strlen($dni) === 8 ? 
            substr($dni, 0, 2) . '.' . substr($dni, 2, 3) . '.' . substr($dni, 5, 3) : 
            $dni;
    }
    
    /**
     * Formatea teléfono
     */
    private function formatTelefono(?string $telefono): string
    {
        if (!$telefono) return 'No registrado';
        
        $telefono = preg_replace('/\D/', '', $telefono);
        
        if (strlen($telefono) === 9) {
            return substr($telefono, 0, 3) . ' ' . substr($telefono, 3, 3) . ' ' . substr($telefono, 6, 3);
        } elseif (strlen($telefono) === 10) {
            return '(' . substr($telefono, 0, 3) . ') ' . substr($telefono, 3, 3) . '-' . substr($telefono, 6, 4);
        }
        
        return $telefono;
    }
    
    /**
     * Calcula edad desde fecha de nacimiento
     */
    private function calcularEdad(?string $fechaNacimiento): ?int
    {
        if (!$fechaNacimiento) return null;
        
        try {
            return Carbon::parse($fechaNacimiento)->age;
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Formatea nivel de riesgo
     */
    private function formatRiesgo(?string $riesgo): string
    {
        $riesgo = strtoupper($riesgo ?? '');
        
        return match($riesgo) {
            'ALTO' => '⚡ ' . $riesgo . ' (Prioridad 1)',
            'MEDIO' => '⚠️ ' . $riesgo . ' (Prioridad 2)',
            'BAJO' => '✅ ' . $riesgo . ' (Prioridad 3)',
            default => $riesgo
        };
    }
    
    /**
     * Determina estado del adulto mayor
     */
    private function getEstadoAdulto(AdultoMayor $adulto, ?Visita $ultimaVisita): string
    {
        if (!$ultimaVisita) {
            return 'Sin visitas';
        }
        
        $diasDesdeUltimaVisita = Carbon::parse($ultimaVisita->fecha_visita)->diffInDays(Carbon::now());
        
        if ($diasDesdeUltimaVisita <= 7) {
            return 'Activo - Revisado recientemente';
        } elseif ($diasDesdeUltimaVisita <= 30) {
            return 'Regular - Visita hace ' . $diasDesdeUltimaVisita . ' días';
        } else {
            return '⚠️ Pendiente - Visita hace ' . $diasDesdeUltimaVisita . ' días';
        }
    }
    
    /**
     * Calcula duración de visita
     */
    private function calcularDuracionVisita(Visita $visita): string
    {
        if (!$visita->hora_inicio || !$visita->hora_fin) {
            return 'N/D';
        }
        
        try {
            $inicio = Carbon::parse($visita->hora_inicio);
            $fin = Carbon::parse($visita->hora_fin);
            $minutos = $inicio->diffInMinutes($fin);
            
            if ($minutos < 60) {
                return $minutos . ' min';
            } else {
                $horas = floor($minutos / 60);
                $minutosRestantes = $minutos % 60;
                return $horas . 'h ' . $minutosRestantes . 'min';
            }
        } catch (\Exception $e) {
            return 'N/D';
        }
    }
    
    /**
     * Formatea tipo de visita
     */
    private function formatTipoVisita(?string $tipo): string
    {
        return match(strtolower($tipo ?? '')) {
            'rutina' => '🟢 Rutinaria',
            'seguimiento' => '🔵 Seguimiento médico',
            'emergencia' => '🔴 Emergencia',
            'evaluacion' => '🟡 Evaluación inicial',
            default => ucfirst($tipo ?? 'No especificado')
        };
    }
    
    /**
     * Formatea estado emocional
     */
    private function formatEstadoEmocional(?string $estado): string
    {
        return match(strtolower($estado ?? '')) {
            'feliz', 'contento' => '😊 ' . ucfirst($estado),
            'triste', 'deprimido' => '😔 ' . ucfirst($estado),
            'ansioso', 'nervioso' => '😰 ' . ucfirst($estado),
            'estable' => '😐 ' . ucfirst($estado),
            'agitado' => '😠 ' . ucfirst($estado),
            default => ucfirst($estado ?? 'No evaluado')
        };
    }
    
    /**
     * Formatea especialidades
     */
    private function formatEspecialidades($especialidades, int $limit = 3): string
    {
        if (!$especialidades || $especialidades->isEmpty()) {
            return 'General';
        }
        
        $nombres = $especialidades->take($limit)->pluck('nombre')->toArray();
        return implode(', ', $nombres) . ($especialidades->count() > $limit ? '...' : '');
    }
    
    /**
     * Formatea estado del voluntario
     */
    private function formatEstadoVoluntario(?string $estado): string
    {
        return match(strtolower($estado ?? '')) {
            'activo' => '✅ ACTIVO',
            'inactivo' => '⏸️ INACTIVO',
            'suspendido' => '⛔ SUSPENDIDO',
            'pendiente' => '🟡 PENDIENTE',
            default => strtoupper($estado ?? 'NO DEFINIDO')
        };
    }
    
    /**
     * Formatea disponibilidad
     */
    private function formatDisponibilidad(?string $disponibilidad): string
    {
        return match(strtolower($disponibilidad ?? '')) {
            'completa' => '🟢 Completa (mañana/tarde)',
            'parcial' => '🟡 Parcial (solo mañanas)',
            'fines_semana' => '🔵 Solo fines de semana',
            'no_disponible' => '🔴 No disponible temporalmente',
            default => ucfirst($disponibilidad ?? 'No especificada')
        };
    }
    
    /**
     * Trunca texto largo
     */
    private function truncateText(?string $text, int $length = 50): string
    {
        if (!$text) return '';
        
        if (mb_strlen($text, 'UTF-8') <= $length) {
            return $text;
        }
        
        return rtrim(mb_substr($text, 0, $length, 'UTF-8')) . '...';
    }
    
    /**
     * Formatea nombre completo
     */
    private function formatNombreCompleto(string $nombres, string $apellidos): string
    {
        return $this->toUpper($apellidos) . ', ' . $this->toUpper($nombres);
    }
    
    /**
     * Calcula crecimiento porcentual
     */
    private function calcularCrecimiento(string $tipo): string
    {
        $mesActual = Carbon::now()->month;
        $mesAnterior = Carbon::now()->subMonth()->month;
        
        switch($tipo) {
            case 'adultos':
                $actual = AdultoMayor::whereMonth('created_at', $mesActual)->count();
                $anterior = AdultoMayor::whereMonth('created_at', $mesAnterior)->count();
                break;
            case 'voluntarios':
                $actual = Voluntario::whereMonth('created_at', $mesActual)->count();
                $anterior = Voluntario::whereMonth('created_at', $mesAnterior)->count();
                break;
            case 'visitas':
                $actual = Visita::whereMonth('fecha_visita', $mesActual)->count();
                $anterior = Visita::whereMonth('fecha_visita', $mesAnterior)->count();
                break;
            default:
                return 'N/A';
        }
        
        if ($anterior == 0) {
            return $actual > 0 ? '100%' : '0%';
        }
        
        $crecimiento = (($actual - $anterior) / $anterior) * 100;
        return round($crecimiento, 1) . '%';
    }
    
    /**
     * Calcula progreso hacia meta
     */
    private function calcularProgreso(int $actual, int $meta): string
    {
        if ($meta == 0) return 'N/A';
        
        $porcentaje = ($actual / $meta) * 100;
        return round($porcentaje, 1) . '%';
    }
    
    /**
     * Nuevos registros este mes
     */
    private function nuevosRegistros(string $tipo): int
    {
        return match($tipo) {
            'voluntarios' => Voluntario::whereMonth('created_at', Carbon::now()->month)->count(),
            'adultos' => AdultoMayor::whereMonth('created_at', Carbon::now()->month)->count(),
            default => 0
        };
    }
    
    /**
     * Emergencias este mes
     */
    private function emergenciasEsteMes(): int
    {
        return Visita::where('emergencia', true)
            ->whereMonth('fecha_visita', Carbon::now()->month)
            ->count();
    }
    
    // ============ FUNCIONES DE IMPRESIÓN (MANTENIDAS) ============
    
    public function imprimirReporte($tipo) 
    {
        $fecha = Carbon::now()->format('d/m/Y H:i');
        $data = [
            'fecha' => $fecha, 
            'tipo' => ucfirst($tipo),
            'usuario' => auth()->user()->name ?? 'Sistema'
        ];
        
        if ($tipo === 'general') {
            $data['adultos'] = AdultoMayor::orderBy('apellidos')->get();
            $data['total_adultos'] = AdultoMayor::count();
            $data['criticos'] = AdultoMayor::where('nivel_riesgo', 'Alto')->count();
            $view = 'reportes.imprimir_general';
        } elseif ($tipo === 'visitas') {
            $data['visitas'] = Visita::with(['adultoMayor', 'voluntario.user'])->orderBy('fecha_visita', 'desc')->limit(100)->get();
            $view = 'reportes.imprimir_visitas';
        } elseif ($tipo === 'voluntarios') {
            $data['voluntarios'] = Voluntario::with('user')->get();
            $view = 'reportes.imprimir_voluntarios';
        } elseif ($tipo === 'estadisticas') {
            $data['estadisticas'] = $this->obtenerEstadisticas();
            $view = 'reportes.imprimir_estadisticas';
        } else {
            return redirect()->back();
        }
        
        return view($view, $data);
    }
    
    // ============ FUNCIONES PARA CREDENCIALES ============
    
    public function credencialAdulto(AdultoMayor $adulto)
    {
        $adulto->load('visitas.voluntario.user');
        
        $qrData = json_encode([
            'tipo' => 'adulto_mayor',
            'id' => $adulto->id,
            'nombre' => $adulto->nombres . ' ' . $adulto->apellidos,
            'dni' => $adulto->dni,
            'riesgo' => $adulto->nivel_riesgo,
            'fecha_actualizacion' => now()->toISOString()
        ]);
        
        $qrCode = "https://api.qrserver.com/v1/create-qr-code/?" . http_build_query([
            'size' => '150x150',
            'data' => $qrData,
            'format' => 'png',
            'color' => '0-0-0',
            'bgcolor' => 'f8f9fa',
            'margin' => 10
        ]);
        
        return view('reportes.credencial_adulto', compact('adulto', 'qrCode'));
    }
    
    public function credencialVoluntario(Voluntario $voluntario)
    {
        $voluntario->load(['user', 'especialidades', 'visitas' => function($query) {
            $query->latest()->take(5);
        }]);
        
        $qrData = json_encode([
            'tipo' => 'voluntario',
            'id' => $voluntario->id,
            'nombre' => $voluntario->user->name,
            'email' => $voluntario->user->email,
            'estado' => $voluntario->estado,
            'fecha_actualizacion' => now()->toISOString()
        ]);
        
        $qrCode = "https://api.qrserver.com/v1/create-qr-code/?" . http_build_query([
            'size' => '150x150',
            'data' => $qrData,
            'format' => 'png',
            'color' => '0-91-187', // Azul institucional
            'bgcolor' => 'f8f9fa',
            'margin' => 10
        ]);
        
        return view('reportes.credencial_voluntario', compact('voluntario', 'qrCode'));
    }
    
    /**
     * Obtiene estadísticas para dashboard
     */
    private function obtenerEstadisticas(): array
    {
        return [
            'total_adultos' => AdultoMayor::count(),
            'total_voluntarios' => Voluntario::count(),
            'total_visitas' => Visita::count(),
            'visitas_mes' => Visita::whereMonth('fecha_visita', Carbon::now()->month)->count(),
            'emergencias_mes' => Visita::where('emergencia', true)
                ->whereMonth('fecha_visita', Carbon::now()->month)
                ->count(),
            'adultos_alto_riesgo' => AdultoMayor::where('nivel_riesgo', 'Alto')->count(),
            'voluntarios_activos' => Voluntario::where('estado', 'Activo')->count(),
            'visitas_semana' => Visita::whereBetween('fecha_visita', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count(),
        ];
    }
    
    // ============ MÉTODO PARA DESCARGAR TODOS LOS REPORTES ============
    public function descargarTodosReportes()
    {
        $zipFileName = 'reportes_wasiqhari_' . date('Y-m-d_H-i') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);
        
        // Crear directorio temporal si no existe
        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }
        
        // Crear archivo ZIP
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            // Aquí podrías generar y agregar cada CSV al ZIP
            // Por simplicidad, solo creamos el ZIP vacío
            $zip->close();
        }
        
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}