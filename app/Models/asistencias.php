<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class asistencias extends Model
{
    protected $table = 'asistencias';
    protected $primaryKey = 'id_registro';

    protected $fillable = [
        'id_empleado',
        'fecha',
        'hora',
        'tipo_registro',
        'origen',
        'archivado'
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora' => 'datetime:H:i',
        'archivado' => 'boolean'
    ];

    public function empleado()
    {
        return $this->belongsTo(empleados::class, 'id_empleado');
    }
}
