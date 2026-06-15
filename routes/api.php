<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IoTController;

Route::post('/alertas', [IoTController::class, 'recibirAlerta']);
Route::get('/iot/estado/{pacienteId}', [IoTController::class, 'obtenerEstado']);
Route::get('/ultimas-alertas', [IoTController::class, 'ultimasAlertas']);