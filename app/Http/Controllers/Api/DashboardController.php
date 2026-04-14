<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            // 1. Sanctum ya validó el token. Obtenemos al usuario directamente.
            // En una API NO usamos session().
            $extraPaciente = $request->user();
            
            if (!$extraPaciente) {
                return response()->json(['status' => 'error', 'message' => 'Usuario no identificado'], 401);
            }

            // 2. Cargamos el paciente (Asegúrate que la relación esté en el modelo ExtraPaciente)
            $paciente = $extraPaciente->paciente;

            if (!$paciente) {
                return response()->json(['status' => 'error', 'message' => 'No se encontró el perfil de paciente'], 404);
            }

            // 3. Lógica de Citas (Usamos now() para mayor compatibilidad)
            $citas = $paciente->citas()
                ->where('fecha', '>=', now()->format('Y-m-d'))
                ->orderBy('fecha')
                ->orderBy('hora')
                ->get();

            // 4. Lógica de Ejercicios
            $ejercicios = $extraPaciente->ejercicios()
                ->orderByDesc('id_ejercicios')
                ->get();

            // 5. Respuesta Maestra en JSON
            return response()->json([
                'status' => 'success',
                'data' => [
                    'perfil'         => $paciente,
                    'proximas_citas' => $citas,
                    'ejercicios'     => $ejercicios,
                    'metas'          => [
                        'total_ejercicios' => $ejercicios->count(),
                        'citas_pendientes' => $citas->count(),
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            // Si algo falla, el 500 ahora nos dirá QUÉ pasó
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}