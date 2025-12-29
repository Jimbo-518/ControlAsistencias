<?php
namespace App\Http\Controllers;
use App\Models\horarios;
use App\Models\depto_edificio;
use App\Models\depto_edificio_horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function index()
    {
        $horarios = horarios::orderBy('created_at', 'desc')->get();

        return view('horarios.index', compact('horarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'required|boolean',
        ]);

        horarios::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => $request->activo,
        ]);

        return redirect()
            ->route('horarios.index')
            ->with('success', 'Horario registrado correctamente.');
    }

    public function show(horarios $horario)
    {
        $horario->load([
            'detalles',
            'asignaciones.deptoEdificio.departamento',
            'asignaciones.deptoEdificio.edificio',
        ]);

        $deptoEdificios = depto_edificio::with(['departamento', 'edificio'])
            ->where('activo', 1)
            ->get();

        return view('horarios.show', compact('horario', 'deptoEdificios'));
    }

    public function asignarDepto(Request $request)
    {
        $request->validate([
            'id_horario' => 'required|exists:horarios,id_horario',
            'id_de' => 'required|exists:depto_edificio,id_de',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'activo' => 'required|boolean',
        ]);

        depto_edificio_horario::create([
            'id_horario' => $request->id_horario,
            'id_de' => $request->id_de,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'activo' => $request->activo,
        ]);

        return back()->with('success', 'Horario asignado correctamente.');
    }
}