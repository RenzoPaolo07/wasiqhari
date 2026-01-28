<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VgiEvaluacion extends Model
{
    use HasFactory;

    protected $table = 'vgi_evaluaciones';

    // 🔥 CAMBIO 1: Usar un array vacío libera TOTALMENTE el modelo.
    // Esto evita cualquier error de "Mass Assignment" silencioso.
    protected $guarded = []; 

    protected $casts = [
        // 🔥 CAMBIO 2: Usar 'date' en vez de 'datetime' evita problemas 
        // de comparación con el input type="date" del HTML.
        'fecha_evaluacion' => 'date', 
        
        // Los booleanos están perfectos
        'tiene_hta' => 'boolean',
        'tiene_diabetes' => 'boolean',
        'tiene_demencia' => 'boolean',
        // ... el resto de tus booleanos
    ];

    // Relación Inversa: Una evaluación pertenece a un Adulto Mayor
    public function adultoMayor()
    {
        return $this->belongsTo(AdultoMayor::class, 'adulto_mayor_id');
    }

    // Relación: Una evaluación fue hecha por un Usuario
    public function evaluador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}