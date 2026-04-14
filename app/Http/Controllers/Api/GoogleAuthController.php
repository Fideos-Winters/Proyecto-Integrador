<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExtraPaciente;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    // 1. Redirigir a Google
    public function redirect()
    {
        return Socialite::driver('google')
            ->scopes(['https://www.googleapis.com/auth/calendar.events'])
            ->stateless()
            ->redirect();
    }

    // 2. Recibir respuesta de Google y "saltar" al Cliente 
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Buscar al paciente por el correo que viene de Google
            $user = ExtraPaciente::where('correo', $googleUser->getEmail())->first();

              if ($user) {
              $retrato = $googleUser->getAvatar();

             // 1. Guardamos en la cuenta de acceso
             $user->update(['foto' => $retrato]);

                // 2. Guardamos en la ficha clínica (la que ve la psicóloga)
                // Asumiendo que tienes la relación 'paciente' definida en tu modelo ExtraPaciente
                if ($user->paciente) {
               $user->paciente->update(['imagen' => $retrato]);
                }
            }
    
            if (!$user) {
                // Si no existe, lo mandamos al login del CLIENTE (8002) con error
                return redirect("http://cliente.umbrellastella.com/login?error=no_registrado");
            }

            // Creamos el token de Sanctum (tu llave para el santuario)
            $token = $user->createToken('patient_token')->plainTextToken;

            $googleToken = $googleUser->token;

      

            
            return redirect("http://cliente.umbrellastella.com/auth/callback?token={$token}&google_token={$googleToken}");
            
        } catch (\Exception $e) {
            return redirect("http://cliente.umbrellastella.com/login?error=error");
        }
    }
}