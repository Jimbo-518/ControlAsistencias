<?php
namespace App\Http\Controllers;
use App\Models\horario_detalle;
use Illuminate\Http\Request;

class HorarioDetalleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_horario' => 'required|exists:horarios,id_horario',
            'dia' => 'required|integer|min:1|max:7',
            'entrada' => 'required',
            'tolerancia' => 'required|integer|min:0',
            'salida' => 'required',
            'comida_inicio' => 'nullable',
            'comida_fin' => 'nullable',
        ]);

        // Evitar días duplicados
        $existe = horario_detalle::where('id_horario', $request->id_horario)
            ->where('dia', $request->dia)
            ->exists();

        if ($existe) {
            return back()->withErrors([
                'dia' => 'Este día ya está configurado para el horario.'
            ]);
        }

        horario_detalle::create($request->all());

        return back()->with('success', 'Día agregado correctamente al horario.');
    }
}