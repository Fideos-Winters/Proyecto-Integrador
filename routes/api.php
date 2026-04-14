<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\EjerciciosController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\DashboardController;

/**
 * Rutas Públicas de Diagnóstico
 */
Route::get('/ping', fn() => response()->json(['message' => 'API Admin en línea']));

/**
 * Autenticación de Administrador / Login Tradicional
 * Se asigna nombre 'login' para prevenir excepciones de redirección en Sanctum.
 */
Route::post('/login', [AuthController::class, 'login'])->name('login');

/**
 * Ritual de Google OAuth para Pacientes
 */
Route::prefix('auth')->group(function () {
    Route::get('/google/redirect', [GoogleAuthController::class, 'redirect']);
    Route::get('/google/callback', [GoogleAuthController::class, 'callback']);
});

/**
 * Rutas Protegidas vía Sanctum
 * Requieren el encabezado: Authorization: Bearer {token}
 */
Route::middleware('auth:sanctum')->group(function () {
    
    // Gestión de Sesión
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Recursos del Paciente
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/ejercicios', [EjerciciosController::class, 'index']);
    
    // Sincronización de Calendario (Google Calendar)
    Route::post('/citas/sincronizar', [CalendarController::class, 'sincronizar']);
});

/**
 * Captura de Rutas inexistentes (Fallback)
 */
Route::fallback(fn() => response()->json(['status' => 'error', 'message' => 'Endpoint no encontrado'], 404));