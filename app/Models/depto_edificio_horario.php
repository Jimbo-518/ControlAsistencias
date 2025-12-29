<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class depto_edificio_horario extends Model
{
    protected $table = 'depto_edificio_horario';
    protected $primaryKey = 'id_deh';

    protected $fillable = [
        'id_horario',
        'id_de',
        'fecha_inicio',
        'fecha_fin',
        'activo'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean',
    ];

    public function horario()
    {
        return $this->belongsTo(horarios::class, 'id_horario');
    }

    public function deptoEdificio()
    {
        return $this->belongsTo(depto_edificio::class, 'id_de');
    }
}
