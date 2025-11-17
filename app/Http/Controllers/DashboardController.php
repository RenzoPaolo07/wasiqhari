<?php

namespace App\Http\Controllers;

use App\Models\AdultoMayor;
use App\Models\Visita;
use App\Models\Voluntario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Muestra la vista principal del dashboard.
     */
    public function index()
    {
        // ... (Código del index igual que antes)
        $ultimasVisitas = Visita::with(['adultoMayor', 'voluntario.user'])
                                ->latest('fecha_visita')->take(5)->get();
        $adultosParaMapa = AdultoMayor::whereNotNull('lat')->whereNotNull('lon')
                                      ->get(['nombres', 'apellidos', 'lat', 'lon', 'nivel_riesgo']);
        $saludData = AdultoMayor::select('estado_salud', DB::raw('count(*) as total'))
                         ->groupBy('estado_salud')->pluck('total', 'estado_salud');
        $actividadesData = AdultoMayor::select('actividad_calle', DB::raw('count(*) as total'))
                         ->groupBy('actividad_calle')->pluck('total', 'actividad_calle');
        $distribucionDistritos = AdultoMayor::select('distrito', DB::raw('count(*) as cantidad'))
                       ->whereNotNull('distrito')->where('distrito', '!=', '')
                       ->groupBy('distrito')->orderBy('cantidad', 'desc')->get();
        $stats = [
            'total_adultos' => AdultoMayor::count(),
            'total_voluntarios' => Voluntario::count(),
            'total_visitas' => Visita::count(),
            'adultos_criticos' => AdultoMayor::where('nivel_riesgo', 'Alto')->count(), 
            'ultimas_visitas' => $ultimasVisitas,
            'distribucion_distritos' => $distribucionDistritos
        ];
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

    // --- GESTIÓN DE ADULTOS (¡MEJORADO CON BÚSQUEDA!) ---
    public function adultos(Request $request)
    {
        // Usamos el scopeSearch que creamos en el Modelo
        $query = AdultoMayor::search($request->search)->latest('fecha_registro');
        
        $adultos = $query->paginate(10);

        // 1. Búsqueda (Live Search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombres', 'like', "%$search%")
                  ->orWhere('apellidos', 'like', "%$search%")
                  ->orWhere('dni', 'like', "%$search%");
            });
        }

        // 2. Filtro por Riesgo
        if ($request->has('riesgo') && $request->riesgo != '') {
            $query->where('nivel_riesgo', $request->riesgo);
        }

        // Ordenamos y paginamos (Mantenemos los filtros en los links de paginación)
        $adultos = $query->latest('fecha_registro')->paginate(10)->withQueryString();
        
        $data = [
            'title' => 'Gestión de Adultos - WasiQhari',
            'page' => 'adultos',
            'adultos' => $adultos
        ];
        
        // Si es una petición AJAX (Live Search), devolvemos solo la tabla
        if ($request->ajax()) {
            return view('dashboard.partials.tabla_adultos', compact('adultos'))->render();
        }
        
        $data = [
            'title' => 'Gestión de Adultos - WasiQhari',
            'page' => 'adultos',
            'adultos' => $adultos
        ];
        
        return view('dashboard.adultos', $data);
    }

    public function storeAdulto(Request $request)
    {
        // ... (Tu validación original aquí) ...
        $validator = Validator::make($request->all(), [
            'fecha_registro' => 'required|date',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'dni' => 'nullable|string|max:8|unique:adultos_mayores,dni',
            'sexo' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'edad' => 'required|integer|min:60',
            'distrito' => 'required|string|max:50',
            'zona_ubicacion' => 'required|string|max:100',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:9',
            'lee_escribe' => 'required|in:Si,No,Poco',
            'nivel_estudio' => 'required|in:Ninguno,Primaria,Secundaria',
            'apoyo_familiar' => 'required|in:Ninguno,Poco,Ocasional',
            'estado_abandono' => 'required|in:Total,Parcial,Situación Calle',
            'estado_salud' => 'required|in:Bueno,Regular,Malo,Critico',
            'actividad_calle' => 'required|string',
            'necesidades' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'nivel_riesgo' => 'required|in:Bajo,Medio,Alto',
            'lat' => 'nullable|numeric',
            'lon' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->route('adultos')->withErrors($validator)->withInput()->with('error_form', 'Error al guardar.');
        }

        AdultoMayor::create($request->all());
        return redirect()->route('adultos')->with('success', 'Registrado con éxito');
    }
    public function show(AdultoMayor $adulto) { return response()->json($adulto); }
    public function update(Request $request, AdultoMayor $adulto) {
        $adulto->update($request->all());
        return redirect()->route('adultos')->with('success', 'Actualizado con éxito');
    }
    public function destroy(AdultoMayor $adulto) {
        $adulto->delete();
        return response()->json(['success' => true, 'message' => 'Eliminado con éxito']);
    }


    // --- GESTIÓN DE VOLUNTARIOS (¡MEJORADO CON BÚSQUEDA!) ---
    public function voluntarios(Request $request)
    {
        $query = Voluntario::with('user'); // Eager load user

        // 1. Búsqueda (Nombre o Email del usuario)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        // 2. Filtro por Estado
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        $voluntarios = $query->paginate(10)->withQueryString();
        
        $data = [
            'title' => 'Gestión de Voluntarios - WasiQhari',
            'page' => 'voluntarios',
            'voluntarios' => $voluntarios
        ];

        // Si es AJAX, devolvemos solo la tabla (aunque por ahora usaremos el método de reemplazo de DOM en el JS)
        if ($request->ajax()) {
             // Nota: Para simplificar, usaremos el método de JS que extrae el HTML, 
             // así no tienes que crear archivos parciales extra.
        }
        
        return view('dashboard.voluntarios', $data);
    }
    
    // ... (Resto de funciones de voluntarios show, update, destroy iguales que antes) ...
    public function showVoluntario(Voluntario $voluntario) {
        $voluntario->load('user');
        return response()->json($voluntario);
    }
    public function updateVoluntario(Request $request, Voluntario $voluntario) {
        $voluntario->user->update(['name' => $request->name, 'email' => $request->email]);
        $voluntario->update($request->except(['name', 'email']));
        return redirect()->route('voluntarios')->with('success', 'Actualizado con éxito');
    }
    public function destroyVoluntario(Voluntario $voluntario) {
        $voluntario->delete();
        return response()->json(['success' => true, 'message' => 'Eliminado con éxito']);
    }

    // --- GESTIÓN DE VISITAS (Visitas también se pueden buscar si quieres) ---
    public function visitas()
    {
        $visitas = Visita::with(['adultoMayor', 'voluntario.user'])
                         ->latest('fecha_visita')
                         ->paginate(10);
        $adultos = AdultoMayor::all(['id', 'nombres', 'apellidos']);
        $voluntarios = Voluntario::with('user')->get();
        
        $data = [
            'title' => 'Gestión de Visitas - WasiQhari',
            'page' => 'visitas',
            'visitas' => $visitas,
            'adultos' => $adultos,
            'voluntarios' => $voluntarios
        ];
        return view('dashboard.visitas', $data);
    }
    // ... (storeVisita, showVisita, etc. iguales que antes) ...
    public function storeVisita(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'adulto_id' => 'required|exists:adultos_mayores,id',
            'voluntario_id' => 'required|exists:voluntarios,id',
            'fecha_visita' => 'required|date',
            'foto_evidencia' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->route('visitas')->withErrors($validator)->withInput()->with('error_form', 'Error al guardar.');
        }

        $data = $request->all();

        if ($request->hasFile('foto_evidencia')) {
            $path = $request->file('foto_evidencia')->store('evidencias', 'public');
            $data['foto_evidencia'] = $path;
        }

        // Crear la visita
        $visita = Visita::create($data);

        // --- LÓGICA DE NOTIFICACIÓN ---
        // Si es emergencia, notificamos a todos los usuarios con rol 'organizacion' o al admin
        if ($visita->emergencia) {
            // Por simplicidad, notificamos al usuario actual (para que veas que funciona)
            // En producción, harías: User::where('role', 'organizacion')->get()
            $usersToNotify = User::all(); 
            
            foreach ($usersToNotify as $user) {
                $user->notify(new \App\Notifications\NuevaEmergencia($visita));
            }
        }
        // -----------------------------------------

        return redirect()->route('visitas')->with('success', '¡Visita registrada con éxito!');
    }
    
    // --- MARCAR TODO COMO LEÍDO ---
    public function markNotificationsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
    public function showVisita(Visita $visita) {
        $visita->load(['adultoMayor', 'voluntario.user']);
        if ($visita->foto_evidencia) $visita->foto_url = asset('storage/' . $visita->foto_evidencia);
        return response()->json($visita);
    }
    public function updateVisita(Request $request, Visita $visita) {
        $visita->update($request->all());
        return redirect()->route('visitas')->with('success', 'Actualizada');
    }
    public function destroyVisita(Visita $visita) {
        if ($visita->foto_evidencia) Storage::disk('public')->delete($visita->foto_evidencia);
        $visita->delete();
        return response()->json(['success' => true, 'message' => 'Eliminada']);
    }

    // Rutas extra del calendario (de la mejora anterior)
    public function calendario() { return view('dashboard.calendario', ['title' => 'Calendario', 'page' => 'calendario']); }
    public function getEventosCalendario() {
        $visitas = Visita::with(['adultoMayor', 'voluntario.user'])->get();
        $eventos = $visitas->map(function($visita) {
            $color = '#3788d8';
            if ($visita->emergencia) $color = '#e74c3c';
            return [
                'id' => $visita->id,
                'title' => ($visita->adultoMayor->nombres ?? 'N/A'),
                'start' => $visita->fecha_visita->format('Y-m-d\TH:i:s'),
                'backgroundColor' => $color
            ];
        });
        return response()->json($eventos);
    }
    
    // Rutas simples
    public function ai() { return view('dashboard.ai', ['title' => 'IA', 'page' => 'ai']); }
    public function reporters() 
    {
        $data = [
            'title' => 'Reportes - WasiQhari',
            'page' => 'reportes' // Esto asegura que el menú se marque como activo
        ];
        return view('dashboard.reportes', $data);
    }
    public function settings() { return view('dashboard.settings', ['title' => 'Config', 'page' => 'settings']); }
}