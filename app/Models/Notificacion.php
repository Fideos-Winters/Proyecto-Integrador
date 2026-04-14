<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';
    protected $primaryKey = 'id_notificaciones';
    public $timestamps = false; // Ya tienes fecha_envio manual

    protected $fillable = [
        'tipo_notificacion',
        'medio_envio',       
        'fecha_envio',
        'id_citas'
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'id_citas', 'id_citas');
    }
}