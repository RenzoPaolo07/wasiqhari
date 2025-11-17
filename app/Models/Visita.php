<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    use HasFactory;

    protected $table = 'visitas';
    
    /**
     * ¡Corregido! 
     * Ahora usa 'adulto_id' como dijiste.
     */
    protected $fillable = [
        'adulto_id', // <-- CORREGIDO
        'voluntario_id',
        'fecha_visita',
        'tipo_visita',
        'observaciones',
        'estado_emocional',
        'estado_fisico',
        'necesidades_detectadas',
        'emergencia',
        'foto_evidencia'
    ];

    /**
     * ¡Corregido! 
     * Eliminado 'fecha_registro' que no estaba en tu $fillable.
     */
    protected $dates = [
        'fecha_visita',
    ];

    protected $casts = [
        'fecha_visita' => 'datetime',
        'emergencia' => 'boolean'
    ];

    /**
     * Obtener el adulto mayor de la visita
     * ¡Corregido! Ahora apunta a la columna 'adulto_id'.
     */
    public function adultoMayor()
    {
        return $this->belongsTo(AdultoMayor::class, 'adulto_id');
    }

    /**
     * Obtener el voluntario de la visita
     */
    public function voluntario()
    {
        return $this->belongsTo(Voluntario::class);
    }

    /**
     * Obtener todas las visitas con relaciones
     * ¡Corregido! Usamos la relación 'adultoMayor', no 'adulto'.
     */
    public function getAllVisitas()
    {
        return $this->with(['adultoMayor', 'voluntario'])
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
     * ¡Corregido! Usamos la relación 'adultoMayor', no 'adulto'.
     */
    public function getUltimasVisitas($limit = 5)
    {
        return $this->with(['adultoMayor', 'voluntario'])
                    ->orderBy('fecha_visita', 'DESC')
                    ->limit($limit)
                    ->get()
                    ->toArray();
    }

    /**
     * Obtener visitas por voluntario
     * ¡Corregido! Usamos la relación 'adultoMayor', no 'adulto'.
     */
    public function getVisitasPorVoluntario($voluntario_id)
    {
        return $this->with('adultoMayor')
                    ->where('voluntario_id', $voluntario_id)
                    ->orderBy('fecha_visita', 'DESC')
                    ->get()
                    ->toArray();
    }
}