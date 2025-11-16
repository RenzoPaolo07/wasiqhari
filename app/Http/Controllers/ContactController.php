<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'subject' => 'required|string',
            'message' => 'required|string|min:10'
        ]);

        // Aquí puedes:
        // 1. Guardar en la base de datos
        // 2. Enviar email
        // 3. Integrar con algún servicio

        return back()->with('success', 'Mensaje enviado correctamente. Te contactaremos pronto.');
    }
}