<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Rutas de autenticación
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/login', [UserController::class, 'authenticate']);
Route::post('/register', [UserController::class, 'store']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Rutas protegidas (dashboard)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Gestión de adultos
    Route::get('/dashboard/adultos', [DashboardController::class, 'adultos'])->name('adultos');
    Route::post('/dashboard/adultos', [DashboardController::class, 'storeAdulto'])->name('adultos.store');
    
    // Gestión de voluntarios
    Route::get('/dashboard/voluntarios', [DashboardController::class, 'voluntarios'])->name('voluntarios');
    
    // Gestión de visitas
    Route::get('/dashboard/visitas', [DashboardController::class, 'visitas'])->name('visitas');
    Route::post('/dashboard/visitas', [DashboardController::class, 'storeVisita'])->name('visitas.store');
    
    // IA
    Route::get('/dashboard/ai', [DashboardController::class, 'ai'])->name('ai');
    
    // Reportes
    Route::get('/dashboard/reporters', [DashboardController::class, 'reporters'])->name('reporters');
    
    // Configuración
    Route::get('/dashboard/settings', [DashboardController::class, 'settings'])->name('settings');
    
    // Contacto
    Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
    
    // Perfil de usuario
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});

// Ruta para errores 404
Route::fallback([ErrorController::class, 'notFound']);