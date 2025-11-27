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
    Route::post('/ai/chat', [App\Http\Controllers\AIController::class, 'chat'])->name('ai.chat.process');
    // Notificaciones
    Route::get('/notifications/mark-read', [DashboardController::class, 'markNotificationsRead'])->name('notifications.read');
    
    Route::post('/ai/chat', [App\Http\Controllers\AIController::class, 'chat'])->name('ai.chat.process');

    // Gestión de Adultos
    Route::get('/dashboard/adultos', [DashboardController::class, 'adultos'])->name('adultos');
    Route::post('/dashboard/adultos', [DashboardController::class, 'storeAdulto'])->name('adultos.store');
    Route::get('/dashboard/adultos/{adulto}', [DashboardController::class, 'show'])->name('adultos.show'); 
    Route::put('/dashboard/adultos/{adulto}', [DashboardController::class, 'update'])->name('adultos.update');
    Route::delete('/dashboard/adultos/{adulto}', [DashboardController::class, 'destroy'])->name('adultos.destroy');
    // RUTA NUEVA: Expediente Evolutivo
    Route::get('/dashboard/adultos/{adulto}/evolucion', [DashboardController::class, 'evolucionAdulto'])->name('adultos.evolucion');
    //
    
    Route::get('/dashboard/auditoria', [DashboardController::class, 'auditoria'])->name('auditoria');
    
    // ============ CREDENCIALES ============
    Route::get('/dashboard/adultos/{adulto}/credencial', [ReporteController::class, 'credencialAdulto'])->name('adultos.credencial');
    Route::get('/dashboard/voluntarios/{voluntario}/credencial', [ReporteController::class, 'credencialVoluntario'])->name('voluntarios.credencial');
    // ===============================================
    
    // Ruta temporal para ver qué modelos tienes disponibles
    Route::get('/debug-models', function() {
        $apiKey = env('GEMINI_API_KEY');
        $response = Illuminate\Support\Facades\Http::get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");
        return $response->json();
    });
    
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
    
    // ============ GESTIÓN DE INVENTARIO (¡NUEVO!) ============
    Route::get('/dashboard/inventario', [DashboardController::class, 'inventario'])->name('inventario');
    Route::post('/dashboard/inventario', [DashboardController::class, 'storeInventario'])->name('inventario.store');
    Route::get('/dashboard/inventario/{item}', [DashboardController::class, 'showInventario'])->name('inventario.show');
    Route::put('/dashboard/inventario/{item}', [DashboardController::class, 'updateInventario'])->name('inventario.update');
    Route::delete('/dashboard/inventario/{item}', [DashboardController::class, 'destroyInventario'])->name('inventario.destroy');
    // =========================================================
    
    // Ruta para COMENTARIOS (Chat)
    Route::post('/dashboard/visitas/{visita}/comentarios', [DashboardController::class, 'storeComentario'])->name('visitas.comentarios.store');

    // Calendario
    Route::get('/dashboard/calendario', [DashboardController::class, 'calendario'])->name('calendario');
    Route::get('/api/eventos-calendario', [DashboardController::class, 'getEventosCalendario'])->name('api.calendario');
    
    // Reportes
    Route::get('/dashboard/reportes', [DashboardController::class, 'reporters'])->name('reportes');
    Route::get('/reportes/exportar/general', [ReporteController::class, 'exportarGeneralExcel'])->name('reportes.excel.general');
    Route::get('/reportes/exportar/visitas', [ReporteController::class, 'exportarVisitasExcel'])->name('reportes.excel.visitas');
    Route::get('/reportes/exportar/voluntarios', [ReporteController::class, 'exportarVoluntariosExcel'])->name('reportes.excel.voluntarios');
    Route::get('/reportes/imprimir/{tipo}', [ReporteController::class, 'imprimirReporte'])->name('reportes.imprimir');
    
    // Ruta temporal para arreglar coordenadas
    Route::get('/fix-mapa', function() {
    $adultos = \App\Models\AdultoMayor::all();
    foreach($adultos as $a) {
        $a->update([
            'lat' => -13.5319 + (mt_rand(-100, 100) / 10000),
            'lon' => -71.9675 + (mt_rand(-100, 100) / 10000)
        ]);
    }
    return "Coordenadas generadas. Ve al dashboard.";
});

    // Otros
    Route::get('/dashboard/ai', [DashboardController::class, 'ai'])->name('ai');
    Route::get('/dashboard/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});

// RUTA TEMPORAL PARA ACTUALIZAR LA BASE DE DATOS
/*Route::get('/instalar-doctor-ia', function() {
    try {
        Illuminate\Support\Facades\Schema::table('visitas', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->text('recomendacion_ia')->nullable()->after('observaciones');
        });
        return "¡Éxito! Columna 'recomendacion_ia' creada correctamente. Ya puedes borrar esta ruta.";
    } catch (\Exception $e) {
        return "Error (quizás ya existe la columna): " . $e->getMessage();
    }
});*/

// RUTA DE EMERGENCIA PARA ARREGLAR IMÁGENES
Route::get('/fix-storage', function () {
    $targetFolder = storage_path('app/public');
    $linkFolder = public_path('storage');

    // 1. Si existe un enlace viejo y roto, lo borramos
    if (file_exists($linkFolder)) {
        // En Windows es rmdir, en Linux unlink suele funcionar mejor para symlinks
        @unlink($linkFolder); 
    }

    // 2. Creamos el nuevo enlace
    try {
        symlink($targetFolder, $linkFolder);
        return '¡ÉXITO! 📸 El puente de imágenes ha sido reparado. <br> Ruta Origen: '.$targetFolder.'<br> Ruta Destino: '.$linkFolder;
    } catch (\Exception $e) {
        return 'ERROR: No se pudo crear el enlace. <br> Detalle: ' . $e->getMessage();
    }
});

Route::fallback([ErrorController::class, 'notFound']);