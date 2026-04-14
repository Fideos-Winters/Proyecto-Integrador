<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InicioController extends Controller
{
    //
public function principal() 
{
    // Traemos todas las citas con el nombre del paciente
    $citas = \App\Models\Cita::with('paciente')->get();

    // Transformamos las citas al formato que FullCalendar entiende
    $eventos = $citas->map(function($cita) {
        return [
            'title' => 'Cita: ' . $cita->paciente->nombre,
            'start' => $cita->fecha . 'T' . $cita->hora,
            // Si tus citas duran 1 hora por defecto, calculamos el fin
            'end'   => $cita->fecha . 'T' . \Carbon\Carbon::parse($cita->hora)->addHour()->toTimeString(),
            'color' => '#010e6b', // Color principal de tu diseño
            'paciente' => $cita->paciente->nombre . ' ' . $cita->paciente->apellido,
            'hora_formateada' => \Carbon\Carbon::parse($cita->hora)->format('h:i A')
        ];
    });

    return view('inicio.principal', compact('eventos'));
}
}
