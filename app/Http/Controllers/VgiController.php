<?php

namespace App\Http\Controllers;

use App\Models\AdultoMayor;
use App\Models\VgiEvaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VgiController extends Controller
{
    // Muestra la historia clínica (Si existe una previa, la carga)
    public function show($adultoId)
    {
        $adulto = AdultoMayor::findOrFail($adultoId);
        
        // Buscamos la última evaluación VGI realizada
        $vgi = VgiEvaluacion::where('adulto_mayor_id', $adultoId)
                            ->latest('fecha_evaluacion')
                            ->first();

        // Retornamos la vista (que crearemos en el Paso 3)
        // Enviamos los datos del adulto y su evaluación (si tiene)
        return view('dashboard.vgi.form', compact('adulto', 'vgi'));
    }

    // Guarda una NUEVA evaluación (Cada vez que guardan, es una foto médica nueva en el tiempo)
    public function store(Request $request, $adultoId)
    {
        // 1. Validamos que sea personal autorizado (Médico o Admin)
        // Puedes ajustar esto según tus roles exactos en BD
        /* if (Auth::user()->role !== 'medico' && Auth::user()->role !== 'admin') {
            return abort(403, 'No tienes permiso para firmar historias clínicas.');
        } */ 
        // Lo dejo comentado por ahora para que TÚ puedas probarlo. Descoméntalo para producción.

        // 2. Creamos la evaluación
        // Usamos $request->except para guardar TODO el formulario de golpe
        // Laravel filtrará automáticamente gracias al modelo que creamos antes
        $data = $request->except(['_token']);
        
        $data['adulto_mayor_id'] = $adultoId;
        $data['user_id'] = Auth::id(); // El médico que está logueado
        $data['fecha_evaluacion'] = now();

        // Cálculos Automáticos de Totales (Opcional: Si el front no los manda, los sumamos aquí)
        // Por ahora confiamos en que el formulario envíe los totales o sean 0.

        VgiEvaluacion::create($data);

        return redirect()->route('adultos.vgi', $adultoId)
                         ->with('success', 'Historia Clínica VGI actualizada correctamente.');
    }
}