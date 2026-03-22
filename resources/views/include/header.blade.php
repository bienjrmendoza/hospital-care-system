<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="mainMenu">
                <ul class="navbar-nav align-items-lg-center menu-links">
                    <li class="nav-item text-secondary">
                        <a href="/">HOME</a>
                    </li>
                    <li class="nav-item text-secondary">
                        <a href="/about">ABOUT</a>
                    </li>
                    <li class="nav-item text-secondary">
                        <a href="{{ route('home') }}">SCHEDULES</a>
                    </li>
                    @auth
                        @if(auth()->user()->isUser())
                            <li class="nav-item text-secondary"><a href="{{ route('user.dashboard') }}">MY REQUESTS</a></li>
                            @if(auth()->user()->vitals()->exists())
                                <li class="nav-item text-secondary position-relative">
                                    <a href="{{ route('user.vitals.index') }}">
                                        <i class="fa-solid fa-bell d-none d-lg-inline" style="font-size: 20px;"></i>
                                        <span class="d-inline d-lg-none">REPORT</span>
                                    </a>
                                    <span class="report-notice">
                                        
                                    </span>
                                </li>
                            @endif
                        @endif
                        @if(auth()->user()->isDoctor())
                            <li class="nav-item text-secondary"><a href="{{ route('doctor.dashboard') }}">DASHBOARD</a></li>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item text-secondary"><a href="{{ route('admin.dashboard') }}">DASHBOARD</a></li>
                        @endif
                    @endauth
                    @guest
                        <li class="nav-item text-secondary">
                            <a href="/login">LOGIN</a>
                        </li>
                        <li class="nav-item">
                            <a href="/register">
                                <button class="bg-primary text-white button secondary-hover">
                                    Register <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </a>
                        </li>
                    @else
                        @php
                            $parts = preg_split('/\s+/', trim(auth()->user()->name)) ?: [];
                            $initials = collect($parts)->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('');
                        @endphp
                        <li class="nav-item user-menu">
                            <button
                                class="avatar-trigger bg-primary text-white p-3 w-100"
                                type="button"
                                id="userMenuTrigger"
                                aria-haspopup="menu"
                                aria-expanded="false"
                                aria-controls="userMenuPanel"
                                aria-label="Open user menu"
                            >
                                <!-- {{ $initials !== '' ? $initials : 'U' }} -->
                                <!-- PAGMALAKI SCREEN -->
                                <span class="d-none d-lg-inline text-white">{{ $initials !== '' ? $initials : 'U' }}</span>
                                <!-- PAGMALIIT SCREEN -->
                                <span class="d-inline d-lg-none text-white">{{ auth()->user()->name }}</span>
                            </button>
                            <div
                                class="user-menu-panel"
                                id="userMenuPanel"
                                role="menu"
                                aria-labelledby="userMenuTrigger"
                                aria-hidden="true"
                            >
                                <div>
                                    <p class="user-menu-name">
                                        {{ auth()->user()->name }}
                                        <span class="user-menu-role text-primary"><strong>({{ ucfirst(auth()->user()->role) }})</strong></span>
                                    </p>
                                </div>
                                <hr class="user-menu-divider">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="user-menu-logout" type="submit" role="menuitem">
                                        <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
</header>


<script>
const userMenuTrigger = document.getElementById('userMenuTrigger');
const userMenuPanel = document.getElementById('userMenuPanel');

if (userMenuTrigger && userMenuPanel) {
    function openUserMenu() {
        userMenuTrigger.setAttribute('aria-expanded', 'true');
        userMenuPanel.setAttribute('aria-hidden', 'false');
        userMenuPanel.classList.add('is-open');
    }

    function closeUserMenu() {
        userMenuTrigger.setAttribute('aria-expanded', 'false');
        userMenuPanel.setAttribute('aria-hidden', 'true');
        userMenuPanel.classList.remove('is-open');
    }

    userMenuTrigger.addEventListener('click', function () {
        const expanded = userMenuTrigger.getAttribute('aria-expanded') === 'true';
        if (expanded) {
            closeUserMenu();
        } else {
            openUserMenu();
        }
    });

    document.addEventListener('click', function (event) {
        const clickInside = userMenuTrigger.contains(event.target) || userMenuPanel.contains(event.target);
        if (!clickInside) {
            closeUserMenu();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeUserMenu();
            userMenuTrigger.focus();
        }
    });
}
</script>