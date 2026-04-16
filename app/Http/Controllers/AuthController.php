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

        $psicologo->tokens()->delete();

        $token = $psicologo->createToken('auth_token')->plainTextToken;

     
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
            'foto_perfil'  => $psicologo->url_imagen, 
        ]);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        session()->flush();
        session()->invalidate();
        session()->regenerateToken();

        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
    
    public function guardarSesion(Request $request) {
    session([
        'id_psicologa' => $request->id_psicologa, 
        'usuario'      => $request->usuario,
        'token'        => $request->token,
    ]);
    return response()->json(['status' => 'ok']);
}
}