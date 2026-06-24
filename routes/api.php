<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IoTController;

Route::post('/alertas', [IoTController::class, 'recibirAlerta']);
Route::get('/iot/estado/{pacienteId}', [IoTController::class, 'obtenerEstado']);
Route::get('/ultimas-alertas', [IoTController::class, 'ultimasAlertas']);
Route::get('/iot/resumen', [IoTController::class, 'resumenIoT']);
Route::get('/iot/alertas-recientes', [IoTController::class, 'alertasRecientes']);
Route::get('/iot/pacientes', [IoTController::class, 'pacientesConDispositivos']);
Route::get('/iot/estadisticas-alertas', [IoTController::class, 'estadisticasAlertas']);
Route::get('/iot/ubicaciones', [IoTController::class, 'ubicacionesPacientes']);
Route::get('/iot/exportar-excel', [IoTController::class, 'exportarExcel']);
Route::get('/iot/datos-sensores', [IoTController::class, 'datosSensores']);
Route::post('/iot/datos-sensores', [IoTController::class, 'recibirDatosSensores']);
Route::get('/iot/estadisticas-alertas', [IoTController::class, 'estadisticasAlertas']);
Route::get('/arduino/simular', [IoTController::class, 'simularArduino']);