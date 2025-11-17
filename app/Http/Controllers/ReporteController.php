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
    // --- 1. EXPORTAR A EXCEL (CSV) ---
    
    public function exportarGeneralExcel()
    {
        $fileName = 'reporte_general_' . date('Y-m-d') . '.csv';
        
        // Preparamos los datos de Adultos Mayores
        $adultos = AdultoMayor::select('nombres', 'apellidos', 'dni', 'distrito', 'estado_salud', 'nivel_riesgo', 'fecha_registro')->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = function() use ($adultos) {
            $file = fopen('php://output', 'w');
            
            // Escribir BOM para que Excel reconozca caracteres latinos (ñ, tildes)
            fputs($file, "\xEF\xBB\xBF"); 
            
            // Encabezados
            fputcsv($file, ['Nombres', 'Apellidos', 'DNI', 'Distrito', 'Salud', 'Riesgo', 'Fecha Registro']);

            // Datos
            foreach ($adultos as $adulto) {
                fputcsv($file, [
                    $adulto->nombres,
                    $adulto->apellidos,
                    $adulto->dni ?? 'S/D',
                    $adulto->distrito,
                    $adulto->estado_salud,
                    $adulto->nivel_riesgo,
                    $adulto->fecha_registro->format('d/m/Y')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportarVisitasExcel()
    {
        $fileName = 'reporte_visitas_' . date('Y-m-d') . '.csv';
        $visitas = Visita::with(['adultoMayor', 'voluntario.user'])->orderBy('fecha_visita', 'desc')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($visitas) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, ['Fecha', 'Hora', 'Adulto Mayor', 'Voluntario', 'Tipo Visita', 'Estado Emocional', 'Emergencia']);

            foreach ($visitas as $visita) {
                fputcsv($file, [
                    $visita->fecha_visita->format('d/m/Y'),
                    $visita->fecha_visita->format('H:i'),
                    $visita->adultoMayor->nombres . ' ' . $visita->adultoMayor->apellidos,
                    $visita->voluntario->user->name,
                    $visita->tipo_visita,
                    $visita->estado_emocional,
                    $visita->emergencia ? 'SI' : 'NO'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    
    public function exportarVoluntariosExcel()
    {
        $fileName = 'reporte_voluntarios_' . date('Y-m-d') . '.csv';
        $voluntarios = Voluntario::with('user')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($voluntarios) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, ['Nombre', 'Email', 'Teléfono', 'Distrito', 'Estado', 'Disponibilidad']);

            foreach ($voluntarios as $vol) {
                fputcsv($file, [
                    $vol->user->name,
                    $vol->user->email,
                    $vol->telefono ?? '-',
                    $vol->distrito ?? '-',
                    $vol->estado,
                    $vol->disponibilidad
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    // --- 2. GENERAR PDF (VISTA IMPRIMIBLE) ---

    public function imprimirReporte($tipo)
    {
        $fecha = Carbon::now()->format('d/m/Y');
        $data = [
            'fecha' => $fecha,
            'tipo' => ucfirst($tipo)
        ];

        if ($tipo === 'general') {
            $data['adultos'] = AdultoMayor::orderBy('apellidos')->get();
            $data['total_adultos'] = AdultoMayor::count();
            $data['criticos'] = AdultoMayor::where('nivel_riesgo', 'Alto')->count();
            $view = 'reportes.imprimir_general';
        } 
        elseif ($tipo === 'visitas') {
            $data['visitas'] = Visita::with(['adultoMayor', 'voluntario.user'])
                                     ->orderBy('fecha_visita', 'desc')
                                     ->limit(100) // Limitamos a 100 para PDF
                                     ->get();
            $view = 'reportes.imprimir_visitas';
        }
        elseif ($tipo === 'voluntarios') {
            $data['voluntarios'] = Voluntario::with('user')->get();
            $view = 'reportes.imprimir_voluntarios';
        }
        else {
            return redirect()->back();
        }

        return view($view, $data);
    }
}