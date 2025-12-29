@extends('layouts.app')

@section('title', 'Alta de empleados')
@section('title-page', 'Registrar empleado')

@section('content')
<div class="card" style="max-width: 700px;">
    <h2>Datos del empleado</h2><br>

    <form method="POST" action="{{ route('empleados.store') }}">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label>Nombre (s)</label>
                <input type="text" name="nombre" required>
            </div>

            <div class="form-group">
                <label>Apellido paterno</label>
                <input type="text" name="apellido_paterno" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Apellido materno</label>
                <input type="text" name="apellido_materno">
            </div>

            <div class="form-group">
                <label>Correo</label>
                <input type="email" name="correo" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono">
            </div>

            <div class="form-group">
                <label>Fecha de ingreso</label>
                <input type="date" name="fecha_ingreso" required>
            </div>
        </div>

        <div class="form-group">
            <label>Departamento / Edificio</label>
            <select name="id_depto_edificio" required>
                <option value="">Selecciona</option>
                @foreach($deptoEdificios as $de)
                    <option value="{{ $de->id_de }}">
                        {{ $de->departamento->nombre }}
                        — {{ $de->edificio->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-primary">Registrar empleado</button>
    </form>
</div>
@endsection