<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\Cita;
use Carbon\Carbon;

class NotificacionController extends Controller
{
public function index() 
{
    // 1. LIMPIEZA: Borrar notificaciones de citas que YA NO son para mañana
    // o que ya pasaron de fecha.
    $mañana = \Carbon\Carbon::tomorrow()->toDateString();
    
    \App\Models\Notificacion::whereHas('cita', function($query) use ($mañana) {
        $query->where('fecha', '!=', $mañana);
    })->delete();

    // 2. GENERACIÓN: (Tu lógica anterior de crear las de mañana)
    $this->generarNotificacionesAutomaticas();

    // 3. MOSTRAR:
    $notificaciones = Notificacion::with('cita.paciente')->get();
    return view('notificaciones.index', compact('notificaciones'));
}

    private function generarNotificacionesAutomaticas()
    {
        $mañana = Carbon::tomorrow()->toDateString();

        // Buscamos citas de mañana que aún no tengan una notificación creada
        $citasParaNotificar = Cita::where('fecha', $mañana)
            ->whereDoesntHave('notificaciones')
            ->get();

        foreach ($citasParaNotificar as $cita) {
            Notificacion::create([
                'tipo_notificacion' => 'Recordatorio de Cita',
                'medio_envio'       => 'Sistema Interno',
                'fecha_envio'       => now(),
                'id_citas'          => $cita->id_citas
            ]);
        }
    }
}