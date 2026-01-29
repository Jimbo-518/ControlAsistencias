<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\empleados;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function index()
    {
        $userIdEmpleado = auth()->user()->id_empleado;

        $empleado = empleados::with(['usuario.rol'])
            ->where('id_empleado', $userIdEmpleado)
            ->first();

        return view('empleados.profile', compact('empleado'));
    }

    public function actualizarFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $idEmpleado = auth()->user()->id_empleado;

        if (!$idEmpleado) {
            return back()->with('error', 'Sesión inválida.');
        }

        $ruta = "perfiles/{$idEmpleado}.jpg";

        // Eliminar foto anterior si existe
        if (Storage::disk('public')->exists($ruta)) {
            Storage::disk('public')->delete($ruta);
        }

        // Guardar nueva foto con nombre fijo
        $request->file('foto')->storeAs(
            'perfiles',
            "{$idEmpleado}.jpg",
            'public'
        );

        // Actualizar sesión para menú y perfil
        session([
            'foto_perfil' => asset("storage/perfiles/{$idEmpleado}.jpg")
        ]);

        return back()->with('success', 'Foto de perfil actualizada.');
    }
}
