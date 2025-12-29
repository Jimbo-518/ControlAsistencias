<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class horarios extends Model
{
    protected $table = 'horarios';
    protected $primaryKey = 'id_horario';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function detalles()
    {
        return $this->hasMany(horario_detalle::class, 'id_horario');
    }

    public function asignaciones()
    {
        return $this->hasMany(depto_edificio_horario::class, 'id_horario');
    }
}
