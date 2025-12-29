@extends('layouts.app')

@section('title', 'Departamentos')
@section('title-page', 'Departamentos')

@section('content')

    {{-- Mensaje si no hay departamentos --}}
    @if($departamentos->isEmpty())
        <div class="alert alert-info">
            No hay departamentos registrados todavía.<br>
            Registra al menos uno para poder asignarlo a edificios y empleados.
        </div>
    @endif

    {{-- Tabla --}}
    @if(!$departamentos->isEmpty())
        <table class="info-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Estatus</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departamentos as $departamento)
                    <tr>
                        <td>{{ $departamento->nombre }}</td>
                        <td>{{ $departamento->descripcion ?? '—' }}</td>
                        <td>
                            {{ $departamento->activo ? 'Activo' : 'Inactivo' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr class="divider">
    @endif

    {{-- Formulario --}}
    <div class="card">
        <h2>Registrar nuevo departamento</h2>

        <form method="POST" action="{{ route('departamentos.store') }}">
            @csrf

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" required>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" rows="3"></textarea>
            </div>

            <div class="form-check">
                <input type="hidden" name="activo" value="0">
                <input type="checkbox" name="activo" value="1" checked>
                <label>Departamento activo</label>
            </div>

            <button type="submit">Guardar departamento</button>
        </form>
    </div>
@endsection