@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <h2>Empleados</h2><br>

            @if($empleados->isEmpty())
                <div class="alert alert-warning">
                    No hay empleados registrados.
                </div>
            @else
                <table class="info-table">
                    <thead>
                        <tr>
                            <th>Nombre completo</th>
                            <th>Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($empleados as $e)
                            <tr>
                                <td>
                                    {{ $e->nombre }}
                                    {{ $e->apellido_paterno }}
                                    {{ $e->apellido_materno }}
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
@endsection