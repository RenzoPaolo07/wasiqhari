<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Obtener el perfil de voluntario asociado
     */
    public function voluntario()
    {
        return $this->hasOne(Voluntario::class, 'user_id');
    }

    /**
     * Obtener las visitas realizadas por el usuario
     */
    public function visitas()
    {
        return $this->hasMany(Visita::class, 'voluntario_id');
    }

    /**
     * Crear nuevo usuario
     */
    public function createUser($name, $email, $password, $role)
    {
        return $this->create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'role' => $role
        ]);
    }

    /**
     * Autenticar usuario (ya manejado por Laravel)
     */
    // Este método ya no es necesario ya que Laravel maneja la autenticación

    /**
     * Obtener usuario por ID
     */
    public function getUserById($id)
    {
        return $this->select('id', 'name', 'email', 'role', 'created_at')
                    ->find($id);
    }
}