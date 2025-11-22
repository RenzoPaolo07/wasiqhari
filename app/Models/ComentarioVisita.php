<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComentarioVisita extends Model
{
    use HasFactory;

    protected $table = 'comentarios_visitas';

    protected $fillable = ['visita_id', 'user_id', 'contenido'];

    // Quién escribió el comentario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}