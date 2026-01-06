<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EdificioController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\HorarioDetalleController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\FaceIdController;
use App\Http\Controllers\ChecadorController;

Route::get('login', function () {
    return view('login');
})->name('login');

Route::get('/', function () {
    return view('index');
});

//Checador
Route::get('/checador', [ChecadorController::class, 'index'])
    ->name('checador.index');
Route::post('/checador/identificar', [ChecadorController::class, 'identificar'])
    ->name('checador.identificar');
Route::post('/checador/registrar', [ChecadorController::class, 'registrar'])
    ->name('checador.registrar');

//Edificios
Route::get('/edificios', [EdificioController::class, 'index'])
    ->name('edificios.index');
Route::post('/edificios', [EdificioController::class, 'store'])
    ->name('edificios.store');

Route::get('/edificios/{id}', [EdificioController::class, 'show'])
    ->name('edificios.show');
Route::post('/edificios/{id}/departamentos', [EdificioController::class, 'assignDepartamento'])
    ->name('edificios.departamentos.assign');

//Departamentos
Route::get('/departamentos', [DepartamentoController::class, 'index'])
    ->name('departamentos.index');
Route::post('/departamentos', [DepartamentoController::class, 'store'])
    ->name('departamentos.store');

//Horarios
Route::get('/horarios', [HorarioController::class, 'index'])
    ->name('horarios.index');
Route::post('/horarios', [HorarioController::class, 'store'])
    ->name('horarios.store');

Route::get('/horarios/{horario}', [HorarioController::class, 'show'])
    ->name('horarios.show');
Route::post('/horarios/asignar-departamento',[HorarioController::class, 'asignarDepto'])
    ->name('horarios.asignar');
Route::post('/horarios/detalle', [HorarioDetalleController::class, 'store'])
    ->name('horarios.detalle.store');

//Empleados
Route::get('/empleados/alta', [EmpleadoController::class, 'create'])
    ->name('empleados.create');
Route::post('/empleados', [EmpleadoController::class, 'store'])
    ->name('empleados.store');

Route::get('/empleados/{empleado}/face',[FaceIdController::class, 'create'])
    ->name('empleados.face.create');
Route::post('/empleados/{empleado}/face',[FaceIdController::class, 'store'])
    ->name('empleados.face.store');

Route::get('/empleados', [EmpleadoController::class, 'index'])
    ->name('empleados.index');
