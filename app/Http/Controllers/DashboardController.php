<?php

namespace App\Http\Controllers;

use App\Models\AdultoMayor; // ¡Importante!
use App\Models\Visita;
use App\Models\Voluntario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Muestra la página de Gestión de Adultos.
     */
    public function adultos()
    {
        // ... (Esta función ya estaba bien)
        $adultos = AdultoMayor::latest('fecha_registro')->paginate(10); 
        $data = [
            'title' => 'Gestión de Adultos - WasiQhari',
            'page' => 'adultos',
            'adultos' => $adultos
        ];
        return view('dashboard.adultos', $data);
    }

    /**
     * Guarda un nuevo Adulto Mayor.
     */
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

    /**
     * ==============================================
     * ¡NUEVA FUNCIÓN! Muestra datos de un adulto
     * ==============================================
     * Usa Route-Model Binding para encontrar al adulto automáticamente.
     * Devuelve JSON para que JavaScript lo lea.
     */
    public function show(AdultoMayor $adulto)
    {
        return response()->json($adulto);
    }

    /**
     * ==============================================
     * ¡NUEVA FUNCIÓN! Actualiza un adulto
     * ==============================================
     */
    public function update(Request $request, AdultoMayor $adulto)
    {
        // Validamos (el DNI debe ser único, ignorando el DNI actual)
        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'dni' => 'nullable|string|max:8|unique:adultos_mayores,dni,' . $adulto->id,
            'sexo' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'edad' => 'required|integer|min:60',
            'distrito' => 'required|string|max:50',
            // ... (añade todas las demás reglas de validación igual que en storeAdulto)
            'estado_salud' => 'required|in:Bueno,Regular,Malo,Critico',
            'nivel_riesgo' => 'required|in:Bajo,Medio,Alto',
            'lat' => 'nullable|numeric',
            'lon' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->route('adultos')
                            ->withErrors($validator)
                            ->withInput()
                            ->with('error_form_edit', 'No se pudo actualizar. Revisa los campos.'); // Flag diferente
        }

        // Actualizamos el adulto
        $adulto->update($request->all());

        return redirect()->route('adultos')->with('success', '¡Registro actualizado con éxito!');
    }

    /**
     * ==============================================
     * ¡NUEVA FUNCIÓN! Elimina un adulto
     * ==============================================
     */
    public function destroy(AdultoMayor $adulto)
    {
        try {
            $adulto->delete();
            // Devolvemos JSON para que SweetAlert sepa que todo salió bien
            return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito.']);
        } catch (\Exception $e) {
            // En caso de error (ej. restricción de llave foránea)
            return response()->json(['success' => false, 'message' => 'No se pudo eliminar el registro.'], 500);
        }
    }


    // --- Funciones de Voluntarios y Visitas (ya estaban bien) ---
    
    public function voluntarios()
    {
        $voluntarios = Voluntario::with('user')->paginate(10);
        $data = [
            'title' => 'Gestión de Voluntarios - WasiQhari',
            'page' => 'voluntarios',
            'voluntarios' => $voluntarios
        ];
        return view('dashboard.voluntarios', $data);
    }

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