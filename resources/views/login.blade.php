<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Acceso</title>

    <link rel="stylesheet" href="{{ asset('assets/css/checador.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
</head>

<body>

    <div class="header">
        <a href="checador">
            <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24"
                fill="none" stroke="#ffffff" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 14l-4 -4l4 -4" />
                <path d="M5 10h11a4 4 0 1 1 0 8h-1" />
            </svg>
        </a>
    </div>

    <hr class="divider">

    <div class="auth-wrapper">

        <!-- CONTENEDOR FORMULARIOS -->
        <div class="auth-forms">

            <!-- LOGIN -->
            <div class="auth-panel auth-login">
                <h1>Bienvenido</h1>
                <p>Accede al sistema de control de asistencias</p>

                <form>
                    <input type="email" placeholder="Correo">
                    <input type="password" placeholder="Contraseña">
                    <button class="btn-ok">Entrar</button>
                </form>

                <span class="auth-switch" id="openRegister">
                    ¿No tienes cuenta? Crear una
                </span>
            </div>

            <!-- REGISTRO -->
            <div class="auth-panel auth-register">
                <h1>Únete al sistema</h1>
                <p>Controla accesos, horarios y asistencia en un solo lugar</p>

                <form>
                    <input type="text" placeholder="Nombre completo">
                    <input type="email" placeholder="Correo">
                    <input type="password" placeholder="Contraseña">
                    <button class="btn-ok">Registrarme</button>
                </form>

                <span class="auth-switch" id="closeRegister">
                    Ya tengo cuenta
                </span>
            </div>

        </div>

        <!-- LOGO -->
        <div class="auth-logo">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo del sistema">
        </div>

    </div>

    <script>
        const wrapper = document.querySelector('.auth-wrapper');

        document.getElementById('openRegister').onclick = () => {
            wrapper.classList.add('show-register');
        };

        document.getElementById('closeRegister').onclick = () => {
            wrapper.classList.remove('show-register');
        };
    </script>

</body>
</html>
