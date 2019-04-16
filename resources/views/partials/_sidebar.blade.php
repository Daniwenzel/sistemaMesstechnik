<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <div class="nav-link">
                <div class="user-wrapper">
                    <div class="profile-image">
                        <img src="{{ asset('images/faces-clipart/pic-1.png') }}" alt="profile image">
                    </div>
                    <div class="text-wrapper">
                        <p class="profile-name">{{ Auth::user()->name }}</p>
                        <div>
                            <small class="designation text-muted">Manager</small>
                            <span class="status-indicator online"></span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="menu-icon mdi mdi-television"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        @role('Engenheiro')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user') }}">
                <i class="menu-icon mdi mdi-account-box"></i>
                <span class="menu-title">Usuários</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('company') }}">
                <i class="menu-icon mdi mdi-briefcase"></i>
                <span class="menu-title">Empresas</span>
            </a>
        </li>
        @endrole
        <li class="nav-item">
            <a class="nav-link" href="{{ route('windfarm') }}">
                <i class="menu-icon mdi mdi-weather-windy"></i>
                <span class="menu-title">Complexos Eólicos</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('role.permission') }}">
                <i class="menu-icon mdi mdi-settings"></i>
                <span class="menu-title">Cargos e Permissões</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ action('SensorController@openFile') }}">
                <i class="menu-icon mdi mdi-settings"></i>
                <span class="menu-title">openfile</span>
            </a>
        </li>

    </ul>
</nav>