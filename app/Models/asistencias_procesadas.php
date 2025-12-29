<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class asistencias_procesadas extends Model
{
    protected $table = 'asistencias_procesadas';
    protected $primaryKey = 'id_registro';

    protected $fillable = [
        'id_empleado',
        'fecha',
        'estado',
        'hora_entrada',
        'hora_salida',
        'minutos_faltantes',
        'minutos_extra',
        'id_tipo_incidencia',
        'id_periodo',
        'cerrado',
        'inconsistencia',
        'motivo_inconsistencia'
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_entrada' => 'datetime:H:i',
        'hora_salida' => 'datetime:H:i',
        'cerrado' => 'boolean',
        'inconsistencia' => 'boolean',
    ];

    public function empleado()
    {
        return $this->belongsTo(empleados::class, 'id_empleado');
    }

    public function tipoIncidencia()
    {
        return $this->belongsTo(tipo_incidencia::class, 'id_tipo_incidencia');
    }

    public function periodo()
    {
        return $this->belongsTo(periodos_control::class, 'id_periodo');
    }

    public function aclaraciones()
    {
        return $this->hasMany(aclaraciones::class, 'id_registro');
    }
}
