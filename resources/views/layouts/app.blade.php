<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Control de Asistencias')</title>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('styles')
</head>

<body>
    {{-- MENÚ --}}
    @include('partials.menu')

    <div class="content">
        <h1 class="page-title">@yield('title-page', 'Control de Asistencias')</h1>
        <hr class="divider">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // Reloj
            function updateClock() {
                const clock = document.getElementById("clock");
                if (!clock) return;

                const now = new Date();
                const h = String(now.getHours()).padStart(2, '0');
                const m = String(now.getMinutes()).padStart(2, '0');
                clock.textContent = `${h}:${m}`;
            }

            setInterval(updateClock, 1000);
            updateClock();

            // Menús desplegables
            document.querySelectorAll(".dropdown").forEach(item => {
                item.addEventListener("click", () => {
                    const target = item.dataset.target;
                    const submenu = document.getElementById(target);
                    if (!submenu) return;

                    submenu.style.display =
                        submenu.style.display === "block" ? "none" : "block";
                });
            });

        });
    </script>
</body>

</html>