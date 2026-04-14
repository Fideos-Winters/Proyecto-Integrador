<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    //nombre de la tabla
    protected $table = 'expediente';
//se define la variable expediente el id
    protected $primaryKey = 'id_expediente';

    // 3. El Guardián: Campos que permitimos llenar desde el formulario
    protected $fillable = [
        'id_pacientes',
        'motivo_consulta',
        'diagnostico',
        'ocupacion',
        'edad'
    ];

//desacrivams el timestamp
    public $timestamps = false;

//relacion de paciente con expediente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_pacientes', 'id_pacientes');
    }

  //relacion de expediente con sesiones
    public function sesiones()
    {
        return $this->hasMany(Sesion::class, 'id_expediente', 'id_expediente');
    }
}