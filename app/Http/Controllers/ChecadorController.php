<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\FaceRecognitionService;
use App\Models\empleados;
use App\Services\Checador\ChecadorService;
use App\Services\Checador\HorarioService;

class ChecadorController extends Controller
{
    public function index()
    {
        return view('/checador');
    }

    public function identificar(Request $request, FaceRecognitionService $faceService)
    {
        $request->validate([
            'descriptor' => 'required|array',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        $resultado = $faceService->identificar($request->descriptor);

        if (!$resultado) {
            return response()->json([
                'ok' => false,
                'message' => 'Rostro no reconocido'
            ], 200);
        }

        $empleado = empleados::find($resultado['face']->id_empleado);

        if (!$empleado || $empleado->estatus !== 'activo') {
            return response()->json([
                'ok' => false,
                'message' => 'Empleado inactivo'
            ], 403);
        }

        return response()->json([
            'ok' => true,
            'empleado' => [
                'id_empleado' => $empleado->id_empleado,
                'nombre_completo' => trim(
                    $empleado->nombre . ' ' .
                    $empleado->apellido_paterno . ' ' .
                    $empleado->apellido_materno
                ),
                'estatus' => $empleado->estatus
            ],
            'score' => $resultado['distance']
        ]);
    }

    public function registrar(Request $request, HorarioService $horarioService, ChecadorService $checadorService)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id_empleado',
            'accion_confirmada' => 'nullable|in:entrada,entrada_comida,salida_comida,salida'
        ]);

        $empleado = empleados::findOrFail($request->empleado_id);

        if ($empleado->estatus !== 'activo') {
            return response()->json([
                'ok' => false,
                'message' => 'Empleado inactivo'
            ], 403);
        }

        try {
            $detalle = $horarioService->obtenerHorarioDelDia($empleado);

            // Si el frontend ya confirmó una acción, NO se vuelve a evaluar
            if ($request->filled('accion_confirmada')) {

                $checadorService->guardar(
                    $empleado,
                    $request->accion_confirmada,
                    $detalle
                );

                return response()->json([
                    'ok' => true,
                    'tipo' => $request->accion_confirmada,
                    'message' => "Registro guardado: {$request->accion_confirmada}"
                ]);
            }

            // Si NO hay confirmación, se evalúa normalmente
            $decision = $checadorService->evaluar($empleado, $detalle);

            if ($decision['requiere_confirmacion']) {
                return response()->json([
                    'ok' => true,
                    'confirmacion' => true,
                    'data' => $decision
                ]);
            }

            $checadorService->guardar(
                $empleado,
                $decision['accion'],
                $detalle
            );

            return response()->json([
                'ok' => true,
                'tipo' => $decision['accion'],
                'message' => "Registro guardado: {$decision['accion']}"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

}
