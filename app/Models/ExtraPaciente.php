<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; 
class ExtraPaciente extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $keyType = 'int'; 
public $incrementing = true;

public function getKey()
{
    return $this->id_extrapaciente;
}
    protected $table = 'extra_pacientes';
    protected $primaryKey = 'id_extrapaciente';
    public $timestamps = false;

    protected $fillable = [
        'usuario', 
        'contrasena', 
        'correo', 
        'id_paciente'
    ];


    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    /**
     * Relación con el paciente principal
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente', 'id_pacientes');
    }

    /**
     * Relación con los ejercicios (para el controlador que vimos antes)
     */
public function ejercicios()
    {
      
        return $this->hasMany(Ejercicio::class, 'id_extrapaciente', 'id_extrapaciente');
    }
    
}