<?php

namespace App\Http\Controllers;

use App\Models\AdultoMayor;
use App\Models\Visita;
use App\Models\Voluntario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ¡Importante! Añade esto.

class DashboardController extends Controller
{
    /**
     * Muestra la vista principal del dashboard con todas las estadísticas.
     */
    public function index()
    {
        // --- ¡AQUÍ ESTÁ LA MAGIA! ---

        // 1. Obtenemos las últimas 5 visitas
        // Usamos with() para traer al adulto y voluntario en una sola consulta. ¡Es más rápido!
        // Nota: cargamos 'voluntario.user' como lo pide tu vista.
        $ultimasVisitas = Visita::with(['adultoMayor', 'voluntario.user'])
                                ->latest() // Ordena por fecha (la más nueva primero)
                                ->take(5)    // Trae solo 5
                                ->get();

        // 2. Obtenemos los adultos mayores para el mapa
        $adultosParaMapa = AdultoMayor::whereNotNull('lat')
                                      ->whereNotNull('lon')
                                      ->get(['nombres', 'apellidos', 'lat', 'lon', 'nivel_riesgo']);

        // 3. Datos para el gráfico de Salud
        // Contamos cuántos hay de cada estado de salud
        $saludData = AdultoMayor::select('estado_salud', DB::raw('count(*) as total'))
                         ->groupBy('estado_salud')
                         ->pluck('total', 'estado_salud'); // Devuelve un array como ['Bueno' => 10, 'Regular' => 5]

        // 4. Datos para el gráfico de Actividades en Calle
        $actividadesData = AdultoMayor::select('actividad_calle', DB::raw('count(*) as total'))
                         ->groupBy('actividad_calle')
                         ->pluck('total', 'actividad_calle');

        // 5. Datos de distribución por distrito
        $distribucionDistritos = AdultoMayor::select('distrito', DB::raw('count(*) as cantidad'))
                       ->whereNotNull('distrito')
                       ->where('distrito', '!=', '')
                       ->groupBy('distrito')
                       ->orderBy('cantidad', 'desc')
                       ->get();

        // 6. Creamos el array de $stats que tu vista espera
        $stats = [
            'total_adultos' => AdultoMayor::count(),
            'total_voluntarios' => Voluntario::count(),
            'total_visitas' => Visita::count(),
            'adultos_criticos' => AdultoMayor::where('nivel_riesgo', 'Alto')->count(), // Mapeamos 'Alto' a 'Crítico'
            'ultimas_visitas' => $ultimasVisitas,
            'distribucion_distritos' => $distribucionDistritos
        ];

        // 7. Pasamos toda la información a la vista
        $data = [
            'title' => 'Dashboard - WasiQhari',
            'page' => 'dashboard',
            'stats' => $stats, // El array grande que tu vista usa
            'adultosParaMapa' => $adultosParaMapa, // Para el script del mapa
            'saludData' => $saludData, // Para el gráfico de salud
            'actividadesData' => $actividadesData, // Para el gráfico de actividades
        ];
        
        return view('dashboard.index', $data);
    }

    // --- EL RESTO DE TUS FUNCIONES (sin cambios) ---

    public function adultos()
    {
        $data = [
            'title' => 'Gestión de Adultos - WasiQhari',
            'page' => 'adultos'
        ];
        return view('dashboard.adultos', $data);
    }

    public function storeAdulto(Request $request)
    {
        // Lógica para guardar adulto
    }

    public function voluntarios()
    {
        $data = [
            'title' => 'Gestión de Voluntarios - WasiQhari',
            'page' => 'voluntarios'
        ];
        return view('dashboard.voluntarios', $data);
    }

    public function visitas()
    {
        $data = [
            'title' => 'Gestión de Visitas - WasiQhari',
            'page' => 'visitas'
        ];
        return view('dashboard.visitas', $data);
    }

    public function storeVisita(Request $request)
    {
        // Lógica para guardar visita
    }

    public function ai()
    {
        $data = [
            'title' => 'Análisis IA - WasiQhari',
            'page' => 'ai'
        ];
        return view('dashboard.ai', $data);
    }

    public function reporters() // (Tenías 'reporters', quizás quisiste decir 'reportes')
    {
        $data = [
            'title' => 'Reportes - WasiQhari',
            'page' => 'reportes'
        ];
        return view('dashboard.reportes', $data);
    }

    public function settings()
    {
        $data = [
            'title' => 'Configuración - WasiQhari',
            'page' => 'settings'
        ];
        return view('dashboard.settings', $data);
    }
}