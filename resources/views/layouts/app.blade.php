<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Hospital Care Schedules' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg bg-white border-bottom mb-4">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ route('home') }}">Hospital Care</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#appNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div id="appNav" class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Schedules</a></li>
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
            </ul>
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="nav-item"><span class="nav-link">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-sm btn-outline-secondary mt-1" type="submit">Logout</button></form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<main class="container pb-5">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{ $slot ?? '' }}
    @yield('content')
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>

