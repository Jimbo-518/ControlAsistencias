<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class auditoria extends Model
{
    protected $table = 'auditoria';
    protected $primaryKey = 'id_auditoria';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'accion',
        'tabla',
        'id_registro',
        'descripcion',
        'fecha',
        'ip'
    ];

    protected $casts = [
        'fecha' => 'datetime'
    ];

    public function usuario()
    {
        return $this->belongsTo(usuarios::class, 'id_usuario');
    }
}
