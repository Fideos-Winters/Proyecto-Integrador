<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\PsicologoController;
use App\Http\Controllers\Api\GoogleAuthController;


Route::get('/', function () {
    return view('auth.login');
});
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);


//route::view('/login', '/auth/login');
Route::view('/sidebar','/layouts/app');
//Route::view('/Inicio','/inicio/principal');

//Login
Route::get('/login', function () {
    return view('auth.login');
});

Route::post('/logout', function (Request $request) {
    $token = session('token');
    if ($token) {
        \Laravel\Sanctum\PersonalAccessToken::findToken($token)?->delete();
    }

    // 2. Limpiar sesión PHP completamente
    session()->forget(['token', 'usuario', 'correo']);
    session()->invalidate();
    session()->regenerateToken();

    return redirect('/login');
});

//guardar sesion
Route::post('/guardar-sesion', function (Request $request) {
    session(['token'   => $request->token]);
    session(['usuario' => $request->usuario]);
    session(['correo'  => $request->correo]);
    return response()->json(['ok' => true]);
});

//protegidas
Route::middleware(['auth.token'])->group(function () {
    
Route::get('/inicio', [App\Http\Controllers\InicioController::class, 'principal'])->name('inicio');

    // Rutas de Pacientes 
    Route::resource('pacientes', PacienteController::class
    );
//pacientes.create 
//pacientes.
//pacientes.edit
//pacientes.update
//pacientes.destroy





    //estas son las rutas de citas 


    
    Route::resource('expedientes', ExpedienteController::class);
    Route::resource('sesiones', SesionController::class);

    //ruta para  la cita
    Route::get('/citas/historial', [CitaController::class, 'historial'])->name('citas.historial');
    Route::resource('citas', CitaController::class);

    //notificaciones
    Route::get('/notificaciones', [App\Http\Controllers\NotificacionController::class, 'index'])
    ->name('notificaciones.index');



    //psicologos
Route::resource('psicologos', PsicologoController::class)->parameters([
    'psicologos' => 'id_psicologa'



    
]);
    // Cualquier otra ruta que añadas aquí también estará protegida por el middleware 'auth.token'


    
});