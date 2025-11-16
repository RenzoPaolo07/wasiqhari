<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    use HasFactory;

    protected $table = 'visitas';
    
    protected $fillable = [
        'adulto_id',
        'voluntario_id',
        'fecha_visita',
        'tipo_visita',
        'observaciones',
        'estado_emocional',
        'estado_fisico',
        'necesidades_detectadas',
        'emergencia'
    ];

    protected $dates = [
        'fecha_visita',
        'fecha_registro'
    ];

    /**
     * Obtener el adulto mayor de la visita
     */
    public function adulto()
    {
        return $this->belongsTo(AdultoMayor::class, 'adulto_id');
    }

    /**
     * Obtener el voluntario que realizó la visita
     */
    public function voluntario()
    {
        return $this->belongsTo(User::class, 'voluntario_id');
    }

    /**
     * Obtener todas las visitas con relaciones
     */
    public function getAllVisitas()
    {
        return $this->with(['adulto', 'voluntario'])
                    ->orderBy('fecha_visita', 'DESC')
                    ->get()
                    ->toArray();
    }

    /**
     * Crear nueva visita
     */
    public function createVisita($data)
    {
        return $this->create($data);
    }

    /**
     * Obtener total de visitas
     */
    public function getTotalVisitas()
    {
        return $this->count();
    }

    /**
     * Obtener últimas visitas
     */
    public function getUltimasVisitas($limit = 5)
    {
        return $this->with(['adulto', 'voluntario'])
                    ->orderBy('fecha_visita', 'DESC')
                    ->limit($limit)
                    ->get()
                    ->toArray();
    }

    /**
     * Obtener visitas por voluntario
     */
    public function getVisitasPorVoluntario($voluntario_id)
    {
        return $this->with('adulto')
                    ->where('voluntario_id', $voluntario_id)
                    ->orderBy('fecha_visita', 'DESC')
                    ->get()
                    ->toArray();
    }
}