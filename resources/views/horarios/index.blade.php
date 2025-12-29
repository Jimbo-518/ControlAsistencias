@extends('layouts.app')

@section('title', 'Horarios')
@section('title-page', 'Horarios')

@section('content')
    {{-- Mensaje cuando no hay registros --}}
    @if($horarios->isEmpty())
        <div class="alert alert-info">
            No hay horarios registrados todavía.<br>
            Registra uno para poder asignarlo a edificios y departamentos.
        </div>
    @endif

    {{-- Tabla --}}
    @if(!$horarios->isEmpty())
        <table class="info-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($horarios as $horario)
                    <tr>
                        <td>{{ $horario->nombre }}</td>
                        <td>{{ $horario->descripcion ?? '—' }}</td>
                        <td>
                            {{ $horario->activo ? 'Sí' : 'No' }}
                        </td>
                        <td>
                            <a href="{{ route('horarios.show', $horario->id_horario) }}" class="btn">
                                Ver detalle
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr class="divider">
    @endif

    {{-- Formulario --}}
    <div class="card">
        <h2>Registrar nuevo horario</h2>

        <form method="POST" action="{{ route('horarios.store') }}">
            @csrf

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" required>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="activo" required>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>

            <button type="submit">Guardar horario</button>
        </form>
    </div>
@endsection