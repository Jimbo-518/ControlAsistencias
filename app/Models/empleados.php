<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class empleados extends Model
{
    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado';

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'correo',
        'telefono',
        'fecha_ingreso',
        'id_depto_edificio',
        'estatus',
        'vacaciones_tomadas'
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'vacaciones_tomadas' => 'integer',
    ];

    public function deptoEdificio()
    {
        return $this->belongsTo(depto_edificio::class, 'id_depto_edificio');
    }

    public function faceIds()
    {
        return $this->hasMany(face_id::class, 'id_empleado');
    }

    public function usuario()
    {
        return $this->hasOne(usuarios::class, 'id_empleado', 'id_empleado');
    }
}
