<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ejercicio extends Model
{
    protected $table = 'ejercicios'; // Aseguraos que el nombre sea plural como en vuestro DB
    protected $primaryKey = 'id_ejercicios';
    public $timestamps = false;

protected $fillable = [
    'titulo',          
    'descripcion', 
    'id_sesion', 
    'id_extrapaciente'  
];

    public function sesion()
    {
        return $this->belongsTo(Sesion::class, 'id_sesion', 'id_sesion');
    }
}