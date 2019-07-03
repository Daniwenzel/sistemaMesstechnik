<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <div class="nav-link">
                <div class="user-wrapper">
                    <div class="profile-image">
                        @if(Auth::user()->getFirstMedia('profile'))
                            {{ Auth::user()->getFirstMedia('profile') }}
                        @else
                            <img src="{{ asset('images/faces-clipart/pic-1.png') }}" alt="profile image">
                        @endif
                    </div>
                    <div class="text-wrapper">
                        <p class="profile-name">{{ Auth::user()->name }}</p>
                        <div>
                            <small class="designation text-muted">{{ Auth::user()->empresa->nome }}</small>
                            <span class="status-indicator online"></span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="menu-icon mdi mdi-television"></i>
                <span class="menu-title">{{ __('buttons.dashboard') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user') }}">
                <i class="menu-icon mdi mdi-account-box"></i>
                <span class="menu-title">{{ __('buttons.users') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('windfarm') }}">
                <i class="menu-icon mdi mdi-weather-windy"></i>
                <span class="menu-title">{{ __('buttons.wind_farm') }}</span>
            </a>
        </li>
        @role('Admin')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('company') }}">
                <i class="menu-icon mdi mdi-briefcase"></i>
                <span class="menu-title">{{ __('buttons.company') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('role.permission') }}">
                <i class="menu-icon mdi mdi-settings"></i>
                <span class="menu-title">{{ __('buttons.permissions') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('log') }}">
                <i class="menu-icon mdi mdi-file-alert"></i>
                <span class="menu-title">{{ __('buttons.log') }}</span>
            </a>
        </li>
        @endrole
    </ul>
</nav>