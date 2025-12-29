@extends('layouts.app')

@section('title', 'Detalle del horario')
@section('title-page', 'Horario: ' . $horario->nombre)

@section('content')
    <style>
        .horario-form {
            max-width: 700px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 6px;
        }
    </style>

    {{-- INFO DEL HORARIO --}}
    <div class="card">
        <h2>Información general</h2>

        <p><strong>Nombre:</strong> {{ $horario->nombre }}</p>
        <p><strong>Descripción:</strong> {{ $horario->descripcion ?? '—' }}</p>
        <p><strong>Estado:</strong> {{ $horario->activo ? 'Activo' : 'Inactivo' }}</p>
    </div>

    <hr class="divider">

    {{-- ASIGNAR HORARIO A DEPARTAMENTOEDIFICIO --}}
    <div class="card">
        <h2>Asignar horario a departamento</h2><br>

        <form method="POST" action="{{ route('horarios.asignar') }}" class="horario-form">
            @csrf

            <input type="hidden" name="id_horario" value="{{ $horario->id_horario }}">

            <div class="form-row">
                <div class="form-group">
                    <label>Departamento / Edificio</label>
                    <select name="id_de" required>
                        <option value="">Selecciona</option>
                        @foreach($deptoEdificios as $de)
                            <option value="{{ $de->id_de }}">
                                {{ $de->departamento->nombre }} — {{ $de->edificio->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="activo" required>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Fecha inicio</label>
                    <input type="date" name="fecha_inicio">
                </div>

                <div class="form-group">
                    <label>Fecha fin</label>
                    <input type="date" name="fecha_fin">
                </div>
            </div>

            <button type="submit" class="btn-primary">Asignar horario</button>
        </form>
    </div>

    <hr class="divider">

    {{-- DEPARTAMENTOS QUE USAN ESTE HORARIO --}}
    <div class="card">
        <h2>Departamentos asignados</h2><br>

        @if($horario->asignaciones->isEmpty())
            <div class="alert alert-info">
                Este horario aún no está asignado a ningún departamento.
            </div>
        @else
            <table class="info-table">
                <thead>
                    <tr>
                        <th>Departamento</th>
                        <th>Edificio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($horario->asignaciones as $asig)
                        <tr>
                            <td>{{ $asig->deptoEdificio->departamento->nombre }}</td>
                            <td>{{ $asig->deptoEdificio->edificio->nombre }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <hr class="divider">

    {{-- DETALLES DEL HORARIO --}}
    <div class="card">
        <h2>Detalle por día</h2><br>

        @if($horario->detalles->isEmpty())
            <div class="alert alert-warning">
                Este horario aún no tiene días configurados.
            </div>
        @else
            @php
                $dias = [
                    7 => 'Domingo',
                    1 => 'Lunes',
                    2 => 'Martes',
                    3 => 'Miércoles',
                    4 => 'Jueves',
                    5 => 'Viernes',
                    6 => 'Sábado',
                ];
            @endphp

            <table class="info-table">
                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Entrada</th>
                        <th>Tolerancia</th>
                        <th>Comida</th>
                        <th>Salida</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($horario->detalles as $d)
                        <tr>
                            <td>{{ $dias[$d->dia] ?? '—' }}</td>
                            <td>{{ $d->entrada?->format('H:i') }}</td>
                            <td>{{ $d->tolerancia }} min</td>
                            <td>
                                {{ $d->comida_inicio?->format('H:i') }}
                                -
                                {{ $d->comida_fin?->format('H:i') }}
                            </td>
                            <td>{{ $d->salida?->format('H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <hr class="divider">

    {{-- FORMULARIO NUEVO DÍA --}}
    <div class="card">
        <h2>Agregar día al horario</h2><br>

        <form method="POST" action="{{ route('horarios.detalle.store') }}" class="horario-form">
            @csrf

            <input type="hidden" name="id_horario" value="{{ $horario->id_horario }}">

            {{-- Día + tolerancia --}}
            <div class="form-row">
                <div class="form-group">
                    <label>Día</label>
                    <select name="dia" required>
                        <option value="">Selecciona</option>
                        <option value="1">Lunes</option>
                        <option value="2">Martes</option>
                        <option value="3">Miércoles</option>
                        <option value="4">Jueves</option>
                        <option value="5">Viernes</option>
                        <option value="6">Sábado</option>
                        <option value="7">Domingo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tolerancia (min)</label>
                    <input type="number" name="tolerancia" min="0" required>
                </div>
            </div>

            {{-- Entrada + salida --}}
            <div class="form-row">
                <div class="form-group">
                    <label>Hora de entrada</label>
                    <input type="time" name="entrada" required>
                </div>

                <div class="form-group">
                    <label>Hora de salida</label>
                    <input type="time" name="salida" required>
                </div>
            </div>

            {{-- Comida --}}
            <div class="form-row">
                <div class="form-group">
                    <label>Inicio comida</label>
                    <input type="time" name="comida_inicio">
                </div>

                <div class="form-group">
                    <label>Fin comida</label>
                    <input type="time" name="comida_fin">
                </div>
            </div>

            <button type="submit" class="btn-primary">Agregar día</button>
        </form>
    </div>
@endsection