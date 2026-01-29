@extends('layouts.app')

@section('title', 'Editar empleado')
@section('title-page', 'Editar empleado')

@section('content')
<div class="container">
    <div class="card">
        <h2>Editar empleado</h2>
        <hr class="divider">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('empleados.update', $empleado->id_empleado) }}">
            @csrf
            @method('PUT')

            <h3>Datos del empleado</h3><br>

            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" name="nombre" value="{{ $empleado->nombre }}">
            </div>

            <div class="form-group">
                <label for="apellido_paterno">Apellido paterno</label>
                <input type="text" name="apellido_paterno" value="{{ $empleado->apellido_paterno }}">
            </div>

            <div class="form-group">
                <label for="apellido_materno">Apellido materno</label>
                <input type="text" name="apellido_materno" value="{{ $empleado->apellido_materno }}">
            </div>

            <div class="form-group">
                <label for="correo">Correo</label>
                <input type="email" name="correo" value="{{ $empleado->correo }}">
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" value="{{ $empleado->telefono }}">
            </div>

            <div class="form-group">
                <label for="estatus">Estatus</label>
                <select name="estatus">
                    <option value="activo" {{ $empleado->estatus === 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ $empleado->estatus === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            @if ($empleado->usuario)
                <hr class="divider">

                <h3>Datos de acceso</h3><br>

                <div class="form-group">
                    <label for="rol">Rol</label>
                    <select name="id_rol">
                        @foreach($roles as $rol)
                            <option value="{{ $rol->id_rol }}"
                                {{ $empleado->usuario->id_rol == $rol->id_rol ? 'selected' : '' }}>
                                {{ ucfirst($rol->nombre) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                <div class="alert alert-info">
                    Este empleado no cuenta aún con un usuario.
                </div>
            @endif

            <button type="submit">Guardar cambios</button>
        </form>
    </div>
</div>
@endsection
