<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventarios';

    protected $fillable = [
        'nombre',
        'categoria',
        'cantidad',
        'unidad',
        'fecha_vencimiento',
        'descripcion',
        'estado'
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
    ];
}