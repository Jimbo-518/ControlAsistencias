<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class aclaraciones extends Model
{
    protected $table = 'aclaraciones';
    protected $primaryKey = 'id_aclaracion';

    protected $fillable = [
        'id_registro',
        'id_usuario',
        'id_tipo_incidencia',
        'descripcion',
        'fecha_aclaracion',
        'evidencia',
        'archivado'
    ];

    protected $casts = [
        'fecha_aclaracion' => 'datetime',
        'archivado' => 'boolean',
    ];

    public function asistenciaProcesada()
    {
        return $this->belongsTo(asistencias_procesadas::class, 'id_registro');
    }

    public function usuario()
    {
        return $this->belongsTo(usuarios::class, 'id_usuario');
    }

    public function tipoIncidencia()
    {
        return $this->belongsTo(tipo_incidencia::class, 'id_tipo_incidencia');
    }
}
