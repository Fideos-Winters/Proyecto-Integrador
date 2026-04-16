<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'citas';
    protected $primaryKey = 'id_citas';
    public $timestamps = false; 

    protected $fillable = [
        'fecha', 
        'hora', 
        'id_pacientes', 
        'id_psicologa'
    ];

    // Relación inversa: Una cita pertenece a un paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_pacientes', 'id_pacientes');
    }
    public function notificaciones()
{
    // hasMany(Modelo, llave_foranea_en_notificaciones, llave_primaria_en_citas)
    return $this->hasMany(Notificacion::class, 'id_citas', 'id_citas');
}
}