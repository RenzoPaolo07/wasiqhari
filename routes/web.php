<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReporteController; // ¡Asegúrate de importar esto!
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
    
    // Notificaciones
    Route::get('/notifications/mark-read', [DashboardController::class, 'markNotificationsRead'])->name('notifications.read');
    
    // Gestión de Adultos
    Route::get('/dashboard/adultos', [DashboardController::class, 'adultos'])->name('adultos');
    Route::post('/dashboard/adultos', [DashboardController::class, 'storeAdulto'])->name('adultos.store');
    Route::get('/dashboard/adultos/{adulto}', [DashboardController::class, 'show'])->name('adultos.show'); 
    Route::put('/dashboard/adultos/{adulto}', [DashboardController::class, 'update'])->name('adultos.update');
    Route::delete('/dashboard/adultos/{adulto}', [DashboardController::class, 'destroy'])->name('adultos.destroy');
    
    // ============ CREDENCIALES (¡NUEVO!) ============
    Route::get('/dashboard/adultos/{adulto}/credencial', [ReporteController::class, 'credencialAdulto'])->name('adultos.credencial');
    Route::get('/dashboard/voluntarios/{voluntario}/credencial', [ReporteController::class, 'credencialVoluntario'])->name('voluntarios.credencial');
    // ===============================================
    
    // Gestión de Voluntarios
    Route::get('/dashboard/voluntarios', [DashboardController::class, 'voluntarios'])->name('voluntarios');
    Route::get('/dashboard/voluntarios/{voluntario}', [DashboardController::class, 'showVoluntario'])->name('voluntarios.show');
    Route::put('/dashboard/voluntarios/{voluntario}', [DashboardController::class, 'updateVoluntario'])->name('voluntarios.update');
    Route::delete('/dashboard/voluntarios/{voluntario}', [DashboardController::class, 'destroyVoluntario'])->name('voluntarios.destroy');
    
    // Gestión de Visitas
    Route::get('/dashboard/visitas', [DashboardController::class, 'visitas'])->name('visitas');
    Route::post('/dashboard/visitas', [DashboardController::class, 'storeVisita'])->name('visitas.store');
    Route::get('/dashboard/visitas/{visita}', [DashboardController::class, 'showVisita'])->name('visitas.show');
    Route::put('/dashboard/visitas/{visita}', [DashboardController::class, 'updateVisita'])->name('visitas.update');
    Route::delete('/dashboard/visitas/{visita}', [DashboardController::class, 'destroyVisita'])->name('visitas.destroy');

    // Calendario
    Route::get('/dashboard/calendario', [DashboardController::class, 'calendario'])->name('calendario');
    Route::get('/api/eventos-calendario', [DashboardController::class, 'getEventosCalendario'])->name('api.calendario');
    
    // Reportes
    Route::get('/dashboard/reportes', [DashboardController::class, 'reporters'])->name('reportes');
    Route::get('/reportes/exportar/general', [ReporteController::class, 'exportarGeneralExcel'])->name('reportes.excel.general');
    Route::get('/reportes/exportar/visitas', [ReporteController::class, 'exportarVisitasExcel'])->name('reportes.excel.visitas');
    Route::get('/reportes/exportar/voluntarios', [ReporteController::class, 'exportarVoluntariosExcel'])->name('reportes.excel.voluntarios');
    Route::get('/reportes/imprimir/{tipo}', [ReporteController::class, 'imprimirReporte'])->name('reportes.imprimir');
    
    // Otros
    Route::get('/dashboard/ai', [DashboardController::class, 'ai'])->name('ai');
    Route::get('/dashboard/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});

Route::fallback([ErrorController::class, 'notFound']);