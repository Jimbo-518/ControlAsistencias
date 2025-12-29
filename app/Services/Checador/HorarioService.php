<?php
namespace App\Services\Checador;
use App\Models\empleados;
use App\Models\depto_edificio_horario;
use App\Models\horario_detalle;

class HorarioService
{
    public function obtenerHorarioDelDia(empleados $empleado): horario_detalle
    {
        $deh = depto_edificio_horario::where('id_de', $empleado->id_depto_edificio)
            ->where('activo', true)
            ->whereDate('fecha_inicio', '<=', now())
            ->where(function ($q) {
                $q->whereNull('fecha_fin')
                  ->orWhereDate('fecha_fin', '>=', now());
            })
            ->first();

        if (!$deh) {
            throw new \Exception('No hay horario activo asignado');
        }

        $dia = now()->dayOfWeekIso; // 1=lunes ... 7=domingo

        $detalle = horario_detalle::where('id_horario', $deh->id_horario)
            ->where('dia', $dia)
            ->first();

        if (!$detalle) {
            throw new \Exception('No hay horario configurado para hoy');
        }

        return $detalle;
    }
}
