@extends('layouts.app')

@section('title', 'Subir Justificante')
@section('title-page', 'Justificantes')

@section('content')
<div class="card">
    <form action="{{ route('justificantes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="id_tipo_incidencia">Tipo de incidencia *</label>
            <select name="id_tipo_incidencia" id="id_tipo_incidencia" required>
                <option value="">-- Seleccionar tipo --</option>
                @foreach($tipos_incidencia as $tipo)
                    <option value="{{$tipo->id_tipo}}" data-alcance="{{ $tipo->alcance }}"
                            {{old('id_tipo_incidencia') == $tipo->id_tipo ? 'selected' : '' }} >
                        {{ $tipo->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group" id="grupo-empleado">
            <label for="id_empleado">Empleado</label>
            <select name="id_empleado" id="id_empleado">
                <option>-- Seleccionar empleado --</option>
                <option value="">Todos</option>
                @foreach($empleados as $emp)
                    <option value="{{ $emp->id_empleado }}" {{ old('id_empleado') == $emp->id_empleado ? 'selected' : '' }}>
                        {{ $emp->nombre }} {{ $emp->apellido_paterno }} {{ $emp->apellido_materno }}
                    </option>
                @endforeach
            </select>
            <small style="color: #9ccfff; margin-top: 5px; display: block;">
                Dejar vacío si aplica para todos (ej: día feriado)
            </small>
        </div>

        <div class="form-group">
            <label for="fecha_inicio">Fecha de inicio *</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
        </div>

        <div class="form-group">
            <label for="fecha_fin">Fecha de fin</label>
            <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin') }}">
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="4" placeholder="Detalles adicionales (opcional)">{{ old('descripcion') }}</textarea>
        </div>

        <div class="form-group">
            <label for="evidencia">Evidencia (PDF, imagen)</label>
            <div class="file-row">
                <input type="file" name="evidencia" id="evidencia" accept=".pdf,.jpg,.jpeg,.png">
            </div>
            <small style="color: #9ccfff; margin-top: 5px; display: block;">
                Formatos permitidos: PDF, JPG, PNG (máx. 5MB). Opcional para días feriados.
            </small>
        </div>

        <button type="submit" class="btn-primary" style="width: 100%;">
            Registrar Justificante
        </button>
    </form>
</div>

<script>
// Controlar visibilidad del campo empleado según el tipo
document.getElementById('id_tipo_incidencia').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const alcance = selectedOption.dataset.alcance;
    const grupoEmpleado = document.getElementById('grupo-empleado');
    const inputEmpleado = document.getElementById('id_empleado');
    
    if (alcance === 'general' || alcance === 'todos') {
        // Ocultar campo empleado para feriados
        grupoEmpleado.style.display = 'none';
        inputEmpleado.value = '';
        inputEmpleado.required = false;
    } else {
        // Mostrar para justificantes individuales
        grupoEmpleado.style.display = 'block';
        inputEmpleado.required = true;
    }
});

// Validar que fecha_fin >= fecha_inicio
document.getElementById('fecha_fin').addEventListener('change', function() {
    const inicio = document.getElementById('fecha_inicio').value;
    const fin = this.value;
    
    if (inicio && fin && fin < inicio) {
        alert('La fecha de fin no puede ser anterior a la fecha de inicio');
        this.value = '';
    }
});
</script>
@endsection
