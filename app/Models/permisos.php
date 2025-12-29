<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class permisos extends Model
{
    protected $table = 'permisos';
    protected $primaryKey = 'id_permiso';

    protected $fillable = [
        'clave'
    ];

    public function roles()
    {
        return $this->belongsToMany(
            Rol::class,
            'rol_permiso',
            'id_permiso',
            'id_rol'
        );
    }
}
