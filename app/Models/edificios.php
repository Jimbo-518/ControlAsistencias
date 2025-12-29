<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class edificios extends Model
{

    use HasFactory;
    protected $table = 'edificios';
    protected $primaryKey = 'id_edificio';

    protected $fillable = [
        'nombre',
        'direccion',
        'lat',
        'lng',
    ];

    public function departamentos()
    {
        return $this->belongsToMany(
            departamentos::class,
            'depto_edificio',
            'id_edificio',
            'id_departamento'
        )->withPivot('activo');
    }

}