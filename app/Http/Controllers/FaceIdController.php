<?php

namespace App\Http\Controllers;

use App\Models\face_id;
;
use App\Models\empleados;
use Illuminate\Http\Request;

class FaceIdController extends Controller
{
    public function create(empleados $empleado)
    {
        return view('empleados.face', compact('empleado'));
    }

    public function store(Request $request, empleados $empleado)
    {
        $request->validate([
            'descriptors' => 'required|array|min:3',
        ]);

        face_id::updateOrCreate(
            ['id_empleado' => $empleado->id_empleado],
            [
                'embedding' => $request->descriptors,
                'motor' => 'face-api.js',
                'version_modelo' => 'tiny_face_detector_v1',
                'activo' => true,
                'fecha_registro' => now(),
            ]
        );

        return response()->json([
            'ok' => true,
            'message' => 'Datos faciales registrados correctamente',
            'redirect' => route('empleados.index')
        ]);
    }
}