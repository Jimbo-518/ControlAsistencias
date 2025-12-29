<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reconocimiento de empleados</title>
    <link rel="stylesheet" href="{{ asset('assets/css/checador.css') }}">

    <script defer src="{{ asset('assets/js/face-api.min.js') }}"></script>
    <script defer src="{{ asset('assets/js/script-reconocer.js') }}"></script>
</head>

<body>
    <!-- ENCABEZADO -->
    <div class="header">
        <div class="header-title">Reconocimiento de empleados</div>
        <a href="/" class="btn-entrar">Entrar</a>
    </div>

    <hr class="divider">

    <!-- CÁMARA (ÚNICA) -->
    <div id="contenedor">
        <video id="video" autoplay muted playsinline></video>
        <!-- canvas lo agrega JS -->
    </div>

    <div id="status"></div>

    <hr class="divider">

    <!-- OTRO MÉTODO -->
    <div class="other-method">Probar otro método</div>

    <!-- MODAL CONFIRMACIÓN -->
    <div id="modalConfirmacion" class="modal">
        <div class="modal-content">
            <h2 id="tituloModal">Confirmación</h2><br>

            <h3 id="empNombre"></h3><br>
            <p id="empEstatus"></p>

            <p id="mensajeConfirmacion"></p>

            <div class="modal-actions" id="accionesConfirmacion">
                <!-- botones dinámicos -->
            </div>
        </div>
    </div>
</body>
</html>