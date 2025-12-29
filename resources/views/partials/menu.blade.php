<div class="menu">
    <!-- Perfil -->
    <div class="profile-section">
        <img src="https://i.pravatar.cc/300" alt="Usuario">
    </div>
    <div class="clock" id="clock">--:--</div>

    <!-- INICIO -->
    <a href="/" class="menu-item">
        <span>Inicio</span>
    </a>

    <a href="{{ route('checador.index') }}" class="menu-item">
        <span>Checador</span>
    </a>

    <!-- EMPLEADOS -->
    <div class="menu-item dropdown" data-target="empleados">
        <div class="dropdown-title">
            <span>Empleados</span>
            <span>▼</span>
        </div>
    </div>
    <div class="submenu" id="empleados">
        <div>Mi perfil</div>
        <a href="{{ route('empleados.create') }}">
            <div>Dar de alta</div>
        </a>
        <a href="{{ route('empleados.index') }}">
            <div>Ver empleados</div>
        </a>
    </div>

    <!-- JUSTIFICANTES -->
    <div class="menu-item dropdown" data-target="justificantes">
        <div class="dropdown-title">
            <span>Justificantes</span>
            <span>▼</span>
        </div>
    </div>
    <div class="submenu" id="justificantes">
        <div>Subir justificante</div>
        <div>Ver justificantes</div>
    </div>

    <!-- ADMINISTRAR -->
    <div class="menu-item dropdown" data-target="administrar">
        <div class="dropdown-title">
            <span>Administrar</span>
            <span>▼</span>
        </div>
    </div>
    <div class="submenu" id="administrar">
        <a href="{{ route('edificios.index') }}">
            <div>Edificios</div>
        </a>
        <a href="{{ route('departamentos.index') }}">
            <div>Departamentos</div>
        </a>
        <a href="{{ route('horarios.index') }}">
            <div>Horarios</div>
        </a>
    </div>

    <!-- REPORTES -->
    <div class="menu-item dropdown" data-target="reportes">
        <div class="dropdown-title">
            <span>Reportes</span>
            <span>▼</span>
        </div>
    </div>
    <div class="submenu" id="reportes">
        <div>Reporte general</div>
        <div>Mi reporte</div>
    </div>
</div>