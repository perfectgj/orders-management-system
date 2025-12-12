<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button">☰</a></li>
        <li class="nav-item d-none d-sm-inline-block"><a
                href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('staff.dashboard')) : url('/') }}"
                class="nav-link">Home</a></li>
    </ul>

    <ul class="navbar-nav ms-auto">
        @auth
            <li class="nav-item me-2"><a class="nav-link"
                    href="{{ auth()->user()->isAdmin() ? route('admin.profile.show') : route('staff.profile.show') }}"><i
                        class="fas fa-user"></i> {{ auth()->user()->name }}</a></li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-danger btn-sm">Logout</button>
                </form>
            </li>
        @endauth
    </ul>
</nav>
