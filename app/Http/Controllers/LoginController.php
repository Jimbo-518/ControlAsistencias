<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usuarios;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        // Buscar usuario
        $usuario = usuarios::where('usuario', $request->usuario)->first();

        if (!$usuario || $usuario->activo == 0) {
            return back()->with('error', 'Usuario o contraseña incorrectos.');
        }

        // Intentar login con Auth
        if (
            !Auth::attempt([
                'usuario' => $request->usuario,
                'password' => $request->password
            ])
        ) {
            return back()->with('error', 'Usuario o contraseña incorrectos.');
        }

        // Regenera sesión
        $request->session()->regenerate();

        // Foto perfil
        $idEmpleado = auth()->user()->id_empleado;

        $rutaFoto = storage_path("app/public/perfiles/{$idEmpleado}.jpg");

        $fotoPerfil = file_exists($rutaFoto)
            ? asset("storage/perfiles/{$idEmpleado}.jpg")
            : asset("storage/perfiles/default.jpg");

        session([
            'foto_perfil' => $fotoPerfil,
            'rol' => auth()->user()->id_rol
        ]);

        // Último acceso
        auth()->user()->update([
            'ultimo_acceso' => now()
        ]);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.index');
    }
}
