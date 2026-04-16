<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EjerciciosController extends Controller
{
    public function index(Request $request)
    {
        $extraPaciente = $request->user(); 

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

        return response()->json([
            'status'  => 'success',
            'paciente' => $paciente,
            'data'    => $ejercicios
        ], 200);
    }
}