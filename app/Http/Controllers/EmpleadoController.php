<?php

namespace App\Http\Controllers;
use App\Models\empleados;
use App\Models\depto_edificio;
use Illuminate\Http\Request;
use App\Models\roles;

class EmpleadoController extends Controller
{
    public function create()
    {
        // Para el select edificio + departamento
        $deptoEdificios = depto_edificio::with(['edificio', 'departamento'])
            ->where('activo', true)
            ->get();

        return view('empleados.create', compact('deptoEdificios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'required|string|max:100',
            'correo' => 'required|email|unique:empleados,correo',
            'telefono' => 'nullable|string|max:20',
            'fecha_ingreso' => 'required|date',
            'id_depto_edificio' => 'required|exists:depto_edificio,id_de',
        ]);

        $empleado = empleados::create([
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'fecha_ingreso' => $request->fecha_ingreso,
            'id_depto_edificio' => $request->id_depto_edificio,
            'estatus' => 'activo',
            'vacaciones_tomadas' => 0,
        ]);

        return redirect()
            ->route('empleados.face.create', $empleado->id_empleado)
            ->with('success', 'Empleado registrado. Continúa con el registro facial.');

    }

    public function index()
    {
        $empleados = empleados::select(
            'id_empleado',
            'nombre',
            'apellido_paterno',
            'apellido_materno',
            'estatus'
        )
            ->whereIn('estatus', ['activo', 'inactivo'])
            ->orderBy('nombre')
            ->get();

        return view('empleados.index', compact('empleados'));
    }

    public function edit($id)
    {
        $empleado = empleados::with('usuario')->findOrFail($id);
        $roles = roles::all(); // Solo admin verá esto

        return view('empleados.edit', compact('empleado', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $empleado = empleados::with('usuario')->findOrFail($id);

        // Validación
        $request->validate([
            'nombre' => 'required|string',
            'apellido_paterno' => 'required|string',
            'apellido_materno' => 'nullable|string',
            'correo' => 'required|email',
            'telefono' => 'nullable|string',
            'estatus' => 'required|in:activo,inactivo',

            'id_rol' => 'nullable|exists:roles,id_rol',
        ]);

        // 🔹 Actualizar EMPLEADO
        $empleado->update([
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'estatus' => $request->estatus,
        ]);

        // 🔹 Actualizar USUARIO
        if ($empleado->usuario && $request->filled('id_rol')) {
            $usuarioData = ['id_rol' => $request->id_rol,];

            $empleado->usuario->update($usuarioData);
        }

        return redirect()
            ->route('empleados.index')
            ->with('success', 'Empleado actualizado correctamente.');
    }

    public function baja($id)
    {
        $empleado = empleados::findOrFail($id);
        $empleado->update(['estatus' => 'archivado']);

        return back()->with('success', 'Empleado dado de baja.');
    }
}
