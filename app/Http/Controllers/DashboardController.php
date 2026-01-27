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
use Illuminate\Support\Facades\Http; // <--- AGREGA ESTA LÍNEA
use Illuminate\Support\Facades\Log;  // <--- Y ESTA TAMBIÉN
use App\Models\VgiEvaluacion; // <--- AGREGA ESTO ARRIBA

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

        $visita = Visita::create($data);

        // --- CÓDIGO IA (TEXTO + FOTO) ---
        try {
            $visita->load('adultoMayor'); 
            
            // 1. Primero analiza el Texto (Doctor Wasiqhari)
            $this->analizarVisitaConIA($visita);

            // 2. Si subió foto, analiza la Foto (Ojo Clínico)
            if (isset($data['foto_evidencia'])) {
                $this->analizarFotoConIA($visita, $data['foto_evidencia']);
            }
            
        } catch (\Exception $e) {
            // Silencioso para no detener al usuario
        }
        // --- FIN CÓDIGO IA ---

        ActivityLog::registrar('Crear', 'Visitas', "Registró visita ID #{$visita->id}");

        ActivityLog::registrar('Crear', 'Visitas', "Registró visita ID #{$visita->id}");

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
    // --- EXPEDIENTE EVOLUTIVO (CONECTADO A VGI REAL) ---
    public function evolucionAdulto(AdultoMayor $adulto)
    {
        // 1. Obtener evaluaciones VGI ordenadas por fecha (para el gráfico)
        // Usamos VgiEvaluacion en lugar de Visita para tener los datos médicos exactos
        $evaluaciones = VgiEvaluacion::where('adulto_mayor_id', $adulto->id)
                        ->orderBy('fecha_evaluacion', 'asc')
                        ->get();

        $chartLabels = [];
        $chartFisico = [];    // Usaremos el Índice de Barthel (0-100)
        $chartEmocional = []; // Usaremos GDS/Yesavage normalizados a %

        foreach ($evaluaciones as $eval) {
            // Eje X: Fechas
            $chartLabels[] = \Carbon\Carbon::parse($eval->fecha_evaluacion)->format('d/m/Y');

            // --- A. ESTADO FÍSICO (Barthel) ---
            // Barthel ya viene en escala 0 a 100, así que lo usamos directo.
            $chartFisico[] = $eval->barthel_total ?? 0;

            // --- B. ESTADO EMOCIONAL (GDS / Yesavage) ---
            // Meta: Convertir los puntajes "malos" a porcentaje de "Bienestar" (0-100)
            
            $puntajeEmo = 0;

            // Si el GDS-4 fue 2 o más, se activó Yesavage (Escala de 15 puntos)
            if (($eval->gds_total ?? 0) >= 2) {
                // Escala Yesavage: 0 es mejor (100%), 15 es peor (0%)
                // Fórmula: 100 - (Puntaje * 6.66)
                $puntos = $eval->yesavage_total ?? 0;
                $puntajeEmo = 100 - ($puntos * 6.66);
            } else {
                // Escala GDS-4: 0 es mejor (100%), 4 es peor (0%)
                // Fórmula: 100 - (Puntaje * 25)
                $puntos = $eval->gds_total ?? 0;
                $puntajeEmo = 100 - ($puntos * 25);
            }

            // Aseguramos que esté entre 0 y 100 y redondeamos
            $chartEmocional[] = round(max(0, min(100, $puntajeEmo)));
        }

        // 2. Obtener visitas para el Timeline (historial de visitas domiciliarias)
        $visitasTimeline = $adulto->visitas()
            ->with('voluntario.user')
            ->orderBy('fecha_visita', 'desc')
            ->get();

        return view('dashboard.adultos_evolucion', [
            'title' => 'Expediente: ' . $adulto->nombres,
            'page' => 'adultos',
            'adulto' => $adulto,
            'visitas' => $visitasTimeline, // Lista de abajo (Timeline)
            'chartLabels' => $chartLabels, // Gráfico (Eje X)
            'chartFisico' => $chartFisico, // Gráfico (Línea Verde - Barthel)
            'chartEmocional' => $chartEmocional // Gráfico (Línea Naranja - GDS/Yesavage)
        ]);
    }

    // --- FUNCIÓN PRIVADA: DOCTOR WASIQHARI (MODO DEBUG VISUAL) ---
    private function analizarVisitaConIA($visita)
    {
        $apiKey = env('GEMINI_API_KEY');
        
        // 1. Verificar si hay llave
        if (empty($apiKey)) {
            $visita->recomendacion_ia = "❌ ERROR: Falta API KEY en .env";
            $visita->save();
            return;
        }

        $prompt = "Actúa como médico geriatra. Analiza:
        - Paciente: {$visita->adultoMayor->nombres} ({$visita->adultoMayor->edad} años)
        - Estado: {$visita->estado_fisico}
        - Observaciones: \"{$visita->observaciones}\"
        
        INSTRUCCIONES:
        1. Si ves RIESGO (dolor, caída, sangre, mareo, golpe, tristeza), responde: 'PELIGRO: [Causa breve]'
        2. Si todo está bien, responde: 'OK: Estable'
        Responde solo con esa línea.";

        try {
            // USAMOS 'gemini-flash-latest' (El comodín que suele funcionar siempre)
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}";
            
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ]);

            $data = $response->json();

            // 2. Si Google nos da error, LO MOSTRAMOS EN PANTALLA
            if (isset($data['error'])) {
                $mensajeError = $data['error']['message'] ?? 'Error desconocido';
                $visita->recomendacion_ia = "❌ GOOGLE ERROR: " . substr($mensajeError, 0, 50) . "...";
                $visita->save();
                Log::error("DOCTOR IA API ERROR FULL: " . json_encode($data));
                return;
            }

            // 3. Procesar respuesta
            $textoIA = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $textoIA = trim(str_replace(['*', '`', '"'], '', $textoIA));

            if (empty($textoIA)) {
                $visita->recomendacion_ia = "⚠️ ALERTA: Google respondió vacío.";
            } elseif (str_starts_with(strtoupper($textoIA), 'PELIGRO')) {
                $mensaje = substr($textoIA, 7); 
                $mensaje = ltrim($mensaje, ": ");
                $visita->recomendacion_ia = "⚠️ DR. IA: " . ($mensaje ?: 'Riesgo detectado.');
            } else {
                $visita->recomendacion_ia = "✅ DR. IA: Paciente estable.";
            }
            
            $visita->save();

        } catch (\Exception $e) {
            // 4. Si el código explota, LO MOSTRAMOS
            $visita->recomendacion_ia = "❌ CRASH: " . substr($e->getMessage(), 0, 50);
            $visita->save();
            Log::error("DOCTOR IA CRASH: " . $e->getMessage());
        }
    }

    // --- FUNCIÓN PRIVADA: OJO CLÍNICO (ANÁLISIS DE FOTOS) ---
    private function analizarFotoConIA($visita, $imagePath)
    {
        $apiKey = env('GEMINI_API_KEY');
        if (empty($apiKey)) return;

        // 1. Convertir imagen a Base64 (Para que Gemini la pueda "ver" desde tu servidor)
        try {
            $fullPath = public_path("storage/" . $imagePath);
            if (!file_exists($fullPath)) return;
            
            $imageData = base64_encode(file_get_contents($fullPath));
        } catch (\Exception $e) {
            return;
        }

        // 2. El Prompt para la Imagen
        $prompt = "Actúa como trabajador social experto. Analiza esta FOTO de una visita domiciliaria.
        Busca signos de:
        1. Pobreza extrema o falta de higiene grave.
        2. Riesgos de seguridad (cables sueltos, desorden peligroso).
        3. Estado anímico visible del paciente (si aparece).
        
        INSTRUCCIONES:
        - Si ves algo ALARMANTE, inicia con 'FOTO-ALERTA:'.
        - Si todo se ve normal/aceptable, responde 'FOTO-OK'.
        - Sé muy breve (max 15 palabras).";

        try {
            // USAMOS GEMINI 2.0 FLASH (Multimodal)
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";
            
            $payload = [
                'contents' => [[
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => 'image/jpeg', // Asumimos jpeg por simplicidad
                                'data' => $imageData
                            ]
                        ]
                    ]
                ]]
            ];

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);

            $data = $response->json();
            $textoIA = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $textoIA = trim(str_replace(['*', '`'], '', $textoIA));

            // 3. Guardar resultado (Concatenamos con lo que ya dijo el Doctor Texto)
            if (str_starts_with(strtoupper($textoIA), 'FOTO-ALERTA')) {
                $mensaje = substr($textoIA, 12); // Quitamos "FOTO-ALERTA:"
                // Agregamos un salto de línea si ya había texto
                $visita->recomendacion_ia .= "\n👁️ OJO: " . ($mensaje ?: 'Riesgo visual detectado.');
                $visita->save();
            }

        } catch (\Exception $e) {
            Log::error("VISION IA CRASH: " . $e->getMessage());
        }
    }
}