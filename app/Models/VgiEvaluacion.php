<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VgiEvaluacion extends Model
{
    use HasFactory;

    protected $table = 'vgi_evaluaciones';

    // Permitimos asignación masiva para no escribir los 100 campos aquí
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'fecha_evaluacion' => 'datetime',
        'tiene_hta' => 'boolean',
        'tiene_diabetes' => 'boolean',
        'tiene_demencia' => 'boolean',
        // ... Laravel convierte automáticamente los tinyint(1) a boolean, pero esto ayuda a ser explícito
    ];

    // Relación Inversa: Una evaluación pertenece a un Adulto Mayor
    public function adultoMayor()
    {
        return $this->belongsTo(AdultoMayor::class, 'adulto_mayor_id');
    }

    // Relación: Una evaluación fue hecha por un Usuario (Médico/Voluntario)
    public function evaluador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}