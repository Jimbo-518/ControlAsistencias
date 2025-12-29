<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class depto_edificio extends Model
{
    protected $table = 'depto_edificio';
    protected $primaryKey = 'id_de';

    protected $fillable = [
        'id_edificio',
        'id_departamento',
        'activo'
    ];

    public function edificio()
    {
        return $this->belongsTo(edificios::class, 'id_edificio');
    }

    public function departamento()
    {
        return $this->belongsTo(departamentos::class, 'id_departamento');
    }
}