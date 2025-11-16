<?php

namespace App\Http\Controllers;

// ¡IMPORTANTE! Asegúrate de importar todos los modelos que vamos a usar
use App\Models\AdultoMayor;
use App\Models\Visita;
use App\Models\Voluntario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Muestra la vista principal del dashboard con todas las estadísticas.
     * (Esta función ya la teníamos bien)
     */
    public function index()
    {
        // 1. Obtenemos las últimas 5 visitas
        $ultimasVisitas = Visita::with(['adultoMayor', 'voluntario.user'])
                                ->latest('fecha_visita') // Ordena por fecha de visita
                                ->take(5)
                                ->get();

        // 2. Obtenemos los adultos mayores para el mapa
        $adultosParaMapa = AdultoMayor::whereNotNull('lat')
                                      ->whereNotNull('lon')
                                      ->get(['nombres', 'apellidos', 'lat', 'lon', 'nivel_riesgo']);

        // 3. Datos para el gráfico de Salud
        $saludData = AdultoMayor::select('estado_salud', DB::raw('count(*) as total'))
                         ->groupBy('estado_salud')
                         ->pluck('total', 'estado_salud');

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
            'adultos_criticos' => AdultoMayor::where('nivel_riesgo', 'Alto')->count(), 
            'ultimas_visitas' => $ultimasVisitas,
            'distribucion_distritos' => $distribucionDistritos
        ];

        // 7. Pasamos toda la información a la vista
        $data = [
            'title' => 'Dashboard - WasiQhari',
            'page' => 'dashboard',
            'stats' => $stats,
            'adultosParaMapa' => $adultosParaMapa,
            'saludData' => $saludData,
            'actividadesData' => $actividadesData,
        ];
        
        return view('dashboard.index', $data);
    }

    // --- ¡AQUÍ VIENEN LAS CORRECCIONES! ---

    /**
     * Muestra la página de Gestión de Adultos
     */
    public function adultos()
    {
        // ¡ARREGLADO! Ahora buscamos los adultos y los pasamos a la vista.
        $adultos = AdultoMayor::latest('fecha_registro')->get(); // Obtenemos todos los adultos
        
        $data = [
            'title' => 'Gestión de Adultos - WasiQhari',
            'page' => 'adultos',
            'adultos' => $adultos // <-- ¡LA VARIABLE QUE FALTABA!
        ];
        
        return view('dashboard.adultos', $data);
    }

    public function storeAdulto(Request $request)
    {
        // Lógica para guardar adulto
        // (Asegúrate de que esta lógica esté implementada)
    }

    /**
     * Muestra la página de Gestión de Voluntarios
     */
    public function voluntarios()
    {
        // ¡ARREGLADO! Ahora buscamos los voluntarios y los pasamos a la vista.
        $voluntarios = Voluntario::with('user')->get(); // Obtenemos voluntarios con su info de user
        
        $data = [
            'title' => 'Gestión de Voluntarios - WasiQhari',
            'page' => 'voluntarios',
            'voluntarios' => $voluntarios // <-- ¡LA VARIABLE QUE FALTABA!
        ];
        
        return view('dashboard.voluntarios', $data);
    }

    /**
     * Muestra la página de Gestión de Visitas
     */
    public function visitas()
    {
        // ¡ARREGLADO! Buscamos visitas, adultos y voluntarios.
        $visitas = Visita::with(['adultoMayor', 'voluntario.user'])
                         ->latest('fecha_visita')
                         ->get();
                         
        $adultos = AdultoMayor::all(['id', 'nombres', 'apellidos']); // Para el dropdown
        $voluntarios = Voluntario::with('user')->get(); // Para el dropdown
        
        $data = [
            'title' => 'Gestión de Visitas - WasiQhari',
            'page' => 'visitas',
            'visitas' => $visitas,         // <-- Variable para la tabla
            'adultos' => $adultos,         // <-- Variable para el formulario
            'voluntarios' => $voluntarios  // <-- ¡LA VARIABLE QUE FALTABA!
        ];
        
        return view('dashboard.visitas', $data);
    }

    public function storeVisita(Request $request)
    {
        // Lógica para guardar visita
        // (Asegúrate de que esta lógica esté implementada)
    }

    public function ai()
    {
        $data = [
            'title' => 'Análisis IA - WasiQhari',
            'page' => 'ai'
        ];
        return view('dashboard.ai', $data);
    }

    public function reporters()
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