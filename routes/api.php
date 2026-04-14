<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\EjerciciosController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\DashboardController;

// 1. Diagnóstico y Públicas
Route::get('/ping', function () {
    return response()->json(['message' => 'API funcionando']);
});
Route::get('/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
Route::post('/login', [AuthController::class, 'login']);

// 2. Rutas de Google OAuth (FUERA del fallback y del middleware auth)
Route::prefix('auth')->group(function () {
    Route::get('/google/redirect', [GoogleAuthController::class, 'redirect']);
    Route::get('/google/callback', [GoogleAuthController::class, 'callback']);
    
});


// 3. Rutas Protegidas (Requieren Token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    Route::get('/ejercicios', [EjerciciosController::class, 'index']);
    
// AQUÍ ES DONDE SUCEDE LA MAGIA
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::middleware('auth:sanctum')->post('/citas/sincronizar', [CalendarController::class, 'sincronizar']);    
    // También el endpoint 'me' por si lo necesitas
    Route::get('/me', [AuthController::class, 'me']);});

// 4. Fallback (DEBE IR AL FINAL DE TODO)
Route::fallback(function(){
    return response()->json([
        'status' => 'error',
        'message' => 'Ruta no encontrada. Revisa la documentación de la API.'
    ], 404);
});