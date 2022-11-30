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
                            <span class="status-indicator online"></span>
                            <small class="designation text-muted">{{ Auth::user()->getRoleNames()->first() }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.index') }}">
                <i class="menu-icon mdi mdi-home"></i>
                <span class="menu-title">{{ __('buttons.dashboard') }}</span>
            </a>
        </li>
        @role('Admin')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="menu-icon mdi mdi-account-box"></i>
                <span class="menu-title">{{ __('buttons.users') }}</span>
            </a>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link" href=" route('company.index') ">
                <i class="menu-icon mdi mdi-briefcase"></i>
                <span class="menu-title">{{ __('buttons.company') }}</span>
            </a>
        </li> -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('log.index') }}">
                <i class="menu-icon mdi mdi-clipboard-text-outline"></i>
                <span class="menu-title">{{ __('buttons.log') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('clients.index') }}">
                <i class="menu-icon mdi mdi-wind-turbine"></i>
                <span class="menu-title">{{ __('Estações') }}</span>
            </a>
        </li>
        @else
        <li class="nav-item">
            <a class="nav-link" href="{{ route('stations.index', Auth::user()->empresa->razaosocial ) }}">
                <i class="menu-icon mdi mdi-wind-turbine"></i>
                <span class="menu-title">{{ __('Estações') }}</span>
            </a>
        </li>
        @endrole
        <li class="nav-item">
            <a class="nav-link" href="{{ route('reports.index') }}">
                <i class="menu-icon mdi mdi-poll"></i>
                <span class="menu-title">{{ __('buttons.report') }}</span>
            </a>
        </li>
    </ul>
</nav>
