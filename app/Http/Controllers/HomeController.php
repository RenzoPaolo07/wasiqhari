<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'WasiQhari - Red de Apoyo y Monitoreo Social',
            'page' => 'home'
        ];
        
        return view('home', $data);
    }
    
    public function about()
    {
        $data = [
            'title' => 'Sobre Nosotros - WasiQhari',
            'page' => 'about'
        ];
        
        return view('about', $data);
    }
    
    public function services()
    {
        $data = [
            'title' => 'Servicios - WasiQhari',
            'page' => 'services'
        ];
        
        return view('services', $data);
    }
    
    public function contact()
    {
        $data = [
            'title' => 'Contacto - WasiQhari',
            'page' => 'contact'
        ];
        
        return view('contact', $data);
    }
}