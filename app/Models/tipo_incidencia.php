<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tipo_incidencia extends Model
{
    protected $table = 'tipo_incidencia';
    protected $primaryKey = 'id_tipo';

    protected $fillable = [
        'nombre',
        'descripcion',
        'alcance',
        'genera_falta',
        'elimina_falta',
        'permite_asistencia',
        'modifica_horario',
        'hora_entrada',
        'hora_salida',
        'activo'
    ];

    protected $casts = [
        'genera_falta'       => 'boolean',
        'elimina_falta'     => 'boolean',
        'permite_asistencia'=> 'boolean',
        'modifica_horario'  => 'boolean',
        'activo'            => 'boolean',
        'hora_entrada'      => 'datetime:H:i',
        'hora_salida'       => 'datetime:H:i',
    ];

    public function incidencias()
    {
        return $this->hasMany(incidencias::class, 'id_tipo_incidencia');
    }
}
