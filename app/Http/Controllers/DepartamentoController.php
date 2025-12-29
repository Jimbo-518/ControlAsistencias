<?php
namespace App\Http\Controllers;
use App\Models\departamentos;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function index()
    {
        $departamentos = departamentos::orderBy('created_at', 'desc')->get();

        return view('departamentos.index', compact('departamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'activo' => 'nullable|boolean',
        ]);

        departamentos::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()
            ->route('departamentos.index')
            ->with('success', 'Departamento registrado correctamente.');
    }
}