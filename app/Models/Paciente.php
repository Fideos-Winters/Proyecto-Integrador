<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';

    protected $primaryKey = 'id_pacientes';

    public $timestamps = false; 

    protected $fillable = [
        'nombre', 
        'imagen',
        'apellido', 
        'telefono', 
        'correo', 
        'fecha_nacimiento', 
        'id_psicologa'
    ];


    public function expediente()
    {
        return $this->hasOne(Expediente::class, 'id_pacientes', 'id_pacientes');
    }


// En App\Models\Paciente.php

public function extras()
{

    return $this->hasOne(ExtraPaciente::class, 'id_paciente', 'id_pacientes');
}

public function citas()
    {
        return $this->hasMany(Cita::class, 'id_pacientes', 'id_pacientes');
    }

   

    
}