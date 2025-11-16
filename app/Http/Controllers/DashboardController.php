<?php

namespace App\Http\Controllers;

use App\Models\AdultoMayor;
use App\Models\Voluntario;
use App\Models\Visita;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        try {
            $data = [
                'title' => 'Dashboard - WasiQhari',
                'page' => 'dashboard',
                'stats' => [
                    'total_adultos' => AdultoMayor::count(),
                    'total_voluntarios' => Voluntario::where('estado', 'Activo')->count(),
                    'total_visitas' => Visita::count(),
                    'adultos_criticos' => AdultoMayor::where('estado_salud', 'Critico')->count(),
                    'ultimas_visitas' => Visita::with(['adulto', 'voluntario.user'])
                                            ->orderBy('fecha_visita', 'DESC')
                                            ->limit(5)
                                            ->get(),
                    'distribucion_distritos' => AdultoMayor::select('distrito', DB::raw('COUNT(*) as cantidad'))
                                                        ->groupBy('distrito')
                                                        ->get()
                ]
            ];
            
            return view('dashboard.index', $data);
            
        } catch (\Exception $e) {
            // Si hay errores, mostrar datos básicos
            $data = [
                'title' => 'Dashboard - WasiQhari',
                'page' => 'dashboard',
                'stats' => [
                    'total_adultos' => 0,
                    'total_voluntarios' => 0,
                    'total_visitas' => 0,
                    'adultos_criticos' => 0,
                    'ultimas_visitas' => [],
                    'distribucion_distritos' => []
                ]
            ];
            
            return view('dashboard.index', $data);
        }
    }
    
    public function adultos()
    {
        $data = [
            'title' => 'Gestión de Adultos Mayores - WasiQhari',
            'page' => 'adultos',
            'adultos' => AdultoMayor::orderBy('fecha_registro', 'DESC')->get()
        ];
        
        return view('dashboard.adultos', $data);
    }
    
    public function voluntarios()
    {
        $data = [
            'title' => 'Gestión de Voluntarios - WasiQhari',
            'page' => 'voluntarios',
            'voluntarios' => Voluntario::with('user')
                                    ->orderBy('fecha_registro', 'DESC')
                                    ->get()
        ];
        
        return view('dashboard.voluntarios', $data);
    }
    
    public function visitas()
    {
        $data = [
            'title' => 'Registro de Visitas - WasiQhari',
            'page' => 'visitas',
            'visitas' => Visita::with(['adulto', 'voluntario'])
                            ->orderBy('fecha_visita', 'DESC')
                            ->get(),
            'adultos' => AdultoMayor::orderBy('nombres')->get(),
            'voluntarios' => User::where('role', 'voluntario')->get()
        ];
        
        return view('dashboard.visitas', $data);
    }

    public function settings()
    {
        $data = [
            'title' => 'Configuración - WasiQhari',
            'page' => 'settings'
        ];
        
        return view('dashboard.settings', $data);
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
        $adultoModel = new AdultoMayor();
        
        $data = [
            'title' => 'Reportes y Estadísticas - WasiQhari',
            'page' => 'reporters',
            'estadisticas' => $adultoModel->getEstadisticasCompletas()
        ];
        
        return view('dashboard.reporters', $data);
    }

    // Métodos para manejar formularios (si los necesitas)
    public function storeAdulto(Request $request)
    {
        $request->validate([
            'dni' => 'required|unique:adultos_mayores,dni',
            'nombres' => 'required',
            'apellidos' => 'required',
            'fecha_nacimiento' => 'required|date',
        ]);

        AdultoMayor::create($request->all());

        return redirect()->route('adultos')
                        ->with('success', 'Adulto mayor registrado correctamente.');
    }

    public function storeVisita(Request $request)
    {
        $request->validate([
            'adulto_id' => 'required|exists:adultos_mayores,id',
            'voluntario_id' => 'required|exists:users,id',
            'fecha_visita' => 'required|date',
            'tipo_visita' => 'required',
        ]);

        Visita::create($request->all());

        return redirect()->route('visitas')
                        ->with('success', 'Visita registrada correctamente.');
    }
}