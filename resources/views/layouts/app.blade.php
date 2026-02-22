<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'To All Beneficiaries Of Hospital Care' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    @stack('styles')
</head>
<body class="bg-light">
<header class="admin-header">
    <nav class="navbar navbar-expand-lg bg-white">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('assets/images/logo.png') }}" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#appNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div id="appNav" class="collapse navbar-collapse">
                <!-- <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @auth
                        @if(auth()->user()->isUser())
                            <li class="nav-item"><a class="nav-link" href="{{ route('user.dashboard') }}">My Requests</a></li>
                        @endif
                        @if(auth()->user()->isDoctor())
                            <li class="nav-item"><a class="nav-link" href="{{ route('doctor.dashboard') }}">Doctor Dashboard</a></li>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                        @endif
                    @endauth
                </ul> -->
                <ul class="navbar-nav ms-auto menu-links align-items-lg-center">
                    <li class="nav-item"><a class="nav-link text-secondary" href="{{ route('home') }}">Schedules</a></li>
                    @auth
                        @if(auth()->user()->isUser())
                            <li class="nav-item"><a class="nav-link text-secondary" href="{{ route('user.dashboard') }}">My Requests</a></li>
                        @endif
                        @if(auth()->user()->isDoctor())
                            <li class="nav-item"><a class="nav-link text-secondary" href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item"><a class="nav-link text-secondary" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        @endif
                    @endauth
                    @guest
                        <li class="nav-item"><a class="nav-link text-secondary" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="bg-primary text-white button secondary-hover" href="{{ route('register') }}">Register <i class="fa-solid fa-arrow-right"></i></a></li>
                    @else
                        @php
                            $parts = preg_split('/\s+/', trim(auth()->user()->name)) ?: [];
                            $initials = collect($parts)->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('');
                        @endphp
                        <li class="nav-item user-menu">
                            <button
                                class="avatar-trigger"
                                type="button"
                                id="userMenuTrigger"
                                aria-haspopup="menu"
                                aria-expanded="false"
                                aria-controls="userMenuPanel"
                                aria-label="Open user menu"
                            >
                                {{ $initials !== '' ? $initials : 'U' }}
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
                                        <span class="user-menu-role">({{ ucfirst(auth()->user()->role) }})</span>
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

<main>
    <section class="admin">
        <div class="container">
            {{ $slot ?? '' }}
            @yield('content')
        </div>
    </section>
</main>

<div class="toast-container position-fixed top-0 end-0 p-3" id="appToastContainer"></div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
window.showToast = function (type, message) {
    const palette = {
        success: 'text-bg-success',
        danger: 'text-bg-danger',
        warning: 'text-bg-warning',
        info: 'text-bg-info',
    };

    const toastClass = palette[type] || 'text-bg-secondary';
    const id = 'toast-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
    const html = `
        <div id="${id}" class="toast align-items-center border-0 ${toastClass}" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">${$('<div>').text(message).html()}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    const $container = $('#appToastContainer');
    $container.append(html);
    const el = document.getElementById(id);
    const toast = new bootstrap.Toast(el, { delay: 3500 });
    toast.show();
    el.addEventListener('hidden.bs.toast', function () {
        el.remove();
    });
};

$(function () {
    @if(session('success'))
        window.showToast('success', @json(session('success')));
    @endif

    @if(session('error'))
        window.showToast('danger', @json(session('error')));
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            window.showToast('danger', @json($error));
        @endforeach
    @endif
});

const backBtn = document.getElementById('backBtn');
if (backBtn) {
    backBtn.addEventListener('click', function () {
        window.history.back();
    });
}

@if(session('admin_email_notification'))
    toastr.info("{{ session('admin_email_notification') }}", "Admin Alert", {
        positionClass: 'toast-top-right',
        timeOut: 5000,
        closeButton: true,
        progressBar: true
    });
@endif

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
@stack('scripts')
</body>
</html>
