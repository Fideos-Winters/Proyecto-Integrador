<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table = 'notas';
    protected $primaryKey = 'id_notas';
    public $timestamps = false;

    protected $fillable = [
        'subjetivo',
        'anotaciones',
        'id_sesion'
    ];

    // Una nota pertenece a una sesión
    public function sesion()
    {
        return $this->belongsTo(Sesion::class, 'id_sesion', 'id_sesion');
    }
}