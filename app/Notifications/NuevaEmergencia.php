<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NuevaEmergencia extends Notification
{
    use Queueable;

    protected $visita;

    // Recibimos la visita creada
    public function __construct($visita)
    {
        $this->visita = $visita;
    }

    // Definimos que usaremos la base de datos
    public function via($notifiable)
    {
        return ['database'];
    }

    // Guardamos los datos en la base de datos
    public function toDatabase($notifiable)
    {
        return [
            'titulo' => '🚨 Alerta de Emergencia',
            'mensaje' => 'Se reportó una emergencia con ' . $this->visita->adultoMayor->nombres . ' ' . $this->visita->adultoMayor->apellidos,
            'visita_id' => $this->visita->id,
            'fecha' => $this->visita->fecha_visita,
            'icon' => 'fas fa-exclamation-triangle text-danger'
        ];
    }
}