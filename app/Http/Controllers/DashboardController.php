<?php

namespace App\Http\Controllers;

use App\Models\AdultoMayor;
use App\Models\Visita;
use App\Models\Voluntario;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\ComentarioVisita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    // --- DASHBOARD PRINCIPAL ---
    public function index()
    {
        $ultimasVisitas = Visita::with(['adultoMayor', 'voluntario.user'])
            ->latest('fecha_visita')
            ->take(5)
            ->get();

        $adultosParaMapa = AdultoMayor::whereNotNull('lat')
            ->whereNotNull('lon')
            ->get(['nombres', 'apellidos', 'lat', 'lon', 'nivel_riesgo']);

        $saludData = AdultoMayor::select('estado_salud', DB::raw('count(*) as total'))
            ->groupBy('estado_salud')
            ->pluck('total', 'estado_salud');

        $actividadesData = AdultoMayor::select('actividad_calle', DB::raw('count(*) as total'))
            ->groupBy('actividad_calle')
            ->pluck('total', 'actividad_calle');

        $distribucionDistritos = AdultoMayor::select('distrito', DB::raw('count(*) as cantidad'))
            ->whereNotNull('distrito')
            ->where('distrito', '!=', '')
            ->groupBy('distrito')
            ->orderBy('cantidad', 'desc')
            ->get();
        
        $stats = [
            'total_adultos' => AdultoMayor::count(),
            'total_voluntarios' => Voluntario::count(),
            'total_visitas' => Visita::count(),
            'adultos_criticos' => AdultoMayor::where('nivel_riesgo', 'Alto')->count(), 
            'ultimas_visitas' => $ultimasVisitas,
            'distribucion_distritos' => $distribucionDistritos
        ];
        
        return view('dashboard.index', [
            'title' => 'Dashboard - WasiQhari',
            'page' => 'dashboard',
            'stats' => $stats,
            'adultosParaMapa' => $adultosParaMapa,
            'saludData' => $saludData,
            'actividadesData' => $actividadesData,
        ]);
    }

    // --- AUDITORÍA ---
    public function auditoria()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(20);
        
        return view('dashboard.auditoria', [
            'title' => 'Auditoría del Sistema',
            'page' => 'auditoria',
            'logs' => $logs
        ]);
    }

    // --- GESTIÓN DE ADULTOS ---
    public function adultos(Request $request)
    {
        $query = AdultoMayor::search($request->search)->latest('fecha_registro');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombres', 'like', "%$search%")
                  ->orWhere('apellidos', 'like', "%$search%")
                  ->orWhere('dni', 'like', "%$search%");
            });
        }

        if ($request->has('riesgo') && $request->riesgo != '') {
            $query->where('nivel_riesgo', $request->riesgo);
        }

        $adultos = $query->paginate(10)->withQueryString();
        
        if ($request->ajax()) {
            return view('dashboard.partials.tabla_adultos', compact('adultos'))->render();
        }
        
        return view('dashboard.adultos', [
            'title' => 'Gestión de Adultos - WasiQhari',
            'page' => 'adultos',
            'adultos' => $adultos
        ]);
    }

    public function storeAdulto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_registro' => 'required|date',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'dni' => 'nullable|string|max:8|unique:adultos_mayores,dni',
            'sexo' => 'required',
            'fecha_nacimiento' => 'required',
            'edad' => 'required',
            'distrito' => 'required',
            'zona_ubicacion' => 'required',
            'lee_escribe' => 'required',
            'nivel_estudio' => 'required',
            'apoyo_familiar' => 'required',
            'estado_abandono' => 'required',
            'estado_salud' => 'required',
            'actividad_calle' => 'required',
            'nivel_riesgo' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('adultos')
                ->withErrors($validator)
                ->withInput()
                ->with('error_form', 'Error al guardar.');
        }

        $adulto = AdultoMayor::create($request->all());
        
        ActivityLog::registrar('Crear', 'Adultos', "Registró al beneficiario {$adulto->nombres} {$adulto->apellidos}");

        return redirect()->route('adultos')->with('success', 'Registrado con éxito');
    }
    
    public function show(AdultoMayor $adulto)
    {
        return response()->json($adulto);
    }
    
    public function update(Request $request, AdultoMayor $adulto)
    {
        $adulto->update($request->all());
        
        ActivityLog::registrar('Actualizar', 'Adultos', "Actualizó datos de {$adulto->nombres} {$adulto->apellidos}");
        
        return redirect()->route('adultos')->with('success', 'Actualizado con éxito');
    }
    
    public function destroy(AdultoMayor $adulto)
    {
        $nombre = $adulto->nombres . ' ' . $adulto->apellidos;
        $adulto->delete();
        
        ActivityLog::registrar('Eliminar', 'Adultos', "Eliminó el registro de {$nombre}");
        
        return response()->json(['success' => true, 'message' => 'Eliminado con éxito']);
    }

    // --- GESTIÓN DE VOLUNTARIOS ---
    public function voluntarios(Request $request)
    {
        $query = Voluntario::with('user');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        $voluntarios = $query->paginate(10)->withQueryString();
        
        return view('dashboard.voluntarios', [
            'title' => 'Gestión de Voluntarios - WasiQhari',
            'page' => 'voluntarios',
            'voluntarios' => $voluntarios
        ]);
    }
    
    public function showVoluntario(Voluntario $voluntario)
    {
        $voluntario->load('user');
        return response()->json($voluntario);
    }
    
    public function updateVoluntario(Request $request, Voluntario $voluntario)
    {
        $voluntario->user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);
        
        $voluntario->update($request->except(['name', 'email']));
        
        ActivityLog::registrar('Actualizar', 'Voluntarios', "Actualizó perfil de {$voluntario->user->name}");
        
        return redirect()->route('voluntarios')->with('success', 'Actualizado con éxito');
    }
    
    public function destroyVoluntario(Voluntario $voluntario)
    {
        $nombre = $voluntario->user->name;
        $voluntario->delete();
        
        ActivityLog::registrar('Eliminar', 'Voluntarios', "Eliminó al voluntario {$nombre}");
        
        return response()->json(['success' => true, 'message' => 'Eliminado con éxito']);
    }

    // --- GESTIÓN DE VISITAS ---
    public function visitas()
    {
        $visitas = Visita::with(['adultoMayor', 'voluntario.user'])
            ->latest('fecha_visita')
            ->paginate(10);
            
        $adultos = AdultoMayor::all(['id', 'nombres', 'apellidos']);
        $voluntarios = Voluntario::with('user')->get();
        
        return view('dashboard.visitas', [
            'title' => 'Gestión de Visitas - WasiQhari',
            'page' => 'visitas',
            'visitas' => $visitas,
            'adultos' => $adultos,
            'voluntarios' => $voluntarios
        ]);
    }
    
    public function storeVisita(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'adulto_id' => 'required|exists:adultos_mayores,id',
            'voluntario_id' => 'required|exists:voluntarios,id',
            'fecha_visita' => 'required|date',
            'foto_evidencia' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->route('visitas')
                ->withErrors($validator)
                ->withInput()
                ->with('error_form', 'Error al guardar.');
        }

        $data = $request->all();

        if ($request->hasFile('foto_evidencia')) {
            $path = $request->file('foto_evidencia')->store('evidencias', 'public');
            $data['foto_evidencia'] = $path;
        }

        $visita = Visita::create($data);

        ActivityLog::registrar('Crear', 'Visitas', "Registró visita ID #{$visita->id}");

        // NOTIFICACIÓN DE EMERGENCIA
        if ($visita->emergencia) {
            $usersToNotify = User::all(); 
            foreach ($usersToNotify as $user) {
                $user->notify(new \App\Notifications\NuevaEmergencia($visita));
            }
        }

        return redirect()->route('visitas')->with('success', '¡Visita registrada con éxito!');
    }
    
    public function showVisita(Visita $visita)
    {
        $visita->load(['adultoMayor', 'voluntario.user', 'comentarios.user']);
        
        if ($visita->foto_evidencia) {
            $visita->foto_url = asset('storage/' . $visita->foto_evidencia);
        }

        return response()->json($visita);
    }

    public function updateVisita(Request $request, Visita $visita)
    {
        $data = $request->all();
        
        if ($request->hasFile('foto_evidencia')) {
            if ($visita->foto_evidencia) {
                Storage::disk('public')->delete($visita->foto_evidencia);
            }
            $data['foto_evidencia'] = $request->file('foto_evidencia')->store('evidencias', 'public');
        }
        
        $visita->update($data);
        
        ActivityLog::registrar('Actualizar', 'Visitas', "Actualizó visita #{$visita->id}");

        return redirect()->route('visitas')->with('success', 'Actualizada');
    }

    public function destroyVisita(Visita $visita)
    {
        if ($visita->foto_evidencia) {
            Storage::disk('public')->delete($visita->foto_evidencia);
        }
        
        $visita->delete();
        
        ActivityLog::registrar('Eliminar', 'Visitas', "Eliminó visita #{$visita->id}");

        return response()->json(['success' => true, 'message' => 'Eliminada']);
    }

    public function storeComentario(Request $request, Visita $visita)
    {
        $request->validate([
            'contenido' => 'required|string|max:500'
        ]);
        
        $comentario = ComentarioVisita::create([
            'visita_id' => $visita->id,
            'user_id' => auth()->id(),
            'contenido' => $request->contenido
        ]);
        
        return response()->json($comentario->load('user'));
    }

    // --- OTRAS FUNCIONES ---
    public function markNotificationsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }

    public function calendario()
    {
        return view('dashboard.calendario', [
            'title' => 'Calendario',
            'page' => 'calendario'
        ]);
    }
    
    public function getEventosCalendario()
    {
        $visitas = Visita::with(['adultoMayor', 'voluntario.user'])->get();
        
        $eventos = $visitas->map(function($visita) {
            $color = $visita->emergencia ? '#e74c3c' : '#3788d8';
            
            // Construimos el título con cuidado para evitar errores si falta info
            $nombreAdulto = $visita->adultoMayor->nombres ?? 'N/A';
            $nombreVoluntario = $visita->voluntario->user->name ?? 'N/A';
            
            return [
                'id' => $visita->id,
                'title' => "$nombreAdulto - $nombreVoluntario",
                'start' => $visita->fecha_visita->format('Y-m-d\TH:i:s'),
                'backgroundColor' => $color,
                'borderColor' => $color,
                // Propiedades extendidas para el modal del calendario
                'extendedProps' => [
                    'tipo' => $visita->tipo_visita,
                    'adulto' => $nombreAdulto,
                    'voluntario' => $nombreVoluntario,
                    'emergencia' => $visita->emergencia ? 'SI' : 'No',
                    'observaciones' => $visita->observaciones ?? 'Sin observaciones'
                ]
            ];
        });
        
        return response()->json($eventos);
    }
    
    public function ai()
    {
        return view('dashboard.ai', [
            'title' => 'IA',
            'page' => 'ai'
        ]);
    }
    
    public function reporters() 
    {
        return view('dashboard.reportes', [
            'title' => 'Reportes',
            'page' => 'reportes'
        ]);
    }
    
    public function settings()
    {
        return view('dashboard.settings', [
            'title' => 'Config',
            'page' => 'settings'
        ]);
    }

    // --- GESTIÓN DE INVENTARIO ---
    public function inventario(Request $request)
    {
        $query = \App\Models\Inventario::query();

        // Filtros
        if ($request->has('search') && $request->search != '') {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }
        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('categoria', $request->categoria);
        }

        $items = $query->latest()->paginate(10)->withQueryString();
        
        // Alertas de stock bajo (menos de 10 unidades)
        $stockBajo = \App\Models\Inventario::where('cantidad', '<', 10)->count();
        $totalItems = \App\Models\Inventario::count();

        return view('dashboard.inventario', [
            'title' => 'Gestión de Inventario - WasiQhari',
            'page' => 'inventario',
            'items' => $items,
            'stockBajo' => $stockBajo,
            'totalItems' => $totalItems
        ]);
    }

    public function storeInventario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|in:Alimentos,Medicinas,Ropa,Equipamiento,Otros',
            'cantidad' => 'required|integer|min:0',
            'unidad' => 'required|string|max:50',
            'fecha_vencimiento' => 'nullable|date',
            'descripcion' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->route('inventario')
                ->withErrors($validator)
                ->withInput()
                ->with('error_form', 'Error al guardar el ítem.');
        }

        $data = $request->all();
        // Estado automático
        $data['estado'] = $request->cantidad > 0 ? 'Disponible' : 'Agotado';

        $item = \App\Models\Inventario::create($data);

        // LOG
        ActivityLog::registrar('Crear', 'Inventario', "Agregó {$item->cantidad} {$item->unidad} de {$item->nombre}");

        return redirect()->route('inventario')->with('success', 'Ítem registrado con éxito');
    }

    public function showInventario(\App\Models\Inventario $item)
    {
        return response()->json($item);
    }

    public function updateInventario(Request $request, \App\Models\Inventario $item)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        $data['estado'] = $request->cantidad > 0 ? 'Disponible' : 'Agotado';
        
        $item->update($data);

        // LOG
        ActivityLog::registrar('Actualizar', 'Inventario', "Actualizó stock de {$item->nombre} a {$item->cantidad}");

        return redirect()->route('inventario')->with('success', 'Inventario actualizado');
    }

    public function destroyInventario(\App\Models\Inventario $item)
    {
        $nombre = $item->nombre;
        $item->delete();

        // LOG
        ActivityLog::registrar('Eliminar', 'Inventario', "Eliminó el ítem {$nombre}");

        return response()->json(['success' => true, 'message' => 'Ítem eliminado']);
    }
    // --- EXPEDIENTE EVOLUTIVO ---
    public function evolucionAdulto(AdultoMayor $adulto)
    {
        // 1. Obtener visitas ordenadas cronológicamente (antiguas primero para el gráfico)
        $visitasGrafico = $adulto->visitas()->orderBy('fecha_visita', 'asc')->get();

        $labels = [];
        $fisicoData = [];
        $emocionalData = [];

        // Mapas de conversión (Texto -> Puntaje)
        // Ajusta los textos EXACTAMENTE como están en tu base de datos/enum
        $mapFisico = [
            'Bueno' => 100, 
            'Regular' => 75, 
            'Malo' => 50, 
            'Critico' => 25, 'Crítico' => 25 
        ];
        
        $mapEmocional = [
            'Eufórico' => 100, 
            'Estable' => 80, 
            'Triste' => 50, 
            'Ansioso' => 40, 
            'Deprimido' => 20 
        ];

        foreach ($visitasGrafico as $v) {
            $labels[] = $v->fecha_visita->format('d/m/Y');
            $fisicoData[] = $mapFisico[$v->estado_fisico] ?? 50; // 50 por defecto
            $emocionalData[] = $mapEmocional[$v->estado_emocional] ?? 50;
        }

        // 2. Obtener visitas para el Timeline (nuevas primero)
        $visitasTimeline = $adulto->visitas()->with('voluntario.user')->orderBy('fecha_visita', 'desc')->get();

        return view('dashboard.adultos_evolucion', [
            'title' => 'Expediente: ' . $adulto->nombres,
            'page' => 'adultos',
            'adulto' => $adulto,
            'visitas' => $visitasTimeline,
            'chartLabels' => $labels,
            'chartFisico' => $fisicoData,
            'chartEmocional' => $emocionalData
        ]);
    }
}