<?php
namespace App\Services\Checador;
use App\Models\empleados;
use App\Models\asistencias;
use App\Models\asistencias_procesadas;
use App\Models\horario_detalle;
use Carbon\Carbon;

class ChecadorService
{
    public function evaluar(empleados $empleado, horario_detalle $detalle): array
    {
        $registrosHoy = asistencias::where('id_empleado', $empleado->id_empleado)
            ->whereDate('fecha', now()->toDateString())
            ->orderBy('hora')
            ->get();

        if ($registrosHoy->contains('tipo_registro', 'salida')) {
            throw new \Exception('El día ya fue cerrado.');
        }

        $ultimo = $registrosHoy->last();
        $horaActual = Carbon::now()->format('H:i:s');

        // Primer registro
        if (!$ultimo) {
            return [
                'accion' => 'entrada',
                'requiere_confirmacion' => false
            ];
        }

        // Dentro del horario de comida
        if (
            $detalle->comida_inicio &&
            $detalle->comida_fin &&
            $horaActual >= $detalle->comida_inicio &&
            $horaActual < $detalle->comida_fin
        ) {
            $yaSalioComida = $registrosHoy->contains('tipo_registro', 'salida_comida');

            if (!$yaSalioComida) {
                return [
                    'accion' => 'salida_comida',
                    'requiere_confirmacion' => false
                ];
            }

            return [
                'accion' => 'entrada_comida',
                'requiere_confirmacion' => false
            ];
        }

        // Después de comida sin regreso registrado
        $tieneRegreso = $registrosHoy->contains('tipo_registro', 'entrada_comida');

        if (!$tieneRegreso) {
            return [
                'accion' => 'ambigua',
                'requiere_confirmacion' => true,
                'mensaje' => '¿Estás regresando de comida o deseas registrar una salida?',
                'opciones' => ['entrada_comida', 'salida']
            ];
        }

        // Caso normal: salida
        return [
            'accion' => 'salida',
            'requiere_confirmacion' => false
        ];
    }

    public function guardar(empleados $empleado, string $tipo, horario_detalle $detalle)
    {
        asistencias::create([
            'id_empleado' => $empleado->id_empleado,
            'fecha' => now()->toDateString(),
            'hora' => now()->toTimeString(),
            'tipo_registro' => $tipo,
            'origen' => 'biometrico',
            'archivado' => false,
        ]);

        if ($tipo === 'salida') {
            $this->procesarAsistencia($empleado, $detalle);
        }
    }

    protected function procesarAsistencia(empleados $empleado, horario_detalle $detalle): void
    {
        $registros = asistencias::where('id_empleado', $empleado->id_empleado)
            ->whereDate('fecha', now()->toDateString())
            ->orderBy('hora')
            ->get();

        $entrada = $registros->firstWhere('tipo_registro', 'entrada');
        $salida = $registros
            ->where('tipo_registro', 'salida')
            ->last();


        if (!$entrada || !$salida) {
            return; // inconsistencia, luego se audita
        }

        asistencias_procesadas::updateOrCreate(
            [
                'id_empleado' => $empleado->id_empleado,
                'fecha' => now()->toDateString(),
            ],
            [
                'hora_entrada' => $entrada->hora,
                'hora_salida' => $salida->hora,
                'estado' => 'completo',
                'cerrado' => true,
                'inconsistencia' => false,
            ]
        );
    }
}