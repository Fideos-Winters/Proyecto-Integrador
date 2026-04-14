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
    

    // Revocar tokens anteriores 
    $psicologo->tokens()->delete();

    $token = $psicologo->createToken('auth_token')->plainTextToken;

    // Guardamos los datos en la sesión para que Blade pueda leerlos
    session([
        'id_psicologa' => $psicologo->id_psicologa,
        'usuario'      => $psicologo->usuario,
        'correo'       => $psicologo->correo,
        'token'        => $token,
        'foto_perfil'  => $psicologo->url_imagen,
    ]);
    // ───────────────────────────────────────────────────────────────

    return response()->json([
        'message'      => 'Login exitoso.',
        'access_token' => $token,
        'token_type'   => 'Bearer',
        'usuario'      => $psicologo->usuario,
        'correo'       => $psicologo->correo,
        'id_psicologa' => $psicologo->id_psicologa,
    ]);
}

public function logout(Request $request)
{
    // Borra token de Sanctum 
    $request->user()->currentAccessToken()->delete();

    // Limpiar TODA la sesión, incluyendo el id_psicologa
    session()->forget(['token', 'usuario', 'correo', 'id_psicologa']);
    session()->invalidate();
    session()->regenerateToken();

    return response()->json(['message' => 'Sesión cerrada correctamente.']);
}

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}