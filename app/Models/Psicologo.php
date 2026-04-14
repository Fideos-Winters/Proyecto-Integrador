<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens; 

class Psicologo extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'psicologo'; 
    protected $primaryKey = 'id_psicologa'; 


    public $timestamps = false; 

    protected $fillable = [
        'correo',
        'usuario',
        'contrasena',
        'imagen',
        // 'imangen' que aun no pongo en la base de datos 
    ];

    protected $hidden = [
        'contrasena',
    ];

    /**
     * le digo a laravel que voy a usar contraseña en vez de password para la autenticación'
     */
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    /**
     * Mutador para encriptar siempre que se asigne una contraseña   
     */
    public function setContrasenaAttribute($value)
    {
        // Solo encripta si no está ya encriptado (evita doble hash)
        $this->attributes['contrasena'] = \Hash::needsRehash($value) 
            ? bcrypt($value) 
            : $value;
        
    }

public function getUrlImagenAttribute()
{
    $valorReal = $this->attributes['imagen'] ?? null;

    // 1. Si no hay nada, el default
    if (empty($valorReal)) {
        return "https://admin.umbrellastella.com/assets/iconos/perfil_psicologa.jpg";
    }

    // 2. Si es de Google, se manda tal cual
    if (filter_var($valorReal, FILTER_VALIDATE_URL)) {
        return $valorReal;
    }

    // 3. Limpieza: Nos aseguramos de que no tenga prefijos raros
    // El valorReal ya debe traer "psicologos/nombre.jpg"
    $path = ltrim($valorReal, '/');

    // 4. Construimos la URL que SÍ FUNCIONA
    return "https://admin.umbrellastella.com/storage/" . $path;
}

}