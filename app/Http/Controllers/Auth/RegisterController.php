<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\usuarios;
use App\Models\empleados;
use App\Mail\ConfirmarCuentaMail;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function registrar(Request $request)
    {
        $request->validate([
            'id_empleado' => 'required|numeric|unique:usuarios,id_empleado',
            'usuario' => 'required|string|unique:usuarios,usuario',
            'password' => 'required|string|min:6'
        ]);

        // Verificar empleado
        $empleado = empleados::where('id_empleado', $request->id_empleado)->first();

        if (!$empleado) {
            return back()->with('error', 'El empleado no existe en registros.');
        }

        // Crear usuario inactivo
        $usuario = usuarios::create([
            'id_empleado' => $empleado->id_empleado,
            'usuario' => $request->usuario,
            'password' => Hash::make($request->password),
            'id_rol' => null,
            'activo' => 0
        ]);

        // Crear token
        $token = Str::random(60);

        DB::table('usuario_confirmaciones')->insert([
            'id_usuario' => $usuario->id_usuario,
            'token' => $token,
            'expira_en' => Carbon::now()->addDay(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Enviar correo
        Mail::to($empleado->correo)->send(new ConfirmarCuentaMail($token));

        return back()->with('success', 'Registro creado. Revisa tu correo para confirmar tu cuenta.');
    }

    public function confirmar($token)
    {
        $registro = DB::table('usuario_confirmaciones')
            ->where('token', $token)
            ->where('confirmado', 0)
            ->first();

        if (!$registro) {
            return redirect('/login')->with('error', 'Token inválido o ya usado.');
        }

        if ($registro->expira_en && Carbon::now()->gt($registro->expira_en)) {
            return redirect('/login')->with('error', 'El enlace expiró, solicita uno nuevo.');
        }

        usuarios::where('id_usuario', $registro->id_usuario)
            ->update(['activo' => 1]);

        DB::table('usuario_confirmaciones')
            ->where('id_confirmacion', $registro->id_confirmacion)
            ->update(['confirmado' => 1]);

        return redirect('/login')->with('success', 'Cuenta confirmada. Ya puedes iniciar sesión.');
    }
}
