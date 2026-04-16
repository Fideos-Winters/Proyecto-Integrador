<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $extraPaciente = $request->user();
            
            if (!$extraPaciente) {
                return response()->json(['status' => 'error', 'message' => 'Usuario no identificado'], 401);
            }

            $paciente = $extraPaciente->paciente;

            if (!$paciente) {
                return response()->json(['status' => 'error', 'message' => 'No se encontró el perfil de paciente'], 404);
            }

            $citas = $paciente->citas()
                ->where('fecha', '>=', now()->format('Y-m-d'))
                ->orderBy('fecha')
                ->orderBy('hora')
                ->get();

            $ejercicios = $extraPaciente->ejercicios()
                ->orderByDesc('id_ejercicios')
                ->get();

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
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}