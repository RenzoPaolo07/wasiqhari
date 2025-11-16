<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // ✅ Agregar esta línea

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
        'observaciones'
    ];

    protected $dates = [
        'fecha_registro',
        'fecha_nacimiento'
    ];

    /**
     * Obtener todas las visitas del adulto mayor
     */
    public function visitas()
    {
        return $this->hasMany(Visita::class, 'adulto_id');
    }

    /**
     * Obtener todos los adultos mayores
     */
    public function getAllAdultos()
    {
        return $this->orderBy('fecha_registro', 'DESC')->get();
    }

    /**
     * Obtener adulto por ID
     */
    public function getAdultoById($id)
    {
        return $this->find($id);
    }

    /**
     * Crear nuevo adulto mayor
     */
    public function createAdulto($data)
    {
        return $this->create($data);
    }

    /**
     * Actualizar adulto mayor
     */
    public function updateAdulto($id, $data)
    {
        $adulto = $this->find($id);
        if ($adulto) {
            return $adulto->update($data);
        }
        return false;
    }

    /**
     * Eliminar adulto mayor
     */
    public function deleteAdulto($id)
    {
        $adulto = $this->find($id);
        if ($adulto) {
            return $adulto->delete();
        }
        return false;
    }

    /**
     * Obtener total de adultos mayores
     */
    public function getTotalAdultos()
    {
        return $this->count();
    }

    /**
     * Obtener adultos en estado crítico
     */
    public function getAdultosCriticos()
    {
        return $this->where('estado_salud', 'Critico')->count();
    }

    /**
     * Obtener distribución por distrito
     */
    public function getDistribucionPorDistrito()
    {
        return $this->select('distrito', DB::raw('COUNT(*) as cantidad')) // ✅ Ahora DB está definido
                    ->groupBy('distrito')
                    ->get()
                    ->toArray();
    }

    /**
     * Obtener estadísticas completas
     */
    public function getEstadisticasCompletas()
    {
        $estadisticas = [];
        
        // Total por estado de salud
        $estadisticas['estado_salud'] = $this->select('estado_salud', DB::raw('COUNT(*) as cantidad')) // ✅
                                            ->groupBy('estado_salud')
                                            ->get()
                                            ->toArray();
        
        // Total por actividad en calle
        $estadisticas['actividad_calle'] = $this->select('actividad_calle', DB::raw('COUNT(*) as cantidad')) // ✅
                                               ->groupBy('actividad_calle')
                                               ->get()
                                               ->toArray();
        
        // Total por distrito
        $estadisticas['distritos'] = $this->select('distrito', DB::raw('COUNT(*) as cantidad')) // ✅
                                         ->groupBy('distrito')
                                         ->get()
                                         ->toArray();
        
        // Promedio de edad
        $estadisticas['promedio_edad'] = round($this->avg('edad'), 1);
        
        return $estadisticas;
    }

    /**
     * Buscar adultos con filtros
     */
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