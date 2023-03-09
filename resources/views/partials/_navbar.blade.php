<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
        <a class="navbar-brand brand-logo" href="{{ route('dashboard.index') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Imagem logo" />
        </a>
        <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard.index') }}">
            <img class="img-xs rounded-circle" src="{{ asset('images/auth/logo.png') }}" alt="Imagem perfil">
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center ">
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown nav-profile cursor-pointer">
                <span class="nav-link dropdown-toggle d-none d-xl-inline-block" id="UserDropdown" data-toggle="dropdown" aria-expanded="false">
                <span class="profile-text">{{ __('buttons.hello',['attribute' => Auth::user()->name]) }}</span>
                        <img class="img-xs rounded-circle" src="{{ asset('images/faces-clipart/pic-1.png') }}" alt="Imagem perfil">
                    </span>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <a class="dropdown-item mt-2" href="{{ route('users.edit', Auth::user()->codigo) }}">
                        {{ __('buttons.manage_account') }}
                    </a>
                    <a class="dropdown-item" href="{{ route('users.password') }}">
                        {{ __('buttons.change_password') }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}" name="LogoutForm">
                        @csrf
                        <span class="dropdown-item" onclick="LogoutForm.submit()">
                            {{ __('buttons.sign_out') }}
                        </span>

                        {{--                        id="logout-form"--}}
                        {{--                        <a class="dropdown-item" onclick="event.preventDefault();--}}
                        {{--                        document.getElementById('logout-form').submit()">--}}
                        {{--                            {{ __('buttons.sign_out') }}--}}
                        {{--                        </a>--}}
                    </form>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>