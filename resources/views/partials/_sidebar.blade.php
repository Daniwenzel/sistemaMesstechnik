<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
{{--        <li class="nav-item">--}}
{{--            <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">--}}
{{--                <a class="navbar-brand brand-logo" href="{{ route('dashboard.index') }}">--}}
{{--                    <img src="{{ asset('images/logo.png') }}" alt="Imagem logo" />--}}
{{--                </a>--}}
{{--                <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard.index') }}">--}}
{{--                    <img class="img-xs rounded-circle" src="{{ asset('images/auth/logo.png') }}" alt="Imagem perfil">--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </li>--}}
        <li class="nav-item nav-profile">
            <div class="nav-link">
                <div class="user-wrapper">
                    <div class="profile-image">
{{--                        @if(Auth::user()->getFirstMedia('profile'))--}}
{{--                            {{ Auth::user()->getFirstMedia('profile') }}--}}
{{--                        @else--}}
                            <img src="{{ asset('images/faces-clipart/pic-1.png') }}" alt="profile image">
{{--                        @endif--}}
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
            <a class="nav-link" href="{{ route('dashboard.index') }}">
                <i class="menu-icon mdi mdi-television"></i>
                <span class="menu-title">{{ __('buttons.dashboard') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="menu-icon mdi mdi-account-box"></i>
                <span class="menu-title">{{ __('buttons.users') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('windfarms.index') }}">
                <i class="menu-icon mdi mdi-weather-windy"></i>
                <span class="menu-title">{{ __('buttons.wind_farm') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('companies.index') }}">
                <i class="menu-icon mdi mdi-briefcase"></i>
                <span class="menu-title">{{ __('buttons.company') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('log') }}">
                <i class="menu-icon mdi mdi-file-alert"></i>
                <span class="menu-title">{{ __('buttons.log') }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('reports.index') }}">
                <i class="menu-icon mdi mdi-graphql"></i>
                <span class="menu-title">{{ __('buttons.report') }}</span>
            </a>
        </li>
    </ul>
</nav>
