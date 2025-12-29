@extends('layouts.app')

@section('title', 'Edificio')
@section('title-page', 'Edificio: ' . $edificio->nombre)

@section('content')

{{-- INFO EDIFICIO --}}
<div class="card">
    <h2>Datos del edificio</h2>
    <p><strong>Dirección:</strong> {{ $edificio->direccion }}</p>
    <p><strong>Ubicación:</strong> {{ $edificio->lat }}, {{ $edificio->lng }}</p>
</div>

<hr class="divider">

{{-- DEPARTAMENTOS ASIGNADOS --}}
<div class="card">
    <h2>Departamentos asignados</h2>
    <br>
    @if($edificio->departamentos->isEmpty())
        <div class="alert alert-info">
            Este edificio aún no tiene departamentos asignados.
        </div>
    @else
        <table class="info-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($edificio->departamentos as $dep)
                    <tr>
                        <td>{{ $dep->nombre }}</td>
                        <td>{{ $dep->descripcion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<hr class="divider">

{{-- ASIGNAR NUEVO DEPARTAMENTO --}}
<div class="card">
    <h2>Asignar departamento</h2>
    <br>
    @if($departamentosDisponibles->isEmpty())
        <div class="alert alert-warning">
            No hay departamentos disponibles para asignar.
        </div>
    @else
        <form method="POST" action="{{ route('edificios.departamentos.assign', $edificio->id_edificio) }}">
            @csrf

            <div class="form-group">
            <select name="id_departamento" required>
                <option value="">Seleccione un departamento</option>
                @foreach($departamentosDisponibles as $dep)
                    <option value="{{ $dep->id_departamento }}">
                        {{ $dep->nombre }}
                    </option>
                @endforeach
            </select>
            </div>

            <button type="submit">Asignar</button>
        </form>
    @endif
</div>
@endsection