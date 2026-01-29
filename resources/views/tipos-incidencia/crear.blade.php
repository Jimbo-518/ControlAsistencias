@extends('layouts.app')

@section('title', 'Crear Tipo de Incidencia')
@section('title-page', 'Tipos de Incidencia')

@section('content')
<div class="card">
    <form action="{{ route('tipos-incidencia.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nombre">Nombre del tipo *</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                   placeholder="Ej: Incapacidad médica, Día feriado" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="3"
                      placeholder="Detalles opcionales sobre este tipo">{{ old('descripcion') }}</textarea>
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

        <h3 style="color: #8ecbff; margin-bottom: 15px; font-size: 18px;">Comportamiento</h3>

        <!-- Genera Falta -->
        <div class="switch-container">
            <div class="switch-label">
                <span class="title">Genera falta</span>
                <span class="description">Marca ausencia injustificada en el sistema</span>
            </div>
            <label class="switch">
            <input type="checkbox" name="genera_falta" id="genera_falta" value="1"
                   {{ old('genera_falta') ? 'checked' : '' }}>
            <span class="slider"></span>
        </div>

        <!-- Elimina Falta -->
        <div class="switch-container">
            <div class="switch-label">
                <span class="title">Elimina falta</span>
                <span class="description">Justifica ausencia previamente registrada</span>
            </div>
            <label class="switch">
            <input type="checkbox" name="elimina_falta" id="elimina_falta" value="1"
                   {{ old('elimina_falta') ? 'checked' : '' }}>
            <span class="slider"></span>
        </div>

        <!-- Permite Asistencia -->
        <div class="switch-container">
            <div class="switch-label">
                <span class="title">Permite registro de asistencia</span>
                <span class="description">El empleado puede checar entrada/salida</span>
            </div>
            <label class="switch">
                <input type="checkbox" name="permite_asistencia" id="permite_asistencia" value="1"
                       {{ old('permite_asistencia') ? 'checked' : '' }}>
                <span class="slider"></span>
        </div>

        <!-- Modifica Horario -->
        <div class="switch-container">
            <div class="switch-label">
                <span class="title">Modifica horario</span>
                <span class="description">Usa horario personalizado en lugar del estándar</span>
            </div>
            <label class="switch">
                <input type="checkbox" name="modifica_horario" id="modifica_horario" value="1"
                       {{ old('modifica_horario') ? 'checked' : '' }}>
                <span class="slider"></span>
        </div>

        <div id="horario-custom" style="display: none; margin-top: 20px;">
            <h3 style="color: #8ecbff; margin-bottom: 15px; font-size: 18px;">Horario Personalizado</h3>
            
            <div class="form-group">
                <label for="hora_entrada">Hora de entrada</label>
                <input type="time" name="hora_entrada" id="hora_entrada" value="{{ old('hora_entrada') }}">
            </div>

            <div class="form-group">
                <label for="hora_salida">Hora de salida</label>
                <input type="time" name="hora_salida" id="hora_salida" value="{{ old('hora_salida') }}">
            </div>
        </div>

        <button type="submit" class="btn-primary" style="width: 100%; margin-top: 25px;">
            Crear Tipo de Incidencia
        </button>
    </form>
</div>

<script>
document.getElementById('modifica_horario').addEventListener('change', function() {
    const horarioDiv = document.getElementById('horario-custom');
    horarioDiv.style.display = this.checked ? 'block' : 'none';
});

// Mostrar si ya venía marcado (old input)
if (document.getElementById('modifica_horario').checked) {
    document.getElementById('horario-custom').style.display = 'block';
}
</script>

@endsection
