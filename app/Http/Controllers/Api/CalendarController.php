<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CalendarController extends Controller
{
    public function index()
    {
        $extraPaciente = Auth::guard('patient')->user();
        $paciente      = $extraPaciente->paciente;

        $citas = $paciente
            ->citas()
            ->where('fecha', '>=', today())
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        return view('patient.citas', compact('paciente', 'citas'));
    }


public function sincronizar(\Illuminate\Http\Request $request)
{
    $tokenGoogle = $request->input('google_token');

    if (!$tokenGoogle) {
        return response()->json(['status' => 'error', 'message' => 'Token de Google ausente.'], 400);
    }

    $extraPaciente = $request->user();
    // Asegúrate de que la relación "paciente" esté cargada
    $paciente = $extraPaciente->paciente;

    if (!$paciente) {
        return response()->json(['status' => 'error', 'message' => 'No se encontró el perfil del paciente.'], 404);
    }

    $citas = $paciente->citas()
        ->where('fecha', '>=', today())
        ->orderBy('fecha')
        ->get();

    $exitosas = 0;
    $errores = 0;
    $ultimoError = '';

    foreach ($citas as $cita) {
        
        $inicio = date('Y-m-d\TH:i:s', strtotime($cita->fecha . ' ' . $cita->hora));
        $fin = date('Y-m-d\TH:i:s', strtotime($cita->fecha . ' ' . $cita->hora) + 3600);

        $evento = [
            'summary'     => 'Cita — Sanando Almas',
            'description' => 'Cita programada en el portal Sanando Almas.',
            'start'       => ['dateTime' => $inicio, 'timeZone' => 'America/Mexico_City'],
            'end'         => ['dateTime' => $fin, 'timeZone' => 'America/Mexico_City'],
        ];

        $response = Http::withToken($tokenGoogle)
            ->post('https://www.googleapis.com/calendar/v3/calendars/primary/events', $evento);

        if ($response->successful()) {
            $exitosas++;
        } else {
            $errores++;
            $ultimoError = $response->json()['error']['message'] ?? 'Error desconocido';
        }
    }

    if ($exitosas > 0) {
        return response()->json([
            'status' => 'success', 
            'message' => "¡Éxito! Se sincronizaron {$exitosas} citas."
        ]);
    }

    if ($exitosas === 0 && $errores === 0) {
        return response()->json(['status' => 'info', 'message' => "No hay citas para sincronizar."]);
    }

    return response()->json([
        'status' => 'error', 
        'message' => "Error de Google: " . $ultimoError
    ], 500);
}

}

