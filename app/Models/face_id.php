<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class face_id extends Model
{
    protected $table = 'face_ids';
    protected $primaryKey = 'id_face';

    protected $fillable = [
        'id_empleado',
        'embedding',
        'motor',
        'version_modelo',
        'activo',
        'fecha_registro',
    ];

    protected $casts = [
        'embedding' => 'array',
        'activo' => 'boolean',
        'fecha_registro' => 'datetime',
    ];

    public function empleado()
    {
        return $this->belongsTo(empleados::class, 'id_empleado');
    }
}