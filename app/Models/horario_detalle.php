<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class horario_detalle extends Model
{
    protected $table = 'horario_detalle';
    protected $primaryKey = 'id_detalle';

    protected $fillable = [
        'id_horario',
        'dia',
        'entrada',
        'tolerancia',
        'comida_inicio',
        'comida_fin',
        'salida'
    ];

    protected $casts = [
        'dia' => 'integer',
        'entrada' => 'datetime:H:i',
        'salida' => 'datetime:H:i',
        'comida_inicio' => 'datetime:H:i',
        'comida_fin' => 'datetime:H:i',
    ];

    public function horario()
    {
        return $this->belongsTo(horarios::class, 'id_horario');
    }
}