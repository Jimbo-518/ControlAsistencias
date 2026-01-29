@extends('layouts.app')

@section('title', 'Perfil de empleado')
@section('title-page', 'Mi perfil')

@section('content')

    <div class="content">
        <center>
            {{-- TARJETA DE PERFIL --}}
            <div class="chart-container">

                {{-- FOTO + BOTÓN CAMBIO --}}
                <div style="display:flex; align-items:center; gap:25px;">

                    <div class="profile-section" style="margin-bottom:0;">
                        <img src="{{ session('foto_perfil') }}" alt="Foto de perfil">
                    </div>

                    <div>
                        <h2 style="color:#8ecbff;">
                            {{ $empleado->nombre }} {{ $empleado->apellido_paterno }} {{ $empleado->apellido_materno }}
                        </h2>

                        <form action="{{ route('perfil.foto') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <br>
                            <div class="file-row">
                                <input id="photo" type="file" name="foto" accept="image/*" required>
                                <button type="submit" class="btn-primary">
                                    Cambiar foto
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>


            {{-- DATOS DEL EMPLEADO --}}
            <div class="chart-container">
                <h3 style="color:#8ecbff; margin-bottom:10px;">Información personal</h3>

                <table class="info-table">
                    <tr>
                        <th>ID Empleado</th>
                        <td>{{ $empleado->id_empleado }}</td>
                    </tr>
                    <tr>
                        <th>Correo</th>
                        <td>{{ $empleado->correo }}</td>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <td>{{ $empleado->telefono }}</td>
                    </tr>
                    <tr>
                        <th>Fecha ingreso</th>
                        <td>{{ $empleado->fecha_ingreso }}</td>
                    </tr>
                    <tr>
                        <th>Estatus</th>
                        <td>{{ $empleado->estatus }}</td>
                    </tr>
                    <tr>
                        <th>Vacaciones tomadas</th>
                        <td>{{ $empleado->vacaciones_tomadas }}</td>
                    </tr>
                </table>
            </div>


            {{-- DATOS DE USUARIO --}}
            <div class="chart-container">
                <h3 style="color:#8ecbff; margin-bottom:10px;">Cuenta del sistema</h3>

                <table class="info-table">
                    <tr>
                        <th>Usuario</th>
                        <td>{{ $empleado->usuario->usuario ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Rol</th>
                        <td>{{ $empleado->usuario->rol->nombre ?? '—' }}</td>
                    </tr>
                </table>
            </div>
        </center>
    </div>

@endsection
