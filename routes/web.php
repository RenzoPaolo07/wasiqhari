<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\VgiController;
use App\Http\Controllers\AIController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan; // Importante para los comandos

// --- RUTAS PÚBLICAS ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// --- RUTAS DE AUTENTICACIÓN ---
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::get('/register', [UserController::class, 'register'])->name('register');
Route::post('/login', [UserController::class, 'authenticate']);
Route::post('/register', [UserController::class, 'store']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// --- RUTAS PROTEGIDAS (DASHBOARD) ---
Route::middleware(['auth'])->group(function () {
    
    // Dashboard & AI
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/ai/chat', [AIController::class, 'chat'])->name('ai.chat.process');
    Route::get('/dashboard/ai', [DashboardController::class, 'ai'])->name('ai');

    // Notificaciones
    Route::get('/notifications/mark-read', [DashboardController::class, 'markNotificationsRead'])->name('notifications.read');
    
    // Perfil y Configuración
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/dashboard/settings', [DashboardController::class, 'settings'])->name('settings');

    // Auditoría
    Route::get('/dashboard/auditoria', [DashboardController::class, 'auditoria'])->name('auditoria');

    // ==================================================
    // GESTIÓN DE ADULTOS MAYORES
    // ==================================================
    Route::get('/dashboard/adultos', [DashboardController::class, 'adultos'])->name('adultos');
    Route::post('/dashboard/adultos', [DashboardController::class, 'storeAdulto'])->name('adultos.store');
    Route::get('/dashboard/adultos/{adulto}', [DashboardController::class, 'show'])->name('adultos.show'); 
    Route::put('/dashboard/adultos/{adulto}', [DashboardController::class, 'update'])->name('adultos.update');
    Route::delete('/dashboard/adultos/{adulto}', [DashboardController::class, 'destroy'])->name('adultos.destroy');

    // --- HISTORIA CLÍNICA VGI ---
    // Ver formulario
    Route::get('/dashboard/adultos/{id}/vgi', [VgiController::class, 'show'])->name('adultos.vgi');
    // Guardar formulario (Apunta a VgiController@store)
    Route::post('/dashboard/adultos/{id}/vgi/store', [VgiController::class, 'store'])->name('adultos.vgi.store');
    
    // --- EXPEDIENTE EVOLUTIVO ---
    Route::get('/dashboard/adultos/{adulto}/evolucion', [DashboardController::class, 'evolucionAdulto'])->name('adultos.evolucion');

    // --- CREDENCIALES ---
    Route::get('/dashboard/adultos/{adulto}/credencial', [ReporteController::class, 'credencialAdulto'])->name('adultos.credencial');


    // ==================================================
    // GESTIÓN DE VOLUNTARIOS
    // ==================================================
    Route::get('/dashboard/voluntarios', [DashboardController::class, 'voluntarios'])->name('voluntarios');
    Route::get('/dashboard/voluntarios/{voluntario}', [DashboardController::class, 'showVoluntario'])->name('voluntarios.show');
    Route::put('/dashboard/voluntarios/{voluntario}', [DashboardController::class, 'updateVoluntario'])->name('voluntarios.update');
    Route::delete('/dashboard/voluntarios/{voluntario}', [DashboardController::class, 'destroyVoluntario'])->name('voluntarios.destroy');
    Route::get('/dashboard/voluntarios/{voluntario}/credencial', [ReporteController::class, 'credencialVoluntario'])->name('voluntarios.credencial');

    // ==================================================
    // GESTIÓN DE VISITAS
    // ==================================================
    Route::get('/dashboard/visitas', [DashboardController::class, 'visitas'])->name('visitas');
    Route::post('/dashboard/visitas', [DashboardController::class, 'storeVisita'])->name('visitas.store');
    Route::get('/dashboard/visitas/{visita}', [DashboardController::class, 'showVisita'])->name('visitas.show');
    Route::put('/dashboard/visitas/{visita}', [DashboardController::class, 'updateVisita'])->name('visitas.update');
    Route::delete('/dashboard/visitas/{visita}', [DashboardController::class, 'destroyVisita'])->name('visitas.destroy');
    // Comentarios en Visitas
    Route::post('/dashboard/visitas/{visita}/comentarios', [DashboardController::class, 'storeComentario'])->name('visitas.comentarios.store');

    // ==================================================
    // GESTIÓN DE INVENTARIO
    // ==================================================
    Route::get('/dashboard/inventario', [DashboardController::class, 'inventario'])->name('inventario');
    Route::post('/dashboard/inventario', [DashboardController::class, 'storeInventario'])->name('inventario.store');
    Route::get('/dashboard/inventario/{item}', [DashboardController::class, 'showInventario'])->name('inventario.show');
    Route::put('/dashboard/inventario/{item}', [DashboardController::class, 'updateInventario'])->name('inventario.update');
    Route::delete('/dashboard/inventario/{item}', [DashboardController::class, 'destroyInventario'])->name('inventario.destroy');

    // ==================================================
    // CALENDARIO Y REPORTES
    // ==================================================
    Route::get('/dashboard/calendario', [DashboardController::class, 'calendario'])->name('calendario');
    Route::get('/api/eventos-calendario', [DashboardController::class, 'getEventosCalendario'])->name('api.calendario');
    
    Route::get('/dashboard/reportes', [DashboardController::class, 'reporters'])->name('reportes');
    Route::get('/reportes/exportar/general', [ReporteController::class, 'exportarGeneralExcel'])->name('reportes.excel.general');
    Route::get('/reportes/exportar/visitas', [ReporteController::class, 'exportarVisitasExcel'])->name('reportes.excel.visitas');
    Route::get('/reportes/exportar/voluntarios', [ReporteController::class, 'exportarVoluntariosExcel'])->name('reportes.excel.voluntarios');
    Route::get('/reportes/imprimir/{tipo}', [ReporteController::class, 'imprimirReporte'])->name('reportes.imprimir');
});

// ==========================================================
// 🛠️ RUTAS DE MANTENIMIENTO DEL SISTEMA 🛠️
// ==========================================================

// 1. RUTA PARA EJECUTAR MIGRACIONES (SOLUCIONA TU ERROR DE BASE DE DATOS)
Route::get('/update-system', function () {
    try {
        // Ejecutar Migraciones (Crea las columnas faltantes: sindrome_caidas, vive_con, etc)
        Artisan::call('migrate', ['--force' => true]);
        $migracion = Artisan::output();

        // Limpiar Caché
        Artisan::call('optimize:clear');
        $cache = Artisan::output();

        // Enlazar Storage (Para las fotos)
        try {
            Artisan::call('storage:link');
            $storage = Artisan::output();
        } catch (\Exception $e) {
            $storage = "El storage ya estaba linkeado.";
        }

        return "
            <div style='font-family: sans-serif; padding: 20px;'>
                <h1 style='color: green;'>¡Sistema Actualizado con Éxito! 🚀</h1>
                <p>Las columnas faltantes se han creado en la base de datos.</p>
                <hr>
                <h3>Detalle de Migraciones:</h3>
                <pre style='background: #f4f4f4; padding: 10px;'>$migracion</pre>
                <hr>
                <h3>Caché:</h3>
                <pre style='background: #f4f4f4; padding: 10px;'>$cache</pre>
                <br>
                <a href='/dashboard/adultos' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Volver al Panel</a>
            </div>
        ";

    } catch (\Exception $e) {
        return "<h1 style='color: red;'>❌ Error Crítico</h1><pre>" . $e->getMessage() . "</pre>";
    }
});

// 2. RUTA PARA ARREGLAR IMÁGENES (SI NO CARGAN)
Route::get('/fix-storage', function () {
    $targetFolder = storage_path('app/public');
    $linkFolder = public_path('storage');
    if (file_exists($linkFolder)) { @unlink($linkFolder); }
    try {
        symlink($targetFolder, $linkFolder);
        return '¡ÉXITO! 📸 El puente de imágenes ha sido reparado.';
    } catch (\Exception $e) {
        return 'ERROR: ' . $e->getMessage();
    }
});

// Ruta de Fallback (Error 404)
Route::fallback([ErrorController::class, 'notFound']);