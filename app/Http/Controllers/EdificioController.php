<?php
namespace App\Http\Controllers;
use App\Models\edificios;
use App\Models\departamentos;
use Illuminate\Http\Request;

class EdificioController extends Controller
{
    public function index()
    {
        $edificios = edificios::orderBy('created_at', 'desc')->get();

        return view('edificios.index', compact('edificios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        edificios::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);

        return redirect()
            ->route('edificios.index')
            ->with('success', 'Edificio registrado correctamente.');
    }

    public function show($id)
    {
        $edificio = edificios::with('departamentos')->findOrFail($id);

        $departamentosDisponibles = departamentos::where('activo', 1)
            ->whereNotIn('id_departamento', $edificio->departamentos->pluck('id_departamento'))
            ->get();

        return view('edificios.show', compact(
            'edificio',
            'departamentosDisponibles'
        ));
    }

    public function assignDepartamento(Request $request, $id)
    {
        $request->validate([
            'id_departamento' => 'required|exists:departamentos,id_departamento',
        ]);

        $edificio = edificios::findOrFail($id);
        $edificio->departamentos()->attach($request->id_departamento);

        return redirect()
            ->route('edificios.show', $id)
            ->with('success', 'Departamento asignado correctamente.');
    }
}
