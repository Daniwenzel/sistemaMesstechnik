<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
        <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="logo" />
        </a>
        <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}">
            <img class="img-xs rounded-circle" src="{{ asset('images/auth/logo.png') }}" alt="Profile image">
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center ">
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown nav-profile cursor-pointer">
                <a class="nav-link dropdown-toggle d-none d-xl-inline-block" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                    <span class="profile-text">Olá, {{ Auth::user()->name  }} !</span>
                    @if(Auth::user()->getFirstMedia('profile'))
                        <img class="img-xs rounded-circle" src="{{ Auth::user()->getFirstMedia('profile')->getUrl('avatar') }}" alt="profile image">
                    @else
                        <img class="img-xs rounded-circle" src="{{ asset('images/faces-clipart/pic-1.png') }}" alt="profile image">
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <a class="dropdown-item p-0">
                        <div class="d-flex border-bottom">
                            <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-bookmark-plus-outline mr-0 text-gray"></i>
                            </div>
                            <div class="py-3 px-4 d-flex align-items-center justify-content-center border-left border-right">
                                <i class="mdi mdi-account-outline mr-0 text-gray"></i>
                            </div>
                            <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-alarm-check mr-0 text-gray"></i>
                            </div>
                        </div>
                    </a>
                    <a class="dropdown-item mt-2" href="{{ route('show.config', Auth::user()->id) }}">
                        Manage Account
                    </a>
                    <a class="dropdown-item">
                        Change Password
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        {{ csrf_field() }}
                        <a class="dropdown-item" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit()">
                            Sign Out
                        </a>
                    </form>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>