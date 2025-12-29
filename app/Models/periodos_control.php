<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class periodos_control extends Model
{
    protected $table = 'periodos_control';
    protected $primaryKey = 'id_periodo';

    protected $fillable = [
        'fecha_inicio',
        'fecha_fin',
        'cerrado',
        'fecha_cierre'
    ];

    protected $casts = [
        'cerrado' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_cierre' => 'datetime',
    ];

    public function asistenciasProcesadas()
    {
        return $this->hasMany(asistencias_procesadas::class, 'id_periodo');
    }
}
