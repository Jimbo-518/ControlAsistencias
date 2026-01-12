<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class usuarios extends Model
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'id_empleado',
        'usuario',
        'password',
        'id_rol',
        'activo',
        'ultimo_acceso'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'ultimo_acceso' => 'datetime'
    ];

    public function empleado()
    {
        return $this->belongsTo(empleados::class, 'id_empleado');
    }

    public function rol()
    {
        return $this->belongsTo(roles::class, 'id_rol');
    }

    public function auditorias()
    {
        return $this->hasMany(auditoria::class, 'id_usuario');
    }

    public function tienePermiso(string $clave): bool
    {
        return $this->rol
            ->permisos
            ->contains('clave', $clave);
    }
}
