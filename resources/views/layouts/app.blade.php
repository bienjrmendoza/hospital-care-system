<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'To All Beneficiaries Of Hospital Care' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
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
                            <li class="nav-item"><a class="nav-link text-secondary" href="{{ route('doctor.dashboard') }}">Doctor Dashboard</a></li>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item"><a class="nav-link text-secondary" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                        @endif
                    @endauth
                    @guest
                        <li class="nav-item"><a class="nav-link text-secondary" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="bg-primary text-white button secondary-hover" href="{{ route('register') }}">Register <i class="fa-solid fa-arrow-right"></i></a></li>
                    @else
                        <li class="nav-item"><span class="nav-link text-secondary">{{ auth()->user()->name }} (<span class="text-primary">{{ auth()->user()->role }}</span>)</span></li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">@csrf<button class="bg-primary text-white button secondary-hover" type="submit">Logout <i class="fa-solid fa-arrow-right"></i></button></form>
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
</script>
@stack('scripts')
</body>
</html>

