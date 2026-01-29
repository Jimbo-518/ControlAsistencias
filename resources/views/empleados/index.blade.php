@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <h2>Empleados</h2><br>

            <div class="actions-bar">
                <a id="btnEditar" class="action-btn action-edit btn-disabled">
                    Editar
                </a>

                <form id="formEstatus" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button id="btnEstatus" type="button" class="action-danger btn-disabled" disabled>
                        Dar de baja
                    </button>
                </form>
            </div>

            @if($empleados->isEmpty())
                <div class="alert alert-warning">
                    No hay empleados registrados.
                </div>
            @else
                <table class="info-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nombre completo</th>
                            <th>Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($empleados as $e)
                            <tr class="fila-empleado" data-id="{{ $e->id_empleado }}" data-estatus="{{ $e->estatus }}">

                                <td>
                                    <input type="radio" name="empleadoSeleccionado">
                                </td>

                                <td>
                                    {{ $e->nombre }} {{ $e->apellido_paterno }} {{ $e->apellido_materno }}
                                </td>

                                <td>
                                    <span class="badge {{ $e->estatus === 'activo' ? 'badge-success' : 'badge-danger' }}">
                                        {{ ucfirst($e->estatus) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @endif
        </div>
    </div>

    <div id="modalConfirmacion" class="modal hidden">
        <div class="modal-glass">
            <h3 class="modal-title">Confirmar acción</h3>

            <p id="modalTexto" class="modal-text">
                ¿Estás seguro de que deseas dar de baja a este empleado?
            </p>

            <div class="modal-actions">
                <button id="btnCancelar" class="action-btn action-edit">
                    Cancelar
                </button>
                <button id="btnConfirmar" class="action-btn action-danger">
                    Confirmar
                </button>
            </div>
        </div>
    </div>


    <script>
        document.querySelectorAll('.fila-empleado').forEach(row => {
            row.addEventListener('click', () => {

                // marcar radio
                row.querySelector('input').checked = true;

                const id = row.dataset.id;
                const estatus = row.dataset.estatus;

                // Editar
                const btnEditar = document.getElementById('btnEditar');
                btnEditar.href = `/empleados/${id}/editar`;
                btnEditar.classList.remove('btn-disabled');

                // Baja / Alta
                const form = document.getElementById('formEstatus');
                const btn = document.getElementById('btnEstatus');

                form.action = `/empleados/${id}/baja`;
                btn.textContent = 'Dar de baja';
                btn.className = 'btn btn-sm btn-danger';

                btn.disabled = false;
            });
        });
    </script>

    <script>
        const modal = document.getElementById('modalConfirmacion');
        const btnConfirmar = document.getElementById('btnConfirmar');
        const btnCancelar = document.getElementById('btnCancelar');
        const modalTexto = document.getElementById('modalTexto');
        const formEstatus = document.getElementById('formEstatus');
        const btnEstatus = document.getElementById('btnEstatus');

        btnEstatus.addEventListener('click', (e) => {
            e.preventDefault();

            modalTexto.textContent =
                '¿Estás seguro de que deseas dar de baja a este empleado?';

            modal.classList.remove('hidden');
        });

        btnCancelar.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        btnConfirmar.addEventListener('click', () => {
            formEstatus.submit();
        });
    </script>
@endsection
