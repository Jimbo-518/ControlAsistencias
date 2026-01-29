<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EdificioController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\HorarioDetalleController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\FaceIdController;
use App\Http\Controllers\ChecadorController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\JustificanteController;
use App\Http\Controllers\TipoIncidenciaController;

Route::get('/', function () {return view('index');})
    ->name('home');

// Login
Route::get('/login', [LoginController::class, 'index'])
    ->name('login.index');
Route::post('/login', [LoginController::class, 'login'])
    ->name('login.validar');
Route::get('/logout', [LoginController::class, 'logout'])
    ->name('logout');

// Registrar y validar usuario
Route::post('/registrar', [RegisterController::class, 'registrar'])
    ->name('registrar');
Route::get('/confirmar-cuenta/{token}', [RegisterController::class, 'confirmar']);

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

Route::get('/empleados/{id}/editar', [EmpleadoController::class, 'edit'])
    ->name('empleados.edit');
Route::put('/empleados/{id}', [EmpleadoController::class, 'update'])
    ->name('empleados.update');
Route::patch('/empleados/{id}/baja', [EmpleadoController::class, 'baja'])
    ->name('empleados.baja');

//Perfil
Route::get('/mi-perfil', [PerfilController::class, 'index'])
    ->name('perfil.index');
Route::post('/perfil/foto', [PerfilController::class, 'actualizarFoto'])
    ->name('perfil.foto');

//Justificantes
Route::get('/justificantes/subir', [JustificanteController::class, 'create'])
    ->name('justificantes.create');
Route::post('/justificantes', [JustificanteController::class, 'store'])
    ->name('justificantes.store');

Route::get('/justificantes', [JustificanteController::class, 'index'])
    ->name('justificantes.index');
Route::patch('/justificantes/{id}/revisar', [JustificanteController::class, 'revisar'])
    ->name('justificantes.revisar');

//Tipos de Incidencia
Route::get('/tipos-incidencia', [TipoIncidenciaController::class, 'index'])
    ->name('tipos-incidencia.index');
Route::get('/tipos-incidencia/crear', [TipoIncidenciaController::class, 'create'])
    ->name('tipos-incidencia.create');
Route::post('/tipos-incidencia', [TipoIncidenciaController::class, 'store'])
    ->name('tipos-incidencia.store');
    
Route::get('/tipos-incidencia/{id}/editar', [TipoIncidenciaController::class, 'edit'])
    ->name('tipos-incidencia.edit');
Route::put('/tipos-incidencia/{id}', [TipoIncidenciaController::class, 'update'])
    ->name('tipos-incidencia.update');
Route::patch('/tipos-incidencia/{id}/toggle', [TipoIncidenciaController::class, 'toggleActivo'])
    ->name('tipos-incidencia.toggle');
