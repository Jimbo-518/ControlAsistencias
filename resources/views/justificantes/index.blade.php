@extends('layouts.app')

@section('title', 'Revisar Justificantes')
@section('title-page', 'Justificantes')

@section('content')
    <div class="actions-bar">
        <a href="{{ route('justificantes.create') }}" class="action-edit">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#1f4bb2"
                class="icon icon-tabler icons-tabler-filled icon-tabler-square-rounded-plus">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path
                    d="M12 2l.324 .001l.318 .004l.616 .017l.299 .013l.579 .034l.553 .046c4.785 .464 6.732 2.411 7.196 7.196l.046 .553l.034 .579c.005 .098 .01 .198 .013 .299l.017 .616l.005 .642l-.005 .642l-.017 .616l-.013 .299l-.034 .579l-.046 .553c-.464 4.785 -2.411 6.732 -7.196 7.196l-.553 .046l-.579 .034c-.098 .005 -.198 .01 -.299 .013l-.616 .017l-.642 .005l-.642 -.005l-.616 -.017l-.299 -.013l-.579 -.034l-.553 -.046c-4.785 -.464 -6.732 -2.411 -7.196 -7.196l-.046 -.553l-.034 -.579a28.058 28.058 0 0 1 -.013 -.299l-.017 -.616c-.003 -.21 -.005 -.424 -.005 -.642l.001 -.324l.004 -.318l.017 -.616l.013 -.299l.034 -.579l.046 -.553c.464 -4.785 2.411 -6.732 7.196 -7.196l.553 -.046l.579 -.034c.098 -.005 .198 -.01 .299 -.013l.616 -.017c.21 -.003 .424 -.005 .642 -.005zm0 6a1 1 0 0 0 -1 1v2h-2l-.117 .007a1 1 0 0 0 .117 1.993h2v2l.007 .117a1 1 0 0 0 1.993 -.117v-2h2l.117 -.007a1 1 0 0 0 -.117 -1.993h-2v-2l-.007 -.117a1 1 0 0 0 -.993 -.883z"
                    fill="#ffffff" stroke-width="0" />
            </svg>
            Subir Justificante
        </a>

        <button id="btn-revisar" class="action-edit btn-disabled" disabled>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#d6d9e0"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-zoom-question">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                <path d="M21 21l-6 -6" />
                <path d="M10 13l0 .01" />
                <path d="M10 10a1.5 1.5 0 1 0 -1.14 -2.474" />
            </svg>
            Revisar Seleccionado
        </button>
    </div>

    <table class="info-table">
        <thead>
            <tr>
                <th style="width: 40px;"></th>
                <th>Empleado</th>
                <th>Tipo</th>
                <th>Fecha(s)</th>
                <th>Descripción</th>
                <th>Evidencia</th>
                <th>Estatus</th>
                <th>Auditor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($justificantes as $just)
                <tr class="fila-justificante" data-id="{{ $just->id_incidencia }}"
                    data-empleado="{{ $just->empleado ? $just->empleado->nombre . ' ' . $just->empleado->apellido_paterno : 'Todos' }}"
                    data-tipo="{{ $just->tipo->nombre }}" data-estatus="{{ $just->estatus }}">
                    <td style="text-align: center;">
                        @if($just->estatus === 'pendiente')
                            <input type="radio" name="justificante_seleccionado" value="{{ $just->id_incidencia }}"
                                class="radio-justificante">
                        @else
                            <span style="opacity: 0.3;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($just->empleado)
                            {{ $just->empleado->nombre }} {{ $just->empleado->apellido_paterno }}
                        @else
                            <em style="opacity: 0.6;">Todos los empleados</em>
                        @endif
                    </td>
                    <td>{{ $just->tipo->nombre }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($just->fecha_inicio)->format('d/m/Y') }}
                        @if($just->fecha_fin && $just->fecha_fin != $just->fecha_inicio)
                            <br>al {{ \Carbon\Carbon::parse($just->fecha_fin)->format('d/m/Y') }}
                        @endif
                    </td>
                    <td style="max-width: 200px; font-size: 13px;">
                        {{ Str::limit($just->descripcion, 80) ?? '-' }}
                    </td>
                    <td style="text-align: center;">
                        @if($just->evidencia_url)
                            <a href="{{ asset('storage/' . $just->evidencia_url) }}" target="_blank"
                                style="color: #7bbdff; text-decoration: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#d6d9e0"
                                    class="icon icon-tabler icons-tabler-filled icon-tabler-eye">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M12 4c4.29 0 7.863 2.429 10.665 7.154l.22 .379l.045 .1l.03 .083l.014 .055l.014 .082l.011 .1v.11l-.014 .111a.992 .992 0 0 1 -.026 .11l-.039 .108l-.036 .075l-.016 .03c-2.764 4.836 -6.3 7.38 -10.555 7.499l-.313 .004c-4.396 0 -8.037 -2.549 -10.868 -7.504a1 1 0 0 1 0 -.992c2.831 -4.955 6.472 -7.504 10.868 -7.504zm0 5a3 3 0 1 0 0 6a3 3 0 0 0 0 -6" />
                                </svg>
                            </a>
                        @else
                            <span style="opacity: 0.5;">-</span>
                        @endif
                    </td>
                    <td>
                        @if($just->estatus === 'pendiente')
                            <span style="color: #ffa500;">Pendiente</span>
                        @elseif($just->estatus === 'aplicada')
                            <span style="color: #5dff5d;">Aplicada</span>
                        @else
                            <span style="color: #ff6b6b;">Rechazada</span>
                        @endif
                    </td>
                    <td style="font-size: 13px; opacity: 0.8;">
                        @if($just->auditorUsuario)
                            {{ $just->auditorUsuario->usuario }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; opacity: 0.6;">
                        No hay justificantes registrados
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Modal de Revisión -->
    <div id="modal-revision" class="modal hidden">
        <div class="modal-glass" style="max-width: 500px;">
            <h2 class="modal-title">Revisar Justificante</h2>
            <p class="modal-text" id="modal-info"></p>

            <form id="form-revision" method="POST" style="margin-top: 20px;">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label for="">Decisión *</label>
                    <select name="estatus" required
                        style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid rgba(0, 138, 255, 0.4); background: rgba(0, 0, 0, 0.4); color: white;">
                        <option value="">-- Seleccionar --</option>
                        <option value="aplicada">✅ Aprobar y Aplicar</option>
                        <option value="rechazada">❌ Rechazar</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Comentario (opcional)</label>
                    <textarea name="comentario" rows="3" placeholder="Razón de aprobación o rechazo"
                        style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid rgba(0, 138, 255, 0.4); background: rgba(0, 0, 0, 0.4); color: white;"></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" onclick="cerrarModal()"
                        style="padding: 10px 20px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.3); background: rgba(255, 255, 255, 0.1); color: white; cursor: pointer;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-primary">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let idSeleccionado = null;

        // Detectar clic en fila
        document.querySelectorAll('.fila-justificante').forEach(fila => {
            fila.addEventListener('click', function (e) {
                const estatus = this.dataset.estatus;

                // Solo permitir selección si está pendiente
                if (estatus !== 'pendiente') return;

                // Si se clickeó el radio, dejarlo funcionar normal
                if (e.target.classList.contains('radio-justificante')) return;

                // Simular clic en el radio
                const radio = this.querySelector('.radio-justificante');
                if (radio) {
                    radio.checked = true;
                    actualizarSeleccion();
                }
            });
        });

        // Detectar cambio en radios
        document.querySelectorAll('.radio-justificante').forEach(radio => {
            radio.addEventListener('change', actualizarSeleccion);
        });

        function actualizarSeleccion() {
            const radioSeleccionado = document.querySelector('.radio-justificante:checked');
            const btnRevisar = document.getElementById('btn-revisar');

            if (radioSeleccionado) {
                const fila = radioSeleccionado.closest('.fila-justificante');
                idSeleccionado = fila.dataset.id;

                btnRevisar.classList.remove('btn-disabled');
                btnRevisar.disabled = false;
            } else {
                btnRevisar.classList.add('btn-disabled');
                btnRevisar.disabled = true;
            }
        }

        // Abrir modal al hacer clic en revisar
        document.getElementById('btn-revisar').addEventListener('click', function () {
            if (!idSeleccionado) return;

            const fila = document.querySelector(`[data-id="${idSeleccionado}"]`);
            const empleado = fila.dataset.empleado;
            const tipo = fila.dataset.tipo;

            document.getElementById('modal-info').textContent = `Empleado: ${empleado} | Tipo: ${tipo}`;
            document.getElementById('form-revision').action = `/justificantes/${idSeleccionado}/revisar`;
            document.getElementById('modal-revision').classList.remove('hidden');
        });

        function cerrarModal() {
            document.getElementById('modal-revision').classList.add('hidden');
            document.getElementById('form-revision').reset();
        }

        // Cerrar modal con ESC
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') cerrarModal();
        });
    </script>
@endsection