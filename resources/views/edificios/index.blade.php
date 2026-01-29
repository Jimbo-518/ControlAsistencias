@extends('layouts.app')

@section('title', 'Edificios')
@section('title-page', 'Edificios')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

@section('content')
    {{-- Mensajes --}}
    @if($edificios->isEmpty())
        <div class="alert alert-info">
            No hay edificios registrados todavía.<br>
            Registra el primero para poder asignar departamentos y horarios.
        </div>
    @endif

    {{-- Tabla --}}
    @if(!$edificios->isEmpty())
        <table class="info-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Ubicación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($edificios as $edificio)
                    <tr>
                        <td>{{ $edificio->nombre }}</td>
                        <td>{{ $edificio->direccion }}</td>
                        <td>{{ $edificio->lat }}, {{ $edificio->lng }}</td>
                        <td>
                            <a href="{{ route('edificios.show', $edificio->id_edificio) }}" class="btn btn-small">
                                Ver
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr class="divider">
    @endif

    {{-- Formulario --}}
    <div class="card">
        <h2>Registrar nuevo edificio</h2>
        <form method="POST" action="{{ route('edificios.store') }}">
            @csrf

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" required>
            </div>

            <div class="form-group">
                <label>Dirección:</label>
                <small class="hint">
                    Escribe una dirección lo más completa posible.<br>
                    Ejemplo: <em>Av. Paseo de la Reforma 222, CDMX</em>
                </small><br>
                <input type="text" id="direccion" name="direccion" class="form-control" required>
                <button type="button" id="buscarDireccion" class="btn">Buscar en el mapa</button>
            </div>

            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">

            <div class="form-group">
                <small class="hint">
                    El mapa define la ubicación real del edificio.<br>
                    Si la búsqueda no es exacta, ajusta el marcador manualmente.
                </small>
                <br>
                <div id="map" style="height: 400px; border-radius: 12px;"></div>
            </div>

            <button type="submit">Guardar edificio</button>
        </form>
    </div>

    </div>

    <script>
        const map = L.map('map').setView([19.4326, -99.1332], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        let marker;

        document.getElementById('buscarDireccion').addEventListener('click', () => {
            const direccion = document.getElementById('direccion').value;

            if (!direccion) {
                alert('Escribe una dirección primero');
                return;
            }

            const query = `${direccion}, México`;

            fetch(`https://nominatim.openstreetmap.org/search?` + new URLSearchParams({
                format: 'json',
                q: query,
                limit: 1,
                addressdetails: 1
            }))
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        alert('No se encontró la dirección exacta. Ajusta el marcador manualmente.');
                        map.setView([19.4326, -99.1332], 12);
                        return;
                    }

                    const lat = parseFloat(data[0].lat);
                    const lng = parseFloat(data[0].lon);

                    map.setView([lat, lng], 16);

                    if (!marker) {
                        marker = L.marker([lat, lng], {
                            draggable: true
                        }).addTo(map);

                        marker.on('dragend', e => {
                            const pos = e.target.getLatLng();
                            document.getElementById('lat').value = pos.lat;
                            document.getElementById('lng').value = pos.lng;
                        });
                    } else {
                        marker.setLatLng([lat, lng]);
                    }

                    document.getElementById('lat').value = lat;
                    document.getElementById('lng').value = lng;
                })
                .catch(() => {
                    alert('Error al consultar el servicio de mapas');
                });
        });
    </script>
@endsection
