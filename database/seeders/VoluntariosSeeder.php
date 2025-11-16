<?php

namespace Database\Seeders;

use App\Models\Voluntario;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VoluntariosSeeder extends Seeder
{
    public function run()
    {
        // Crear usuarios de voluntarios
        $voluntariosData = [
            [
                'name' => 'María González',
                'email' => 'maria.gonzalez@wasiqhari.com',
                'telefono' => '984123456',
                'direccion' => 'Av. El Sol 123',
                'distrito' => 'Cusco',
                'habilidades' => 'Acompañamiento, Apoyo emocional, Primeros auxilios',
                'disponibilidad' => 'Tardes',
                'zona_cobertura' => 'Cusco, Wanchaq, San Blas'
            ],
            [
                'name' => 'Carlos Rodríguez',
                'email' => 'carlos.rodriguez@wasiqhari.com',
                'telefono' => '984123457',
                'direccion' => 'Jr. Mantas 456',
                'distrito' => 'Cusco',
                'habilidades' => 'Entrega de alimentos, Logística, Conducción',
                'disponibilidad' => 'Fines de semana',
                'zona_cobertura' => 'Cusco, San Sebastián, Santiago'
            ],
            [
                'name' => 'Ana Mendoza',
                'email' => 'ana.mendoza@wasiqhari.com',
                'telefono' => '984123458',
                'direccion' => 'Calle Plateros 789',
                'distrito' => 'Cusco',
                'habilidades' => 'Enfermería, Cuidado de adultos mayores, Medicación',
                'disponibilidad' => 'Mañanas',
                'zona_cobertura' => 'Cusco Centro, Wanchaq'
            ],
            [
                'name' => 'Luis Quispe',
                'email' => 'luis.quispe@wasiqhari.com',
                'telefono' => '984123459',
                'direccion' => 'Av. de la Cultura 321',
                'distrito' => 'Cusco',
                'habilidades' => 'Psicología, Terapia ocupacional, Apoyo emocional',
                'disponibilidad' => 'Flexible',
                'zona_cobertura' => 'Todos los distritos de Cusco'
            ],
            [
                'name' => 'Sofia Huamán',
                'email' => 'sofia.huaman@wasiqhari.com',
                'telefono' => '984123460',
                'direccion' => 'Jr. Resbalosa 654',
                'distrito' => 'Cusco',
                'habilidades' => 'Cocina, Nutrición, Entrega de alimentos',
                'disponibilidad' => 'Noches',
                'zona_cobertura' => 'Cusco, San Blas, Santiago'
            ],
            [
                'name' => 'Jorge Condori',
                'email' => 'jorge.condori@wasiqhari.com',
                'telefono' => '984123461',
                'direccion' => 'Av. Pardo 987',
                'distrito' => 'Wanchaq',
                'habilidades' => 'Reparaciones menores, Mantenimiento, Transporte',
                'disponibilidad' => 'Fines de semana',
                'zona_cobertura' => 'Wanchaq, Cusco'
            ],
            [
                'name' => 'Rosa Mamani',
                'email' => 'rosa.mamani@wasiqhari.com',
                'telefono' => '984123462',
                'direccion' => 'Calle Saphy 159',
                'distrito' => 'Cusco',
                'habilidades' => 'Quechua, Mediación cultural, Acompañamiento',
                'disponibilidad' => 'Tardes',
                'zona_cobertura' => 'Cusco, San Sebastián'
            ],
            [
                'name' => 'Miguel Torres',
                'email' => 'miguel.torres@wasiqhari.com',
                'telefono' => '984123463',
                'direccion' => 'Av. Ejército 753',
                'distrito' => 'San Sebastián',
                'habilidades' => 'Educación física, Rehabilitación, Actividades recreativas',
                'disponibilidad' => 'Mañanas',
                'zona_cobertura' => 'San Sebastián, Wanchaq'
            ],
            [
                'name' => 'Elena Castro',
                'email' => 'elena.castro@wasiqhari.com',
                'telefono' => '984123464',
                'direccion' => 'Jr. Tigre 852',
                'distrito' => 'Cusco',
                'habilidades' => 'Artesanías, Terapia ocupacional, Manualidades',
                'disponibilidad' => 'Flexible',
                'zona_cobertura' => 'Cusco Centro'
            ],
            [
                'name' => 'Roberto Silva',
                'email' => 'roberto.silva@wasiqhari.com',
                'telefono' => '984123465',
                'direccion' => 'Av. Collasuyo 741',
                'distrito' => 'Cusco',
                'habilidades' => 'Informática, Comunicaciones, Redes sociales',
                'disponibilidad' => 'Noches',
                'zona_cobertura' => 'Todos los distritos'
            ]
        ];

        foreach ($voluntariosData as $voluntarioData) {
            // Crear usuario
            $user = User::create([
                'name' => $voluntarioData['name'],
                'email' => $voluntarioData['email'],
                'password' => Hash::make('password123'),
                'role' => 'voluntario'
            ]);

            // Crear perfil de voluntario
            Voluntario::create([
                'user_id' => $user->id,
                'telefono' => $voluntarioData['telefono'],
                'direccion' => $voluntarioData['direccion'],
                'distrito' => $voluntarioData['distrito'],
                'habilidades' => $voluntarioData['habilidades'],
                'disponibilidad' => $voluntarioData['disponibilidad'],
                'zona_cobertura' => $voluntarioData['zona_cobertura'],
                'estado' => 'Activo',
                'fecha_registro' => now()
            ]);
        }
    }
}