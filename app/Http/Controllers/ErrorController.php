<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function notFound()
    {
        $data = [
            'title' => 'Página No Encontrada - WasiQhari',
            'page' => 'error'
        ];
        
        return response()->view('error.404', $data, 404);
    }
}