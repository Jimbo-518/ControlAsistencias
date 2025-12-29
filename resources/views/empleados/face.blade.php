@extends('layouts.app')

@section('title', 'Registro facial')
@section('title-page', 'Registro facial del empleado')

@push('styles')
<style>
    .face-container {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }

    .face-left {
        width: 420px;
    }

    #contenedor {
        position: relative;
        width: 400px;
        height: 300px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(0,138,255,0.3);
    }

    video, canvas {
        position: absolute;
        top: 0;
        left: 0;
    }

    .face-buttons {
        margin-top: 12px;
        display: flex;
        gap: 10px;
    }

    #thumbs img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 8px;
        border: 1px solid rgba(255,255,255,0.2);
    }

    #log {
        background: rgba(255,255,255,0.05);
        padding: 10px;
        height: 180px;
        overflow: auto;
        border-radius: 8px;
        font-size: 12px;
    }

    .status {
        margin-top: 10px;
        font-size: 14px;
        opacity: .85;
    }
</style>
@endpush

@section('content')

<div class="card">
    <h2>Empleado</h2>
    <p>
        <strong>{{ $empleado->nombre }}</strong>
        {{ $empleado->apellido_paterno }}
        {{ $empleado->apellido_materno }}
    </p>
</div>

<hr class="divider">

<div class="card">
    <h2>Registro facial</h2><br>

    <input type="hidden" id="id_empleado" value="{{ $empleado->id_empleado }}">

    <div class="face-container">

        {{-- IZQUIERDA --}}
        <div class="face-left">
            <div id="contenedor">
                <video id="video" width="400" height="300" autoplay muted></video>
            </div>

            <div class="face-buttons">
                <button id="startBtn" class="btn-primary">Iniciar cámara</button>
                <button id="captureBtn" disabled>Capturar</button>
                <button id="finishBtn" disabled>Finalizar</button>
            </div>

            <div id="status" class="status">
                Se recomiendan al menos 6 capturas para un mejor reconocimiento facial.
            </div>
        </div>

        {{-- DERECHA --}}
        <div>
            <h3>Capturas</h3>
            <div id="thumbs" style="display:flex; margin-bottom:10px;"></div>

            <h4>Log</h4>
            <pre id="log"></pre>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">

<script defer src="{{ asset('assets/js/face-api.min.js') }}"></script>
<script defer src="{{ asset('assets/js/script-register.js') }}"></script>
@endpush