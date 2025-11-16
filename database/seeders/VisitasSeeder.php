<?php

namespace Database\Seeders;

use App\Models\Visita;
use App\Models\AdultoMayor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VisitasSeeder extends Seeder
{
    public function run()
    {
        // Obtener algunos adultos mayores y voluntarios
        $adultos = AdultoMayor::take(5)->get();
        $voluntarios = User::where('role', 'voluntario')->take(3)->get();

        $visitas = [
            [
                'adulto_id' => $adultos[0]->id,
                'voluntario_id' => $voluntarios[0]->id,
                'fecha_visita' => Carbon::now()->subDays(2),
                'tipo_visita' => 'Acompañamiento',
                'observaciones' => 'Adulto mayor se encontraba estable, conversamos sobre sus necesidades básicas',
                'estado_emocional' => 'Estable',
                'estado_fisico' => 'Regular',
                'necesidades_detectadas' => 'Necesita medicamentos para la artritis y abrigo',
                'emergencia' => false
            ],
            [
                'adulto_id' => $adultos[1]->id,
                'voluntario_id' => $voluntarios[1]->id,
                'fecha_visita' => Carbon::now()->subDays(1),
                'tipo_visita' => 'Entrega de alimentos',
                'observaciones' => 'Se entregó canasta básica, adulto mayor presentaba tos persistente',
                'estado_emocional' => 'Triste',
                'estado_fisico' => 'Malo',
                'necesidades_detectadas' => 'Requiere atención médica urgente para problemas respiratorios',
                'emergencia' => true
            ],
            [
                'adulto_id' => $adultos[2]->id,
                'voluntario_id' => $voluntarios[2]->id,
                'fecha_visita' => Carbon::now()->subDays(3),
                'tipo_visita' => 'Apoyo emocional',
                'observaciones' => 'Sesión de apoyo emocional, adulto mayor mostró mejoría anímica',
                'estado_emocional' => 'Estable',
                'estado_fisico' => 'Bueno',
                'necesidades_detectadas' => 'Seguimiento psicológico semanal',
                'emergencia' => false
            ],
            [
                'adulto_id' => $adultos[3]->id,
                'voluntario_id' => $voluntarios[0]->id,
                'fecha_visita' => Carbon::now()->subDays(5),
                'tipo_visita' => 'Atención médica',
                'observaciones' => 'Revisión de signos vitales, control de medicación',
                'estado_emocional' => 'Ansioso',
                'estado_fisico' => 'Regular',
                'necesidades_detectadas' => 'Cambio en medicación para diabetes',
                'emergencia' => false
            ],
            [
                'adulto_id' => $adultos[4]->id,
                'voluntario_id' => $voluntarios[1]->id,
                'fecha_visita' => Carbon::now()->subDays(7),
                'tipo_visita' => 'Acompañamiento',
                'observaciones' => 'Paseo al parque, adulto mayor mostró mejoría en estado de ánimo',
                'estado_emocional' => 'Eufórico',
                'estado_fisico' => 'Bueno',
                'necesidades_detectadas' => 'Actividades recreativas regulares',
                'emergencia' => false
            ]
        ];

        foreach ($visitas as $visita) {
            Visita::create($visita);
        }
    }
}