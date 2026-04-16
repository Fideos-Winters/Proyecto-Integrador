<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sesion extends Model
{
    protected $table = 'sesion';
    protected $primaryKey = 'id_sesion';
    public $timestamps = false;

    protected $fillable = [
        'fecha', 
        'hora_inicio', 
        'hora_fin', 
        'id_expediente',
        'id_notas',      
        'id_ejercicios'  
    ];

    public function notas()
    {
        return $this->hasMany(Nota::class, 'id_sesion', 'id_sesion');
    }

    public function ejercicios()
    {
        return $this->hasMany(Ejercicio::class, 'id_sesion', 'id_sesion');
    }

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'id_expediente', 'id_expediente');
    }
}