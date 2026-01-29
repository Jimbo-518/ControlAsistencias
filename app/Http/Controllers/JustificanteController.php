<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\empleados;
use App\Models\tipo_incidencia;
use App\Models\incidencias;
use App\Models\depto_edificio_horario;
use App\Models\horario_detalle;
use Carbon\Carbon;

class JustificanteController extends Controller
{
    public function create()
    {
        $empleados = empleados::whereIn('estatus', ['activo', 'inactivo'])
            ->orderBy('nombre')
            ->get();

        $tipos_incidencia = tipo_incidencia::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('justificantes.subir', compact('empleados', 'tipos_incidencia'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tipo_incidencia' => 'required|exists:tipo_incidencia,id_tipo',
            'id_empleado' => 'nullable|exists:empleados,id_empleado',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string|max:500',
            'evidencia' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Subir archivo si existe
        $path = null;
        if ($request->hasFile('evidencia')) {
            $path = $request->file('evidencia')->store('justificantes', 'public');
        }

        // Crear incidencia con estatus pendiente
        incidencias::create([
            'id_empleado' => $request->id_empleado,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'descripcion' => $request->descripcion,
            'id_tipo_incidencia' => $request->id_tipo_incidencia,
            'auditor' => null,
            'evidencia_url' => $path,
            'estatus' => 'pendiente',
            'fecha_registro' => now(),
            'archivada' => false,
        ]);

        return redirect()->route('justificantes.create')
            ->with('success', 'Justificante registrado correctamente. Pendiente de revisión.');
    }

    public function index()
    {
        $justificantes = incidencias::with(['empleado', 'tipo'])
            ->where('archivada', false)
            ->orderByRaw("FIELD(estatus, 'pendiente', 'aplicada', 'rechazada')")
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        return view('justificantes.index', compact('justificantes'));
    }

    private function esVacacion($tipoIncidencia)
    {
        return strtolower($tipoIncidencia->nombre) === 'vacaciones';
    }

    private function contarDiasHabiles($id_empleado, $fechaInicio, $fechaFin)
    {
        $empleado = empleados::findOrFail($id_empleado);

        if (!$empleado->id_depto_edificio) {
            // Si no tiene departamento asignado, contar lunes a viernes por defecto
            return $this->contarDiasHabilesDefault($fechaInicio, $fechaFin);
        }

        // Obtener horario activo del departamento/edificio
        $horarioActivo = depto_edificio_horario::where('id_de', $empleado->id_depto_edificio)
            ->where('activo', true)
            ->whereDate('fecha_inicio', '<=', now())
            ->where(function ($q) {
                $q->whereNull('fecha_fin')
                    ->orWhereDate('fecha_fin', '>=', now());
            })
            ->first();

        if (!$horarioActivo) {
            // Si no tiene horario activo, contar lunes a viernes por defecto
            return $this->contarDiasHabilesDefault($fechaInicio, $fechaFin);
        }

        // Obtener días laborales del horario
        $diasLaborales = horario_detalle::where('id_horario', $horarioActivo->id_horario)
            ->whereNotNull('entrada')
            ->whereNotNull('salida')
            ->pluck('dia')
            ->toArray();

        // Contar días hábiles según el horario
        $dias = 0;
        $fecha = $fechaInicio->copy();

        while ($fecha->lte($fechaFin)) {
            // dayOfWeekIso: 1=lunes, 7=domingo
            $diaISO = $fecha->dayOfWeekIso;

            if (in_array($diaISO, $diasLaborales)) {
                $dias++;
            }

            $fecha->addDay();
        }

        return $dias;
    }

    private function contarDiasHabilesDefault($fechaInicio, $fechaFin)
    {
        $dias = 0;
        $fecha = $fechaInicio->copy();

        while ($fecha->lte($fechaFin)) {
            // dayOfWeek: 0=domingo, 6=sábado
            if ($fecha->dayOfWeek !== 0 && $fecha->dayOfWeek !== 6) {
                $dias++;
            }
            $fecha->addDay();
        }

        return $dias;
    }

    public function revisar(Request $request, $id)
    {
        $request->validate([
            'estatus' => 'required|in:aplicada,rechazada',
            'comentario' => 'nullable|string|max:500',
        ]);

        $incidencia = incidencias::with(['empleado', 'tipo'])->findOrFail($id);

        // Validar que esté pendiente
        if ($incidencia->estatus !== 'pendiente') {
            return back()->with('error', 'Este justificante ya fue revisado.');
        }

        // Si se aprueba y es vacación, validar y actualizar
        if ($request->estatus === 'aplicada') {
            $tipoIncidencia = $incidencia->tipo;

            if ($this->esVacacion($tipoIncidencia) && $incidencia->id_empleado) {
                $empleado = empleados::findOrFail($incidencia->id_empleado);

                // Calcular días solicitados
                $diasSolicitados = $this->contarDiasHabiles(
                    $incidencia->id_empleado,
                    Carbon::parse($incidencia->fecha_inicio),
                    $incidencia->fecha_fin ? Carbon::parse($incidencia->fecha_fin) : Carbon::parse($incidencia->fecha_inicio)
                );

                // Calcular vacaciones disponibles
                $antiguedad = Carbon::parse($empleado->fecha_ingreso)->diffInYears(now());
                $vacacionesDisponibles = $this->calcularVacacionesPorAntiguedad($antiguedad);
                $vacacionesRestantes = $vacacionesDisponibles - $empleado->vacaciones_tomadas;

                // Validar disponibilidad
                if ($diasSolicitados > $vacacionesRestantes) {
                    return back()->with(
                        'error',
                        "El empleado solo tiene {$vacacionesRestantes} días disponibles. Solicitó {$diasSolicitados} días."
                    );
                }

                // Actualizar contador de vacaciones
                $empleado->vacaciones_tomadas += $diasSolicitados;
                $empleado->save();
            }
        }

        // Actualizar incidencia
        $incidencia->estatus = $request->estatus;

        // Guardar ID del usuario autenticador
        $incidencia->auditor = auth()->id();

        if ($request->comentario) {
            $incidencia->descripcion .= "\n\n[Auditor] " . $request->comentario;
        }

        $incidencia->save();

        $mensaje = $request->estatus === 'aplicada'
            ? 'Justificante aprobado y aplicado correctamente.'
            : 'Justificante rechazado.';

        return redirect()->route('justificantes.index')->with('success', $mensaje);
    }

    /**
     * Calcular vacaciones según antigüedad (Ley Federal del Trabajo - México)
     */
    private function calcularVacacionesPorAntiguedad($anios)
    {
        if ($anios < 1) {
            return 12;
        } elseif ($anios < 2) {
            return 14;
        } elseif ($anios < 3) {
            return 16;
        } elseif ($anios < 4) {
            return 18;
        } elseif ($anios < 5) {
            return 20;
        }
        // A partir del 5to año: 2 días por cada año adicional
        return 20 + (($anios - 5) * 2);
    }
}
