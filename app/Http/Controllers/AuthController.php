<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Psicologo;

class AuthController extends Controller
{
    public function login(Request $request)
    {   
        $request->validate([
            'usuario'    => 'required|string',
            'contrasena' => 'required|string',  
        ]);

        $psicologo = Psicologo::where('usuario', $request->usuario)->first();

        if (!$psicologo || !Hash::check($request->contrasena, $psicologo->contrasena)) {
            return response()->json([
                'message' => 'Credenciales incorrectas.'
            ], 401);
        }       

        // Revocar tokens anteriores para mantener el santuario limpio
        $psicologo->tokens()->delete();

        $token = $psicologo->createToken('auth_token')->plainTextToken;

        /**
         * Guardamos solo lo vital en la sesión.
         * No guardamos 'foto_perfil' aquí para evitar que se quede obsoleta (caché).
         * El Layout se encargará de traer la foto más reciente usando el 'id_psicologa'.
         */
        session([
            'id_psicologa' => $psicologo->id_psicologa,
            'usuario'      => $psicologo->usuario,
            'correo'       => $psicologo->correo,
            'token'        => $token,
        ]);

        return response()->json([
            'message'      => 'Login exitoso.',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'usuario'      => $psicologo->usuario,
            'correo'       => $psicologo->correo,
            'id_psicologa' => $psicologo->id_psicologa,
            'foto_perfil'  => $psicologo->url_imagen, // En el JSON de respuesta sí es útil enviarla
        ]);
    }

    public function logout(Request $request)
    {
        // Borra el token de Sanctum (la llave de la API)
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        // Limpiar TODA la sesión del servidor para evitar fantasmas
        session()->flush();
        session()->invalidate();
        session()->regenerateToken();

        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }

    public function me(Request $request)
    {
        // Retornamos los datos frescos del usuario autenticado
        return response()->json($request->user());
    }
}