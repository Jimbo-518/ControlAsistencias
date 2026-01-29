<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class incidencias extends Model
{
    protected $table = 'incidencias';
    protected $primaryKey = 'id_incidencia';

    protected $fillable = [
        'id_empleado',
        'fecha_inicio',
        'fecha_fin',
        'descripcion',
        'id_tipo_incidencia',
        'auditor',
        'evidencia_url',
        'estatus',
        'archivada'
    ];

    protected $casts = [
        'fecha_inicio'           => 'date',
        'fecha_fin'              => 'date',
        'fecha_registro'  => 'datetime',
        'archivada'       => 'boolean'
    ];

    public function empleado()
    {
        return $this->belongsTo(empleados::class, 'id_empleado');
    }

    public function tipo()
    {
        return $this->belongsTo(tipo_incidencia::class, 'id_tipo_incidencia');
    }

    public function auditorUsuario()
    {
        return $this->belongsTo(usuarios::class, 'auditor', 'id_usuario');
    }
}
