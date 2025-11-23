<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'accion', 'modulo', 'descripcion'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Helper estático para registrar actividades fácilmente desde cualquier lado
    public static function registrar($accion, $modulo, $descripcion)
    {
        self::create([
            'user_id' => auth()->id(),
            'accion' => $accion,
            'modulo' => $modulo,
            'descripcion' => $descripcion
        ]);
    }
}