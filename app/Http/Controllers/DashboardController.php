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
use Illuminate\Support\Facades\Storage; // ¡Importante para borrar fotos!

class DashboardController extends Controller
{
    public function index()
    {
        // (Código del index igual que antes...)
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

    // --- ADULTOS (Igual que antes) ---
    public function adultos() {
        $adultos = AdultoMayor::latest('fecha_registro')->paginate(10); 
        return view('dashboard.adultos', ['title' => 'Gestión de Adultos', 'page' => 'adultos', 'adultos' => $adultos]);
    }
    public function storeAdulto(Request $request) {
        // (Tu validación y create aquí...)
        // Por brevedad, asumo que usas el código validado anterior
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

    // --- VOLUNTARIOS (Igual que antes) ---
    public function voluntarios() {
        $voluntarios = Voluntario::with('user')->paginate(10);
        return view('dashboard.voluntarios', ['title' => 'Voluntarios', 'page' => 'voluntarios', 'voluntarios' => $voluntarios]);
    }
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

    // --- VISITAS (¡MEJORADO!) ---
    
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
            'foto_evidencia' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Validación de imagen
        ]);

        if ($validator->fails()) {
            return redirect()->route('visitas')->withErrors($validator)->withInput()->with('error_form', 'Error al guardar.');
        }

        $data = $request->all();

        // Subir foto si existe
        if ($request->hasFile('foto_evidencia')) {
            $path = $request->file('foto_evidencia')->store('evidencias', 'public');
            $data['foto_evidencia'] = $path;
        }

        Visita::create($data);
        return redirect()->route('visitas')->with('success', '¡Visita registrada con éxito!');
    }

    public function showVisita(Visita $visita)
    {
        // Cargamos relaciones y URL de la foto
        $visita->load(['adultoMayor', 'voluntario.user']);
        
        // Añadimos la URL completa de la foto para el front-end
        if ($visita->foto_evidencia) {
            $visita->foto_url = asset('storage/' . $visita->foto_evidencia);
        }

        return response()->json($visita);
    }

    public function updateVisita(Request $request, Visita $visita)
    {
        $validator = Validator::make($request->all(), [
            'fecha_visita' => 'required|date',
            'foto_evidencia' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->route('visitas')->withErrors($validator)->withInput()->with('error_form_edit', 'Error al actualizar.');
        }

        $data = $request->all();

        // Manejo de nueva foto
        if ($request->hasFile('foto_evidencia')) {
            // Borrar foto anterior si existe
            if ($visita->foto_evidencia) {
                Storage::disk('public')->delete($visita->foto_evidencia);
            }
            $path = $request->file('foto_evidencia')->store('evidencias', 'public');
            $data['foto_evidencia'] = $path;
        }

        $visita->update($data);
        return redirect()->route('visitas')->with('success', '¡Visita actualizada con éxito!');
    }

    public function destroyVisita(Visita $visita)
    {
        try {
            // Borrar foto si existe
            if ($visita->foto_evidencia) {
                Storage::disk('public')->delete($visita->foto_evidencia);
            }
            $visita->delete();
            return response()->json(['success' => true, 'message' => 'Visita eliminada con éxito.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'No se pudo eliminar.'], 500);
        }
    }

    // Resto de funciones (ai, reporters, settings) igual...
    public function ai() { return view('dashboard.ai', ['title' => 'IA', 'page' => 'ai']); }
    public function reporters() { return view('dashboard.reportes', ['title' => 'Reportes', 'page' => 'reportes']); }
    public function settings() { return view('dashboard.settings', ['title' => 'Config', 'page' => 'settings']); }
}