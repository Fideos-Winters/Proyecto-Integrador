<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\ExtraPaciente;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validar la entrada
        $request->validate([
            'correo'     => 'required|email',
            'contrasena' => 'required',
        ]);

        // 2. Buscar al paciente por correo
        $user = ExtraPaciente::where('correo', $request->correo)->first();

        // 3. Verificar si existe y si la contraseña es correcta
        if (!$user || !Hash::check($request->contrasena, $user->contrasena)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Las credenciales son incorrectas.'
            ], 401);
        }

        // 4. Crear el Token de Sanctum
        $token = $user->createToken('patient_token')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Bienvenido al santuario',
            'token'   => $token,
            'user'    => [
                'id'      => $user->id_extrapaciente,
                'usuario' => $user->usuario,
                'correo'  => $user->correo,
            ]
        ], 200);
    }

    public function me(Request $request)
    {
        // Retorna los datos del usuario autenticado por el token
        return response()->json($request->user()->load('paciente'));
    }

    public function logout(Request $request)
    {
        // Borramos el token actual para invalidar la sesión
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Token revocado correctamente.'
        ]);
    }
}