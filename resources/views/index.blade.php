@extends('layouts.app')

@section('title', 'Inicio')
@section('title-page', 'Inicio')

@section('content')
        <!-- GRÁFICA -->
        <div class="chart-container">
            <canvas id="entradaChart"></canvas>
        </div>

        <!-- TABLA -->
        <hr class="divider">
        <table class="info-table">
            <thead>
                <tr>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Librería de Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('entradaChart');

        // Datos inventados (hora promedio de entrada por día)
        const dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
        const horasEntrada = [8.5, 8.3, 8.7, 8.4, 8.6];
        // ej: 8.5 = 8:30 a.m.

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dias,
                datasets: [{
                    label: 'Hora promedio de entrada',
                    data: horasEntrada,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                scales: {
                    y: {
                        suggestedMin: 7,
                        suggestedMax: 10
                    }
                }
            }
        });
    </script>
@endsection