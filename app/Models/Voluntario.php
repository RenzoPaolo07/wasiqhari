<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voluntario extends Model
{
    use HasFactory;

    protected $table = 'voluntarios';
    
    protected $fillable = [
        'user_id',
        'telefono',
        'direccion',
        'distrito',
        'habilidades',
        'disponibilidad',
        'zona_cobertura',
        'estado',
        'fecha_registro'
    ];

    protected $dates = [
        'fecha_registro'
    ];

    /**
     * Obtener el usuario asociado al voluntario
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtener las visitas realizadas por el voluntario
     */
    public function visitas()
    {
        return $this->hasMany(Visita::class, 'voluntario_id');
    }

    /**
     * Obtener todos los voluntarios
     */
    public function getAllVoluntarios()
    {
        return $this->with('user')
                    ->orderBy('fecha_registro', 'DESC')
                    ->get()
                    ->toArray();
    }

    /**
     * Obtener voluntario por ID
     */
    public function getVoluntarioById($id)
    {
        return $this->with('user')->find($id);
    }

    /**
     * Crear nuevo voluntario
     */
    public function createVoluntario($data)
    {
        return $this->create($data);
    }

    /**
     * Actualizar voluntario
     */
    public function updateVoluntario($id, $data)
    {
        $voluntario = $this->find($id);
        if ($voluntario) {
            return $voluntario->update($data);
        }
        return false;
    }

    /**
     * Obtener total de voluntarios activos
     */
    public function getTotalVoluntarios()
    {
        return $this->where('estado', 'Activo')->count();
    }

    /**
     * Obtener voluntarios cercanos por distrito
     */
    public function getVoluntariosCercanos($distrito)
    {
        return $this->with('user')
                    ->where('distrito', $distrito)
                    ->where('estado', 'Activo')
                    ->orderBy('fecha_registro', 'DESC')
                    ->get()
                    ->toArray();
    }
}