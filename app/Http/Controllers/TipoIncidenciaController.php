<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\tipo_incidencia;

class TipoIncidenciaController extends Controller
{
    public function index()
    {
        $tipos = tipo_incidencia::orderBy('nombre')->get();
        return view('tipos-incidencia.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipos-incidencia.crear');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:tipo_incidencia,nombre',
            'descripcion' => 'nullable|string|max:500',
            'alcance' => 'required|in:dia_completo,parcial',
            'genera_falta' => 'boolean',
            'elimina_falta' => 'boolean',
            'permite_asistencia' => 'boolean',
            'modifica_horario' => 'boolean',
            'hora_entrada' => 'nullable|date_format:H:i',
            'hora_salida' => 'nullable|date_format:H:i',
        ]);

        tipo_incidencia::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'alcance' => $request->alcance,
            'genera_falta' => $request->has('genera_falta'),
            'elimina_falta' => $request->has('elimina_falta'),
            'permite_asistencia' => $request->has('permite_asistencia'),
            'modifica_horario' => $request->has('modifica_horario'),
            'hora_entrada' => $request->hora_entrada,
            'hora_salida' => $request->hora_salida,
            'activo' => true,
        ]);

        return redirect()->route('tipos-incidencia.index')
            ->with('success', 'Tipo de incidencia creado correctamente.');
    }

    public function edit($id)
    {
        $tipo = tipo_incidencia::findOrFail($id);
        return view('tipos-incidencia.editar', compact('tipo'));
    }

    public function update(Request $request, $id)
    {
        $tipo = tipo_incidencia::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100|unique:tipo_incidencia,nombre,' . $id . ',id_tipo',
            'descripcion' => 'nullable|string|max:500',
            'alcance' => 'required|in:dia_completo,parcial',
            'genera_falta' => 'boolean',
            'elimina_falta' => 'boolean',
            'permite_asistencia' => 'boolean',
            'modifica_horario' => 'boolean',
            'hora_entrada' => 'nullable|date_format:H:i',
            'hora_salida' => 'nullable|date_format:H:i',
        ]);

        $tipo->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'alcance' => $request->alcance,
            'genera_falta' => $request->has('genera_falta'),
            'elimina_falta' => $request->has('elimina_falta'),
            'permite_asistencia' => $request->has('permite_asistencia'),
            'modifica_horario' => $request->has('modifica_horario'),
            'hora_entrada' => $request->hora_entrada,
            'hora_salida' => $request->hora_salida,
        ]);

        return redirect()->route('tipos-incidencia.index')
            ->with('success', 'Tipo de incidencia actualizado correctamente.');
    }

    public function toggleActivo($id)
    {
        $tipo = tipo_incidencia::findOrFail($id);
        $tipo->activo = !$tipo->activo;
        $tipo->save();

        $estado = $tipo->activo ? 'activado' : 'desactivado';
        return redirect()->route('tipos-incidencia.index')
            ->with('success', "Tipo de incidencia {$estado} correctamente.");
    }
}
