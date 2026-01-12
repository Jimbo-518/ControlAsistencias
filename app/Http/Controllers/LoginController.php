<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usuarios;
use Illuminate\Support\Facades\Hash;

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
            'password' => 'required|string'
        ]);

        // Buscar usuario
        $usuario = usuarios::where('usuario', $request->usuario)->first();

        // Verificar Usuario y Contraseña
        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()->with('error', 'Usuario o contraseña incorrectos.');
        }

        // Verificar activación
        if ($usuario->activo == 0) {
            return back()->with('error', 'Tu cuenta aún no está confirmada. Revisa tu correo.');
        }

        // Guardar sesión
        session([
            'usuario_id' => $usuario->id_usuario,
            'id_empleado' => $usuario->id_empleado,
            'usuario' => $usuario->usuario,
            'rol' => $usuario->id_rol
        ]);

        // Actualizar último acceso
        $usuario->ultimo_acceso = now();
        $usuario->save();

        // Redirigir
        return redirect()->route('home');
    }
}
