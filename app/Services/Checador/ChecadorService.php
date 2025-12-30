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

        // Día cerrado
        if ($registrosHoy->contains('tipo_registro', 'salida')) {
            throw new \Exception('El día ya fue cerrado.');
        }

        // Primer registro del día
        if ($registrosHoy->isEmpty()) {
            return [
                'accion' => 'entrada',
                'requiere_confirmacion' => false
            ];
        }

        $horaActual = Carbon::now();

        // SALIDA (TIENE PRIORIDAD ABSOLUTA)
        if (!empty($detalle->hora_salida)) {

            $horaSalida = Carbon::today()
                ->setTimeFromTimeString($detalle->hora_salida);

            $minutosAntesSalida = $horaActual->diffInMinutes($horaSalida, false);

            // Automático: últimos 10 min o hasta 60 min después
            if ($minutosAntesSalida <= 10 && $minutosAntesSalida >= -60) {
                return [
                    'accion' => 'salida',
                    'requiere_confirmacion' => false
                ];
            }

            // Preguntar: entre 11 y 30 minutos antes
            if ($minutosAntesSalida <= 30 && $minutosAntesSalida > 10) {
                return [
                    'accion' => 'ambigua',
                    'requiere_confirmacion' => true,
                    'mensaje' => 'Tu horario de salida está próximo. ¿Deseas registrar tu salida ahora?',
                    'opciones' => ['salida']
                ];
            }
        }

        // COMIDA
        $inicioComida = $detalle->comida_inicio
            ? Carbon::today()->setTimeFromTimeString($detalle->comida_inicio)
            : null;

        $finComida = $detalle->comida_fin
            ? Carbon::today()->setTimeFromTimeString($detalle->comida_fin)
            : null;

        $enComida = $inicioComida && $finComida
            ? $horaActual->between($inicioComida, $finComida, false)
            : false;

        $yaSalioComida = $registrosHoy->contains('tipo_registro', 'salida_comida');
        $yaRegresoComida = $registrosHoy->contains('tipo_registro', 'entrada_comida');

        // Dentro de horario de comida
        if ($enComida) {

            if (!$yaSalioComida) {
                return [
                    'accion' => 'salida_comida',
                    'requiere_confirmacion' => false
                ];
            }

            if (!$yaRegresoComida) {
                return [
                    'accion' => 'entrada_comida',
                    'requiere_confirmacion' => false
                ];
            }

            return [
                'accion' => 'ambigua',
                'requiere_confirmacion' => true,
                'mensaje' => 'Ya regresaste de comida. ¿Deseas registrar una salida?',
                'opciones' => ['salida']
            ];
        }

        //FUERA DE COMIDA Y SIN SALIDA PRÓXIMA
        return [
            'accion' => 'ambigua',
            'requiere_confirmacion' => true,
            'mensaje' => '¿Deseas registrar una salida?',
            'opciones' => ['salida']
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
        $fecha = now()->toDateString();

        $registros = asistencias::where('id_empleado', $empleado->id_empleado)
            ->whereDate('fecha', $fecha)
            ->orderBy('hora')
            ->get();

        $entrada = $registros->firstWhere('tipo_registro', 'entrada');
        $salida = $registros->where('tipo_registro', 'salida')->last();
        $salidaComida = $registros->firstWhere('tipo_registro', 'salida_comida');
        $entradaComida = $registros->firstWhere('tipo_registro', 'entrada_comida');

        $inconsistencias = [];
        $minutosFaltantes = 0;
        $minutosExtra = 0;

        /* ===============================
         * VALIDACIONES BÁSICAS
         * =============================== */
        if (!$entrada)
            $inconsistencias[] = 'No registró entrada';
        if (!$salida)
            $inconsistencias[] = 'No registró salida';

        if (!$entrada || !$salida) {
            asistencias_procesadas::updateOrCreate(
                ['id_empleado' => $empleado->id_empleado, 'fecha' => $fecha],
                [
                    'estado' => 'incompleto',
                    'cerrado' => true,
                    'inconsistencia' => true,
                    'motivo_inconsistencia' => implode(' | ', $inconsistencias)
                ]
            );
            return;
        }

        /* ===============================
         * VALIDAR HORARIO
         * =============================== */
        if (!$detalle->hora_entrada || !$detalle->hora_salida) {
            $inconsistencias[] = 'Horario no configurado';
        } else {

            $horaEntradaHorario = Carbon::today()->setTimeFromTimeString($detalle->hora_entrada);
            $horaSalidaHorario = Carbon::today()->setTimeFromTimeString($detalle->hora_salida);

            $horaEntradaReal = Carbon::today()->setTimeFromTimeString($entrada->hora);
            $horaSalidaReal = Carbon::today()->setTimeFromTimeString($salida->hora);

            /* ===============================
             * RETARDO
             * =============================== */
            if ($horaEntradaReal->greaterThan($horaEntradaHorario)) {
                $retardo = $horaEntradaHorario->diffInMinutes($horaEntradaReal);
                $minutosFaltantes += $retardo;
                $inconsistencias[] = "Entró {$retardo} min tarde";
            }

            /* ===============================
             * SALIDA ANTICIPADA
             * =============================== */
            if ($horaSalidaReal->lessThan($horaSalidaHorario)) {
                $faltante = $horaSalidaReal->diffInMinutes($horaSalidaHorario);
                $minutosFaltantes += $faltante;
                $inconsistencias[] = "Salió {$faltante} min antes";
            }

            /* ===============================
             * MINUTOS EXTRA
             * =============================== */
            if ($horaSalidaReal->greaterThan($horaSalidaHorario)) {
                $minutosExtra = $horaSalidaHorario->diffInMinutes($horaSalidaReal);
            }
        }

        /* ===============================
         * TIEMPO DE COMIDA
         * =============================== */
        if ($detalle->comida_inicio && $detalle->comida_fin) {

            if (!$salidaComida)
                $inconsistencias[] = 'No registró salida a comida';

            if (!$entradaComida)
                $inconsistencias[] = 'No registró regreso de comida';

            if ($salidaComida && $entradaComida) {

                $comidaPermitida = Carbon::today()
                    ->setTimeFromTimeString($detalle->comida_inicio)
                    ->diffInMinutes(
                        Carbon::today()->setTimeFromTimeString($detalle->comida_fin)
                    );

                $comidaReal = Carbon::today()
                    ->setTimeFromTimeString($salidaComida->hora)
                    ->diffInMinutes(
                        Carbon::today()->setTimeFromTimeString($entradaComida->hora)
                    );

                if ($comidaReal > $comidaPermitida) {
                    $exceso = $comidaReal - $comidaPermitida;
                    $minutosFaltantes += $exceso;
                    $inconsistencias[] = "Excedió {$exceso} min de comida";
                }
            }
        }

        /* ===============================
         * GUARDAR RESULTADO
         * =============================== */
        asistencias_procesadas::updateOrCreate(
            ['id_empleado' => $empleado->id_empleado, 'fecha' => $fecha],
            [
                'hora_entrada' => $entrada->hora,
                'hora_salida' => $salida->hora,
                'minutos_faltantes' => $minutosFaltantes,
                'minutos_extra' => $minutosExtra,
                'estado' => empty($inconsistencias) ? 'completo' : 'completo_con_observaciones',
                'cerrado' => true,
                'inconsistencia' => !empty($inconsistencias),
                'motivo_inconsistencia' => implode(' | ', $inconsistencias),
            ]
        );
    }
}