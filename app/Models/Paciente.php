<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    // El nombre de la tabla en vuestro santuario
    protected $table = 'pacientes';

    // La clave primaria de los pacientes
    protected $primaryKey = 'id_pacientes';

    // Desactivamos los timestamps ya que la tabla no tiene created_at/updated_at
    public $timestamps = false; 

    // Los campos que se pueden llenar (Mass Assignment)
    protected $fillable = [
        'nombre', 
        'imagen',
        'apellido', 
        'telefono', 
        'correo', 
        'fecha_nacimiento', 
        'id_psicologa'
    ];

    /**
     * Relación con el Expediente Clínico.
     * ESTA ES LA FUNCIÓN QUE FALTA Y CAUSA EL ERROR 500.
     */
    public function expediente()
    {
        // Un paciente tiene un solo expediente (One-to-One)
        return $this->hasOne(Expediente::class, 'id_pacientes', 'id_pacientes');
    }

    /**
     * Relación con los datos de acceso (extra_pacientes).
     */
// En App\Models\Paciente.php

public function extras()
{
    /**
     * hasOne(ModeloRelacionado, llave_foranea_en_tabla_extra, llave_primaria_en_tabla_paciente)
     */
    return $this->hasOne(ExtraPaciente::class, 'id_paciente', 'id_pacientes');
}

public function citas()
    {
        return $this->hasMany(Cita::class, 'id_pacientes', 'id_pacientes');
    }

   

    
}