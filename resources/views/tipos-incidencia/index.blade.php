@extends('layouts.app')

@section('title', 'Tipos de Incidencia')
@section('title-page', 'Tipos de Incidencia')

@section('content')
    <div class="actions-bar">
        <a href="{{ route('tipos-incidencia.create') }}" class="action-edit">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#1f4bb2"
                class="icon icon-tabler icons-tabler-filled icon-tabler-square-rounded-plus">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path
                    d="M12 2l.324 .001l.318 .004l.616 .017l.299 .013l.579 .034l.553 .046c4.785 .464 6.732 2.411 7.196 7.196l.046 .553l.034 .579c.005 .098 .01 .198 .013 .299l.017 .616l.005 .642l-.005 .642l-.017 .616l-.013 .299l-.034 .579l-.046 .553c-.464 4.785 -2.411 6.732 -7.196 7.196l-.553 .046l-.579 .034c-.098 .005 -.198 .01 -.299 .013l-.616 .017l-.642 .005l-.642 -.005l-.616 -.017l-.299 -.013l-.579 -.034l-.553 -.046c-4.785 -.464 -6.732 -2.411 -7.196 -7.196l-.046 -.553l-.034 -.579a28.058 28.058 0 0 1 -.013 -.299l-.017 -.616c-.003 -.21 -.005 -.424 -.005 -.642l.001 -.324l.004 -.318l.017 -.616l.013 -.299l.034 -.579l.046 -.553c.464 -4.785 2.411 -6.732 7.196 -7.196l.553 -.046l.579 -.034c.098 -.005 .198 -.01 .299 -.013l.616 -.017c.21 -.003 .424 -.005 .642 -.005zm0 6a1 1 0 0 0 -1 1v2h-2l-.117 .007a1 1 0 0 0 .117 1.993h2v2l.007 .117a1 1 0 0 0 1.993 -.117v-2h2l.117 -.007a1 1 0 0 0 -.117 -1.993h-2v-2l-.007 -.117a1 1 0 0 0 -.993 -.883z"
                    fill="#ffffff" stroke-width="0" />
            </svg>
            Nuevo Tipo
        </a>

        <a href="#" id="btn-editar" class="action-edit btn-disabled">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415" />
                <path d="M16 5l3 3" />
            </svg>
            Editar
        </a>

        <button id="btn-toggle" class="btn-form action-danger btn-disabled" disabled>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-refresh-dot">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                <path d="M11 12a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
            </svg>
            Cambiar estado
        </button>
    </div>

    <table class="info-table">
        <thead>
            <tr>
                <th style="width: 40px;"></th>
                <th>Nombre</th>
                <th>Alcance</th>
                <th>Genera Falta</th>
                <th>Elimina Falta</th>
                <th>Permite Asistencia</th>
                <th>Modifica Horario</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tipos as $tipo)
                <tr class="fila-tipo" data-id="{{ $tipo->id_tipo }}" data-activo="{{ $tipo->activo ? '1' : '0' }}">
                    <td style="text-align: center;">
                        <input type="radio" name="tipo_seleccionado" value="{{ $tipo->id_tipo }}" class="radio-tipo">
                    </td>
                    <td>{{ $tipo->nombre }}</td>
                    <td>
                        @if($tipo->alcance === 'parcial')
                            Parcial
                        @else
                            Día completo
                        @endif
                    </td>
                    <td style="text-align: center;">
                        {!! $tipo->genera_falta
                ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#1caf08"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg>'
                : '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#930101"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-6.489 5.8a1 1 0 0 0 -1.218 1.567l1.292 1.293l-1.292 1.293l-.083 .094a1 1 0 0 0 1.497 1.32l1.293 -1.292l1.293 1.292l.094 .083a1 1 0 0 0 1.32 -1.497l-1.292 -1.293l1.292 -1.293l.083 -.094a1 1 0 0 0 -1.497 -1.32l-1.293 1.292l-1.293 -1.292l-.094 -.083z" /></svg>'
                                            !!}
                    </td>
                    <td style="text-align: center;">
                        {!! $tipo->elimina_falta
                ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#1caf08"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg>'
                : '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#930101"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-6.489 5.8a1 1 0 0 0 -1.218 1.567l1.292 1.293l-1.292 1.293l-.083 .094a1 1 0 0 0 1.497 1.32l1.293 -1.292l1.293 1.292l.094 .083a1 1 0 0 0 1.32 -1.497l-1.292 -1.293l1.292 -1.293l.083 -.094a1 1 0 0 0 -1.497 -1.32l-1.293 1.292l-1.293 -1.292l-.094 -.083z" /></svg>'
                                            !!}
                    </td>
                    <td style="text-align: center;">
                        {!! $tipo->permite_asistencia
                ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#1caf08"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg>'
                : '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#930101"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-6.489 5.8a1 1 0 0 0 -1.218 1.567l1.292 1.293l-1.292 1.293l-.083 .094a1 1 0 0 0 1.497 1.32l1.293 -1.292l1.293 1.292l.094 .083a1 1 0 0 0 1.32 -1.497l-1.292 -1.293l1.292 -1.293l.083 -.094a1 1 0 0 0 -1.497 -1.32l-1.293 1.292l-1.293 -1.292l-.094 -.083z" /></svg>'
                                            !!}
                    </td>
                    <td style="text-align: center;">
                        {!! $tipo->modifica_horario
                ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#1caf08"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg>'
                : '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#930101"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-6.489 5.8a1 1 0 0 0 -1.218 1.567l1.292 1.293l-1.292 1.293l-.083 .094a1 1 0 0 0 1.497 1.32l1.293 -1.292l1.293 1.292l.094 .083a1 1 0 0 0 1.32 -1.497l-1.292 -1.293l1.292 -1.293l.083 -.094a1 1 0 0 0 -1.497 -1.32l-1.293 1.292l-1.293 -1.292l-.094 -.083z" /></svg>'
                                            !!}
                    </td>
                    <td>
                        @if($tipo->activo)
                            <span style="color: #5dff5d;">● Activo</span>
                        @else
                            <span style="color: #ff6b6b;">● Inactivo</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; opacity: 0.6;">
                        No hay tipos de incidencia registrados
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Formulario oculto para toggle -->
    <form id="form-toggle" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
    </form>

    <script>
        let idSeleccionado = null;
        let estadoActual = null;

        // Detectar selección de fila
        document.querySelectorAll('.fila-tipo').forEach(fila => {
            fila.addEventListener('click', function (e) {
                // Si se clickeó el radio, dejarlo funcionar normal
                if (e.target.classList.contains('radio-tipo')) return;

                // Simular clic en el radio
                const radio = this.querySelector('.radio-tipo');
                radio.checked = true;

                // Actualizar selección
                actualizarSeleccion();
            });
        });

        // Detectar cambio en radios
        document.querySelectorAll('.radio-tipo').forEach(radio => {
            radio.addEventListener('change', actualizarSeleccion);
        });

        function actualizarSeleccion() {
            const radioSeleccionado = document.querySelector('.radio-tipo:checked');

            if (radioSeleccionado) {
                const fila = radioSeleccionado.closest('.fila-tipo');
                idSeleccionado = fila.dataset.id;
                estadoActual = fila.dataset.activo === '1';

                // Activar botones
                const btnEditar = document.getElementById('btn-editar');
                const btnToggle = document.getElementById('btn-toggle');

                btnEditar.classList.remove('btn-disabled');
                btnEditar.href = `/tipos-incidencia/${idSeleccionado}/editar`;

                btnToggle.classList.remove('btn-disabled');
                btnToggle.disabled = false;
                btnToggle.textContent = estadoActual ? '🔴 Desactivar' : '🟢 Activar';
            }
        }

        // Manejar toggle
        document.getElementById('btn-toggle').addEventListener('click', function (e) {
            e.preventDefault();

            if (!idSeleccionado) return;

            const accion = estadoActual ? 'desactivar' : 'activar';

            if (confirm(`¿Estás seguro de ${accion} este tipo de incidencia?`)) {
                const form = document.getElementById('form-toggle');
                form.action = `/tipos-incidencia/${idSeleccionado}/toggle`;
                form.submit();
            }
        });
    </script>
@endsection
