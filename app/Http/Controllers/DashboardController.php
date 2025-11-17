<?php

namespace App\Http\Controllers;

use App\Models\AdultoMayor;
use App\Models\Visita;
use App\Models\Voluntario;
use App\Models\User; // ¡Importante! Añadir User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; // ¡Importante! Añadir Rule

class DashboardController extends Controller
{
    /**
     * Muestra la vista principal del dashboard.
     */
    public function index()
    {
        // ... (Esta función ya estaba bien)
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

    // --- Funciones de Adultos (ya estaban bien) ---
    public function adultos()
    {
        $adultos = AdultoMayor::latest('fecha_registro')->paginate(10); 
        $data = [
            'title' => 'Gestión de Adultos - WasiQhari',
            'page' => 'adultos',
            'adultos' => $adultos
        ];
        return view('dashboard.adultos', $data);
    }

    public function storeAdulto(Request $request)
    {
        // ... (Esta función ya estaba bien)
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
            return redirect()->route('adultos')
                            ->withErrors($validator)
                            ->withInput()
                            ->with('error_form', 'No se pudo guardar. Revisa los campos del formulario.');
        }

        AdultoMayor::create($request->all());
        return redirect()->route('adultos')->with('success', '¡Adulto mayor registrado con éxito!');
    }

    public function show(AdultoMayor $adulto)
    {
        return response()->json($adulto);
    }

    public function update(Request $request, AdultoMayor $adulto)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'dni' => 'nullable|string|max:8|unique:adultos_mayores,dni,' . $adulto->id,
            'sexo' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'edad' => 'required|integer|min:60',
            'distrito' => 'required|string|max:50',
            'estado_salud' => 'required|in:Bueno,Regular,Malo,Critico',
            'nivel_riesgo' => 'required|in:Bajo,Medio,Alto',
            'lat' => 'nullable|numeric',
            'lon' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->route('adultos')
                            ->withErrors($validator)
                            ->withInput()
                            ->with('error_form_edit', 'No se pudo actualizar. Revisa los campos.');
        }

        $adulto->update($request->all());
        return redirect()->route('adultos')->with('success', '¡Registro actualizado con éxito!');
    }

    public function destroy(AdultoMayor $adulto)
    {
        try {
            $adulto->delete();
            return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'No se pudo eliminar el registro.'], 500);
        }
    }


    // --- Funciones de Voluntarios ---
    
    public function voluntarios()
    {
        $voluntarios = Voluntario::with('user')->paginate(10); // 'user' es la relación
        $data = [
            'title' => 'Gestión de Voluntarios - WasiQhari',
            'page' => 'voluntarios',
            'voluntarios' => $voluntarios
        ];
        return view('dashboard.voluntarios', $data);
    }

    /**
     * ===================================================
     * ¡NUEVA FUNCIÓN! Muestra datos de un voluntario
     * ===================================================
     * Carga el voluntario JUNTO con su usuario.
     */
    public function showVoluntario(Voluntario $voluntario)
    {
        // Cargamos la relación 'user' y la devolvemos como JSON
        $voluntario->load('user'); 
        return response()->json($voluntario);
    }

    /**
     * ===================================================
     * ¡NUEVA FUNCIÓN! Actualiza un voluntario
     * ===================================================
     */
    public function updateVoluntario(Request $request, Voluntario $voluntario)
    {
        // 1. Validar los datos del Voluntario
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            // Validar email único, ignorando el user_id actual
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($voluntario->user_id),
            ],
            'telefono' => 'nullable|string|max:15',
            'distrito' => 'nullable|string|max:50',
            'disponibilidad' => 'required|in:Mañanas,Tardes,Noches,Fines de semana,Flexible',
            'estado' => 'required|in:Activo,Inactivo,Suspendido',
        ]);

        if ($validator->fails()) {
            return redirect()->route('voluntarios')
                            ->withErrors($validator)
                            ->withInput()
                            ->with('error_form_edit', 'No se pudo actualizar. Revisa los campos.');
        }

        // 2. Actualizar el modelo User (nombre, email)
        $voluntario->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // 3. Actualizar el modelo Voluntario (el resto)
        $voluntario->update([
            'telefono' => $request->telefono,
            'distrito' => $request->distrito,
            'disponibilidad' => $request->disponibilidad,
            'estado' => $request->estado,
            'zona_cobertura' => $request->zona_cobertura,
            'habilidades' => $request->habilidades,
        ]);

        return redirect()->route('voluntarios')->with('success', '¡Voluntario actualizado con éxito!');
    }

    /**
     * ===================================================
     * ¡NUEVA FUNCIÓN! Elimina un voluntario
     * ===================================================
     * Eliminar al voluntario también eliminará al usuario (por 'onDelete('cascade')' en la migración).
     */
    public function destroyVoluntario(Voluntario $voluntario)
    {
        try {
            // Opcional: Si no tienes 'onDelete('cascade')', borra el usuario primero
            // $voluntario->user->delete(); 
            
            $voluntario->delete();
            return response()->json(['success' => true, 'message' => 'Voluntario eliminado con éxito.']);
        } catch (\Exception $e) {
            // Captura cualquier error (ej. si tiene visitas asignadas)
            return response()->json(['success' => false, 'message' => 'No se pudo eliminar. Puede tener visitas asignadas.'], 500);
        }
    }


    // --- Funciones de Visitas (ya estaban bien) ---
    
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

    public function storeVisita(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'adulto_id' => 'required|exists:adultos_mayores,id',
            'voluntario_id' => 'required|exists:voluntarios,id',
            'fecha_visita' => 'required|date',
        ]);
        if ($validator->fails()) {
            return redirect()->route('visitas')
                            ->withErrors($validator)
                            ->withInput()
                            ->with('error_form', 'No se pudo guardar la visita.');
        }
        Visita::create($request->all());
        return redirect()->route('visitas')->with('success', '¡Visita registrada con éxito!');
    }
    
    // --- Resto de funciones ---

    public function ai()
    {
        $data = ['title' => 'Análisis IA - WasiQhari', 'page' => 'ai'];
        return view('dashboard.ai', $data);
    }

    public function reporters()
    {
        $data = ['title' => 'Reportes - WasiQhari', 'page' => 'reportes'];
        return view('dashboard.reportes', $data);
    }

    public function settings()
    {
        $data = ['title' => 'Configuración - WasiQhari', 'page' => 'settings'];
        return view('dashboard.settings', $data);
    }
}