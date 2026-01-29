@extends('layouts.app')

@section('title', 'Editar Tipo de Incidencia')
@section('title-page', 'Tipos de Incidencia')

@section('content')

@if($errors->any())
    <div class="alert" style="background: rgba(255, 80, 80, 0.2); color: #ffd6d6; margin-bottom: 20px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <form action="{{ route('tipos-incidencia.update', $tipo->id_tipo) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nombre">Nombre del tipo *</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $tipo->nombre) }}" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="3">{{ old('descripcion', $tipo->descripcion) }}</textarea>
        </div>

        <div class="form-group">
            <label for="alcance">Impacto en jornada*</label>
            <select name="alcance" id="alcance" required>
                <option value="">-- Seleccionar --</option>
                <option value="dia_completo" {{ old('alcance') === 'dia_completo' ? 'selected' : '' }}>
                    Día Completo
                </option>
                <option value="parcial" {{ old('alcance') === 'parcial' ? 'selected' : '' }}>
                    Parcial
                </option>
            </select>
        </div>

        <hr style="border: none; height: 1px; background: rgba(0, 138, 255, 0.2); margin: 25px 0;">

        <h3 style="color: #8ecbff; margin-bottom: 15px; font-size: 18px;">⚙️ Comportamiento</h3>

        <!-- Genera Falta -->
        <div class="switch-container">
            <div class="switch-label">
                <span class="title">Genera falta</span>
                <span class="description">Marca ausencia injustificada en el sistema</span>
            </div>
            <label class="switch">
                <input type="checkbox" name="genera_falta" id="genera_falta" value="1"
                       {{ old('genera_falta', $tipo->genera_falta) ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

        <!-- Elimina Falta -->
        <div class="switch-container">
            <div class="switch-label">
                <span class="title">Elimina falta</span>
                <span class="description">Justifica ausencia previamente registrada</span>
            </div>
            <label class="switch">
                <input type="checkbox" name="elimina_falta" id="elimina_falta" value="1"
                       {{ old('elimina_falta', $tipo->elimina_falta) ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

        <!-- Permite Asistencia -->
        <div class="switch-container">
            <div class="switch-label">
                <span class="title">Permite registro de asistencia</span>
                <span class="description">El empleado puede checar entrada/salida</span>
            </div>
            <label class="switch">
                <input type="checkbox" name="permite_asistencia" id="permite_asistencia" value="1"
                       {{ old('permite_asistencia', $tipo->permite_asistencia) ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

        <!-- Modifica Horario -->
        <div class="switch-container">
            <div class="switch-label">
                <span class="title">Modifica horario</span>
                <span class="description">Usa horario personalizado en lugar del estándar</span>
            </div>
            <label class="switch">
                <input type="checkbox" name="modifica_horario" id="modifica_horario" value="1"
                       {{ old('modifica_horario', $tipo->modifica_horario) ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

        <!-- Horario Personalizado -->
        <div id="horario-custom" style="display: {{ old('modifica_horario', $tipo->modifica_horario) ? 'block' : 'none' }}; margin-top: 20px;">
            <h3 style="color: #8ecbff; margin-bottom: 15px; font-size: 18px;">🕒 Horario Personalizado</h3>
            
            <div class="form-group">
                <label for="hora_entrada">Hora de entrada</label>
                <input type="time" name="hora_entrada" id="hora_entrada"
                       value="{{ old('hora_entrada', $tipo->hora_entrada) }}">
            </div>

            <div class="form-group">
                <label for="hora_salida">Hora de salida</label>
                <input type="time" name="hora_salida" id="hora_salida"
                       value="{{ old('hora_salida', $tipo->hora_salida) }}">
            </div>
        </div>

        <button type="submit" class="btn-primary" style="width: 100%; margin-top: 25px;">
            Actualizar Tipo de Incidencia
        </button>
    </form>
</div>

<script>
document.getElementById('modifica_horario').addEventListener('change', function() {
    document.getElementById('horario-custom').style.display = this.checked ? 'block' : 'none';
});
</script>

@endsection
