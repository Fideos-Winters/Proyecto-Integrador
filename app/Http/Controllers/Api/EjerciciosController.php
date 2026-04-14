<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EjerciciosController extends Controller
{
    public function index(Request $request)
    {
        // En el API Admin, el usuario autenticado se obtiene así:
        $extraPaciente = $request->user(); 

        // Verificamos que exista el recurso para evitar errores 500
        if (!$extraPaciente) {
            return response()->json([
                'status' => 'error',
                'message' => 'No autorizado.'
            ], 401);
        }

        $paciente = $extraPaciente->paciente;

        $ejercicios = $extraPaciente
            ->ejercicios()
            ->orderByDesc('id_ejercicios')
            ->get();

        // Devolvemos JSON
        return response()->json([
            'status'  => 'success',
            'paciente' => $paciente,
            'data'    => $ejercicios
        ], 200);
    }
}