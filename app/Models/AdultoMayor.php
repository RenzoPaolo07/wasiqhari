<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdultoMayor extends Model
{
    use HasFactory;

    protected $table = 'adultos_mayores';
    
    protected $fillable = [
        'fecha_registro',
        'dni',
        'apellidos',
        'nombres',
        'sexo',
        'fecha_nacimiento',
        'edad',
        'direccion',
        'distrito',
        'zona_ubicacion',
        'lee_escribe',
        'nivel_estudio',
        'apoyo_familiar',
        'estado_abandono',
        'telefono',
        'estado_salud',
        'actividad_calle',
        'necesidades',
        'observaciones',
        'nivel_riesgo',
        'lat',
        'lon'
    ];

    /**
     * ==========================================================
     * ¡AQUÍ ESTÁ LA CORRECCIÓN!
     * ==========================================================
     * Reemplazamos $dates por $casts.
     * Esto le dice a Laravel que trate estas columnas como objetos de fecha (Carbon).
     */
    protected $casts = [
        'fecha_registro' => 'datetime',
        'fecha_nacimiento' => 'date', // 'date' es suficiente si no guardas la hora
    ];

    /**
     * La propiedad $dates está deprecada, la reemplazamos arriba.
     * protected $dates = [
     * 'fecha_registro',
     * 'fecha_nacimiento'
     * ];
     */


    /**
     * Obtener todas las visitas del adulto mayor
     * (Esto ya estaba bien de la vez pasada)
     */
    public function visitas()
    {
        return $this->hasMany(Visita::class, 'adulto_id');
    }

    // --- El resto de tus funciones (ya estaban bien) ---

    public function getAllAdultos()
    {
        return $this->orderBy('fecha_registro', 'DESC')->get();
    }

    public function getAdultoById($id)
    {
        return $this->find($id);
    }

    public function createAdulto($data)
    {
        return $this->create($data);
    }

    public function updateAdulto($id, $data)
    {
        $adulto = $this->find($id);
        if ($adulto) {
            return $adulto->update($data);
        }
        return false;
    }

    public function deleteAdulto($id)
    {
        $adulto = $this->find($id);
        if ($adulto) {
            return $adulto->delete();
        }
        return false;
    }

    public function getTotalAdultos()
    {
        return $this->count();
    }

    public function getAdultosCriticos()
    {
        return $this->where('nivel_riesgo', 'Alto')->count();
    }

    public function getDistribucionPorDistrito()
    {
        return $this->select('distrito', DB::raw('COUNT(*) as cantidad'))
                    ->groupBy('distrito')
                    ->get()
                    ->toArray();
    }

    public function getEstadisticasCompletas()
    {
        $estadisticas = [];
        
        $estadisticas['estado_salud'] = $this->select('estado_salud', DB::raw('COUNT(*) as cantidad'))
                                            ->groupBy('estado_salud')
                                            ->get()
                                            ->toArray();
        
        $estadisticas['actividad_calle'] = $this->select('actividad_calle', DB::raw('COUNT(*) as cantidad'))
                                               ->groupBy('actividad_calle')
                                               ->get()
                                               ->toArray();
        
        $estadisticas['distritos'] = $this->select('distrito', DB::raw('COUNT(*) as cantidad'))
                                         ->groupBy('distrito')
                                         ->get()
                                         ->toArray();
        
        $estadisticas['promedio_edad'] = round($this->avg('edad'), 1);
        
        return $estadisticas;
    }

    /**
     * Scope para búsqueda inteligente
     */
    public function scopeSearch($query, $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function($q) use ($term) {
            $q->where('nombres', 'LIKE', "%{$term}%")
              ->orWhere('apellidos', 'LIKE', "%{$term}%")
              ->orWhere('dni', 'LIKE', "%{$term}%")
              ->orWhere('distrito', 'LIKE', "%{$term}%");
        });
    }

    public function buscarAdultos($filtros)
    {
        $query = $this->query();
        
        if (!empty($filtros['distrito'])) {
            $query->where('distrito', $filtros['distrito']);
        }
        
        if (!empty($filtros['estado_salud'])) {
            $query->where('estado_salud', $filtros['estado_salud']);
        }
        
        if (!empty($filtros['sexo'])) {
            $query->where('sexo', $filtros['sexo']);
        }
        
        if (!empty($filtros['estado_abandono'])) {
            $query->where('estado_abandono', $filtros['estado_abandono']);
        }
        
        return $query->orderBy('fecha_registro', 'DESC')->get()->toArray();
    }
}